<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstudianteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_estudiante' => $this->id_estudiante,
            'due' => $this->due,
            'nombre_completo' => $this->nombre_completo,
            'carreras' => $this->whenLoaded('carreras', fn () => $this->carreras->map(fn ($c) => [
                'id_carrera' => $c->id_carrera,
                'nombre' => $c->nombre,
                'horas_servicio_social' => $c->horas_servicio_social,
            ])),
            'proyectos' => $this->whenLoaded('proyectos', fn () => $this->proyectos->map(fn ($p) => [
                'id_proyecto' => $p->id_proyecto,
                'titulo' => $p->titulo,
                'fase_actual' => $p->fase_actual,
                'estado_expediente' => $p->estado_expediente,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
