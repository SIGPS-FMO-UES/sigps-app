<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'creado_por',
    'fecha_inicio',
    'fecha_final',
    'titulo',
    'miembros',
    'tutor',
    'subcoordinador',
    'area_de_conocimiento',
    'documento',
])]
class Proyecto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proyectos';

    protected $primaryKey = 'id_proyecto';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_final' => 'date',
        ];
    }

    /**
     * Usuario administrador que creó el proyecto.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'id_usuario');
    }

    /**
     * Área de conocimiento a la que pertenece el proyecto.
     */
    public function areaConocimiento(): BelongsTo
    {
        return $this->belongsTo(AreaConocimiento::class, 'area_de_conocimiento', 'id_area_conocimiento');
    }

    /**
     * Estudiantes asignados al proyecto a través de la tabla pivote normalizada.
     */
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'proyectos_estudiantes', 'id_proyecto', 'id_estudiante')
            ->withPivot('id_proyecto_estudiante')
            ->withTimestamps();
    }

    /**
     * Memoria única o actual asociada al proyecto.
     */
    public function memoria(): HasOne
    {
        return $this->hasOne(Memoria::class, 'proyecto_id', 'id_proyecto');
    }

    /**
     * Historial de memorias o intentos presentados para este proyecto.
     */
    public function memorias(): HasMany
    {
        return $this->hasMany(Memoria::class, 'proyecto_id', 'id_proyecto');
    }
}
