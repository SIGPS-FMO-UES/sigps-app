@extends('layouts.portal')
@section('title', 'Certificaciones')
@section('heading', 'Certificaciones')
@section('subtitle', 'Emite certificaciones y registra su entrega.')

@section('content')
<style>
    .cert-filters { display: grid; grid-template-columns: minmax(220px, 1.5fr) minmax(180px, 1fr) minmax(160px, .8fr) auto; align-items: end; gap: 12px; }
    .cert-filters .field { margin: 0; min-width: 0; }
    .cert-table-wrap { overflow-x: hidden; }
    .cert-table { width: 100%; min-width: 0; table-layout: fixed; }
    .cert-table th, .cert-table td { overflow-wrap: anywhere; vertical-align: middle; }
    .cert-table .primary-text { color: #1e293b; font-weight: 650; }
    .cert-table .secondary-text { color: #64748b; font-size: 11px; margin-top: 3px; }
    .cert-table .student-due { font: 11px ui-monospace, SFMono-Regular, Menlo, monospace; color: #64748b; margin-top: 3px; }
    .cert-table .project-more { color: #64748b; font-size: 11px; margin-top: 4px; }
    .cert-table .project-more summary { cursor: pointer; width: fit-content; }
    .cert-table .project-more-content { padding-top: 5px; color: #475569; line-height: 1.5; }
    .cert-action-group { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; }
    .cert-action-group details { position: relative; }
    .cert-action-group details form { min-width: min(280px, calc(100vw - 48px)); padding: 12px; margin-top: 6px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; box-shadow: 0 8px 24px #18283a1a; }
    .cert-action-group details .field { margin-bottom: 9px; }
    .cert-pagination { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 16px; color:#64748b; font-size:12px; }
    .cert-pagination nav { display:flex; align-items:center; gap:6px; }
    .cert-pagination a, .cert-pagination span.page-state { display:inline-flex; align-items:center; justify-content:center; min-height:34px; min-width:34px; padding:0 10px; border:1px solid #d8dee8; border-radius:7px; color:#334155; background:#fff; text-decoration:none; }
    .cert-pagination span.page-state { background:#1e293b; color:#fff; border-color:#1e293b; }
    .cert-pagination span[aria-disabled="true"] { color:#94a3b8; background:#f8fafc; }
    @media (max-width: 760px) {
        .cert-filters { grid-template-columns: 1fr 1fr; }
        .cert-filters .field:first-child { grid-column: 1 / -1; }
        .cert-filters .btn { width: 100%; justify-content: center; }
        .cert-table thead { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
        .cert-table, .cert-table tbody { display:block; }
        .cert-table tbody tr.cert-row { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:8px 14px; padding:12px 14px; border-bottom:1px solid #e7ebf0; }
        .cert-table tbody tr.cert-row > td { display:block; min-width:0; padding:2px 0; border:0; }
        .cert-table tbody tr.cert-row > td:first-child { grid-column:1 / -1; }
        .cert-table tbody tr.cert-row > td:nth-child(2)::before,
        .cert-table tbody tr.cert-row > td:nth-child(3)::before { display:block; margin-bottom:4px; color:#718096; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; }
        .eligible-table tbody tr.cert-row > td:nth-child(2)::before { content:'Proyecto'; }
        .eligible-table tbody tr.cert-row > td:nth-child(3)::before { content:'Memoria lista'; }
        .history-table tbody tr.cert-row > td:nth-child(2)::before { content:'Proyecto'; }
        .history-table tbody tr.cert-row > td:nth-child(3)::before { content:'Emisión'; }
        .cert-table tbody tr.cert-row > td:last-child { grid-column:1 / -1; padding-top:8px; border-top:1px solid #eef1f4; }
        .cert-pagination { align-items:flex-start; flex-direction:column; }
    }
    @media (max-width: 420px) {
        .cert-filters { grid-template-columns:1fr; }
        .cert-filters .field:first-child { grid-column:auto; }
    }
</style>

<section class="card">
    <div class="card-body">
        <form class="filters cert-filters" method="GET" action="{{ route('ups.certificaciones.index') }}">
            <div class="field">
                <label for="search">Buscar</label>
                <input id="search" name="search" value="{{ $filtros['search'] ?? '' }}" maxlength="100" placeholder="Nombre, carnet o proyecto">
            </div>
            <div class="field">
                <label for="area_id">Área</label>
                <select id="area_id" name="area_id">
                    <option value="">Todas las áreas</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id_area_conocimiento }}" @selected(($filtros['area_id'] ?? '') == $area->id_area_conocimiento)>{{ $area->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="fecha">Aprobación</label>
                <input id="fecha" name="fecha" type="date" value="{{ $filtros['fecha'] ?? '' }}">
            </div>
            <button class="btn">Filtrar</button>
        </form>
    </div>
</section>

<section class="card">
    <div class="card-head">
        <div><h2>Listas para emitir</h2><p class="muted" style="margin:3px 0 0;font-size:12px">Memorias aprobadas</p></div>
        <span class="badge">{{ number_format($memorias->total()) }}</span>
    </div>
    <div class="cert-table-wrap">
        <table class="table cert-table eligible-table">
            <thead><tr><th style="width:29%">Estudiante</th><th style="width:38%">Proyecto</th><th style="width:15%">Memoria lista</th><th style="width:18%">Acción</th></tr></thead>
            <tbody>
            @forelse($memorias as $memoria)
                @foreach($memoria->proyecto?->estudiantes ?? [] as $estudiante)
                    @php($cert = $memoria->proyecto->certificaciones->firstWhere('estudiante_id', $estudiante->id_estudiante))
                    <tr class="cert-row">
                        <td>
                            <div class="primary-text">{{ $estudiante->nombre_completo }}</div>
                            <div class="student-due">{{ $estudiante->due }}</div>
                        </td>
                        <td>
                            <div class="primary-text">#{{ $memoria->numero_proyecto }} · {{ $memoria->proyecto->titulo }}</div>
                            <details class="project-more">
                                <summary>Ver área</summary>
                                <div class="project-more-content">{{ $memoria->proyecto->areaConocimiento?->nombre ?? 'Sin área registrada' }}</div>
                            </details>
                        </td>
                        <td>{{ ($memoria->fecha_listo ?? $memoria->fecha_aprovado)?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if($cert)
                                <a href="{{ route('ups.certificaciones.imprimir', $cert->id_certificacion) }}">Imprimir</a>
                            @else
                                @can('certificaciones.emitir')
                                    <form method="POST" action="{{ route('ups.certificaciones.emitir', $memoria->proyecto->id_proyecto) }}">@csrf<input type="hidden" name="estudiante_id" value="{{ $estudiante->id_estudiante }}"><button class="btn btn-primary btn-small">Emitir</button></form>
                                @else
                                    <span class="muted">Pendiente de jefatura</span>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr><td colspan="4" class="empty">No hay memorias aprobadas para certificar con estos filtros.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($memorias->hasPages())
        <div class="cert-pagination">
            <span>{{ $memorias->firstItem() }}–{{ $memorias->lastItem() }} de {{ number_format($memorias->total()) }}</span>
            <nav aria-label="Paginación de memorias aprobadas">
                @if($memorias->onFirstPage())<span aria-disabled="true">Anterior</span>@else<a href="{{ $memorias->previousPageUrl() }}">Anterior</a>@endif
                <span class="page-state" aria-current="page">{{ $memorias->currentPage() }} / {{ $memorias->lastPage() }}</span>
                @if($memorias->hasMorePages())<a href="{{ $memorias->nextPageUrl() }}">Siguiente</a>@else<span aria-disabled="true">Siguiente</span>@endif
            </nav>
        </div>
    @endif
</section>

<section class="card">
    <div class="card-head">
        <div><h2>Registro de entregas</h2><p class="muted" style="margin:3px 0 0;font-size:12px">Certificados emitidos</p></div>
        <span class="badge">{{ number_format($certificaciones->total()) }}</span>
    </div>
    <div class="cert-table-wrap">
        <table class="table cert-table history-table">
            <thead><tr><th style="width:29%">Folio y estudiante</th><th style="width:37%">Proyecto</th><th style="width:14%">Emisión</th><th style="width:20%">Entrega y acciones</th></tr></thead>
            <tbody>
            @forelse($certificaciones as $cert)
                <tr class="cert-row">
                    <td>
                        <div class="primary-text">{{ $cert->folio }}</div>
                        <div>{{ $cert->estudiante?->nombre_completo }}</div>
                        <div class="student-due">{{ $cert->estudiante?->due }}</div>
                    </td>
                    <td>
                        <div class="primary-text">{{ $cert->proyecto?->titulo }}</div>
                        @if($cert->proyecto?->areaConocimiento)
                            <details class="project-more"><summary>Ver área</summary><div class="project-more-content">{{ $cert->proyecto->areaConocimiento->nombre }}</div></details>
                        @endif
                    </td>
                    <td>{{ $cert->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                    <td>
                        <div class="cert-action-group">
                            @if($cert->fecha_entrega)
                                <span class="badge badge-success">Entregada {{ $cert->fecha_entrega->format('d/m/Y') }}</span>
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                            <a href="{{ route('ups.certificaciones.imprimir', $cert->id_certificacion) }}">Imprimir</a>
                            @if(!$cert->fecha_entrega)
                                <details>
                                    <summary class="btn btn-small">Registrar entrega</summary>
                                    <form method="POST" action="{{ route('ups.certificaciones.entregar', $cert->id_certificacion) }}">
                                        @csrf
                                        <div class="field"><label for="fecha-entrega-{{ $cert->id_certificacion }}">Fecha de entrega</label><input id="fecha-entrega-{{ $cert->id_certificacion }}" type="date" name="fecha_entrega" max="{{ now()->toDateString() }}" required></div>
                                        <div class="field"><label for="firma-receptor-{{ $cert->id_certificacion }}">Quién recibe</label><input id="firma-receptor-{{ $cert->id_certificacion }}" name="firma_receptor" maxlength="150" required></div>
                                        <button class="btn btn-primary btn-small">Guardar</button>
                                    </form>
                                </details>
                            @elseif($cert->firma_receptor)
                                <span class="secondary-text">Recibió: {{ $cert->firma_receptor }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Todavía no hay certificaciones emitidas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($certificaciones->hasPages())
        <div class="cert-pagination">
            <span>{{ $certificaciones->firstItem() }}–{{ $certificaciones->lastItem() }} de {{ number_format($certificaciones->total()) }}</span>
            <nav aria-label="Paginación del registro de entregas">
                @if($certificaciones->onFirstPage())<span aria-disabled="true">Anterior</span>@else<a href="{{ $certificaciones->previousPageUrl() }}">Anterior</a>@endif
                <span class="page-state" aria-current="page">{{ $certificaciones->currentPage() }} / {{ $certificaciones->lastPage() }}</span>
                @if($certificaciones->hasMorePages())<a href="{{ $certificaciones->nextPageUrl() }}">Siguiente</a>@else<span aria-disabled="true">Siguiente</span>@endif
            </nav>
        </div>
    @endif
</section>
@endsection
