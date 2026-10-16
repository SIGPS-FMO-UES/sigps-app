<?php

namespace App\Models;

use App\Enums\EstadoMemoria;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'registrada_por',
    'intento',
    'fecha_recibimiento',
    'estado',
    'fecha_aprovado',
    'fecha_listo',
    'fecha_entrega',
    'observaciones',
    'proyecto_id',
    'documento',
])]
class Memoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'memorias';

    protected $primaryKey = 'id_memoria';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'intento' => 'integer',
            'fecha_recibimiento' => 'date',
            'fecha_aprovado' => 'date',
            'fecha_listo' => 'date',
            'fecha_entrega' => 'date',
            'estado' => EstadoMemoria::class,
        ];
    }

    /**
     * Usuario administrador que registró la memoria.
     */
    public function registrador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'registrada_por', 'id_usuario');
    }

    /**
     * Proyecto al que pertenece esta memoria.
     */
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'id_proyecto');
    }
}
