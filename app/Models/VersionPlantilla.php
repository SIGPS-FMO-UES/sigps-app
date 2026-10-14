<?php

namespace App\Models;

use App\Enums\TipoPlantilla;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'numero_version',
    'id_plantilla',
    'tipo',
    'nombre_archivo',
    'ruta_archivo',
    'esquema',
    'publicada',
])]
class VersionPlantilla extends Model
{
    use HasFactory;

    protected $table = 'versiones_plantillas';

    protected $primaryKey = 'id_version_plantilla';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'numero_version' => 'integer',
            'tipo' => TipoPlantilla::class,
            'esquema' => 'array',
            'publicada' => 'boolean',
        ];
    }

    /**
     * Plantilla padre a la que pertenece esta versión.
     */
    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(Plantilla::class, 'id_plantilla', 'id_plantilla');
    }
}
