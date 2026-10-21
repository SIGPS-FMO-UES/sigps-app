<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use App\Models\Estudiante;
use App\Models\LogAccion;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'area_id' => ['nullable', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
            'fecha' => ['nullable', 'date'],
            'estado' => ['nullable', Rule::in(['sin_memoria', 'recibido', 'en_revision', 'observada', 'aprobada', 'esperando_firmas', 'lista', 'entregada', 'rechazada'])],
            'plazo' => ['nullable', Rule::in(['memoria', 'junta', 'alerta', 'sin_alerta'])],
        ]);

        $fechaLimiteMemoria = now()->subMonths(3)->toDateString();
        $fechaLimiteJunta = now()->subMonths(18)->toDateString();

        $proyectos = Proyecto::query()
            ->with(['areaConocimiento', 'estudiantes', 'memoria'])
            ->when($filtros['search'] ?? null, fn ($query, $search) => $query->where(fn ($subquery) => $subquery->where('titulo', 'ilike', "%{$search}%")
                ->orWhereHas('estudiantes', fn ($estudiantes) => $estudiantes->where('due', 'ilike', "%{$search}%")->orWhere('nombre_completo', 'ilike', "%{$search}%"))))
            ->when($filtros['area_id'] ?? null, fn ($query, $id) => $query->where('area_de_conocimiento', $id))
            ->when($filtros['fecha'] ?? null, fn ($query, $fecha) => $query->whereDate('fecha_inicio', $fecha))
            ->when($filtros['estado'] ?? null, function ($query, string $estado): void {
                if ($estado === 'sin_memoria') {
                    $query->whereDoesntHave('memoria');

                    return;
                }

                $estados = $estado === 'aprobada'
                    ? [EstadoMemoria::Aprobada->value, EstadoMemoria::Aprovada->value]
                    : [$estado];
                $query->whereHas('memoria', fn ($memorias) => $memorias->whereIn('estado', $estados));
            })
            ->when(($filtros['plazo'] ?? null) === 'memoria', fn ($query) => $query->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
            ->when(($filtros['plazo'] ?? null) === 'junta', fn ($query) => $query->whereDate('fecha_inicio', '<=', $fechaLimiteJunta))
            ->when(($filtros['plazo'] ?? null) === 'alerta', fn ($query) => $query->where(fn ($alertas) => $alertas
                ->where(fn ($memoriaVencida) => $memoriaVencida->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
                ->orWhereDate('fecha_inicio', '<=', $fechaLimiteJunta)))
            ->when(($filtros['plazo'] ?? null) === 'sin_alerta', fn ($query) => $query->whereDate('fecha_inicio', '>', $fechaLimiteJunta)
                ->where(fn ($sinAlertaMemoria) => $sinAlertaMemoria->whereDate('fecha_inicio', '>', $fechaLimiteMemoria)->orWhereHas('memoria')))
            ->latest('fecha_inicio')
            ->paginate(12)
            ->withQueryString();

        return view('ups.proyectos.index', [
            'proyectos' => $proyectos,
            'areas' => AreaConocimiento::query()->where('activa', true)->orderBy('nombre')->get(),
            'filtros' => $filtros,
        ]);
    }

    public function create(): View
    {
        return view('ups.proyectos.form', [
            'proyecto' => new Proyecto,
            'areas' => AreaConocimiento::query()->where('activa', true)->orderBy('nombre')->get(),
            'estudiantes' => Estudiante::query()->orderBy('nombre_completo')->get(),
            'participantesSeleccionados' => [],
            'modoEdicion' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglasProyecto(true));
        $participantes = $datos['estudiantes'];
        $archivo = $datos['archivo_documento'] ?? null;
        unset($datos['estudiantes']);
        unset($datos['archivo_documento']);

        $proyecto = DB::transaction(function () use ($datos, $participantes, $archivo): Proyecto {
            $proyecto = Proyecto::create([...$datos, 'creado_por' => auth()->id()]);
            $proyecto->estudiantes()->sync($participantes);
            if ($archivo) {
                $this->guardarDocumento($proyecto, $archivo, 'inscripcion');
            }
            $this->registrarCambio("Apertura del expediente del proyecto #{$proyecto->id_proyecto}: {$proyecto->titulo}");

            return $proyecto;
        });

        return redirect()->route('ups.proyectos.show', $proyecto->id_proyecto)->with('success', 'El proyecto y sus participantes quedaron registrados.');
    }

    public function show(string $id): View
    {
        $proyecto = Proyecto::query()->with([
            'areaConocimiento', 'estudiantes.carreras', 'memoria.revisiones.auditor', 'creador', 'documentos',
            'historialFases',
        ])->findOrFail($id);

        return view('ups.proyectos.show', compact('proyecto'));
    }

    public function historial(string $id): View
    {
        $proyecto = Proyecto::query()->with([
            'areaConocimiento', 'historialFases.usuario', 'procesosExtraordinarios.estudiante',
            'procesosExtraordinarios.creador', 'procesosExtraordinarios.resolutor',
        ])->findOrFail($id);

        return view('ups.proyectos.historial', [
            'proyecto' => $proyecto,
            'historialProyecto' => $this->historialProyecto($proyecto),
        ]);
    }

    private function historialProyecto(Proyecto $proyecto): \Illuminate\Support\Collection
    {
        return $proyecto->historialFases->reject(fn ($movimiento): bool => in_array($movimiento->evento, [
            'proyecto_cancelado', 'estudiante_retirado', 'prorroga_registrada', 'ampliacion_aprobada', 'ampliacion_rechazada',
        ], true))->map(fn ($movimiento): array => [
            'fecha' => $movimiento->created_at,
            'cambio' => ['fase_admitida' => 'Fase admitida', 'proyecto_finalizado' => 'Proyecto finalizado', 'proyecto_cancelado' => 'Proyecto cancelado', 'estudiante_retirado' => 'Estudiante retirado', 'observaciones_guardadas' => 'Observaciones guardadas', 'ampliacion_aprobada' => 'Ampliación aprobada', 'ampliacion_rechazada' => 'Ampliación rechazada', 'prorroga_registrada' => 'Prórroga registrada'][$movimiento->evento] ?? ucfirst(str_replace('_', ' ', $movimiento->evento)),
            'fase' => str_replace('_', ' ', ucfirst($movimiento->fase)),
            'responsable' => $movimiento->usuario?->nombre_completo ?? '—',
            'estado' => null,
            'detalle' => $movimiento->observaciones ?: '—',
        ])->concat($proyecto->procesosExtraordinarios->map(fn ($proceso): array => [
            'fecha' => $proceso->created_at,
            'cambio' => ['retiro_estudiante' => 'Retiro de estudiante', 'cancelacion' => 'Cancelación', 'ampliacion_plazo' => 'Solicitud de ampliación', 'prorroga' => 'Prórroga'][$proceso->tipo] ?? $proceso->tipo,
            'fase' => null,
            'responsable' => collect([$proceso->creador?->nombre_completo, $proceso->resolutor?->nombre_completo])->filter()->unique()->implode(' · ') ?: '—',
            'estado' => ucfirst($proceso->estado),
            'detalle' => collect([$proceso->estudiante?->nombre_completo, $proceso->motivo ?: $proceso->resolucion, $proceso->fecha_nueva ? 'Nueva fecha: '.$proceso->fecha_nueva->format('d/m/Y') : null])->filter()->implode(' · ') ?: '—',
        ]))->sortByDesc('fecha')->values();
    }

    public function edit(string $id): View
    {
        $proyecto = Proyecto::query()->with('estudiantes')->findOrFail($id);
        abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El expediente está cerrado y no se puede editar.');

        return view('ups.proyectos.form', [
            'proyecto' => $proyecto,
            'areas' => AreaConocimiento::query()->where(fn ($query) => $query->where('activa', true)->orWhere('id_area_conocimiento', $proyecto->area_de_conocimiento))->orderBy('nombre')->get(),
            'estudiantes' => Estudiante::query()->orderBy('nombre_completo')->get(),
            'participantesSeleccionados' => $proyecto->estudiantes->modelKeys(),
            'modoEdicion' => true,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $proyecto = Proyecto::findOrFail($id);
        abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El expediente está cerrado y no se puede editar.');
        $datos = $request->validate($this->reglasProyecto(false));
        $participantes = $datos['estudiantes'];
        $archivo = $datos['archivo_documento'] ?? null;
        unset($datos['estudiantes']);
        unset($datos['archivo_documento']);

        DB::transaction(function () use ($proyecto, $datos, $participantes, $archivo): void {
            $proyecto->update($datos);
            $proyecto->estudiantes()->sync($participantes);
            if ($archivo) {
                $this->guardarDocumento($proyecto, $archivo, $proyecto->fase_actual);
            }
            $this->registrarCambio("Actualización de datos autorizados del proyecto #{$proyecto->id_proyecto}: {$proyecto->titulo}");
        });

        return redirect()->route('ups.proyectos.show', $proyecto->id_proyecto)->with('success', 'Se actualizaron los datos del proyecto.');
    }

    private function reglasProyecto(bool $incluyeInicio): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'fecha_inicio' => [$incluyeInicio ? 'required' : 'sometimes', 'date'],
            'fecha_final' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'area_de_conocimiento' => ['required', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
            'subcoordinador' => ['required', 'string', 'max:255'],
            'documento' => ['nullable', 'string', 'max:255'],
            'archivo_documento' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
            'estudiantes' => ['required', 'array', 'min:1'],
            'estudiantes.*' => ['required', 'integer', 'distinct', 'exists:estudiantes,id_estudiante'],
        ];
    }

    private function registrarCambio(string $accion): void
    {
        LogAccion::create(['accion' => $accion, 'usuario' => auth()->id()]);
    }

    private function guardarDocumento(Proyecto $proyecto, UploadedFile $archivo, string $fase): void
    {
        $ruta = $archivo->store("proyectos/{$proyecto->id_proyecto}", 'expedientes_ups');
        $proyecto->documentos()->create([
            'fase' => in_array($fase, ['inscripcion', 'anteproyecto', 'avance_50', 'memoria_final'], true) ? $fase : 'otro',
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta' => $ruta,
            'mime_type' => 'application/pdf',
            'tamano_bytes' => $archivo->getSize(),
            'usuario_id' => auth()->id(),
        ]);
    }
}
