<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use App\Models\Carrera;
use App\Models\Certificacion;
use App\Models\Departamento;
use App\Models\Proyecto;
use App\Services\EstadisticasExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EstadisticasController extends Controller
{
    /**
     * Muestra el panel interactivo de estadísticas de Proyectos y Reporte Oficial MINED.
     */
    public function index(Request $request): View
    {
        $filtros = $this->obtenerFiltros($request);

        $departamentos = Departamento::query()->orderBy('nombre')->get();
        $carreras = Carrera::query()->with('departamentoRel')->orderBy('nombre')->get();
        $areas = AreaConocimiento::query()->orderBy('nombre')->get();

        $plantillasExportacion = EstadisticasExportService::plantillasDisponibles();

        // Si la pestaña activa es MINED
        if ($filtros['tab'] === 'mined') {
            $datosMined = $this->calcularEstadisticasMined($filtros);

            return view('ups.estadisticas.index', array_merge([
                'filtros' => $filtros,
                'departamentos' => $departamentos,
                'carreras' => $carreras,
                'areas' => $areas,
                'plantillasExportacion' => $plantillasExportacion,
            ], $datosMined));
        }

        // Pestaña predeterminada: Analítica de Proyectos
        $datosProyectos = $this->calcularEstadisticasProyectos($filtros);

        return view('ups.estadisticas.index', array_merge([
            'filtros' => $filtros,
            'departamentos' => $departamentos,
            'carreras' => $carreras,
            'areas' => $areas,
            'plantillasExportacion' => $plantillasExportacion,
        ], $datosProyectos));
    }

    /**
     * Exporta los datos estadísticos seleccionados en formato Excel oficial (.xlsx) a partir de plantilla o en CSV.
     */
    public function exportar(Request $request, EstadisticasExportService $exportService): StreamedResponse
    {
        $filtros = $this->obtenerFiltros($request);
        $tipo = $request->input('tipo', $request->input('formato', 'alumnos_departamento'));

        if ($tipo === 'certificaciones_csv' || $tipo === 'csv') {
            return $exportService->exportarCertificacionesCsv($filtros);
        }

        if ($tipo === 'alumnos_area' || $tipo === 'alumnos_area_conocimiento') {
            $areaId = $filtros['area_id'];

            return $exportService->exportarListaAlumnosPorAreaConocimiento($areaId, $filtros);
        }

        $departamentoId = $filtros['departamento_id'];

        return $exportService->exportarListaAlumnosPorDepartamento($departamentoId, $filtros);
    }

    /**
     * Normaliza y valida los filtros de búsqueda y período.
     *
     * @return array<string, mixed>
     */
    private function obtenerFiltros(Request $request): array
    {
        $tab = $request->input('tab', 'proyectos');
        if (! in_array($tab, ['proyectos', 'mined'], true)) {
            $tab = 'proyectos';
        }

        $periodo = $request->input('periodo', 'todos');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        // Mapeo automático de períodos rápidos
        if ($periodo === '2026') {
            $desde = '2026-01-01';
            $hasta = '2026-12-31';
        } elseif ($periodo === '2025') {
            $desde = '2025-01-01';
            $hasta = '2025-12-31';
        } elseif ($periodo === '2024') {
            $desde = '2024-01-01';
            $hasta = '2024-12-31';
        } elseif ($periodo === 'mes_actual') {
            $desde = now()->startOfMonth()->toDateString();
            $hasta = now()->endOfMonth()->toDateString();
        } elseif ($periodo === 'todos') {
            $desde = null;
            $hasta = null;
        }

        $departamentoId = $request->filled('departamento_id') && $request->input('departamento_id') !== 'todos'
            ? (int) $request->input('departamento_id')
            : null;

        $carreraId = $request->filled('carrera_id') && $request->input('carrera_id') !== 'todas'
            ? (int) $request->input('carrera_id')
            : null;

        $areaId = $request->filled('area_id') && $request->input('area_id') !== 'todas'
            ? (int) $request->input('area_id')
            : null;

        return [
            'tab' => $tab,
            'departamento_id' => $departamentoId,
            'carrera_id' => $carreraId,
            'area_id' => $areaId,
            'periodo' => $periodo,
            'desde' => $desde,
            'hasta' => $hasta,
            'fase_proyecto' => $request->input('fase_proyecto', 'todas'),
            'estado_mined' => $request->input('estado_mined', 'todos'),
        ];
    }

    /**
     * Calcula las métricas, distribuciones y listados para la pestaña de Proyectos.
     *
     * @param  array<string, mixed>  $filtros
     * @return array<string, mixed>
     */
    private function calcularEstadisticasProyectos(array $filtros): array
    {
        $query = Proyecto::query()
            ->with([
                'areaConocimiento',
                'estudiantes.carreras.departamentoRel',
            ])
            ->when($filtros['area_id'], fn ($q, $id) => $q->where('area_de_conocimiento', $id))
            ->when($filtros['carrera_id'], fn ($q, $id) => $q->whereHas('estudiantes.carreras', fn ($c) => $c->where('carreras.id_carrera', $id)))
            ->when($filtros['departamento_id'], fn ($q, $id) => $q->whereHas('estudiantes.carreras', fn ($c) => $c->where('carreras.departamento', $id)))
            ->when($filtros['desde'], fn ($q, $date) => $q->whereDate('fecha_inicio', '>=', $date))
            ->when($filtros['hasta'], fn ($q, $date) => $q->whereDate('fecha_inicio', '<=', $date))
            ->when($filtros['fase_proyecto'] !== 'todas', fn ($q) => $q->where('fase_actual', $filtros['fase_proyecto']));

        $totalProyectos = (clone $query)->count();

        // Distribución por Fase Actual
        $fasesRaw = (clone $query)
            ->selectRaw('fase_actual, count(*) as total')
            ->groupBy('fase_actual')
            ->pluck('total', 'fase_actual')
            ->toArray();

        $fases = [
            'inscripcion' => (int) ($fasesRaw['inscripcion'] ?? 0),
            'ejecucion' => (int) ($fasesRaw['ejecucion'] ?? 0),
            'memoria' => (int) ($fasesRaw['memoria'] ?? 0),
            'finalizado' => (int) ($fasesRaw['finalizado'] ?? 0),
        ];

        // Distribución por Estado de Expediente
        $estadosRaw = (clone $query)
            ->selectRaw('estado_expediente, count(*) as total')
            ->groupBy('estado_expediente')
            ->pluck('total', 'estado_expediente')
            ->toArray();

        $estados = [
            'en_tramite' => (int) ($estadosRaw['en_tramite'] ?? 0),
            'finalizado' => (int) ($estadosRaw['finalizado'] ?? 0),
            'cancelado' => (int) ($estadosRaw['cancelado'] ?? 0),
        ];

        // Total de estudiantes participantes distintos en los proyectos filtrados
        $totalEstudiantes = DB::table('proyectos_estudiantes')
            ->join('proyectos', 'proyectos_estudiantes.id_proyecto', '=', 'proyectos.id_proyecto')
            ->whereNull('proyectos.deleted_at')
            ->when($filtros['area_id'], fn ($q, $id) => $q->where('proyectos.area_de_conocimiento', $id))
            ->when($filtros['carrera_id'], fn ($q, $id) => $q->whereExists(function ($sub) use ($id) {
                $sub->select(DB::raw(1))
                    ->from('carreras_estudiante')
                    ->whereColumn('carreras_estudiante.id_estudiante', 'proyectos_estudiantes.id_estudiante')
                    ->where('carreras_estudiante.id_carrera', $id);
            }))
            ->when($filtros['departamento_id'], fn ($q, $id) => $q->whereExists(function ($sub) use ($id) {
                $sub->select(DB::raw(1))
                    ->from('carreras_estudiante')
                    ->join('carreras', 'carreras_estudiante.id_carrera', '=', 'carreras.id_carrera')
                    ->whereColumn('carreras_estudiante.id_estudiante', 'proyectos_estudiantes.id_estudiante')
                    ->where('carreras.departamento', $id);
            }))
            ->when($filtros['desde'], fn ($q, $date) => $q->whereDate('proyectos.fecha_inicio', '>=', $date))
            ->when($filtros['hasta'], fn ($q, $date) => $q->whereDate('proyectos.fecha_inicio', '<=', $date))
            ->when($filtros['fase_proyecto'] !== 'todas', fn ($q) => $q->where('proyectos.fase_actual', $filtros['fase_proyecto']))
            ->distinct('proyectos_estudiantes.id_estudiante')
            ->count('proyectos_estudiantes.id_estudiante');

        // Desglose por Área de Conocimiento
        $desgloseAreas = (clone $query)
            ->selectRaw('area_de_conocimiento, count(*) as total')
            ->groupBy('area_de_conocimiento')
            ->with('areaConocimiento')
            ->get()
            ->map(fn ($row) => [
                'id_area' => $row->area_de_conocimiento,
                'nombre' => $row->areaConocimiento?->nombre ?? 'Sin área asignada',
                'total' => $row->total,
                'porcentaje' => $totalProyectos > 0 ? round(($row->total / $totalProyectos) * 100, 1) : 0,
            ])
            ->sortByDesc('total')
            ->values();

        // Desglose por Carreras
        $desgloseCarreras = DB::table('carreras')
            ->join('carreras_estudiante', 'carreras.id_carrera', '=', 'carreras_estudiante.id_carrera')
            ->join('proyectos_estudiantes', 'carreras_estudiante.id_estudiante', '=', 'proyectos_estudiantes.id_estudiante')
            ->join('proyectos', 'proyectos_estudiantes.id_proyecto', '=', 'proyectos.id_proyecto')
            ->whereNull('proyectos.deleted_at')
            ->when($filtros['area_id'], fn ($q, $id) => $q->where('proyectos.area_de_conocimiento', $id))
            ->when($filtros['carrera_id'], fn ($q, $id) => $q->where('carreras.id_carrera', $id))
            ->when($filtros['departamento_id'], fn ($q, $id) => $q->where('carreras.departamento', $id))
            ->when($filtros['desde'], fn ($q, $date) => $q->whereDate('proyectos.fecha_inicio', '>=', $date))
            ->when($filtros['hasta'], fn ($q, $date) => $q->whereDate('proyectos.fecha_inicio', '<=', $date))
            ->when($filtros['fase_proyecto'] !== 'todas', fn ($q) => $q->where('proyectos.fase_actual', $filtros['fase_proyecto']))
            ->selectRaw('
                carreras.id_carrera,
                carreras.nombre,
                carreras.codigo,
                count(distinct proyectos.id_proyecto) as total_proyectos,
                count(distinct proyectos_estudiantes.id_estudiante) as total_estudiantes
            ')
            ->groupBy('carreras.id_carrera', 'carreras.nombre', 'carreras.codigo')
            ->orderByDesc('total_proyectos')
            ->get();

        // Lista paginada de proyectos
        $proyectos = (clone $query)
            ->orderByDesc('fecha_inicio')
            ->paginate(15, ['*'], 'proyectos_page')
            ->withQueryString();

        $carreraLider = $desgloseCarreras->first();
        $promedioProyectosPorCarrera = $desgloseCarreras->count() > 0 ? round($totalProyectos / $desgloseCarreras->count(), 1) : 0;

        return [
            'totalProyectos' => $totalProyectos,
            'fases' => $fases,
            'estados' => $estados,
            'totalEstudiantes' => $totalEstudiantes,
            'desgloseAreas' => $desgloseAreas,
            'desgloseCarreras' => $desgloseCarreras,
            'carreraLider' => $carreraLider,
            'promedioProyectosPorCarrera' => $promedioProyectosPorCarrera,
            'proyectos' => $proyectos,
        ];
    }

    /**
     * Calcula las métricas de Certificaciones para el Informe Oficial MINED.
     * Cuánto se hicieron, cuánto se entregaron y cuánto están sin entregar.
     *
     * @param  array<string, mixed>  $filtros
     * @return array<string, mixed>
     */
    private function calcularEstadisticasMined(array $filtros): array
    {
        $baseQuery = Certificacion::query()
            ->with([
                'proyecto.areaConocimiento',
                'estudiante.carreras.departamentoRel',
                'emisor',
                'receptorRegistradoPor',
            ])
            ->whereHas('proyecto')
            ->when($filtros['area_id'], fn ($q, $id) => $q->whereHas('proyecto', fn ($p) => $p->where('area_de_conocimiento', $id)))
            ->when($filtros['carrera_id'], fn ($q, $id) => $q->whereHas('estudiante.carreras', fn ($c) => $c->where('carreras.id_carrera', $id)))
            ->when($filtros['departamento_id'], fn ($q, $id) => $q->whereHas('estudiante.carreras', fn ($c) => $c->where('carreras.departamento', $id)))
            ->when($filtros['desde'], fn ($q, $date) => $q->whereDate('fecha_emision', '>=', $date))
            ->when($filtros['hasta'], fn ($q, $date) => $q->whereDate('fecha_emision', '<=', $date));

        // Las 3 métricas oficiales requeridas por MINED:
        $minedHicieron = (clone $baseQuery)->count();
        $minedEntregaron = (clone $baseQuery)->whereNotNull('fecha_entrega')->count();
        $minedSinEntregar = (clone $baseQuery)->whereNull('fecha_entrega')->count();

        $tasaEntrega = $minedHicieron > 0 ? round(($minedEntregaron / $minedHicieron) * 100, 1) : 0;
        $tasaSinEntregar = $minedHicieron > 0 ? round(($minedSinEntregar / $minedHicieron) * 100, 1) : 0;

        // Desglose MINED por Carrera
        $minedPorCarrera = DB::table('certificaciones')
            ->join('estudiantes', 'certificaciones.estudiante_id', '=', 'estudiantes.id_estudiante')
            ->join('carreras_estudiante', 'estudiantes.id_estudiante', '=', 'carreras_estudiante.id_estudiante')
            ->join('carreras', 'carreras_estudiante.id_carrera', '=', 'carreras.id_carrera')
            ->join('proyectos', 'certificaciones.proyecto_id', '=', 'proyectos.id_proyecto')
            ->whereNull('proyectos.deleted_at')
            ->when($filtros['area_id'], fn ($q, $id) => $q->where('proyectos.area_de_conocimiento', $id))
            ->when($filtros['carrera_id'], fn ($q, $id) => $q->where('carreras.id_carrera', $id))
            ->when($filtros['departamento_id'], fn ($q, $id) => $q->where('carreras.departamento', $id))
            ->when($filtros['desde'], fn ($q, $date) => $q->whereDate('certificaciones.fecha_emision', '>=', $date))
            ->when($filtros['hasta'], fn ($q, $date) => $q->whereDate('certificaciones.fecha_emision', '<=', $date))
            ->selectRaw('
                carreras.id_carrera,
                carreras.nombre,
                carreras.codigo,
                count(distinct certificaciones.id_certificacion) as total_hechas,
                count(distinct case when certificaciones.fecha_entrega is not null then certificaciones.id_certificacion end) as total_entregadas,
                count(distinct case when certificaciones.fecha_entrega is null then certificaciones.id_certificacion end) as total_sin_entregar
            ')
            ->groupBy('carreras.id_carrera', 'carreras.nombre', 'carreras.codigo')
            ->orderByDesc('total_hechas')
            ->get()
            ->map(function ($row) {
                $row->tasa_entrega = $row->total_hechas > 0 ? round(($row->total_entregadas / $row->total_hechas) * 100, 1) : 0;

                return $row;
            });

        // Desglose MINED por Departamento
        $minedPorDepartamento = DB::table('certificaciones')
            ->join('estudiantes', 'certificaciones.estudiante_id', '=', 'estudiantes.id_estudiante')
            ->join('carreras_estudiante', 'estudiantes.id_estudiante', '=', 'carreras_estudiante.id_estudiante')
            ->join('carreras', 'carreras_estudiante.id_carrera', '=', 'carreras.id_carrera')
            ->join('departamentos', 'carreras.departamento', '=', 'departamentos.id_departamento')
            ->join('proyectos', 'certificaciones.proyecto_id', '=', 'proyectos.id_proyecto')
            ->whereNull('proyectos.deleted_at')
            ->when($filtros['area_id'], fn ($q, $id) => $q->where('proyectos.area_de_conocimiento', $id))
            ->when($filtros['carrera_id'], fn ($q, $id) => $q->where('carreras.id_carrera', $id))
            ->when($filtros['departamento_id'], fn ($q, $id) => $q->where('carreras.departamento', $id))
            ->when($filtros['desde'], fn ($q, $date) => $q->whereDate('certificaciones.fecha_emision', '>=', $date))
            ->when($filtros['hasta'], fn ($q, $date) => $q->whereDate('certificaciones.fecha_emision', '<=', $date))
            ->selectRaw('
                departamentos.id_departamento,
                departamentos.nombre,
                count(distinct certificaciones.id_certificacion) as total_hechas,
                count(distinct case when certificaciones.fecha_entrega is not null then certificaciones.id_certificacion end) as total_entregadas,
                count(distinct case when certificaciones.fecha_entrega is null then certificaciones.id_certificacion end) as total_sin_entregar
            ')
            ->groupBy('departamentos.id_departamento', 'departamentos.nombre')
            ->orderByDesc('total_hechas')
            ->get()
            ->map(function ($row) {
                $row->tasa_entrega = $row->total_hechas > 0 ? round(($row->total_entregadas / $row->total_hechas) * 100, 1) : 0;

                return $row;
            });

        // Tabla de Certificaciones para Auditoría y Reporte
        $certificaciones = (clone $baseQuery)
            ->when($filtros['estado_mined'] === 'entregadas', fn ($q) => $q->whereNotNull('fecha_entrega'))
            ->when($filtros['estado_mined'] === 'sin_entregar', fn ($q) => $q->whereNull('fecha_entrega'))
            ->orderByDesc('fecha_emision')
            ->paginate(20, ['*'], 'mined_page')
            ->withQueryString();

        $carreraMinedLider = $minedPorCarrera->first();
        $carreraMayorRezago = $minedPorCarrera->sortByDesc('total_sin_entregar')->first();

        return [
            'minedHicieron' => $minedHicieron,
            'minedEntregaron' => $minedEntregaron,
            'minedSinEntregar' => $minedSinEntregar,
            'tasaEntrega' => $tasaEntrega,
            'tasaSinEntregar' => $tasaSinEntregar,
            'minedPorCarrera' => $minedPorCarrera,
            'minedPorDepartamento' => $minedPorDepartamento,
            'carreraMinedLider' => $carreraMinedLider,
            'carreraMayorRezago' => $carreraMayorRezago,
            'certificaciones' => $certificaciones,
        ];
    }
}
