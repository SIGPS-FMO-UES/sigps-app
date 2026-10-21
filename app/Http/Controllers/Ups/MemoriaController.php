<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ups\DictamenMemoriaRequest;
use App\Models\LogAccion;
use App\Models\Memoria;
use App\Models\Proyecto;
use App\Models\RevisionMemoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MemoriaController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'in:recibido,en_revision,observada,aprobada,aprovada,esperando_firmas,lista,entregada,rechazada'],
            'fecha' => ['nullable', 'date'],
        ]);

        $memorias = Memoria::query()->with([
            'proyecto.areaConocimiento',
            'proyecto.estudiantes',
            'registrador',
            'revisiones' => fn ($revisiones) => $revisiones->limit(1),
        ])
            ->when($filtros['search'] ?? null, fn ($query, $search) => $query->whereHas('proyecto', fn ($proyectos) => $proyectos->where('titulo', 'ilike', "%{$search}%")
                ->orWhereHas('estudiantes', fn ($estudiantes) => $estudiantes->where('due', 'ilike', "%{$search}%")->orWhere('nombre_completo', 'ilike', "%{$search}%"))))
            ->when($filtros['estado'] ?? null, fn ($query, $estado) => $query->where('estado', $estado))
            ->when($filtros['fecha'] ?? null, fn ($query, $fecha) => $query->whereDate('fecha_recibimiento', $fecha))
            ->latest('fecha_recibimiento')
            ->paginate(12)
            ->withQueryString();

        $disponiblesParaRevision = Memoria::query()
            ->whereIn('estado', [EstadoMemoria::Recibido->value, EstadoMemoria::EnRevision->value, EstadoMemoria::Observada->value])
            ->count();

        $proyectosSinMemoria = Proyecto::query()
            ->with(['areaConocimiento', 'estudiantes'])
            ->whereDoesntHave('memoria')
            ->orderBy('fecha_inicio')
            ->paginate(8, ['*'], 'proyectos_page')
            ->withQueryString();

        return view('ups.memorias.index', compact('memorias', 'filtros', 'disponiblesParaRevision', 'proyectosSinMemoria'));
    }

    public function show(string $id): View
    {
        $memoria = Memoria::query()
            ->with(['proyecto.areaConocimiento', 'proyecto.estudiantes', 'registrador', 'revisiones.auditor'])
            ->findOrFail($id);

        return view('ups.memorias.show', [
            'memoria' => $memoria,
            'documentoDisponible' => $this->documentoPdfDisponible($memoria),
        ]);
    }

    public function auditar(string $id): View
    {
        $memoria = Memoria::query()
            ->with(['proyecto.areaConocimiento', 'proyecto.estudiantes', 'revisiones.auditor'])
            ->findOrFail($id);

        abort_unless(in_array($memoria->estado->value, [
            EstadoMemoria::Recibido->value,
            EstadoMemoria::EnRevision->value,
            EstadoMemoria::Observada->value,
        ], true), 409, 'Esta memoria ya tiene un dictamen aprobado o fue cerrada.');

        return view('ups.auditoria', [
            'memoria' => $memoria,
            'documentoDisponible' => $this->documentoPdfDisponible($memoria),
        ]);
    }

    public function dictamen(DictamenMemoriaRequest $request, string $id): RedirectResponse
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos, $id): void {
            $memoria = Memoria::query()->lockForUpdate()->findOrFail($id);
            abort_unless($this->documentoPdfDisponible($memoria), 422, 'No hay un PDF de memoria disponible para revisar.');
            abort_unless(in_array($memoria->estado->value, [
                EstadoMemoria::Recibido->value,
                EstadoMemoria::EnRevision->value,
                EstadoMemoria::Observada->value,
            ], true), 409, 'Esta memoria ya tiene un dictamen aprobado o fue cerrada.');

            $resultado = $datos['resultado'];
            $observaciones = $datos['observaciones'] ?? null;

            RevisionMemoria::create([
                'memoria_id' => $memoria->id_memoria,
                'auditor_id' => auth()->id(),
                'resultado' => $resultado,
                'sellos_verificados' => (bool) $datos['sellos_verificados'],
                'tinta_azul_verificada' => (bool) $datos['tinta_azul_verificada'],
                'observaciones' => $observaciones,
            ]);

            $memoria->update([
                'estado' => $resultado === 'aprobado' ? EstadoMemoria::Aprobada : EstadoMemoria::Observada,
                'fecha_aprovado' => $resultado === 'aprobado' ? now()->toDateString() : null,
                'observaciones' => $observaciones,
            ]);

            LogAccion::create([
                'accion' => "Dictamen de memoria #{$memoria->id_memoria}: {$resultado}.",
                'usuario' => auth()->id(),
            ]);
        });

        return redirect()->route('ups.memorias.show', $id)
            ->with('success', 'El dictamen documental quedó registrado en el historial de la memoria.');
    }

    public function documento(string $id): BinaryFileResponse
    {
        $memoria = Memoria::query()->findOrFail($id);
        abort_unless($this->documentoPdfDisponible($memoria), 404);

        return response()->file(Storage::disk('expedientes_ups')->path($memoria->documento), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function documentoPdfDisponible(Memoria $memoria): bool
    {
        $path = $memoria->documento;

        if (! is_string($path) || $path === '' || str_contains($path, '..') || str_contains($path, '\\') || str_starts_with($path, '/')) {
            return false;
        }

        return str_ends_with(strtolower($path), '.pdf')
            && Storage::disk('expedientes_ups')->exists($path);
    }
}
