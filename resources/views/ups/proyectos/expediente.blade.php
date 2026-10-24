@extends('layouts.portal')

@section('title', 'Expediente documental · '.$proyecto->titulo)
@section('heading', 'Expediente documental')
@section('subtitle', 'Documentación oficial y resguardo de archivos · Proyecto #'.($proyecto->numero_proyecto ?? $proyecto->id_proyecto))

@section('page-actions')
    <a class="btn" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">← Volver al proyecto</a>
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
        $totalDocs = $proyecto->documentos->count();
        $docFaseActual = $proyecto->documentos->firstWhere('fase', $proyecto->fase_actual);
    @endphp

    <!-- 1. Estado Documental del Proyecto -->
    <section class="card">
        <div class="card-head">
            <div>
                <h2>{{ $proyecto->titulo }}</h2>
                <p class="muted" style="margin: 4px 0 0;">Expediente #{{ $proyecto->id_proyecto }} · Proyecto #{{ $proyecto->numero_proyecto ?? '—' }}</p>
            </div>
            <span class="badge {{ $proyecto->estado_expediente === 'finalizado' ? 'badge-success' : ($proyecto->estado_expediente === 'cancelado' ? 'badge-danger' : 'badge-info') }}">
                {{ ['en_tramite'=>'En trámite','finalizado'=>'Finalizado','cancelado'=>'Cancelado'][$proyecto->estado_expediente] ?? $proyecto->estado_expediente }}
            </span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail">
                    <small>Fase actual</small>
                    <strong>{{ $infoFase['actual'] }}</strong>
                </div>
                <div class="detail">
                    <small>Documento de fase actual</small>
                    <strong>
                        @if($docFaseActual)
                            <a href="{{ route('ups.documentos-proyecto.descargar', $docFaseActual->id_documento_proyecto) }}" target="_blank" rel="noopener">
                                {{ $docFaseActual->nombre_original }}
                            </a>
                        @else
                            <span class="muted" style="font-weight: normal;">Pendiente de cargar</span>
                        @endif
                    </strong>
                </div>
                <div class="detail">
                    <small>Siguiente fase requerida</small>
                    <strong>{{ $infoFase['sig_nombre'] ?? 'Finalización oficial' }}</strong>
                </div>
                <div class="detail">
                    <small>Total de documentos</small>
                    <strong>{{ $totalDocs }} {{ $totalDocs === 1 ? 'archivo' : 'archivos' }}</strong>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Formulario de Carga Directo -->
    @if($proyecto->estado_expediente === 'en_tramite')
        <section class="card">
            <div class="card-head">
                <h2>Cargar documento al expediente</h2>
                <span class="badge">PDF (máximo 15 MB)</span>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('ups.proyectos.documentos.store', $proyecto->id_proyecto) }}">
                    @csrf
                    <div class="filters">
                        <div class="field" style="max-width: 260px;">
                            <label for="fase">Fase del documento</label>
                            <select id="fase" name="fase" required>
                                <option value="inscripcion" @selected($proyecto->fase_actual === 'inscripcion')>Inscripción</option>
                                <option value="anteproyecto" @selected($proyecto->fase_actual === 'anteproyecto')>Anteproyecto</option>
                                <option value="avance_50" @selected($proyecto->fase_actual === 'avance_50')>Avance 50%</option>
                                <option value="memoria_final" @selected($proyecto->fase_actual === 'memoria_final')>Memoria final</option>
                                <option value="otro">Otro documento / Anexo</option>
                            </select>
                        </div>

                        <div class="field" style="flex: 2; min-width: 240px;">
                            <label for="archivo">Archivo PDF</label>
                            <input id="archivo" name="archivo" type="file" accept="application/pdf,.pdf" required>
                        </div>

                        <div style="display: flex; align-items: flex-end;">
                            <button class="btn btn-primary" type="submit" style="min-height: 40px;">
                                Cargar documento
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    @else
        <div class="alert" style="background:#f8fafc; border-color:#cbd5e1; color:#475569; margin-bottom: 18px;">
            <strong>Expediente en solo lectura:</strong> Este proyecto se encuentra {{ $proyecto->estado_expediente }}. No se pueden adjuntar nuevos documentos.
        </div>
    @endif

    <!-- 3. Lista de Documentos del Expediente -->
    <section class="card">
        <div class="card-head">
            <h2>Documentos registrados en el expediente</h2>
            <span class="badge">{{ $totalDocs }} {{ $totalDocs === 1 ? 'documento' : 'documentos' }}</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Fase / Tipo</th>
                        <th>Detalles</th>
                        <th style="text-align: right;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyecto->documentos as $documento)
                        <tr>
                            <td>
                                <strong>{{ $documento->nombre_original }}</strong>
                            </td>
                            <td>
                                {{ ['inscripcion'=>'Inscripción','anteproyecto'=>'Anteproyecto','avance_50'=>'Avance 50%','memoria_final'=>'Memoria final','otro'=>'Otro anexo'][$documento->fase] ?? $documento->fase }}
                            </td>
                            <td>
                                {{ number_format($documento->tamano_bytes / 1024, 0) }} KB · {{ $documento->usuario?->nombre_completo ?? 'Sistema' }} · {{ $documento->created_at?->format('d/m/Y H:i') }}
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('ups.documentos-proyecto.descargar', $documento->id_documento_proyecto) }}" target="_blank" rel="noopener">
                                    Ver / Descargar →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty">Todavía no hay documentos cargados en este expediente.</td>
                        </tr>
                    @endforelse

                    @if($proyecto->documento)
                        <tr style="background:#fafafa;">
                            <td><strong>{{ $proyecto->documento }}</strong></td>
                            <td>Referencia</td>
                            <td>Referencia heredada del registro inicial</td>
                            <td style="text-align: right;"><span class="muted">Sin archivo</span></td>
                        </tr>
                    @endif

                    @if($proyecto->memoria)
                        <tr style="background:#f8fafc;">
                            <td><strong>Memoria final institucional</strong></td>
                            <td>Memoria final</td>
                            <td>Estado: {{ $proyecto->memoria->estado->label() }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('ups.memorias.show', $proyecto->memoria->id_memoria) }}">Ver memoria →</a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection
