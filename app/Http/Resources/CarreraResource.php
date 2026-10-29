<?php

namespace App\Http\Resources;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Carrera
 */
class CarreraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_carrera' => $this->id_carrera,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'horas_servicio_social' => (int) ($this->horas_servicio_social ?? 500),
            'horas_requeridas' => (int) ($this->horas_servicio_social ?? 500),
            'departamento_id' => $this->departamento,
            'departamento' => $this->whenLoaded('departamentoRel', fn () => [
                'id_departamento' => $this->departamentoRel->id_departamento,
                'nombre' => $this->departamentoRel->nombre,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
