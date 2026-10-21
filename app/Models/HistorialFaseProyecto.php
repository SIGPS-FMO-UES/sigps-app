<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['proyecto_id', 'fase', 'evento', 'observaciones', 'usuario_id'])]
class HistorialFaseProyecto extends Model
{
    protected $table = 'historial_fases_proyecto';

    protected $primaryKey = 'id_historial_fase';

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'id_proyecto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }
}
