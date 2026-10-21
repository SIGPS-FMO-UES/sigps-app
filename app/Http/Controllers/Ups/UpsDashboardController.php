<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\Memoria;
use App\Models\Proyecto;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpsDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'mes' => ['nullable', 'integer', 'between:1,12'],
            'anio' => ['nullable', 'integer', 'between:2020,2100'],
        ]);

        $ahora = CarbonImmutable::now();
        $fechaCalendario = CarbonImmutable::createFromDate(
            (int) ($filtros['anio'] ?? $ahora->year),
            (int) ($filtros['mes'] ?? $ahora->month),
            1,
        );
        $inicioMes = $fechaCalendario->startOfMonth();
        $desfase = $inicioMes->dayOfWeekIso % 7;
        $dias = array_merge(array_fill(0, $desfase, null), range(1, $fechaCalendario->daysInMonth));
        $dias = array_pad($dias, (int) ceil(count($dias) / 7) * 7, null);

        $estadosPendientes = [
            EstadoMemoria::Recibido->value,
            EstadoMemoria::EnRevision->value,
            EstadoMemoria::Observada->value,
        ];

        $expedientesQuery = Memoria::query()
            ->with(['proyecto.areaConocimiento', 'proyecto.estudiantes'])
            ->whereIn('estado', $estadosPendientes)
            ->latest('fecha_recibimiento');

        if (! empty($filtros['buscar'])) {
            $termino = $filtros['buscar'];
            $expedientesQuery->whereHas('proyecto', function ($query) use ($termino): void {
                $query->where('titulo', 'like', "%{$termino}%")
                    ->orWhereHas('estudiantes', function ($estudiantes) use ($termino): void {
                        $estudiantes->where('due', 'like', "%{$termino}%")
                            ->orWhere('nombre_completo', 'like', "%{$termino}%");
                    });
            });
        }

        $expedientes = $expedientesQuery->limit(7)->get()->map(function (Memoria $memoria): array {
            $proyecto = $memoria->proyecto;
            $estudiante = $proyecto?->estudiantes->first();

            return [
                'codigo' => $estudiante?->due ?? 'Sin estudiante',
                'proyecto' => $proyecto?->titulo ?? 'Proyecto no disponible',
                'area' => $proyecto?->areaConocimiento?->nombre ?? 'Sin área',
                'fecha' => $memoria->fecha_recibimiento?->format('d/m/Y') ?? '—',
                'estado' => $memoria->estado instanceof EstadoMemoria ? $memoria->estado->label() : (string) $memoria->estado,
            ];
        });

        $memoriasPendientes = Memoria::query()->whereIn('estado', $estadosPendientes)->count();
        $fechaLimite = $ahora->subMonths(3)->toDateString();
        $proyectosEnAlerta = Proyecto::query()
            ->whereDate('fecha_inicio', '<=', $fechaLimite)
            ->whereDoesntHave('memoria')
            ->orderBy('fecha_inicio')
            ->limit(3)
            ->get(['id_proyecto', 'titulo', 'fecha_inicio']);
        $plazosCriticos = Proyecto::query()
            ->whereDate('fecha_inicio', '<=', $fechaLimite)
            ->whereDoesntHave('memoria')
            ->count();
        $certificacionesPendientes = Memoria::query()
            ->whereIn('estado', [EstadoMemoria::Lista->value, EstadoMemoria::EsperandoFirmas->value])
            ->count();

        return view('ups.dashboard', [
            'fechaActual' => $ahora->locale('es')->translatedFormat('l, d \\d\\e F \\d\\e Y').' · '.$ahora->format('H:i'),
            'mesActual' => ucfirst($fechaCalendario->locale('es')->translatedFormat('F Y')),
            'diasCalendario' => array_chunk($dias, 7),
            'diaSeleccionado' => $fechaCalendario->isSameMonth($ahora) ? $ahora->day : null,
            'mesAnterior' => $fechaCalendario->subMonth(),
            'mesSiguiente' => $fechaCalendario->addMonth(),
            'busqueda' => $filtros['buscar'] ?? '',
            'nombreUsuario' => auth()->user()->nombre_completo,
            'rolUsuario' => auth()->user()->getRoleNames()->first() === 'jefe_ups' ? 'Jefatura UPS' : 'Auxiliar UPS',
            'metricas' => [
                ['valor' => $memoriasPendientes, 'titulo' => 'Expedientes recibidos', 'detalle' => 'Pendientes de revisión UPS', 'icono' => 'archivo'],
                ['valor' => $plazosCriticos, 'titulo' => 'Plazos críticos', 'detalle' => 'Proyectos sin memoria y con más de 3 meses', 'icono' => 'alerta'],
                ['valor' => $certificacionesPendientes, 'titulo' => 'Certificaciones pendientes', 'detalle' => 'Memorias listas para emisión o entrega', 'icono' => 'sello'],
            ],
            'expedientes' => $expedientes,
            'hayExpedientes' => $expedientes->isNotEmpty(),
            'plazosCriticosTotal' => $plazosCriticos,
            'alertas' => $proyectosEnAlerta->map(fn (Proyecto $proyecto): array => [
                'codigo' => $proyecto->titulo,
                'vence' => 'Superó el plazo de 3 meses desde '.CarbonImmutable::parse($proyecto->fecha_inicio)->addMonths(3)->format('d/m/Y'),
            ]),
        ]);
    }
}
