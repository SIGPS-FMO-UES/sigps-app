<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['proyecto_id', 'estudiante_id', 'folio', 'fecha_emision', 'emitida_por', 'fecha_entrega', 'firma_receptor', 'entregada_por'])]
class Certificacion extends Model
{
    protected $table = 'certificaciones';

    protected $primaryKey = 'id_certificacion';

    protected function casts(): array
    {
        return ['fecha_emision' => 'datetime', 'fecha_entrega' => 'date'];
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'id_proyecto');
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id_estudiante');
    }

    public function emisor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'emitida_por', 'id_usuario');
    }

    public function receptorRegistradoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'entregada_por', 'id_usuario');
    }
}
