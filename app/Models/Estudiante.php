<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Certificaciones emitidas al estudiante.
     */
    public function certificaciones(): HasMany
    {
        return $this->hasMany(Certificacion::class, 'estudiante_id', 'id_estudiante');
    }

    /**
     * Iniciales del estudiante para avatar visual.
     */
    public function getInicialesAttribute(): string
    {
        $palabras = array_filter(explode(' ', trim((string) $this->nombre_completo)));
        $iniciales = '';
        foreach (array_slice($palabras, 0, 2) as $p) {
            $iniciales .= mb_strtoupper(mb_substr($p, 0, 1));
        }

        return $iniciales ?: 'E';
    }
}
