<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use App\Models\Carrera;
use App\Models\Estudiante;
use App\Models\LogAccion;
use App\Models\Memoria;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'triaje' => ['nullable', Rule::in(['todos', 'urgentes', 'tramite', 'al_dia', 'concluidos'])],
            'orden' => ['nullable', Rule::in(['gravedad', 'recientes', 'antiguos', 'correlativo'])],
            'area_id' => ['nullable', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
            'fecha' => ['nullable', 'date'],
            'estado' => ['nullable', Rule::in(['sin_memoria', 'recibido', 'en_revision', 'observada', 'aprobada', 'esperando_firmas', 'lista', 'entregada', 'rechazada'])],
            'plazo' => ['nullable', Rule::in(['memoria', 'junta', 'alerta', 'sin_alerta'])],
        ]);

        $triajeActivo = $filtros['triaje'] ?? 'todos';
        $ordenActivo = $filtros['orden'] ?? 'gravedad';

        $fechaLimiteMemoria = now()->subMonths(3)->toDateString();
        $fechaLimiteJunta = now()->subMonths(18)->toDateString();

        $query = Proyecto::query()
            ->with([
                'areaConocimiento:id_area_conocimiento,nombre',
                'estudiantes:id_estudiante,due,nombre_completo',
                'memoria',
                'procesosExtraordinarios',
            ]);

        // 1. Omnibúsqueda universal
        if (! empty($filtros['search'])) {
            $search = trim((string) $filtros['search']);
            $searchNum = preg_replace('/\D/', '', $search);
            $driver = DB::connection()->getDriverName();
            $like = $driver === 'pgsql' ? 'ilike' : 'like';

            $query->where(function ($subquery) use ($search, $searchNum, $like): void {
                $subquery->where('titulo', $like, "%{$search}%")
                    ->orWhere('tutor', $like, "%{$search}%")
                    ->orWhere('subcoordinador', $like, "%{$search}%")
                    ->orWhere('ubicacion', $like, "%{$search}%")
                    ->orWhere('documento', $like, "%{$search}%")
                    ->orWhereHas('estudiantes', fn ($estudiantes) => $estudiantes
                        ->where('due', $like, "%{$search}%")
                        ->orWhere('nombre_completo', $like, "%{$search}%")
                    );

                if ($searchNum !== '') {
                    $subquery->orWhere('numero_proyecto', (int) $searchNum);
                }
            });
        }

        // 2. Filtro rápido de Triaje (Chips superiores)
        if ($triajeActivo === 'urgentes') {
            $query->where(function ($urgentesQuery) use ($fechaLimiteMemoria, $fechaLimiteJunta): void {
                $urgentesQuery->whereDate('fecha_inicio', '<=', $fechaLimiteJunta)
                    ->orWhere(fn ($q) => $q->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
                    ->orWhereHas('memoria', fn ($m) => $m->where('estado', EstadoMemoria::Observada->value));
            });
        } elseif ($triajeActivo === 'tramite') {
            $query->whereHas('memoria', fn ($m) => $m->whereIn('estado', [
                EstadoMemoria::Recibido->value,
                EstadoMemoria::EnRevision->value,
                EstadoMemoria::EsperandoFirmas->value,
                EstadoMemoria::Lista->value,
            ]));
        } elseif ($triajeActivo === 'concluidos') {
            $query->where(fn ($q) => $q->where('estado_expediente', 'finalizado')
                ->orWhereHas('memoria', fn ($m) => $m->where('estado', EstadoMemoria::Entregada->value)));
        } elseif ($triajeActivo === 'al_dia') {
            $query->where('estado_expediente', '!=', 'finalizado')
                ->whereDate('fecha_inicio', '>', $fechaLimiteJunta)
                ->where(function ($alDia) use ($fechaLimiteMemoria): void {
                    $alDia->whereDate('fecha_inicio', '>', $fechaLimiteMemoria)
                        ->orWhereHas('memoria', fn ($m) => $m->whereNotIn('estado', [
                            EstadoMemoria::Observada->value,
                            EstadoMemoria::Entregada->value,
                        ]));
                });
        }

        // 3. Filtros secundarios (en caso de usarse)
        $query->when($filtros['area_id'] ?? null, fn ($q, $id) => $q->where('area_de_conocimiento', $id))
            ->when($filtros['fecha'] ?? null, fn ($q, $fecha) => $q->whereDate('fecha_inicio', $fecha))
            ->when($filtros['estado'] ?? null, function ($q, string $estado): void {
                if ($estado === 'sin_memoria') {
                    $q->whereDoesntHave('memoria');

                    return;
                }

                $estados = $estado === 'aprobada'
                    ? [EstadoMemoria::Aprobada->value, EstadoMemoria::Aprovada->value]
                    : [$estado];
                $q->whereHas('memoria', fn ($memorias) => $memorias->whereIn('estado', $estados));
            })
            ->when(($filtros['plazo'] ?? null) === 'memoria', fn ($q) => $q->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
            ->when(($filtros['plazo'] ?? null) === 'junta', fn ($q) => $q->whereDate('fecha_inicio', '<=', $fechaLimiteJunta))
            ->when(($filtros['plazo'] ?? null) === 'alerta', fn ($q) => $q->where(fn ($alertas) => $alertas
                ->where(fn ($memoriaVencida) => $memoriaVencida->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
                ->orWhereDate('fecha_inicio', '<=', $fechaLimiteJunta)))
            ->when(($filtros['plazo'] ?? null) === 'sin_alerta', fn ($q) => $q->whereDate('fecha_inicio', '>', $fechaLimiteJunta)
                ->where(fn ($sinAlertaMemoria) => $sinAlertaMemoria->whereDate('fecha_inicio', '>', $fechaLimiteMemoria)->orWhereHas('memoria')));

        // 4. Algoritmo de Ordenamiento por Gravedad (Default)
        if ($ordenActivo === 'gravedad') {
            $query->orderByRaw("
                CASE
                    WHEN fecha_inicio <= '{$fechaLimiteJunta}' AND fecha_remision_junta IS NULL THEN 1
                    WHEN fecha_inicio <= '{$fechaLimiteMemoria}' AND NOT EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto) THEN 2
                    WHEN EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto AND memorias.estado = 'observada') THEN 3
                    WHEN EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto AND memorias.estado IN ('recibido', 'en_revision', 'esperando_firmas')) THEN 4
                    WHEN estado_expediente = 'finalizado' OR EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto AND memorias.estado = 'entregada') THEN 6
                    ELSE 5
                END ASC, fecha_inicio ASC
            ");
        } elseif ($ordenActivo === 'recientes') {
            $query->orderByDesc('fecha_inicio');
        } elseif ($ordenActivo === 'antiguos') {
            $query->orderBy('fecha_inicio');
        } elseif ($ordenActivo === 'correlativo') {
            $query->orderByDesc('numero_proyecto');
        }

        $proyectos = $query->paginate(100)->withQueryString();

        // 5. Conteos para chips de triaje superior
        $conteos = [
            'todos' => Proyecto::count(),
            'urgentes' => Proyecto::query()
                ->where(function ($q) use ($fechaLimiteMemoria, $fechaLimiteJunta): void {
                    $q->whereDate('fecha_inicio', '<=', $fechaLimiteJunta)
                        ->orWhere(fn ($sub) => $sub->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
                        ->orWhereHas('memoria', fn ($m) => $m->where('estado', EstadoMemoria::Observada->value));
                })->count(),
            'tramite' => Proyecto::query()
                ->whereHas('memoria', fn ($m) => $m->whereIn('estado', [
                    EstadoMemoria::Recibido->value,
                    EstadoMemoria::EnRevision->value,
                    EstadoMemoria::EsperandoFirmas->value,
                    EstadoMemoria::Lista->value,
                ]))->count(),
            'concluidos' => Proyecto::query()
                ->where(fn ($q) => $q->where('estado_expediente', 'finalizado')
                    ->orWhereHas('memoria', fn ($m) => $m->where('estado', EstadoMemoria::Entregada->value)))
                ->count(),
        ];
        $conteos['al_dia'] = max(0, $conteos['todos'] - $conteos['urgentes'] - $conteos['concluidos']);

        return view('ups.proyectos.index', [
            'proyectos' => $proyectos,
            'areas' => AreaConocimiento::query()->where('activa', true)->orderBy('nombre')->get(),
            'filtros' => $filtros,
            'conteos' => $conteos,
            'triajeActivo' => $triajeActivo,
            'ordenActivo' => $ordenActivo,
        ]);
    }

    public function create(): View
    {
        $siguienteNumero = (Proyecto::max('numero_proyecto') ?? 0) + 1;
        $participantesIds = (array) old('estudiantes', []);
        $participantesSeleccionados = ! empty($participantesIds)
            ? Estudiante::with('carreras')->whereIn('id_estudiante', $participantesIds)->get()
            : collect();

        return view('ups.proyectos.form', [
            'proyecto' => new Proyecto(['numero_proyecto' => $siguienteNumero]),
            'siguienteNumero' => $siguienteNumero,
            'areas' => AreaConocimiento::query()->where('activa', true)->orderBy('nombre')->get(),
            'carreras' => Carrera::query()->orderBy('nombre')->get(),
            'participantesSeleccionados' => $participantesSeleccionados,
            'modoEdicion' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->procesarNuevosEstudiantes($request);
        $datos = $request->validate($this->reglasProyecto(true));
        $participantes = array_values(array_unique($datos['estudiantes']));
        $archivo = $datos['archivo_documento'] ?? null;
        unset($datos['estudiantes']);
        unset($datos['archivo_documento']);

        $proyecto = DB::transaction(function () use ($datos, $participantes, $archivo): Proyecto {
            $proyecto = Proyecto::create([...$datos, 'creado_por' => auth()->id()]);
            $proyecto->estudiantes()->sync($participantes);
            if ($archivo) {
                $this->guardarDocumento($proyecto, $archivo, 'inscripcion');
            }
            $this->registrarCambio("Apertura del expediente del proyecto #{$proyecto->numero_proyecto} (ID #{$proyecto->id_proyecto}): {$proyecto->titulo}");

            return $proyecto;
        });

        return redirect()->route('ups.proyectos.show', $proyecto->id_proyecto)->with('success', 'El proyecto y sus participantes quedaron registrados.');
    }

    public function show(string $id): View
    {
        $proyecto = Proyecto::query()->with([
            'areaConocimiento', 'estudiantes.carreras', 'memoria', 'creador', 'documentos',
            'historialFases', 'procesosExtraordinarios',
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

    private function historialProyecto(Proyecto $proyecto): Collection
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
        $proyecto = Proyecto::query()->with('estudiantes.carreras')->findOrFail($id);
        abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El expediente está cerrado y no se puede editar.');

        $participantesIds = old('estudiantes');
        $participantesSeleccionados = $participantesIds !== null
            ? Estudiante::with('carreras')->whereIn('id_estudiante', (array) $participantesIds)->get()
            : $proyecto->estudiantes;

        return view('ups.proyectos.form', [
            'proyecto' => $proyecto,
            'siguienteNumero' => $proyecto->numero_proyecto,
            'areas' => AreaConocimiento::query()->where(fn ($query) => $query->where('activa', true)->orWhere('id_area_conocimiento', $proyecto->area_de_conocimiento))->orderBy('nombre')->get(),
            'carreras' => Carrera::query()->orderBy('nombre')->get(),
            'participantesSeleccionados' => $participantesSeleccionados,
            'modoEdicion' => true,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $proyecto = Proyecto::findOrFail($id);
        abort_unless($proyecto->estado_expediente === 'en_tramite', 409, 'El expediente está cerrado y no se puede editar.');
        $this->procesarNuevosEstudiantes($request);
        $datos = $request->validate($this->reglasProyecto(false, $proyecto));
        $participantes = array_values(array_unique($datos['estudiantes']));
        $archivo = $datos['archivo_documento'] ?? null;
        unset($datos['estudiantes']);
        unset($datos['archivo_documento']);

        DB::transaction(function () use ($proyecto, $datos, $participantes, $archivo): void {
            $proyecto->update($datos);
            $proyecto->estudiantes()->sync($participantes);
            if ($archivo) {
                $this->guardarDocumento($proyecto, $archivo, $proyecto->fase_actual);
            }
            $this->registrarCambio("Actualización de datos autorizados del proyecto #{$proyecto->numero_proyecto} (ID #{$proyecto->id_proyecto}): {$proyecto->titulo}");
        });

        return redirect()->route('ups.proyectos.show', $proyecto->id_proyecto)->with('success', 'Se actualizaron los datos del proyecto.');
    }

    private function procesarNuevosEstudiantes(Request $request): void
    {
        $nuevos = $request->input('nuevos_estudiantes', []);
        if (! is_array($nuevos) || empty($nuevos)) {
            return;
        }

        $estudiantesIds = (array) $request->input('estudiantes', []);

        foreach ($nuevos as $nuevo) {
            $due = strtoupper(trim((string) ($nuevo['due'] ?? '')));
            $nombre = trim((string) ($nuevo['nombre_completo'] ?? ''));
            if ($due === '' || $nombre === '') {
                continue;
            }

            $estudiante = Estudiante::firstOrCreate(
                ['due' => $due],
                ['nombre_completo' => $nombre]
            );

            if (! empty($nuevo['carrera_id'])) {
                $estudiante->carreras()->syncWithoutDetaching([(int) $nuevo['carrera_id']]);
            }

            $estudiantesIds[] = $estudiante->id_estudiante;
        }

        $request->merge(['estudiantes' => array_values(array_unique($estudiantesIds))]);
    }

    private function reglasProyecto(bool $incluyeInicio, ?Proyecto $proyecto = null): array
    {
        return [
            'numero_proyecto' => [
                'required',
                'integer',
                'min:1',
                $proyecto
                    ? Rule::unique('proyectos', 'numero_proyecto')->ignore($proyecto->id_proyecto, 'id_proyecto')
                    : 'unique:proyectos,numero_proyecto',
            ],
            'titulo' => ['required', 'string', 'max:255'],
            'fecha_inicio' => [$incluyeInicio ? 'required' : 'sometimes', 'date'],
            'fecha_final' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'area_de_conocimiento' => ['required', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
            'tutor' => ['required', 'string', 'max:255'],
            'subcoordinador' => ['required', 'string', 'max:255'],
            'correo_coordinador' => ['nullable', 'email', 'max:255'],
            'ubicacion' => ['required', 'string', 'max:500'],
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
        $faseNormalizada = in_array($fase, ['inscripcion', 'anteproyecto', 'avance_50', 'memoria_final'], true) ? $fase : 'otro';
        $ruta = $archivo->store("proyectos/{$proyecto->id_proyecto}", 'expedientes_ups');
        $proyecto->documentos()->create([
            'fase' => $faseNormalizada,
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta' => $ruta,
            'mime_type' => 'application/pdf',
            'tamano_bytes' => $archivo->getSize(),
            'usuario_id' => auth()->id(),
        ]);

        if ($faseNormalizada === 'memoria_final') {
            $memoriaExistente = $proyecto->memoria;
            $intento = $memoriaExistente
                ? ($memoriaExistente->intento + ($memoriaExistente->estado === EstadoMemoria::Observada ? 1 : 0))
                : 1;

            Memoria::updateOrCreate(
                ['numero_proyecto' => $proyecto->numero_proyecto],
                [
                    'registrada_por' => auth()->id(),
                    'fecha_recibimiento' => now()->toDateString(),
                    'estado' => EstadoMemoria::Recibido,
                    'documento' => $ruta,
                    'intento' => $intento,
                ]
            );
        }
    }
}
