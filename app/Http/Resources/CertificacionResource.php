<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_certificacion' => $this->id_certificacion,
            'folio' => $this->folio,
            'fecha_emision' => $this->fecha_emision?->toIso8601String(),
            'fecha_entrega' => $this->fecha_entrega?->toDateString(),
            'firma_receptor' => $this->firma_receptor,
            'proyecto' => $this->whenLoaded('proyecto', fn () => [
                'id_proyecto' => $this->proyecto?->id_proyecto,
                'titulo' => $this->proyecto?->titulo,
            ]),
            'estudiante' => $this->whenLoaded('estudiante', fn () => [
                'id_estudiante' => $this->estudiante?->id_estudiante,
                'due' => $this->estudiante?->due,
                'nombre_completo' => $this->estudiante?->nombre_completo,
            ]),
            'emitida_por' => $this->whenLoaded('emisor', fn () => [
                'id_usuario' => $this->emisor?->id_usuario,
                'nombre_completo' => $this->emisor?->nombre_completo,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
