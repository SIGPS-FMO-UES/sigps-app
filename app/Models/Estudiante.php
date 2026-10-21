<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['due', 'nombre_completo'])]
class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $primaryKey = 'id_estudiante';

    /**
     * Carreras en las que está inscrito el estudiante.
     */
    public function carreras(): BelongsToMany
    {
        return $this->belongsToMany(Carrera::class, 'carreras_estudiante', 'id_estudiante', 'id_carrera')
            ->withPivot('id_carrera_estudiante')
            ->withTimestamps();
    }

    /**
     * Proyectos de proyección social en los que participa el estudiante.
     */
    public function proyectos(): BelongsToMany
    {
        return $this->belongsToMany(Proyecto::class, 'proyectos_estudiantes', 'id_estudiante', 'id_proyecto')
            ->withPivot('id_proyecto_estudiante')
            ->withTimestamps();
    }
}
