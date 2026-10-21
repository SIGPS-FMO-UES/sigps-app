@extends('layouts.portal')

@section('title', 'Expediente de memoria')
@section('heading', 'Memoria final')
@section('subtitle', 'Documento y dictámenes asociados al ciclo del proyecto.')

@section('page-actions')
    <a class="btn" href="{{ route('ups.memorias.index') }}">← Memorias</a>
    <a class="btn" href="{{ route('ups.proyectos.show', $memoria->proyecto_id) }}">Abrir proyecto</a>
    @if($documentoDisponible && in_array($memoria->estado->value, ['recibido', 'en_revision', 'observada'], true))
        <a class="btn btn-primary" href="{{ route('ups.auditoria.show', $memoria->id_memoria) }}">Auditar sellos y tinta azul</a>
    @endif
@endsection

@section('content')
    <section class="card">
        <div class="card-head">
            <div>
                <h2>{{ $memoria->proyecto?->titulo ?? 'Proyecto no disponible' }}</h2>
                <p class="muted" style="margin:5px 0 0">Expediente #{{ $memoria->proyecto_id }} · Intento {{ $memoria->intento }}</p>
            </div>
            @php($estado = $memoria->estado instanceof \App\Enums\EstadoMemoria ? $memoria->estado->value : $memoria->estado)
            <span class="badge {{ $estado === 'observada' ? 'badge-warning' : (in_array($estado, ['aprobada', 'aprovada'], true) ? 'badge-success' : 'badge-info') }}">{{ $memoria->estado->label() }}</span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail"><small>Área de conocimiento</small><strong>{{ $memoria->proyecto?->areaConocimiento?->nombre ?? '—' }}</strong></div>
                <div class="detail"><small>Fecha de recepción</small><strong>{{ $memoria->fecha_recibimiento?->format('d/m/Y') ?? '—' }}</strong></div>
                <div class="detail"><small>Registrada por</small><strong>{{ $memoria->registrador?->nombre_completo ?? 'Integración institucional' }}</strong></div>
                <div class="detail"><small>Participantes</small><strong>{{ $memoria->proyecto?->estudiantes->pluck('nombre_completo')->join(', ') ?: '—' }}</strong></div>
                <div class="detail"><small>Fecha de aprobación</small><strong>{{ $memoria->fecha_aprovado?->format('d/m/Y') ?? 'Pendiente' }}</strong></div>
                <div class="detail"><small>Estado de entrega</small><strong>{{ $memoria->fecha_entrega?->format('d/m/Y') ?? 'Pendiente' }}</strong></div>
            </div>
            @if($memoria->observaciones)
                <div class="notice" style="margin-top:16px"><strong>Observación más reciente</strong>{{ $memoria->observaciones }}</div>
            @endif
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <div>
                <h2>Documento de memoria</h2>
                <p class="muted" style="margin:5px 0 0">PDF privado recibido en el expediente para cotejo visual.</p>
            </div>
            @if($documentoDisponible)
                <a class="btn btn-small" href="{{ route('ups.documentos.descargar', $memoria->id_memoria) }}" target="_blank" rel="noopener">Abrir PDF</a>
            @endif
        </div>
        <div class="card-body">
            @if($documentoDisponible)
                <iframe title="Documento PDF de la memoria final" src="{{ route('ups.documentos.descargar', $memoria->id_memoria) }}" style="width:100%;height:min(70vh,820px);border:1px solid #dee3ea;border-radius:8px;background:#f8fafc"></iframe>
            @else
                <div class="notice"><strong>Documento aún no disponible</strong>La memoria debe ingresar desde la integración institucional en formato PDF. No se puede registrar un dictamen hasta que el archivo esté disponible en el expediente.</div>
            @endif
        </div>
    </section>

    <section class="card">
        <div class="card-head"><div><h2>Historial de auditoría documental</h2><p class="muted" style="margin:5px 0 0">Registro de cotejo de sellos y tinta azul.</p></div><span class="badge">{{ $memoria->revisiones->count() }} dictámenes</span></div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Fecha</th><th>Auditor</th><th>Resultado</th><th>Sellos</th><th>Tinta azul</th><th>Observaciones</th></tr></thead>
                <tbody>
                    @forelse($memoria->revisiones as $revision)
                        <tr>
                            <td>{{ $revision->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>{{ $revision->auditor?->nombre_completo ?? '—' }}</td>
                            <td><span class="badge {{ $revision->resultado === 'observado' ? 'badge-warning' : 'badge-success' }}">{{ ucfirst($revision->resultado) }}</span></td>
                            <td>{{ $revision->sellos_verificados ? 'Verificados' : 'No verificados' }}</td>
                            <td>{{ $revision->tinta_azul_verificada ? 'Verificada' : 'No verificada' }}</td>
                            <td>{{ $revision->observaciones ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Todavía no se han registrado dictámenes para esta memoria.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
