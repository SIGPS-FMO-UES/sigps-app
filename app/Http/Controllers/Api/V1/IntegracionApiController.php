<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\EstadoMemoria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RecepcionExpedienteAvaladoRequest;
use App\Models\DocumentoProyecto;
use App\Models\HistorialFaseProyecto;
use App\Models\LogAccion;
use App\Models\Memoria;
use App\Models\Proyecto;
use App\Models\User;
use App\Notifications\ExpedienteRecibidoNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IntegracionApiController extends Controller
{
    /**
     * Endpoint de Integración Externa (HU-72):
     * Recepción de expedientes avalados desde el Sistema de Coordinadores (SYS-02).
     */
    public function recibir(RecepcionExpedienteAvaladoRequest $request): JsonResponse
    {
        $archivo = $request->file('archivo_pdf_expediente');
        $hashRecibido = strtolower($request->validated('archivo_hash_sha256'));
        $hashCalculado = strtolower(hash_file('sha256', $archivo->getRealPath()));

        // 1. Verificación Criptográfica de Integridad
        if ($hashRecibido !== $hashCalculado) {
            return response()->json([
                'status' => 'error',
                'codigo' => 'INTEGRITY_MISMATCH',
                'message' => 'Integridad comprometida: el hash SHA-256 no coincide con el archivo binario recibido.',
            ], 422);
        }

        $proyecto = Proyecto::query()->findOrFail($request->validated('proyecto_id'));
        $fase = $request->validated('fase');
        $codigoAval = trim($request->validated('codigo_aval_carrera'));
        $coordinador = trim($request->validated('coordinador_firmante'));

        // 2. Control de Idempotencia y Prevención de Radicación Duplicada
        $avalDuplicado = HistorialFaseProyecto::query()
            ->where('proyecto_id', $proyecto->id_proyecto)
            ->where('fase', $fase)
            ->where('observaciones', 'like', "%{$codigoAval}%")
            ->exists();

        if ($avalDuplicado) {
            return response()->json([
                'status' => 'error',
                'codigo' => 'AVAL_ALREADY_REGISTERED',
                'message' => "Conflicto: El código de aval '{$codigoAval}' ya fue radicado previamente para la fase '{$fase}'.",
            ], 409);
        }

        // 3. Procesamiento Transaccional de Ingreso a Bandeja UPS
        $documento = DB::transaction(function () use ($archivo, $proyecto, $fase, $codigoAval, $coordinador, $hashCalculado): DocumentoProyecto {
            $cleanAval = preg_replace('/[^A-Za-z0-9_-]/', '_', $codigoAval);
            $fileName = "aval_{$cleanAval}_{$hashCalculado}.pdf";
            $storagePath = $archivo->storeAs("proyectos/{$proyecto->id_proyecto}/{$fase}", $fileName, 'expedientes_ups');

            $doc = DocumentoProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => $fase,
                'nombre_original' => $archivo->getClientOriginalName(),
                'ruta' => $storagePath,
                'mime_type' => 'application/pdf',
                'tamano_bytes' => $archivo->getSize(),
                'usuario_id' => $proyecto->creado_por,
            ]);

            // Actualización de estado y fase en el proyecto
            $proyecto->update([
                'fase_actual' => $fase,
                'fecha_radicado' => now(),
            ]);

            // Si es memoria final, se inicializa el registro para auditoría de sellos y tinta azul
            if ($fase === 'memoria_final') {
                Memoria::updateOrCreate(
                    ['proyecto_id' => $proyecto->id_proyecto],
                    [
                        'registrada_por' => $proyecto->creado_por,
                        'fecha_recibimiento' => now()->toDateString(),
                        'estado' => EstadoMemoria::Recibido,
                        'documento' => $storagePath,
                    ]
                );
            }

            // Trazabilidad inmutable en historial de fases
            HistorialFaseProyecto::create([
                'proyecto_id' => $proyecto->id_proyecto,
                'fase' => $fase,
                'evento' => 'radicado_ups',
                'observaciones' => "Expediente avalado recibido desde coordinación (Aval: {$codigoAval}, Coordinador: {$coordinador}).",
                'usuario_id' => $proyecto->creado_por,
            ]);

            // Bitácora general
            LogAccion::create([
                'accion' => "Integración HU-72: Expediente avalado radicado en bandeja UPS para proyecto #{$proyecto->id_proyecto} (Fase: {$fase}, Aval: {$codigoAval}).",
                'usuario' => $proyecto->creado_por,
            ]);

            return $doc;
        });

        User::role(['jefe_ups', 'auxiliar_ups'])
            ->where('activo', true)
            ->get()
            ->each(fn (User $usuario) => $usuario->notify(new ExpedienteRecibidoNotification(
                $proyecto->id_proyecto,
                $proyecto->titulo,
                $fase,
                $codigoAval,
            )));

        return response()->json([
            'status' => 'success',
            'radicado_ups_id' => $documento->id_documento_proyecto,
            'proyecto_id' => $proyecto->id_proyecto,
            'fase' => $fase,
            'codigo_aval_carrera' => $codigoAval,
            'estado' => 'ingresado_en_bandeja_ups',
            'mensaje' => 'Expediente avalado recibido exitosamente y programado para auditoría de sellos.',
            'fecha_radicado' => now()->toIso8601String(),
        ], 201);
    }
}
