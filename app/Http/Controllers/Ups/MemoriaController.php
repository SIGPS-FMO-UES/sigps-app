<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\HistorialFaseProyecto;
use App\Models\LogAccion;
use App\Models\Memoria;
use App\Models\Proyecto;
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
            ->with(['proyecto.areaConocimiento', 'proyecto.estudiantes', 'registrador'])
            ->findOrFail($id);

        return view('ups.memorias.show', [
            'memoria' => $memoria,
            'documentoDisponible' => $this->documentoPdfDisponible($memoria),
        ]);
    }

    public function documento(string $id): BinaryFileResponse
    {
        $memoria = Memoria::query()->findOrFail($id);
        abort_unless($this->documentoPdfDisponible($memoria), 404, 'Documento PDF no encontrado.');

        return response()->file(Storage::disk('expedientes_ups')->path($memoria->documento), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
        ]);
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

    public function aprobar(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403, 'Solo el Jefe de UPS puede aprobar la memoria.');

        DB::transaction(function () use ($id, $request): void {
            $memoria = Memoria::query()->lockForUpdate()->findOrFail($id);
            abort_if(
                in_array($memoria->estado->value, [EstadoMemoria::Aprobada->value, EstadoMemoria::Aprovada->value, EstadoMemoria::Entregada->value], true),
                409,
                'La memoria ya fue aprobada o entregada previamente.'
            );

            $memoria->update([
                'estado' => EstadoMemoria::Aprobada,
                'fecha_aprovado' => now()->toDateString(),
                'observaciones' => $request->input('observaciones', $memoria->observaciones),
            ]);

            LogAccion::create([
                'accion' => "Memoria #{$memoria->id_memoria} aprobada por Jefatura UPS.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'La memoria fue aprobada exitosamente.');
    }

    public function aprobarPorProyecto(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403, 'Solo el Jefe de UPS puede aprobar la memoria.');

        $proyecto = Proyecto::query()->with('memoria')->findOrFail($id);
        abort_unless($proyecto->memoria, 404, 'El proyecto no tiene una memoria registrada.');

        return $this->aprobar($request, (string) $proyecto->memoria->id_memoria);
    }

    public function pasarAFirmas(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403, 'Solo el Jefe de UPS puede gestionar el proceso de firmas.');

        DB::transaction(function () use ($id, $request): void {
            $memoria = Memoria::query()->lockForUpdate()->findOrFail($id);
            abort_unless(
                in_array($memoria->estado->value, [EstadoMemoria::Aprobada->value, EstadoMemoria::Aprovada->value], true),
                422,
                'La memoria debe estar aprobada antes de pasar al trámite de firmas.'
            );

            $memoria->update([
                'estado' => EstadoMemoria::EsperandoFirmas,
                'observaciones' => $request->input('observaciones', $memoria->observaciones),
            ]);

            LogAccion::create([
                'accion' => "Memoria #{$memoria->id_memoria} marcada en proceso de firmas físicas.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'El proyecto quedó marcado en proceso de firmas físicas.');
    }

    public function pasarAFirmasPorProyecto(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403, 'Solo el Jefe de UPS puede gestionar el proceso de firmas.');

        $proyecto = Proyecto::query()->with('memoria')->findOrFail($id);
        abort_unless($proyecto->memoria, 404, 'El proyecto no tiene una memoria registrada.');

        return $this->pasarAFirmas($request, (string) $proyecto->memoria->id_memoria);
    }

    public function marcarLista(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403, 'Solo el Jefe de UPS puede marcar el documento como listo para entrega.');

        DB::transaction(function () use ($id, $request): void {
            $memoria = Memoria::query()->lockForUpdate()->findOrFail($id);
            abort_unless(
                in_array($memoria->estado->value, [
                    EstadoMemoria::EsperandoFirmas->value,
                    EstadoMemoria::Aprobada->value,
                    EstadoMemoria::Aprovada->value,
                ], true),
                422,
                'La memoria debe estar aprobada o en firmas antes de marcarse como lista.'
            );

            $memoria->update([
                'estado' => EstadoMemoria::Lista,
                'fecha_listo' => now()->toDateString(),
                'observaciones' => $request->input('observaciones', $memoria->observaciones),
            ]);

            LogAccion::create([
                'accion' => "Certificado físico de memoria #{$memoria->id_memoria} marcado como listo y retenido en ventanilla.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'El certificado físico quedó registrado como listo y retenido en ventanilla para entrega.');
    }

    public function marcarListaPorProyecto(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('jefe_ups'), 403, 'Solo el Jefe de UPS puede marcar el documento como listo para entrega.');

        $proyecto = Proyecto::query()->with('memoria')->findOrFail($id);
        abort_unless($proyecto->memoria, 404, 'El proyecto no tiene una memoria registrada.');

        return $this->marcarLista($request, (string) $proyecto->memoria->id_memoria);
    }

    public function entregar(Request $request, string $id): RedirectResponse
    {
        $datos = $request->validate([
            'fecha_entrega' => ['required', 'date', 'before_or_equal:today'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($id, $datos): void {
            $memoria = Memoria::query()->with('proyecto')->lockForUpdate()->findOrFail($id);
            $this->validarPermisoEntrega($memoria);

            $memoria->update([
                'estado' => EstadoMemoria::Entregada,
                'fecha_entrega' => $datos['fecha_entrega'],
                'observaciones' => $datos['observaciones'] ?? $memoria->observaciones,
            ]);

            $this->finalizarProyectoAutomaticamente($memoria, $datos['observaciones'] ?? null);

            LogAccion::create([
                'accion' => "Memoria #{$memoria->id_memoria} entregada al estudiante. Proyecto finalizado oficialmente.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'La memoria fue marcada como entregada y el proyecto quedó finalizado automáticamente.');
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
                $this->validarPermisoEntrega($memoria);

                $memoria->update([
                    'estado' => EstadoMemoria::Entregada,
                    'fecha_entrega' => $datos['fecha_entrega'],
                    'observaciones' => $datos['observaciones'] ?? $memoria->observaciones,
                ]);
            } else {
                abort_unless(auth()->user()->hasRole('jefe_ups'), 403, 'Solo el Jefe de UPS puede registrar y entregar directamente una memoria no radicada.');

                $memoria = Memoria::create([
                    'numero_proyecto' => $proyecto->numero_proyecto,
                    'registrada_por' => auth()->id(),
                    'fecha_recibimiento' => $datos['fecha_entrega'],
                    'fecha_aprovado' => $datos['fecha_entrega'],
                    'fecha_entrega' => $datos['fecha_entrega'],
                    'estado' => EstadoMemoria::Entregada,
                    'observaciones' => $datos['observaciones'] ?? null,
                ]);
            }

            $this->finalizarProyectoAutomaticamente($memoria, $datos['observaciones'] ?? null, $proyecto);

            LogAccion::create([
                'accion' => "Memoria final del proyecto #{$proyecto->numero_proyecto} entregada. Proyecto finalizado automáticamente.",
                'usuario' => auth()->id(),
            ]);
        });

        return back()->with('success', 'La memoria final fue entregada y el proyecto quedó finalizado oficialmente.');
    }

    /**
     * Valida permisos según rol:
     * - Jefe UPS y Auxiliar UPS pueden entregar si la memoria fue aprobada por la Jefatura
     *   (estados: aprobada, esperando_firmas, lista).
     */
    private function validarPermisoEntrega(Memoria $memoria): void
    {
        $user = auth()->user();
        abort_if($memoria->estado === EstadoMemoria::Entregada, 409, 'Esta memoria ya fue entregada.');

        if ($user->hasRole('jefe_ups') || $user->hasRole('auxiliar_ups')) {
            abort_unless(
                in_array($memoria->estado->value, [
                    EstadoMemoria::Aprobada->value,
                    EstadoMemoria::Aprovada->value,
                    EstadoMemoria::EsperandoFirmas->value,
                    EstadoMemoria::Lista->value,
                ], true),
                422,
                'La memoria debe ser aprobada por la Jefatura antes de entregarse.'
            );

            return;
        }

        abort(403, 'No tienes permisos para registrar la entrega de la memoria.');
    }

    /**
     * Finaliza automáticamente el proyecto asociado a la memoria.
     */
    private function finalizarProyectoAutomaticamente(Memoria $memoria, ?string $observaciones = null, ?Proyecto $proyectoInstancia = null): void
    {
        $proyecto = $proyectoInstancia ?? $memoria->proyecto ?? Proyecto::where('numero_proyecto', $memoria->numero_proyecto)->first();

        if (! $proyecto || $proyecto->estado_expediente === 'finalizado') {
            return;
        }

        if ($proyecto->fase_actual === 'memoria_final') {
            HistorialFaseProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => 'memoria_final',
                'evento' => 'fase_admitida',
                'observaciones' => 'Memoria final entregada físicamente.',
                'usuario_id' => auth()->id(),
            ]);
        }

        HistorialFaseProyecto::create([
            'proyecto_id' => $proyecto->id_proyecto,
            'fase' => 'finalizacion',
            'evento' => 'proyecto_finalizado',
            'observaciones' => $observaciones ?? 'Entrega física completada. Proyecto finalizado automáticamente.',
            'usuario_id' => auth()->id(),
        ]);

        $proyecto->update([
            'fase_actual' => 'finalizacion',
            'estado_expediente' => 'finalizado',
            'fecha_finalizacion' => now(),
            'fecha_final' => $proyecto->fecha_final ?? now()->toDateString(),
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
