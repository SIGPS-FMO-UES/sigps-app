<?php

namespace App\Http\Resources;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Departamento
 */
class DepartamentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_departamento' => $this->id_departamento,
            'nombre' => $this->nombre,
            'carreras' => CarreraResource::collection($this->whenLoaded('carreras')),
            'carreras_count' => $this->when(isset($this->carreras_count), fn () => (int) $this->carreras_count),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
