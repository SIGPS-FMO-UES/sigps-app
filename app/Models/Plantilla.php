<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'nombre',
    'descripcion',
    'activa',
])]
class Plantilla extends Model
{
    use HasFactory;

    protected $table = 'plantillas';

    protected $primaryKey = 'id_plantilla';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    /**
     * Todas las versiones creadas para esta plantilla.
     */
    public function versiones(): HasMany
    {
        return $this->hasMany(VersionPlantilla::class, 'id_plantilla', 'id_plantilla');
    }

    /**
     * Versión actualmente publicada de la plantilla.
     */
    public function versionPublicada(): HasOne
    {
        return $this->hasOne(VersionPlantilla::class, 'id_plantilla', 'id_plantilla')
            ->where('publicada', true);
    }
}
