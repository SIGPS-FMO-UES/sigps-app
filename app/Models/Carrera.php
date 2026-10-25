<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['codigo', 'nombre', 'departamento', 'horas_servicio_social'])]
class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';

    protected $primaryKey = 'id_carrera';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'horas_servicio_social' => 'integer',
        ];
    }

    /**
     * Alias de horas requeridas para servicio social.
     */
    public function getHorasRequeridasAttribute(): int
    {
        return (int) ($this->horas_servicio_social ?? 500);
    }

    /**
     * Departamento al que pertenece la carrera.
     */
    public function departamentoRel(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento', 'id_departamento');
    }

    /**
     * Alias de relación al departamento.
     */
    public function departamento(): BelongsTo
    {
        return $this->departamentoRel();
    }

    /**
     * Estudiantes inscritos en esta carrera.
     */
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'carreras_estudiante', 'id_carrera', 'id_estudiante')
            ->withPivot('id_carrera_estudiante')
            ->withTimestamps();
    }
}
