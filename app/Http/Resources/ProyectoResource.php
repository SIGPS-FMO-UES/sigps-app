<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProyectoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_proyecto' => $this->id_proyecto,
            'numero_proyecto' => $this->numero_proyecto,
            'titulo' => $this->titulo,
            'fase_actual' => $this->fase_actual,
            'estado_expediente' => $this->estado_expediente,
            'tutor' => $this->tutor,
            'subcoordinador' => $this->subcoordinador,
            'correo_coordinador' => $this->correo_coordinador,
            'fecha_inicio' => $this->fecha_inicio?->toDateString(),
            'fecha_final' => $this->fecha_final?->toDateString(),
            'fecha_radicado' => $this->fecha_radicado?->toIso8601String(),
            'fecha_finalizacion' => $this->fecha_finalizacion?->toIso8601String(),
            'fecha_remision_junta' => $this->fecha_remision_junta?->toIso8601String(),
            'area_conocimiento' => $this->whenLoaded('areaConocimiento', fn () => [
                'id_area_conocimiento' => $this->areaConocimiento?->id_area_conocimiento,
                'nombre' => $this->areaConocimiento?->nombre,
            ]),
            'estudiantes' => $this->whenLoaded('estudiantes', fn () => $this->estudiantes->map(fn ($e) => [
                'id_estudiante' => $e->id_estudiante,
                'due' => $e->due,
                'nombre_completo' => $e->nombre_completo,
            ])),
            'memoria' => $this->whenLoaded('memoria', fn () => [
                'id_memoria' => $this->memoria?->id_memoria,
                'estado' => $this->memoria?->estado?->value ?? $this->memoria?->estado,
                'fecha_recibimiento' => $this->memoria?->fecha_recibimiento,
                'fecha_aprovado' => $this->memoria?->fecha_aprovado,
            ]),
            'documentos_count' => $this->whenCounted('documentos'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
