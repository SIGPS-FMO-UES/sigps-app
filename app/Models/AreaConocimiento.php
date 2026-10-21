<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre', 'detalles', 'activa'])]
class AreaConocimiento extends Model
{
    use HasFactory;

    protected $table = 'areas_de_conocimiento';

    protected $primaryKey = 'id_area_conocimiento';

    protected function casts(): array
    {
        return ['activa' => 'boolean'];
    }

    /**
     * Proyectos registrados en esta área de conocimiento.
     */
    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'area_de_conocimiento', 'id_area_conocimiento');
    }
}
