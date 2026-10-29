@extends('layouts.portal')

@section('title', 'Expediente de memoria')
@section('heading', 'Memoria final')
@section('subtitle', 'Documento y ciclo de aprobación de la memoria del proyecto.')

@section('page-actions')
    <a class="btn" href="{{ route('ups.memorias.index') }}">← Memorias</a>
    <a class="btn" href="{{ route('ups.proyectos.show', $memoria->proyecto?->id_proyecto ?? $memoria->numero_proyecto) }}">Abrir proyecto</a>

    @if(auth()->user()->hasRole('jefe_ups'))
        @if(in_array($memoria->estado->value, ['recibido', 'en_revision', 'observada'], true))
            <form method="POST" action="{{ route('ups.memorias.aprobar', $memoria->id_memoria) }}" style="display:inline;margin:0;">
                @csrf
                <button class="btn btn-primary" type="submit">Aprobar memoria</button>
            </form>
        @elseif(in_array($memoria->estado->value, ['aprobada', 'aprovada'], true))
            <form method="POST" action="{{ route('ups.memorias.firmas', $memoria->id_memoria) }}" style="display:inline;margin:0;">
                @csrf
                <button class="btn" type="submit" title="Pasar al trámite de firmas físicas">Pasar a firmas</button>
            </form>
            <form method="POST" action="{{ route('ups.memorias.lista', $memoria->id_memoria) }}" style="display:inline;margin:0;">
                @csrf
                <button class="btn" type="submit" title="Marcar como lista para entrega en ventanilla">Marcar lista</button>
            </form>
        @elseif($memoria->estado === \App\Enums\EstadoMemoria::EsperandoFirmas)
            <form method="POST" action="{{ route('ups.memorias.lista', $memoria->id_memoria) }}" style="display:inline;margin:0;">
                @csrf
                <button class="btn" type="submit" title="Registrar que el certificado físico está firmado y retenido en ventanilla">Marcar listo en ventanilla</button>
            </form>
        @endif
    @endif

    @php
        $puedeEntregar = false;
        if ($memoria->estado !== \App\Enums\EstadoMemoria::Entregada) {
            if (in_array($memoria->estado->value, ['aprobada', 'aprovada', 'esperando_firmas', 'lista'], true)) {
                $puedeEntregar = true;
            }
        }
    @endphp

    @if($puedeEntregar)
        <details class="dropdown" style="position:relative;display:inline-block">
            <summary class="btn btn-primary" style="cursor:pointer">Entregar y finalizar</summary>
            <form method="POST" action="{{ route('ups.memorias.entregar', $memoria->id_memoria) }}" style="position:absolute;z-index:20;right:0;top:calc(100% + 6px);width:300px;padding:16px;background:#fff;border:1px solid var(--border);border-radius:8px;box-shadow:0 8px 24px rgba(23,40,58,0.14)">
                @csrf
                <p style="font-size:12px;margin:0 0 10px;color:#475569">Al registrar la entrega física, el proyecto asociado quedará <strong>finalizado automáticamente</strong>.</p>
                <div class="field" style="margin-bottom:8px">
                    <label for="fecha_entrega" style="font-size:12px;font-weight:600">Fecha de entrega</label>
                    <input id="fecha_entrega" type="date" name="fecha_entrega" required max="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" style="width:100%;font-size:13px;padding:6px">
                </div>
                <div class="field" style="margin-bottom:10px">
                    <label for="observaciones_entrega" style="font-size:12px;font-weight:600">Observaciones (opcional)</label>
                    <textarea id="observaciones_entrega" name="observaciones" rows="2" maxlength="2000" style="width:100%;font-size:12px;padding:6px" placeholder="Notas de entrega">{{ $memoria->observaciones }}</textarea>
                </div>
                <button class="btn btn-primary btn-small" type="submit" style="width:100%">Confirmar entrega y finalizar</button>
            </form>
        </details>
    @endif
@endsection

@section('content')
    <section class="card">
        <div class="card-head">
            <div>
                <h2>{{ $memoria->proyecto?->titulo ?? 'Proyecto no disponible' }}</h2>
                <p class="muted" style="margin:5px 0 0">Proyecto #{{ $memoria->numero_proyecto }} · Expediente #{{ $memoria->proyecto?->id_proyecto }} · Intento {{ $memoria->intento }}</p>
            </div>
            @php($estado = $memoria->estado instanceof \App\Enums\EstadoMemoria ? $memoria->estado->value : $memoria->estado)
            <span class="badge {{ $estado === 'entregada' ? 'badge-success' : (in_array($estado, ['aprobada', 'aprovada', 'lista'], true) ? 'badge-info' : 'badge-warning') }}">{{ $memoria->estado->label() }}</span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail"><small>N° Proyecto</small><strong>#{{ $memoria->numero_proyecto }}</strong></div>
                <div class="detail"><small>Área de conocimiento</small><strong>{{ $memoria->proyecto?->areaConocimiento?->nombre ?? '—' }}</strong></div>
                <div class="detail"><small>Fecha de recepción</small><strong>{{ $memoria->fecha_recibimiento?->format('d/m/Y') ?? '—' }}</strong></div>
                <div class="detail"><small>Registrada por</small><strong>{{ $memoria->registrador?->nombre_completo ?? 'Integración institucional' }}</strong></div>
                <div class="detail"><small>Participantes</small><strong>{{ $memoria->proyecto?->estudiantes->pluck('nombre_completo')->join(', ') ?: '—' }}</strong></div>
                <div class="detail"><small>Fecha de aprobación</small><strong>{{ $memoria->fecha_aprovado?->format('d/m/Y') ?? 'Pendiente' }}</strong></div>
                <div class="detail"><small>Certificado listo en ventanilla</small><strong>{{ $memoria->fecha_listo?->format('d/m/Y') ?? 'Pendiente' }}</strong></div>
                <div class="detail"><small>Fecha de entrega</small><strong>{{ $memoria->fecha_entrega?->format('d/m/Y') ?? 'Pendiente' }}</strong></div>
            </div>
            @if($memoria->observaciones)
                <div class="notice" style="margin-top:16px"><strong>Observación:</strong> {{ $memoria->observaciones }}</div>
            @endif

            @if($puedeEntregar)
                <div style="margin-top: 18px; padding: 14px 18px; background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <strong style="color: #166534; font-size: 13.5px; display: block; margin-bottom: 2px;">Memoria aprobada y lista para entrega física</strong>
                        <p style="margin: 0; font-size: 12px; color: #15803d;">Al registrar la entrega al estudiante, el proyecto #{{ $memoria->numero_proyecto }} quedará <strong>finalizado automáticamente</strong>.</p>
                    </div>
                    <form method="POST" action="{{ route('ups.memorias.entregar', $memoria->id_memoria) }}" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin: 0;">
                        @csrf
                        <input type="date" name="fecha_entrega" required max="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" style="font-size: 12px; padding: 6px 10px; border: 1px solid #86efac; border-radius: 6px; background: #fff;">
                        <button class="btn btn-primary btn-small" type="submit" style="white-space: nowrap;">Registrar entrega y finalizar</button>
                    </form>
                </div>
            @endif
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <div>
                <h2>Documento de memoria (PDF)</h2>
                <p class="muted" style="margin:5px 0 0">Archivo PDF oficial registrado en el expediente.</p>
            </div>
            @if($documentoDisponible)
                <div style="display:flex;gap:8px">
                    <a class="btn btn-small" href="{{ route('ups.documentos.descargar', $memoria->id_memoria) }}" target="_blank" rel="noopener">Abrir en pestaña nueva</a>
                    <a class="btn btn-small btn-primary" href="{{ route('ups.documentos.descargar', $memoria->id_memoria) }}" download>Descargar PDF</a>
                </div>
            @endif
        </div>
        <div class="card-body">
            @if($documentoDisponible)
                <iframe title="Documento PDF de la memoria final" src="{{ route('ups.documentos.descargar', $memoria->id_memoria) }}" style="width:100%;height:min(70vh,820px);border:1px solid #dee3ea;border-radius:8px;background:#f8fafc"></iframe>
            @else
                <div class="notice"><strong>Documento no disponible</strong>El archivo PDF aún no ha sido adjuntado o radicado en el expediente.</div>
            @endif
        </div>
    </section>
@endsection
