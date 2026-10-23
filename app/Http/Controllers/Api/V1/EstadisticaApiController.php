<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use App\Models\Certificacion;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;

class EstadisticaApiController extends Controller
{
    /**
     * Resumen estadístico gerencial de Proyección Social (FMO-UES).
     */
    public function resumen(): JsonResponse
    {
        $proyectosPorEstado = Proyecto::query()
            ->selectRaw('estado_expediente, count(*) as total')
            ->groupBy('estado_expediente')
            ->pluck('total', 'estado_expediente');

        $proyectosPorFase = Proyecto::query()
            ->selectRaw('fase_actual, count(*) as total')
            ->groupBy('fase_actual')
            ->pluck('total', 'fase_actual');

        $totalCertificacionesEmitidas = Certificacion::query()->count();
        $totalCertificacionesEntregadas = Certificacion::query()->whereNotNull('fecha_entrega')->count();

        $proyectosPorArea = AreaConocimiento::query()
            ->withCount('proyectos')
            ->get()
            ->map(fn ($area) => [
                'id_area' => $area->id_area_conocimiento,
                'nombre' => $area->nombre,
                'total_proyectos' => $area->proyectos_count,
            ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'proyectos_por_estado' => [
                    'en_tramite' => (int) ($proyectosPorEstado['en_tramite'] ?? 0),
                    'finalizado' => (int) ($proyectosPorEstado['finalizado'] ?? 0),
                    'cancelado' => (int) ($proyectosPorEstado['cancelado'] ?? 0),
                    'total' => Proyecto::query()->count(),
                ],
                'proyectos_por_fase' => $proyectosPorFase,
                'certificaciones' => [
                    'emitidas' => $totalCertificacionesEmitidas,
                    'entregadas' => $totalCertificacionesEntregadas,
                    'pendientes_entrega' => $totalCertificacionesEmitidas - $totalCertificacionesEntregadas,
                ],
                'distribucion_por_area' => $proyectosPorArea,
            ],
        ]);
    }
}
