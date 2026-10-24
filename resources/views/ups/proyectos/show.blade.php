@extends('layouts.portal')

@section('title', $proyecto->titulo)
@section('heading', 'Expediente del proyecto')
@section('subtitle', 'Ficha administrativa, participantes y situación documental.')

@section('page-actions')
    <a class="btn" href="{{ route('ups.proyectos.index') }}" title="Volver al listado general de proyectos">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        <span>Volver a proyectos</span>
    </a>

    <a class="btn" href="{{ route('ups.proyectos.documentos', $proyecto->id_proyecto) }}" title="Ver repositorio completo de documentos y archivos adjuntos">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        <span>Todos los documentos ({{ $proyecto->documentos->count() }})</span>
    </a>

    <a class="btn" href="{{ route('ups.proyectos.historial', $proyecto->id_proyecto) }}" title="Ver registro cronológico de cambios de fase y eventos">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span>Historial de cambios</span>
    </a>

    @if($proyecto->estado_expediente === 'en_tramite')
        <details class="project-actions-menu">
            <summary class="btn" title="Opciones extraordinarias y trámites administrativos">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                <span>Más opciones</span>
            </summary>
            <div class="project-actions-popover" role="menu" aria-label="Acciones del proyecto">
                <button type="button" role="menuitem" data-project-modal="modal-ampliar">Ampliar plazo</button>
                @can('prorrogas.resolver')<button type="button" role="menuitem" data-project-modal="modal-prorroga">Registrar prórroga</button>@endcan
                <button type="button" role="menuitem" data-project-modal="modal-retirar">Retirar estudiante</button>
                @if(auth()->user()->hasRole('jefe_ups'))<button type="button" role="menuitem" class="project-actions-danger" data-project-modal="modal-cancelar">Cancelar proyecto</button>@endif
            </div>
        </details>
        <a class="btn btn-primary" href="{{ route('ups.proyectos.edit', $proyecto->id_proyecto) }}" title="Modificar datos del proyecto y participantes">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            <span>Editar proyecto</span>
        </a>
    @endif
@endsection

@section('content')
    @php
        $nombresFases = [
            'inscripcion' => ['actual' => 'Inscripción', 'siguiente' => 'anteproyecto', 'sig_nombre' => 'Anteproyecto'],
            'anteproyecto' => ['actual' => 'Anteproyecto', 'siguiente' => 'avance_50', 'sig_nombre' => 'Avance 50%'],
            'avance_50' => ['actual' => 'Avance 50%', 'siguiente' => 'memoria_final', 'sig_nombre' => 'Memoria final'],
            'memoria_final' => ['actual' => 'Memoria final', 'siguiente' => 'finalizacion', 'sig_nombre' => 'Finalización'],
            'finalizacion' => ['actual' => 'Finalización', 'siguiente' => null, 'sig_nombre' => null],
        ];
        $infoFase = $nombresFases[$proyecto->fase_actual] ?? ['actual' => $proyecto->fase_actual, 'siguiente' => null, 'sig_nombre' => null];
        $docFaseActual = $proyecto->documentos->firstWhere('fase', $proyecto->fase_actual);
        $memoriaAprobada = $proyecto->memoria && in_array($proyecto->memoria->estado->value, [
            \App\Enums\EstadoMemoria::Aprobada->value,
            \App\Enums\EstadoMemoria::Aprovada->value,
            \App\Enums\EstadoMemoria::EsperandoFirmas->value,
            \App\Enums\EstadoMemoria::Lista->value,
            \App\Enums\EstadoMemoria::Entregada->value,
        ], true);
        $extensionVigente = $proyecto->extensionPlazoVigente();
        $extensionSecundaria = $proyecto->extensionPlazoSecundaria();
        $ampliacionPendiente = $proyecto->ampliacionPendiente();
        $prorrogaActiva = $extensionVigente;
    @endphp

    <!-- 1. Ficha del Proyecto, Estado Actual y Siguiente Paso -->
    <section class="card">
        <div class="card-head">
            <div>
                <h2>{{ $proyecto->titulo }}</h2>
                <p class="muted" style="margin:5px 0 0">Número de proyecto #{{ $proyecto->numero_proyecto ?? $proyecto->id_proyecto }} · Expediente #{{ $proyecto->id_proyecto }}</p>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                @if($extensionVigente && $proyecto->estado_expediente === 'en_tramite')
                    <span class="badge badge-warning" style="display: inline-flex; align-items: center; gap: 5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span>{{ $extensionVigente->tipo === 'ampliacion_plazo' ? 'Plazo ampliado' : 'En prórroga' }}</span>
                    </span>
                @endif
                <span class="badge {{ $proyecto->estado_expediente === 'finalizado' ? 'badge-success' : ($proyecto->estado_expediente === 'cancelado' ? 'badge-danger' : 'badge-info') }}">
                    {{ ['en_tramite'=>'En trámite','finalizado'=>'Finalizado','cancelado'=>'Cancelado'][$proyecto->estado_expediente] ?? $proyecto->estado_expediente }}
                </span>
            </div>
        </div>
        <div class="card-body">
            @if($extensionVigente && $proyecto->estado_expediente === 'en_tramite')
                @php
                    $esAmpliacion = $extensionVigente->tipo === 'ampliacion_plazo';
                    $tituloBanner = $esAmpliacion ? 'Proyecto con ampliación de plazo vigente' : 'Proyecto en período de prórroga';
                    $fechaActiva = ($extensionVigente->fecha_nueva ?? $proyecto->fecha_final)?->format('d/m/Y');
                    $resolucionTexto = $extensionVigente->autorizacion ?: $extensionVigente->resolucion;
                @endphp
                <div style="margin-bottom: 20px; padding: 14px 18px; background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 9px; display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: flex-start; gap: 12px; min-width: 260px; flex: 1;">
                        <div style="font-size: 20px; line-height: 1;" aria-hidden="true">⏱</div>
                        <div>
                            <strong style="color: #92400e; font-size: 13.5px; display: block; margin-bottom: 2px;">
                                {{ $tituloBanner }}
                            </strong>
                            <p style="margin: 0; color: #78350f; font-size: 12.5px; line-height: 1.5;">
                                Nueva fecha activa límite: <strong style="font-size: 13px;">{{ $fechaActiva }}</strong>
                                @if($extensionSecundaria)
                                    <span style="color: #b45309;">
                                        (Abarca más que {{ $extensionSecundaria->tipo === 'ampliacion_plazo' ? 'la ampliación previa' : 'la prórroga previa' }} del {{ $extensionSecundaria->fecha_nueva?->format('d/m/Y') }})
                                    </span>
                                @elseif($extensionVigente->fecha_anterior)
                                    <span style="color: #b45309;">(Plazo original: {{ $extensionVigente->fecha_anterior->format('d/m/Y') }})</span>
                                @endif
                                @if($resolucionTexto)
                                    <br><span style="font-size: 11.5px; color: #92400e;">{{ $esAmpliacion ? 'Resolución / Dictamen' : 'Autorización / Resolución' }}: <strong>{{ $resolucionTexto }}</strong></span>
                                @endif
                                @if($extensionVigente->motivo)
                                    <br><span style="font-size: 11.5px; color: #78350f;">Motivo: {{ \Illuminate\Support\Str::limit($extensionVigente->motivo, 160) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('ups.proyectos.historial', $proyecto->id_proyecto) }}" class="btn btn-small" style="background: #ffffff; border-color: #fcd34d; color: #92400e; text-decoration: none;">
                        Ver en historial →
                    </a>
                </div>
            @endif

            @if($ampliacionPendiente && $proyecto->estado_expediente === 'en_tramite')
                <div style="margin-bottom: 20px; padding: 12px 16px; background: #fefce8; border: 1px solid #fef08a; border-radius: 8px; color: #854d0e; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div style="font-size: 12.5px;">
                        <strong>Solicitud de ampliación en trámite:</strong> Se solicitó extensión de plazo hasta el <strong>{{ $ampliacionPendiente->fecha_nueva?->format('d/m/Y') }}</strong> (Pendiente de resolución).
                    </div>
                    @can('prorrogas.resolver')
                        <a href="{{ route('ups.prorrogas.index', ['estado' => 'pendiente']) }}" class="btn btn-small" style="background: #ffffff;">
                            Revisar solicitud →
                        </a>
                    @endcan
                </div>
            @endif
            @if($proyecto->estado_expediente === 'finalizado')
                <div class="notice" style="margin-bottom: 20px;">
                    <strong>Proyecto finalizado</strong>
                    El proyecto concluyó formalmente todas sus etapas el {{ $proyecto->fecha_finalizacion?->format('d/m/Y') ?? '—' }}.
                </div>
            @elseif($proyecto->estado_expediente === 'cancelado')
                <div class="notice" style="margin-bottom: 20px; border-color:#fecaca; color:#991b1b; background:#fef2f2;">
                    <strong>Proyecto cancelado</strong>
                    Este expediente fue cancelado y no admite nuevas transiciones.
                </div>
            @else
                <style>
                    .project-status-box {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        gap: 24px;
                        margin-bottom: 22px;
                        padding: 18px 20px;
                        background: #fbfcfd;
                        border: 1px solid var(--border);
                        border-radius: 9px;
                    }
                    .status-current-col {
                        flex: 1 1 500px;
                        min-width: 280px;
                    }
                    .status-next-col {
                        flex: 0 0 250px;
                        min-width: 220px;
                        border-left: 1px solid var(--border);
                        padding-left: 22px;
                    }
                    .status-doc-card {
                        margin-top: 12px;
                        padding: 14px 16px;
                        background: #edf2f7;
                        border: 1.5px solid #cbd5e1;
                        border-radius: 8px;
                    }
                    .status-doc-card.pending {
                        background: #edf2f7;
                        border: 1.5px dashed #94a3b8;
                    }
                    .status-doc-card.has-doc {
                        background: #f1f5f9;
                        border: 1.5px solid #cbd5e1;
                        border-left: 4px solid var(--navy);
                    }
                    @media (max-width: 768px) {
                        .project-status-box {
                            flex-direction: column;
                            gap: 16px;
                        }
                        .status-next-col {
                            border-left: 0;
                            border-top: 1px solid var(--border);
                            padding-left: 0;
                            padding-top: 14px;
                            width: 100%;
                        }
                    }
                </style>

                <div class="project-status-box">
                    <!-- 1. Estado Actual (Mayor tamaño y énfasis en documentación) -->
                    <div class="status-current-col">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                            <small class="muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: .06em; font-weight: 700;">
                                Estado actual del proyecto
                            </small>
                            <span class="badge {{ $docFaseActual ? 'badge-success' : 'badge-warning' }}" style="font-size: 10px;">
                                {{ $docFaseActual ? 'Documentado' : 'Documento pendiente' }}
                            </span>
                        </div>

                        <div style="font-size: 20px; font-weight: 700; color: #18283a; margin-bottom: 8px;">
                            Fase de {{ $infoFase['actual'] }}
                        </div>

                        <!-- Bloque de Documento Adjunto (Énfasis principal con mayor contraste) -->
                        <div class="status-doc-card {{ $docFaseActual ? 'has-doc' : 'pending' }}">
                            <small style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 8px; color: #334155;">
                                Documento de respaldo de esta fase
                            </small>

                            @if($docFaseActual)
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                    <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                                        <span style="font-size: 20px;" aria-hidden="true">📄</span>
                                        <div style="min-width: 0;">
                                            <a href="{{ route('ups.documentos-proyecto.descargar', $docFaseActual->id_documento_proyecto) }}" target="_blank" rel="noopener" style="font-weight: 700; font-size: 13.5px; color: #0f172a; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 340px;">
                                                {{ $docFaseActual->nombre_original }}
                                            </a>
                                            <small class="muted" style="font-size: 11px;">
                                                {{ number_format($docFaseActual->tamano_bytes / 1024, 0) }} KB · Subido el {{ $docFaseActual->created_at?->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <a href="{{ route('ups.documentos-proyecto.descargar', $docFaseActual->id_documento_proyecto) }}" target="_blank" rel="noopener" class="btn btn-small" style="background: #ffffff;">
                                            Ver PDF ↗
                                        </a>

                                        @if($proyecto->estado_expediente === 'en_tramite')
                                            <details style="position: relative;">
                                                <summary class="btn btn-small" style="background: #ffffff; cursor: pointer; list-style: none;">+ Cargar otro</summary>
                                                <div style="position: absolute; z-index: 20; right: 0; top: calc(100% + 6px); width: 270px; padding: 12px; background: #fff; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 8px 24px rgba(23,40,58,0.12);">
                                                    <form method="POST" enctype="multipart/form-data" action="{{ route('ups.proyectos.documentos.store', $proyecto->id_proyecto) }}">
                                                        @csrf
                                                        <input type="hidden" name="fase" value="{{ $proyecto->fase_actual }}">
                                                        <label for="subir-otro-pdf" style="display: block; font-size: 11px; font-weight: 600; margin-bottom: 6px;">Cargar nuevo PDF (máx. 15 MB)</label>
                                                        <input id="subir-otro-pdf" name="archivo" type="file" accept="application/pdf,.pdf" required style="font-size: 12px; margin-bottom: 8px; width: 100%;">
                                                        <button type="submit" class="btn btn-primary btn-small" style="width: 100%;">Guardar documento</button>
                                                    </form>
                                                </div>
                                            </details>
                                        @endif
                                    </div>
                                </div>
                            @else
                                @if($proyecto->estado_expediente === 'en_tramite')
                                    <form method="POST" enctype="multipart/form-data" action="{{ route('ups.proyectos.documentos.store', $proyecto->id_proyecto) }}">
                                        @csrf
                                        <input type="hidden" name="fase" value="{{ $proyecto->fase_actual }}">
                                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                            <div style="flex: 1; min-width: 220px;">
                                                <input name="archivo" type="file" accept="application/pdf,.pdf" required style="font-size: 12px; width: 100%; padding: 7px 10px; border: 1px solid #94a3b8; border-radius: 6px; background: #ffffff; color: #1e293b;">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-small" style="white-space: nowrap;">
                                                Subir PDF de {{ $infoFase['actual'] }}
                                            </button>
                                        </div>
                                    </form>
                                    <small style="display: block; margin-top: 6px; font-size: 11px; color: #475569;">
                                        Adjunta el archivo PDF oficial (máximo 15 MB) correspondiente a la fase de {{ $infoFase['actual'] }}.
                                    </small>
                                @else
                                    <p class="muted" style="margin: 0; font-size: 12px;">Sin documento de respaldo registrado en esta etapa.</p>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- 2. Siguiente Paso (Menor énfasis) -->
                    <div class="status-next-col">
                        <small class="muted" style="display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px;">
                            Siguiente paso
                        </small>

                        @if($infoFase['siguiente'])
                            <div style="font-size: 14px; font-weight: 600; color: #18283a; margin-bottom: 10px;">
                                {{ $infoFase['sig_nombre'] }}
                            </div>

                            @if($proyecto->fase_actual === 'memoria_final')
                                @if($memoriaAprobada)
                                    <form method="POST" action="{{ route('ups.proyectos.admitirFase', $proyecto->id_proyecto) }}" style="margin: 0;">
                                        @csrf
                                        <button class="btn btn-small" type="submit">Avanzar fase →</button>
                                    </form>
                                @else
                                    <p class="muted" style="margin: 0; font-size: 11.5px; line-height: 1.4;">
                                        Requiere memoria aprobada por coordinación académica.
                                        @if($proyecto->memoria)
                                            <a href="{{ route('ups.memorias.show', $proyecto->memoria->id_memoria) }}" style="display: block; margin-top: 4px;">
                                                Ver estado ({{ $proyecto->memoria->estado->label() }}) →
                                            </a>
                                        @endif
                                    </p>
                                @endif
                            @else
                                <form method="POST" action="{{ route('ups.proyectos.admitirFase', $proyecto->id_proyecto) }}" style="margin: 0;">
                                    @csrf
                                    <button class="btn btn-small" type="submit">Avanzar a {{ $infoFase['sig_nombre'] }} →</button>
                                </form>
                            @endif
                        @elseif($proyecto->fase_actual === 'finalizacion')
                            <div style="font-size: 14px; font-weight: 600; color: #18283a; margin-bottom: 10px;">
                                Finalización oficial
                            </div>
                            @if(auth()->user()->hasRole('jefe_ups'))
                                <form method="POST" action="{{ route('ups.proyectos.finalizar', $proyecto->id_proyecto) }}" style="margin: 0;">
                                    @csrf
                                    <button class="btn btn-small" type="submit">Finalizar proyecto</button>
                                </form>
                            @else
                                <span class="muted" style="font-size: 12px;">Pendiente de Jefatura UPS</span>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            <!-- Datos Generales -->
            <div class="detail-grid">
                <div class="detail"><small>N° Proyecto</small><strong>#{{ $proyecto->numero_proyecto ?? '—' }}</strong></div>
                <div class="detail"><small>Área</small><strong>{{ $proyecto->areaConocimiento?->nombre ?? '—' }}</strong></div>
                <div class="detail"><small>Fecha de inicio</small><strong>{{ $proyecto->fecha_inicio?->format('d/m/Y') ?? '—' }}</strong></div>
                <div class="detail" @if($extensionVigente && $proyecto->estado_expediente === 'en_tramite') style="border-color: #fde68a; background: #fffdf5;" @endif>
                    <small style="{{ $extensionVigente && $proyecto->estado_expediente === 'en_tramite' ? 'color: #92400e; font-weight: 700;' : '' }}">
                        @if($extensionVigente && $proyecto->estado_expediente === 'en_tramite')
                            {{ $extensionVigente->tipo === 'ampliacion_plazo' ? 'Fecha final (Plazo ampliado)' : 'Fecha final (Prórroga activa)' }}
                        @else
                            Fecha final
                        @endif
                    </small>
                    <strong style="{{ $extensionVigente && $proyecto->estado_expediente === 'en_tramite' ? 'color: #b45309;' : '' }}">
                        {{ $proyecto->fecha_final?->format('d/m/Y') ?? 'Pendiente' }}
                    </strong>
                    @if($extensionVigente && $proyecto->estado_expediente === 'en_tramite')
                        @if($extensionSecundaria)
                            <span class="muted" style="display: block; font-size: 10px; margin-top: 2px;">
                                {{ $extensionSecundaria->tipo === 'ampliacion_plazo' ? 'Ampliación previa' : 'Prórroga previa' }}: {{ $extensionSecundaria->fecha_nueva?->format('d/m/Y') }}
                            </span>
                        @elseif($extensionVigente->fecha_anterior)
                            <span class="muted" style="display: block; font-size: 10px; margin-top: 2px;">
                                Plazo inicial: {{ $extensionVigente->fecha_anterior->format('d/m/Y') }}
                            </span>
                        @endif
                    @endif
                </div>
                <div class="detail"><small>Docente tutor</small><strong>{{ $proyecto->tutor ?: '—' }}</strong></div>
                <div class="detail"><small>Subcoordinador</small><strong>{{ $proyecto->subcoordinador }}</strong></div>
                <div class="detail"><small>Correo coordinador</small><strong>{{ $proyecto->correo_coordinador ?: 'No registrado' }}</strong></div>
                <div class="detail"><small>Participantes</small><strong>{{ $proyecto->estudiantes->count() }}</strong></div>
            </div>
        </div>
    </section>

    <!-- 3. Estudiantes Participantes -->
    <section class="card">
        <div class="card-head">
            <h2>Estudiantes participantes</h2>
            <div class="actions">
                @if($proyecto->estado_expediente === 'en_tramite')
                    <a class="btn btn-small" href="{{ route('ups.proyectos.edit', $proyecto->id_proyecto) }}#estudiantes">Asociar estudiantes</a>
                @endif
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Carnet</th><th>Nombre</th><th>Carrera(s)</th><th></th></tr></thead>
                <tbody>
                    @forelse($proyecto->estudiantes as $estudiante)
                        <tr>
                            <td><strong>{{ $estudiante->due }}</strong></td>
                            <td>{{ $estudiante->nombre_completo }}</td>
                            <td>{{ $estudiante->carreras->pluck('nombre')->unique()->join(', ') ?: '—' }}</td>
                            <td><a href="{{ route('ups.estudiantes.show', $estudiante->id_estudiante) }}">Ver ficha →</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty">No hay participantes asociados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- 4. Memoria Final (Si aplica) -->
    @if($proyecto->memoria)
        <section class="card">
            <div class="card-head">
                <div><h2>Memoria final</h2><p class="muted" style="margin:5px 0 0">Estado documental del proyecto.</p></div>
                <a href="{{ route('ups.memorias.show', $proyecto->memoria->id_memoria) }}">Abrir memoria →</a>
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail"><small>Estado</small><strong>{{ $proyecto->memoria->estado->label() }}</strong></div>
                    <div class="detail"><small>Recibida</small><strong>{{ $proyecto->memoria->fecha_recibimiento?->format('d/m/Y') ?? '—' }}</strong></div>
                    <div class="detail"><small>Intento</small><strong>{{ $proyecto->memoria->intento }}</strong></div>
                    <div class="detail"><small>Último dictamen</small><strong>{{ $proyecto->memoria->revisiones->first()?->created_at?->format('d/m/Y H:i') ?? 'Sin revisión' }}</strong></div>
                </div>
                @if($proyecto->memoria->observaciones)<p class="muted" style="margin-top: 10px;">{{ $proyecto->memoria->observaciones }}</p>@endif
            </div>
        </section>
    @endif

    <!-- Diálogos de Acciones Extraordinarias (Menú 'Más acciones') -->
    @if($proyecto->estado_expediente === 'en_tramite')
        <dialog class="project-action-dialog" id="modal-ampliar" aria-labelledby="modal-ampliar-title">
            <form method="POST" enctype="multipart/form-data" action="{{ route('ups.proyectos.ampliar', $proyecto->id_proyecto) }}">
                @csrf <input type="hidden" name="form_key" value="ampliar">
                <div class="project-dialog-head"><h2 id="modal-ampliar-title">Ampliar plazo</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                <p class="muted">Fecha vigente: <strong>{{ $proyecto->fecha_final?->format('d/m/Y') ?? $proyecto->fecha_inicio?->format('d/m/Y') }}</strong></p>
                <div class="field"><label for="ampliar-fecha">Nueva fecha</label><input id="ampliar-fecha" name="fecha_nueva" type="date" required min="{{ ($proyecto->fecha_final ?? $proyecto->fecha_inicio)->copy()->addDay()->format('Y-m-d') }}" value="{{ old('form_key') === 'ampliar' ? old('fecha_nueva') : '' }}">@error('fecha_nueva') @if(old('form_key') === 'ampliar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                <div class="field"><label for="ampliar-motivo">Motivo</label><textarea id="ampliar-motivo" name="motivo" required minlength="10" maxlength="3000" placeholder="Indique el motivo">{{ old('form_key') === 'ampliar' ? old('motivo') : '' }}</textarea>@error('motivo') @if(old('form_key') === 'ampliar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                <div class="field"><label for="ampliar-respaldo">Documento de respaldo (opcional, PDF hasta 15 MB)</label><input id="ampliar-respaldo" name="respaldo" type="file" accept="application/pdf,.pdf"></div>
                <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit">Solicitar ampliación</button></div>
            </form>
        </dialog>

        @can('prorrogas.resolver')
            <dialog class="project-action-dialog" id="modal-prorroga" aria-labelledby="modal-prorroga-title">
                <form method="POST" action="{{ route('ups.proyectos.prorroga.store', $proyecto->id_proyecto) }}">
                    @csrf <input type="hidden" name="form_key" value="prorroga">
                    <div class="project-dialog-head"><h2 id="modal-prorroga-title">Registrar prórroga</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                    <p class="muted">Fecha vigente: <strong>{{ $proyecto->fecha_final?->format('d/m/Y') ?? $proyecto->fecha_inicio?->format('d/m/Y') }}</strong></p>
                    <div class="field"><label for="prorroga-fecha">Nueva fecha</label><input id="prorroga-fecha" name="fecha_nueva" type="date" required min="{{ ($proyecto->fecha_final ?? $proyecto->fecha_inicio)->copy()->addDay()->format('Y-m-d') }}" value="{{ old('form_key') === 'prorroga' ? old('fecha_nueva') : '' }}">@error('fecha_nueva') @if(old('form_key') === 'prorroga')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                    <div class="field"><label for="prorroga-autorizacion">Resolución / autorización</label><input id="prorroga-autorizacion" name="autorizacion" required maxlength="255" value="{{ old('form_key') === 'prorroga' ? old('autorizacion') : '' }}" placeholder="Número o referencia de autorización"></div>
                    <div class="field"><label for="prorroga-motivo">Motivo (opcional)</label><textarea id="prorroga-motivo" name="motivo" maxlength="3000" placeholder="Escriba el motivo">{{ old('form_key') === 'prorroga' ? old('motivo') : '' }}</textarea></div>
                    <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit">Confirmar prórroga</button></div>
                </form>
            </dialog>
        @endcan

        <dialog class="project-action-dialog" id="modal-retirar" aria-labelledby="modal-retirar-title">
            <form method="POST" action="{{ route('ups.proyectos.retirar', $proyecto->id_proyecto) }}">
                @csrf <input type="hidden" name="form_key" value="retirar">
                <div class="project-dialog-head"><h2 id="modal-retirar-title">Retirar estudiante</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                <p>Confirme el retiro del estudiante de este proyecto.</p>
                <div class="field"><label for="retirar-estudiante">Estudiante</label><select id="retirar-estudiante" name="estudiante_id" required><option value="">Seleccione un participante</option>@foreach($proyecto->estudiantes as $estudiante)<option value="{{ $estudiante->id_estudiante }}">{{ $estudiante->due }} · {{ $estudiante->nombre_completo }}</option>@endforeach</select></div>
                <div class="field"><label for="retirar-motivo">Motivo obligatorio</label><textarea id="retirar-motivo" name="motivo" required minlength="10" maxlength="3000" placeholder="Escriba el motivo del retiro"></textarea></div>
                <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit" @disabled($proyecto->estudiantes->isEmpty())>Confirmar retiro</button></div>
            </form>
        </dialog>

        @if(auth()->user()->hasRole('jefe_ups'))
            <dialog class="project-action-dialog" id="modal-cancelar" aria-labelledby="modal-cancelar-title">
                <form method="POST" action="{{ route('ups.proyectos.cancelar', $proyecto->id_proyecto) }}">
                    @csrf <input type="hidden" name="form_key" value="cancelar">
                    <div class="project-dialog-head"><h2 id="modal-cancelar-title">Cancelar proyecto</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                    <p>Está por cancelar <strong>{{ $proyecto->titulo }}</strong>. Esta acción cerrará el expediente.</p>
                    <div class="field"><label for="cancelar-motivo">Motivo obligatorio</label><textarea id="cancelar-motivo" name="motivo" required minlength="10" maxlength="3000" placeholder="Escriba el motivo de cancelación"></textarea></div>
                    <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit">Confirmar cancelación</button></div>
                </form>
            </dialog>
        @endif

        <style>
            .project-actions-menu{position:relative}.project-actions-menu>summary{display:flex;align-items:center;gap:10px;cursor:pointer;list-style:none}.project-actions-menu>summary::-webkit-details-marker{display:none}.project-actions-menu>summary:after{width:7px;height:7px;border-right:1.5px solid currentColor;border-bottom:1.5px solid currentColor;content:"";transform:rotate(45deg) translateY(-2px)}.project-actions-menu[open]>summary:after{transform:rotate(225deg) translate(-1px,-1px)}
            .project-actions-popover{position:absolute;z-index:30;top:calc(100% + 7px);right:0;min-width:205px;padding:5px;border:1px solid var(--border);border-radius:9px;background:#fff;box-shadow:0 10px 28px #17283a20}.project-actions-popover button{display:block;width:100%;padding:10px;border:0;border-radius:6px;color:#243347;background:transparent;font:inherit;font-size:13px;text-align:left;cursor:pointer}.project-actions-popover button:hover{color:var(--red);background:var(--pale)}.project-actions-popover .project-actions-danger{color:var(--red)}
            .project-action-dialog{width:min(600px,calc(100vw - 28px));max-height:min(88vh,800px);padding:0;border:1px solid var(--border);border-radius:12px;color:#243347;background:#fff;box-shadow:0 24px 70px #17283a40}.project-action-dialog::backdrop{background:#14223580;backdrop-filter:blur(2px)}.project-action-dialog>form{display:grid;gap:16px;padding:22px}.project-dialog-head{display:flex;align-items:center;justify-content:space-between;gap:14px}.project-dialog-head h2{margin:0;color:#233247;font-size:19px}.project-dialog-close{width:32px;height:32px;border:0;border-radius:7px;color:#667386;background:transparent;font-size:25px;line-height:1;cursor:pointer}.project-dialog-close:hover{color:var(--red);background:var(--pale)}.project-action-dialog p{margin:0;color:var(--muted);font-size:13px;line-height:1.5}.project-action-dialog .field{min-width:0}.project-action-dialog .field textarea{min-height:100px}.project-field-error{color:var(--red);font-size:12px}.project-action-dialog .form-actions{display:flex;justify-content:flex-end;gap:9px;margin:0;padding:0 22px 22px}
        </style>
        <script>
            (() => {
                const menu = document.querySelector('.project-actions-menu');
                document.querySelectorAll('[data-project-modal]').forEach((button) => button.addEventListener('click', () => {
                    menu?.removeAttribute('open');
                    document.getElementById(button.dataset.projectModal)?.showModal();
                }));
                document.querySelectorAll('[data-project-close]').forEach((button) => button.addEventListener('click', () => button.closest('dialog')?.close()));
                document.querySelectorAll('.project-action-dialog').forEach((dialog) => dialog.addEventListener('click', (event) => {
                    if (event.target === dialog) dialog.close();
                }));
                const formKey = @json(old('form_key'));
                if (formKey) {
                    document.getElementById(`modal-${formKey}`)?.showModal();
                }
            })();
        </script>
    @endif
@endsection
