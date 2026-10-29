<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;

class PlazoApiController extends Controller
{
    /**
     * Tablero de alertas de plazos críticos (18 meses a Junta Directiva y memorias).
     */
    public function alertas(): JsonResponse
    {
        $ahora = now();

        $proyectos = Proyecto::query()
            ->with(['estudiantes', 'areaConocimiento'])
            ->where('estado_expediente', 'en_tramite')
            ->whereNotNull('fecha_inicio')
            ->get();

        $alertas = $proyectos->map(function (Proyecto $p) use ($ahora) {
            $mesesTranscurridos = $p->fecha_inicio ? $ahora->diffInMonths($p->fecha_inicio) : 0;
            $diasPara18Meses = $p->fecha_inicio ? (int) $ahora->diffInDays($p->fecha_inicio->copy()->addMonths(18), false) : 0;

            $nivelAlerta = 'normal';
            if ($diasPara18Meses <= 0) {
                $nivelAlerta = 'critico'; // Vencido el plazo de 18 meses
            } elseif ($diasPara18Meses <= 60) {
                $nivelAlerta = 'advertencia'; // Próximo a vencer (menos de 2 meses)
            }

            return [
                'id_proyecto' => $p->id_proyecto,
                'titulo' => $p->titulo,
                'fase_actual' => $p->fase_actual,
                'fecha_inicio' => $p->fecha_inicio?->toDateString(),
                'meses_transcurridos' => $mesesTranscurridos,
                'dias_para_18_meses' => $diasPara18Meses,
                'nivel_alerta' => $nivelAlerta,
                'remitido_junta' => $p->fecha_remision_junta !== null,
            ];
        })->filter(fn ($item) => in_array($item['nivel_alerta'], ['critico', 'advertencia']))->values();

        return response()->json([
            'status' => 'success',
            'total_alertas' => $alertas->count(),
            'data' => $alertas,
        ]);
    }
}
