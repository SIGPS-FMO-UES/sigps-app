<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre'])]
class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $primaryKey = 'id_departamento';

    /**
     * Carreras pertenecientes a este departamento.
     */
    public function carreras(): HasMany
    {
        return $this->hasMany(Carrera::class, 'departamento', 'id_departamento');
    }
}
