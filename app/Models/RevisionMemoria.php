<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'memoria_id',
    'auditor_id',
    'resultado',
    'sellos_verificados',
    'tinta_azul_verificada',
    'observaciones',
])]
class RevisionMemoria extends Model
{
    use HasFactory;

    protected $table = 'revisiones_memorias';

    protected $primaryKey = 'id_revision_memoria';

    protected function casts(): array
    {
        return [
            'sellos_verificados' => 'boolean',
            'tinta_azul_verificada' => 'boolean',
        ];
    }

    public function memoria(): BelongsTo
    {
        return $this->belongsTo(Memoria::class, 'memoria_id', 'id_memoria');
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'auditor_id', 'id_usuario');
    }
}
