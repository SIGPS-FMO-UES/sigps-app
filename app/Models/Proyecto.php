<?php

namespace App\Models;

use App\Enums\EstadoMemoria;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'numero_proyecto',
    'creado_por',
    'fecha_inicio',
    'fecha_final',
    'titulo',
    'miembros',
    'tutor',
    'subcoordinador',
    'area_de_conocimiento',
    'documento',
    'fase_actual',
    'estado_expediente',
    'fecha_radicado',
    'fecha_finalizacion',
    'fecha_remision_junta',
    'remitido_junta_por',
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
            'numero_proyecto' => 'integer',
            'fecha_inicio' => 'date',
            'fecha_final' => 'date',
            'fecha_radicado' => 'datetime',
            'fecha_finalizacion' => 'datetime',
            'fecha_remision_junta' => 'datetime',
        ];
    }

    /**
     * Usuario administrador que creó el proyecto.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por', 'id_usuario');
    }

    public function remitenteJunta(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'remitido_junta_por', 'id_usuario');
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
     * Memoria única o actual asociada al proyecto mediante el correlativo de proyecto.
     */
    public function memoria(): HasOne
    {
        return $this->hasOne(Memoria::class, 'numero_proyecto', 'numero_proyecto');
    }

    /**
     * Historial de memorias o intentos presentados para este proyecto mediante el correlativo de proyecto.
     */
    public function memorias(): HasMany
    {
        return $this->hasMany(Memoria::class, 'numero_proyecto', 'numero_proyecto');
    }

    public function historialFases(): HasMany
    {
        return $this->hasMany(HistorialFaseProyecto::class, 'proyecto_id', 'id_proyecto')->latest();
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoProyecto::class, 'proyecto_id', 'id_proyecto')->latest();
    }

    public function procesosExtraordinarios(): HasMany
    {
        return $this->hasMany(ProcesoExtraordinario::class, 'proyecto_id', 'id_proyecto')->latest();
    }

    public function certificaciones(): HasMany
    {
        return $this->hasMany(Certificacion::class, 'proyecto_id', 'id_proyecto');
    }

    /**
     * Retorna el estado semafórico y diagnóstico operativo para usuarios administrativos no técnicos.
     *
     * @return array{nivel: string, badge_class: string, etiqueta: string, plazo_humano: string, siguiente_accion: string}
     */
    public function semaforoOperativo(): array
    {
        $fechaInicio = $this->fecha_inicio;
        $memoria = $this->memoria;
        $ahora = now();

        // 1. Concluido formalmente
        if ($this->estado_expediente === 'finalizado' || $memoria?->estado === EstadoMemoria::Entregada->value) {
            return [
                'nivel' => 'concluido',
                'badge_class' => 'badge-info',
                'etiqueta' => 'Concluido',
                'plazo_humano' => 'Expediente cerrado',
                'siguiente_accion' => 'Ver expediente',
            ];
        }

        // 2. Plazo crítico: 18 meses cumplidos (Remisión a Junta Directiva según RN-04)
        $limite18Meses = $fechaInicio ? $fechaInicio->copy()->addMonths(18) : null;
        if ($limite18Meses && $limite18Meses->isPast()) {
            $mesesVencido = (int) $limite18Meses->diffInMonths($ahora);

            return [
                'nivel' => 'urgente',
                'badge_class' => 'badge-danger',
                'etiqueta' => 'Remisión a Junta',
                'plazo_humano' => $mesesVencido > 0 ? "Excedió 18m (+{$mesesVencido}m)" : 'Cumplió 18 meses',
                'siguiente_accion' => $this->fecha_remision_junta ? 'Remitido a Junta' : 'Remitir a Junta',
            ];
        }

        // 3. Plazo crítico: 3 meses cumplidos sin memoria (RN-03)
        if (! $memoria) {
            $limite3Meses = $fechaInicio ? $fechaInicio->copy()->addMonths(3) : null;
            if ($limite3Meses && $limite3Meses->isPast()) {
                $diasVencido = (int) $limite3Meses->diffInDays($ahora);

                return [
                    'nivel' => 'urgente',
                    'badge_class' => 'badge-danger',
                    'etiqueta' => 'Memoria pendiente',
                    'plazo_humano' => "Vencido hace {$diasVencido}d",
                    'siguiente_accion' => 'Notificar estudiante',
                ];
            }

            $diasRestantes = $limite3Meses ? (int) $ahora->diffInDays($limite3Meses, false) : 0;

            return [
                'nivel' => 'al_dia',
                'badge_class' => 'badge-success',
                'etiqueta' => 'En curso normal',
                'plazo_humano' => $diasRestantes > 0 ? "Quedan {$diasRestantes}d de plazo" : 'Vence hoy',
                'siguiente_accion' => 'Seguimiento',
            ];
        }

        // 4. Memoria registrada con observaciones pendientes de subsanar
        if ($memoria->estado === EstadoMemoria::Observada->value) {
            return [
                'nivel' => 'urgente',
                'badge_class' => 'badge-danger',
                'etiqueta' => 'Memoria observada',
                'plazo_humano' => 'Requiere subsanación',
                'siguiente_accion' => 'Ver observaciones',
            ];
        }

        // 5. Memoria en trámite administrativo dentro de la oficina UPS
        if (in_array($memoria->estado, [EstadoMemoria::Recibido->value, EstadoMemoria::EnRevision->value], true)) {
            return [
                'nivel' => 'tramite',
                'badge_class' => 'badge-warning',
                'etiqueta' => 'Pendiente auditoría',
                'plazo_humano' => 'En espera de revisión',
                'siguiente_accion' => 'Auditar sellos',
            ];
        }

        if ($memoria->estado === EstadoMemoria::EsperandoFirmas->value) {
            return [
                'nivel' => 'tramite',
                'badge_class' => 'badge-warning',
                'etiqueta' => 'Esperando firmas',
                'plazo_humano' => 'Firma de Jefatura',
                'siguiente_accion' => 'Firmar certificación',
            ];
        }

        if ($memoria->estado === EstadoMemoria::Lista->value) {
            return [
                'nivel' => 'tramite',
                'badge_class' => 'badge-info',
                'etiqueta' => 'Certificación lista',
                'plazo_humano' => 'Lista para entregar',
                'siguiente_accion' => 'Registrar entrega',
            ];
        }

        return [
            'nivel' => 'al_dia',
            'badge_class' => 'badge-success',
            'etiqueta' => 'Al día',
            'plazo_humano' => 'Trámite normal',
            'siguiente_accion' => 'Ver expediente',
        ];
    }
}
