<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LogAccion;
use App\Models\ProcesoExtraordinario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProrrogaApiController extends Controller
{
    /**
     * Resolución formal de prórrogas y ampliaciones de plazo (exclusivo Jefatura UPS).
     */
    public function resolver(Request $request, string $id): JsonResponse
    {
        $datos = $request->validate([
            'estado' => ['required', 'string', 'in:aprobado,rechazado'],
            'resolucion' => ['required', 'string', 'max:2000'],
        ]);

        $proceso = DB::transaction(function () use ($datos, $id): ProcesoExtraordinario {
            $proc = ProcesoExtraordinario::query()->lockForUpdate()->findOrFail($id);
            abort_unless($proc->estado === 'registrado', 409, 'Esta solicitud ya cuenta con una resolución emitida.');

            $proc->update([
                'estado' => $datos['estado'],
                'resolucion' => $datos['resolucion'],
                'resuelto_por' => auth()->id() ?? 1,
                'resuelto_at' => now(),
            ]);

            // Si es aprobada y define nueva fecha, se actualiza la fecha final del proyecto
            if ($datos['estado'] === 'aprobado' && $proc->fecha_nueva) {
                $proc->proyecto->update(['fecha_final' => $proc->fecha_nueva]);
            }

            LogAccion::create([
                'accion' => "API: Resolución de proceso extraordinario #{$proc->id_proceso_extraordinario}: {$datos['estado']}.",
                'usuario' => auth()->id() ?? 1,
            ]);

            return $proc;
        });

        return response()->json([
            'status' => 'success',
            'message' => "Solicitud {$proceso->tipo} resuelta como {$proceso->estado}.",
            'data' => [
                'id_proceso_extraordinario' => $proceso->id_proceso_extraordinario,
                'proyecto_id' => $proceso->proyecto_id,
                'tipo' => $proceso->tipo,
                'estado' => $proceso->estado,
                'resolucion' => $proceso->resolucion,
                'fecha_nueva' => $proceso->fecha_nueva?->toDateString(),
                'resuelto_at' => $proceso->resuelto_at?->toIso8601String(),
            ],
        ]);
    }
}
