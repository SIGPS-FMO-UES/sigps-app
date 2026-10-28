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
            ->paginate(100)
            ->withQueryString();

        $disponiblesParaRevision = Memoria::query()
            ->whereIn('estado', [EstadoMemoria::Recibido->value, EstadoMemoria::EnRevision->value, EstadoMemoria::Observada->value])
            ->count();

        $proyectosSinMemoria = Proyecto::query()
            ->with(['areaConocimiento', 'estudiantes'])
            ->where('estado_expediente', 'en_tramite')
            ->whereDoesntHave('memoria')
            ->orderBy('fecha_inicio')
            ->paginate(100, ['*'], 'proyectos_page')
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

    public function entregarPorProyecto(Request $request, string $id): RedirectResponse
    {
        $datos = $request->validate([
            'fecha_entrega' => ['required', 'date', 'before_or_equal:today'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($id, $datos): void {
            $proyecto = Proyecto::query()->with('memoria')->lockForUpdate()->findOrFail($id);
            $memoria = $proyecto->memoria;

            if ($memoria) {
                $memoria->update([
                    'estado' => EstadoMemoria::Entregada,
                    'fecha_entrega' => $datos['fecha_entrega'],
                    'observaciones' => $datos['observaciones'] ?? $memoria->observaciones,
                ]);
            } else {
                Memoria::create([
                    'numero_proyecto' => $proyecto->numero_proyecto,
                    'registrada_por' => auth()->id(),
                    'fecha_recibimiento' => $datos['fecha_entrega'],
                    'fecha_entrega' => $datos['fecha_entrega'],
                    'estado' => EstadoMemoria::Entregada,
                    'observaciones' => $datos['observaciones'] ?? null,
                ]);
            }

            LogAccion::create([
                'accion' => "Memoria final del proyecto #{$proyecto->numero_proyecto} marcada como entregada.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'La memoria final fue marcada como entregada.');
    }

    public function entregar(Request $request, string $id): RedirectResponse
    {
        $datos = $request->validate([
            'fecha_entrega' => ['required', 'date', 'before_or_equal:today'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($id, $datos): void {
            $memoria = Memoria::query()->lockForUpdate()->findOrFail($id);
            $memoria->update([
                'estado' => EstadoMemoria::Entregada,
                'fecha_entrega' => $datos['fecha_entrega'],
                'observaciones' => $datos['observaciones'] ?? $memoria->observaciones,
            ]);

            LogAccion::create([
                'accion' => "Memoria #{$memoria->id_memoria} marcada como entregada.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'La memoria final fue marcada como entregada.');
    }

    public function recibirPorProyecto(Request $request, string $id): RedirectResponse
    {
        $datos = $request->validate([
            'fecha_recibimiento' => ['required', 'date', 'before_or_equal:today'],
            'archivo' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($id, $datos, $request): void {
            $proyecto = Proyecto::query()->with('memoria')->lockForUpdate()->findOrFail($id);
            $ruta = null;

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $ruta = $archivo->store("proyectos/{$proyecto->id_proyecto}", 'expedientes_ups');

                $proyecto->documentos()->create([
                    'fase' => 'memoria_final',
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'ruta' => $ruta,
                    'mime_type' => 'application/pdf',
                    'tamano_bytes' => $archivo->getSize(),
                    'usuario_id' => auth()->id(),
                ]);
            }

            $memoriaExistente = $proyecto->memoria;
            $intento = $memoriaExistente
                ? ($memoriaExistente->intento + ($memoriaExistente->estado === EstadoMemoria::Observada ? 1 : 0))
                : 1;

            Memoria::updateOrCreate(
                ['numero_proyecto' => $proyecto->numero_proyecto],
                [
                    'registrada_por' => auth()->id(),
                    'fecha_recibimiento' => $datos['fecha_recibimiento'],
                    'estado' => EstadoMemoria::Recibido,
                    'documento' => $ruta ?? $memoriaExistente?->documento,
                    'observaciones' => $datos['observaciones'] ?? $memoriaExistente?->observaciones,
                    'intento' => $intento,
                ]
            );

            LogAccion::create([
                'accion' => "Recepción de memoria registrada para el proyecto #{$proyecto->numero_proyecto}.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'La recepción de la memoria final quedó registrada en el expediente.');
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
