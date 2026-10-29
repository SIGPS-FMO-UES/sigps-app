<?php

namespace App\Http\Resources;

use App\Models\Plantilla;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Plantilla
 */
class FormatoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $version = $this->versionPublicada;

        return [
            'id_plantilla' => $this->id_plantilla,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'activa' => (bool) $this->activa,
            'version_publicada' => $version ? [
                'id_version_plantilla' => $version->id_version_plantilla,
                'numero_version' => $version->numero_version,
                'tipo' => $version->tipo?->value ?? (string) $version->tipo,
                'tipo_label' => method_exists($version->tipo, 'label') ? $version->tipo->label() : (string) $version->tipo,
                'nombre_archivo' => $version->nombre_archivo,
                'esquema' => $version->esquema,
                'fecha_publicacion' => $version->created_at?->toIso8601String(),
                'url_descarga' => route('api.v1.formatos.descargar', ['id' => $this->id_plantilla]),
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
