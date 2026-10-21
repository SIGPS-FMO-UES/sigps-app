<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['proyecto_id', 'fase', 'nombre_original', 'ruta', 'mime_type', 'tamano_bytes', 'usuario_id'])]
class DocumentoProyecto extends Model
{
    protected $table = 'documentos_proyecto';

    protected $primaryKey = 'id_documento_proyecto';

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'id_proyecto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }
}
