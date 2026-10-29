<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Models\LogAccion;
use App\Models\Memoria;
use App\Models\RevisionMemoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditoriaApiController extends Controller
{
    /**
     * Dictamen de auditoría visual de sellos y tinta azul sobre memoria final (RN-09).
     */
    public function dictaminar(Request $request, string $id): JsonResponse
    {
        $datos = $request->validate([
            'resultado' => ['required', 'string', 'in:aprobado,observado'],
            'sellos_verificados' => ['required', 'boolean'],
            'tinta_azul_verificada' => ['required', 'boolean'],
            'observaciones' => ['nullable', 'string', 'required_if:resultado,observado', 'max:2000'],
        ], [
            'observaciones.required_if' => 'Las observaciones son obligatorias cuando el dictamen es observado.',
        ]);

        $revision = DB::transaction(function () use ($datos, $id): RevisionMemoria {
            $memoria = Memoria::query()->lockForUpdate()->findOrFail($id);

            abort_unless(in_array($memoria->estado->value, [
                EstadoMemoria::Recibido->value,
                EstadoMemoria::EnRevision->value,
                EstadoMemoria::Observada->value,
            ], true), 409, 'Esta memoria ya cuenta con un dictamen aprobado o finalizado.');

            $resultado = $datos['resultado'];
            $observaciones = $datos['observaciones'] ?? null;

            $rev = RevisionMemoria::create([
                'memoria_id' => $memoria->id_memoria,
                'auditor_id' => auth()->id() ?? 1,
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
                'accion' => "API: Dictamen de auditoría para memoria #{$memoria->id_memoria}: {$resultado}.",
                'usuario' => auth()->id() ?? 1,
            ]);

            return $rev;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Dictamen de auditoría documental registrado exitosamente.',
            'data' => [
                'revision_id' => $revision->id_revision_memoria,
                'memoria_id' => $revision->memoria_id,
                'resultado' => $revision->resultado,
                'sellos_verificados' => $revision->sellos_verificados,
                'tinta_azul_verificada' => $revision->tinta_azul_verificada,
                'observaciones' => $revision->observaciones,
                'fecha_revision' => $revision->created_at?->toIso8601String(),
            ],
        ]);
    }
}
