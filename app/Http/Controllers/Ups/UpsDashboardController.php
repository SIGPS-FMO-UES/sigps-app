<?php

namespace App\Http\Controllers\Ups;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\Memoria;
use App\Models\Proyecto;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $fechaLimiteMemoria = $ahora->subMonths(3)->toDateString();
        $fechaLimiteJunta = $ahora->subMonths(18)->toDateString();

        // 1. Tarjeta 1: Plazos Críticos (Urgentes)
        $plazosCriticos = Proyecto::query()
            ->where(function ($q) use ($fechaLimiteMemoria, $fechaLimiteJunta): void {
                $q->whereDate('fecha_inicio', '<=', $fechaLimiteJunta)
                    ->orWhere(fn ($sub) => $sub->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
                    ->orWhereHas('memoria', fn ($m) => $m->where('estado', EstadoMemoria::Observada->value));
            })->count();

        // 2. Tarjeta 2: Auditorías Pendientes (Trámite UPS)
        $auditoriasPendientes = Memoria::query()
            ->whereIn('estado', [
                EstadoMemoria::Recibido->value,
                EstadoMemoria::EnRevision->value,
                EstadoMemoria::EsperandoFirmas->value,
            ])->count();

        // 3. Tarjeta 3: Entregas Pendientes (Certificaciones Listas)
        $entregasPendientes = Memoria::query()
            ->where('estado', EstadoMemoria::Lista->value)
            ->count();

        // 4. Casos Prioritarios del Día (Ordenados por Gravedad Normativa)
        $casosQuery = Proyecto::query()
            ->with([
                'areaConocimiento:id_area_conocimiento,nombre',
                'estudiantes:id_estudiante,due,nombre_completo',
                'memoria',
            ])
            ->where(function ($q) use ($fechaLimiteMemoria, $fechaLimiteJunta): void {
                $q->whereDate('fecha_inicio', '<=', $fechaLimiteJunta)
                    ->orWhere(fn ($sub) => $sub->whereDate('fecha_inicio', '<=', $fechaLimiteMemoria)->whereDoesntHave('memoria'))
                    ->orWhereHas('memoria', fn ($m) => $m->whereIn('estado', [
                        EstadoMemoria::Observada->value,
                        EstadoMemoria::Recibido->value,
                        EstadoMemoria::EnRevision->value,
                        EstadoMemoria::EsperandoFirmas->value,
                        EstadoMemoria::Lista->value,
                    ]));
            });

        // Búsqueda en el dashboard
        if (! empty($filtros['buscar'])) {
            $search = trim((string) $filtros['buscar']);
            $searchNum = preg_replace('/\D/', '', $search);
            $driver = DB::connection()->getDriverName();
            $like = $driver === 'pgsql' ? 'ilike' : 'like';

            $casosQuery->where(function ($subquery) use ($search, $searchNum, $like): void {
                $subquery->where('titulo', $like, "%{$search}%")
                    ->orWhere('tutor', $like, "%{$search}%")
                    ->orWhereHas('estudiantes', fn ($estudiantes) => $estudiantes
                        ->where('due', $like, "%{$search}%")
                        ->orWhere('nombre_completo', $like, "%{$search}%")
                    );

                if ($searchNum !== '') {
                    $subquery->orWhere('numero_proyecto', (int) $searchNum);
                }
            });
        }

        $casosPrioritarios = $casosQuery->orderByRaw("
            CASE
                WHEN fecha_inicio <= '{$fechaLimiteJunta}' AND fecha_remision_junta IS NULL THEN 1
                WHEN fecha_inicio <= '{$fechaLimiteMemoria}' AND NOT EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto) THEN 2
                WHEN EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto AND memorias.estado = 'observada') THEN 3
                WHEN EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto AND memorias.estado IN ('recibido', 'en_revision', 'esperando_firmas')) THEN 4
                WHEN EXISTS (SELECT 1 FROM memorias WHERE memorias.numero_proyecto = proyectos.numero_proyecto AND memorias.estado = 'lista') THEN 5
                ELSE 6
            END ASC, fecha_inicio ASC
        ")->limit(8)->get();

        // Alertas de plazo (top 4 más críticos para el panel lateral)
        $alertas = $casosPrioritarios
            ->filter(fn (Proyecto $p) => $p->semaforoOperativo()['nivel'] === 'urgente')
            ->take(4)
            ->values();

        $usuario = auth()->user();
        $notificaciones = $usuario ? $usuario->notifications()->latest()->limit(8)->get() : collect();
        $notificacionesSinLeer = $usuario ? $usuario->unreadNotifications()->count() : 0;

        return view('ups.dashboard', [
            'fechaActual' => $ahora->locale('es')->translatedFormat('l, d \\d\\e F \\d\\e Y').' · '.$ahora->format('H:i'),
            'mesActual' => ucfirst($fechaCalendario->locale('es')->translatedFormat('F Y')),
            'diasCalendario' => array_chunk($dias, 7),
            'diaSeleccionado' => $fechaCalendario->isSameMonth($ahora) ? $ahora->day : null,
            'mesAnterior' => $fechaCalendario->subMonth(),
            'mesSiguiente' => $fechaCalendario->addMonth(),
            'busqueda' => $filtros['buscar'] ?? '',
            'nombreUsuario' => auth()->user()->nombre_completo,
            'rolUsuario' => auth()->user()->getRoleNames()->first() === 'jefe_ups' ? 'Jefatura UPS' : (auth()->user()->hasRole('admin') ? 'Administrador' : 'Auxiliar UPS'),
            'notificaciones' => $notificaciones,
            'notificacionesSinLeer' => $notificacionesSinLeer,
            'triaje' => [
                'plazosCriticos' => $plazosCriticos,
                'auditoriasPendientes' => $auditoriasPendientes,
                'entregasPendientes' => $entregasPendientes,
            ],
            'casosPrioritarios' => $casosPrioritarios,
            'alertas' => $alertas,
        ]);
    }
}
