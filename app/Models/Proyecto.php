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
use Illuminate\Support\Carbon;

#[Fillable([
    'numero_proyecto',
    'creado_por',
    'fecha_inicio',
    'fecha_final',
    'titulo',
    'miembros',
    'tutor',
    'subcoordinador',
    'correo_coordinador',
    'ubicacion',
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

    /**
     * Alias de correo del coordinador.
     */
    public function getEmailCoordinadorAttribute(): ?string
    {
        return $this->correo_coordinador;
    }

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

    /**
     * Retorna la prórroga aprobada con mayor fecha límite (o más reciente).
     */
    public function prorrogaAprobada(): ?ProcesoExtraordinario
    {
        return $this->procesosExtraordinarios
            ->where('tipo', 'prorroga')
            ->whereIn('estado', ['aprobada', 'registrado'])
            ->sortByDesc(fn (ProcesoExtraordinario $p) => $p->fecha_nueva?->timestamp ?? 0)
            ->first();
    }

    /**
     * Retorna la ampliación de plazo aprobada con mayor fecha límite (o más reciente).
     */
    public function ampliacionAprobada(): ?ProcesoExtraordinario
    {
        return $this->procesosExtraordinarios
            ->where('tipo', 'ampliacion_plazo')
            ->whereIn('estado', ['aprobada', 'registrado'])
            ->sortByDesc(fn (ProcesoExtraordinario $p) => $p->fecha_nueva?->timestamp ?? 0)
            ->first();
    }

    /**
     * Retorna la extensión de plazo vigente que abarca más tiempo (prórroga o ampliación de plazo aprobada).
     * Si ambas existen, prioriza la que otorgue la fecha límite más lejana.
     */
    public function extensionPlazoVigente(): ?ProcesoExtraordinario
    {
        $prorroga = $this->prorrogaAprobada();
        $ampliacion = $this->ampliacionAprobada();

        if ($prorroga && $ampliacion) {
            $fechaProrroga = $prorroga->fecha_nueva?->timestamp ?? 0;
            $fechaAmpliacion = $ampliacion->fecha_nueva?->timestamp ?? 0;

            if ($fechaAmpliacion > $fechaProrroga) {
                return $ampliacion;
            }

            if ($fechaProrroga > $fechaAmpliacion) {
                return $prorroga;
            }

            return ($ampliacion->id_proceso_extraordinario > $prorroga->id_proceso_extraordinario)
                ? $ampliacion
                : $prorroga;
        }

        return $prorroga ?? $ampliacion;
    }

    /**
     * Retorna la extensión de plazo secundaria cuando coexisten prórroga y ampliación aprobadas.
     */
    public function extensionPlazoSecundaria(): ?ProcesoExtraordinario
    {
        $vigente = $this->extensionPlazoVigente();
        if (! $vigente) {
            return null;
        }

        $prorroga = $this->prorrogaAprobada();
        $ampliacion = $this->ampliacionAprobada();

        if ($prorroga && $ampliacion) {
            return $vigente->id_proceso_extraordinario === $prorroga->id_proceso_extraordinario
                ? $ampliacion
                : $prorroga;
        }

        return null;
    }

    /**
     * Alias compatible con extensionPlazoVigente().
     */
    public function prorrogaActiva(): ?ProcesoExtraordinario
    {
        return $this->extensionPlazoVigente();
    }

    /**
     * Retorna la solicitud de ampliación de plazo pendiente de resolución.
     */
    public function ampliacionPendiente(): ?ProcesoExtraordinario
    {
        return $this->procesosExtraordinarios
            ->where('tipo', 'ampliacion_plazo')
            ->where('estado', 'pendiente')
            ->sortByDesc('id_proceso_extraordinario')
            ->first();
    }

    public function certificaciones(): HasMany
    {
        return $this->hasMany(Certificacion::class, 'proyecto_id', 'id_proyecto');
    }

    /**
     * Retorna el estado semafórico y diagnóstico operativo para usuarios administrativos no técnicos.
     *
     * @return array{nivel: string, badge_class: string, etiqueta: string, plazo_humano: string, fecha_referencia?: ?Carbon, siguiente_accion: string}
     */
    public function semaforoOperativo(): array
    {
        $fechaInicio = $this->fecha_inicio;
        $memoria = $this->memoria;
        $estadoMemoria = $memoria?->estado;
        $ahora = now();

        if (is_string($estadoMemoria)) {
            $estadoMemoria = EstadoMemoria::tryFrom($estadoMemoria);
        }

        // 1. Concluido formalmente
        if ($this->estado_expediente === 'finalizado' || $estadoMemoria === EstadoMemoria::Entregada) {
            return [
                'nivel' => 'concluido',
                'badge_class' => 'badge-info',
                'etiqueta' => 'Concluido',
                'plazo_humano' => 'Expediente cerrado',
                'fecha_referencia' => $memoria?->fecha_entrega ?? $this->fecha_finalizacion ?? $this->fecha_final,
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
                'fecha_referencia' => $limite18Meses,
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
                    'fecha_referencia' => $limite3Meses,
                    'siguiente_accion' => 'Notificar estudiante',
                ];
            }

            $diasRestantes = $limite3Meses ? (int) $ahora->diffInDays($limite3Meses, false) : 0;

            return [
                'nivel' => 'al_dia',
                'badge_class' => 'badge-success',
                'etiqueta' => 'En curso normal',
                'plazo_humano' => $diasRestantes > 0 ? "Quedan {$diasRestantes}d de plazo" : 'Vence hoy',
                'fecha_referencia' => $limite3Meses,
                'siguiente_accion' => 'Seguimiento',
            ];
        }

        // 4. Memoria registrada con observaciones pendientes de subsanar
        if ($estadoMemoria === EstadoMemoria::Observada) {
            return [
                'nivel' => 'urgente',
                'badge_class' => 'badge-danger',
                'etiqueta' => 'Memoria observada',
                'plazo_humano' => 'Requiere subsanación',
                'fecha_referencia' => $memoria->fecha_recibimiento,
                'siguiente_accion' => 'Ver observaciones',
            ];
        }

        // 5. Memoria en trámite administrativo dentro de la oficina UPS
        if (in_array($estadoMemoria, [EstadoMemoria::Recibido, EstadoMemoria::EnRevision], true)) {
            return [
                'nivel' => 'tramite',
                'badge_class' => 'badge-warning',
                'etiqueta' => 'Memoria recibida',
                'plazo_humano' => 'Pendiente de aprobación',
                'fecha_referencia' => $memoria->fecha_recibimiento,
                'siguiente_accion' => 'Aprobar memoria',
            ];
        }

        if (in_array($estadoMemoria, [EstadoMemoria::Aprobada, EstadoMemoria::Aprovada], true)) {
            return [
                'nivel' => 'tramite',
                'badge_class' => 'badge-info',
                'etiqueta' => 'Memoria aprobada',
                'plazo_humano' => 'Lista para firmas o entrega',
                'fecha_referencia' => $memoria->fecha_aprovado,
                'siguiente_accion' => 'Firmas o Entrega',
            ];
        }

        if ($estadoMemoria === EstadoMemoria::EsperandoFirmas) {
            return [
                'nivel' => 'tramite',
                'badge_class' => 'badge-warning',
                'etiqueta' => 'En proceso de firmas',
                'plazo_humano' => 'Firmas físicas en trámite',
                'fecha_referencia' => $memoria->fecha_aprovado,
                'siguiente_accion' => 'Marcar listo',
            ];
        }

        if ($estadoMemoria === EstadoMemoria::Lista) {
            return [
                'nivel' => 'tramite',
                'badge_class' => 'badge-info',
                'etiqueta' => 'Lista para entrega',
                'plazo_humano' => 'Retenida en ventanilla',
                'fecha_referencia' => $memoria->fecha_listo ?? $memoria->fecha_aprovado,
                'siguiente_accion' => 'Entregar al estudiante',
            ];
        }

        return [
            'nivel' => 'al_dia',
            'badge_class' => 'badge-success',
            'etiqueta' => 'Al día',
            'plazo_humano' => 'Trámite normal',
            'fecha_referencia' => $this->fecha_final,
            'siguiente_accion' => 'Ver expediente',
        ];
    }
}
