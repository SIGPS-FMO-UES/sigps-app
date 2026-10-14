<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission;

#[Fillable([
    'accion',
    'usuario',
    'permiso_utilizado',
])]
class LogAccion extends Model
{
    use HasFactory;

    protected $table = 'logs_acciones';

    protected $primaryKey = 'id_log';

    /**
     * Usuario que ejecutó la acción.
     */
    public function usuarioRel(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'id_usuario');
    }

    /**
     * Alias de relación con usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->usuarioRel();
    }

    /**
     * Permiso utilizado o comprobado en la acción (si aplica).
     */
    public function permiso(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permiso_utilizado', 'id');
    }
}
