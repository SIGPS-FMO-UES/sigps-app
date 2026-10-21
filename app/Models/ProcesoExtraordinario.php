<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'proyecto_id', 'estudiante_id', 'tipo', 'motivo', 'fecha_anterior', 'fecha_nueva',
    'autorizacion', 'documento_respaldo', 'estado', 'resolucion', 'registrado_por',
    'resuelto_por', 'resuelto_at',
])]
class ProcesoExtraordinario extends Model
{
    protected $table = 'procesos_extraordinarios';

    protected $primaryKey = 'id_proceso_extraordinario';

    protected function casts(): array
    {
        return [
            'fecha_anterior' => 'date',
            'fecha_nueva' => 'date',
            'resuelto_at' => 'datetime',
        ];
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'id_proyecto');
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id_estudiante');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id_usuario');
    }

    public function resolutor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'resuelto_por', 'id_usuario');
    }
}
