@extends('layouts.portal')
@section('title', 'Directorio de Estudiantes')
@section('heading', 'Estudiantes de Servicio Social')
@section('subtitle', 'Consulta, filtra y gestiona la participación de estudiantes en proyectos institucionales.')
@section('page-actions')
<a class="btn btn-primary" href="{{ route('ups.estudiantes.create') }}">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    <span>Añadir estudiante</span>
</a>
@endsection

@section('content')
<style>
    /* =========================================================
       TARJETAS DE TRIAJE / FILTROS VISUALES POR ESTADO
       ========================================================= */
    .triaje-chips-container {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }
    .triaje-chip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 7px;
        padding: 8px 12px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 999px;
        text-decoration: none;
        color: #243347;
        box-shadow: none;
        transition: background-color 0.15s ease, border-color 0.15s ease;
        position: relative;
        overflow: hidden;
    }
    .triaje-chip:hover {
        border-color: #cbd5e1;
        background: #fafbfd;
        transform: none;
        box-shadow: none;
    }
    .triaje-chip.active {
        border-color: var(--navy);
        background: #f8fafc;
        box-shadow: none;
    }
    .triaje-chip-info {
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    .triaje-chip-count {
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        color: #1e293b;
    }
    .triaje-chip-label { font-size: 12px; font-weight: 600; }
    .triaje-chip.active { background: #1e293b; color: #fff; }
    .triaje-chip.active .triaje-chip-count { color: #fff !important; }
    .triaje-chip:focus-visible, .carrera-pill:focus-visible,
    .filter-tag-remove:focus-visible, .clear-all-link:focus-visible {
        outline: 3px solid #2563eb;
        outline-offset: 2px;
    }

    /* =========================================================
       BARRA DE ACCESO RÁPIDO POR CARRERAS (PÍLDORAS)
       ========================================================= */
    .carreras-quick-filter {
        display: flex;
        align-items: center;
        gap: 8px;
        display: none;
    }
    .carreras-quick-filter::-webkit-scrollbar {
        height: 4px;
    }
    .carreras-quick-filter::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .carrera-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .carrera-pill:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #0f172a;
    }
    .carrera-pill.active {
        background: var(--navy);
        border-color: var(--navy);
        color: #ffffff;
    }
    .carrera-pill.active .pill-close {
        color: #cbd5e1;
    }
    .carrera-pill.active .pill-close:hover {
        color: #ffffff;
    }

    /* =========================================================
       OMNIBAR Y CONTROLES DE BÚSQUEDA
       ========================================================= */
    .omnibar-card {
        margin-bottom: 12px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: #ffffff;
    }
    .omnibar-body {
        padding: 12px 14px;
    }
    .omnibar-form {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .omnibar-primary-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: nowrap;
    }
    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
    }
    .search-icon {
        position: absolute;
        left: 12px;
        width: 17px;
        height: 17px;
        color: #94a3b8;
        pointer-events: none;
    }
    .search-input {
        width: 100%;
        height: 42px;
        padding-left: 38px;
        padding-right: 68px;
        border: 1px solid #d0d7e2;
        border-radius: 8px;
        font-size: 13px;
        color: #1e293b;
        background: #ffffff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .search-input:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px var(--pale);
        outline: none;
    }
    .search-clear-btn {
        position: absolute;
        right: 36px;
        width: 22px;
        height: 22px;
        display: none;
        align-items: center;
        justify-content: center;
        border: none;
        background: #e2e8f0;
        color: #475569;
        border-radius: 50%;
        cursor: pointer;
        padding: 0;
        font-size: 12px;
        line-height: 1;
    }
    .search-clear-btn:hover {
        background: #cbd5e1;
        color: #0f172a;
    }
    .search-shortcut-badge {
        position: absolute;
        right: 10px;
        padding: 2px 6px;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        background: #f8fafc;
        pointer-events: none;
    }
    .order-select-container {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .order-select-container label {
        font-size: 12px;
        font-weight: 650;
        color: #475569;
        white-space: nowrap;
    }
    .order-select {
        height: 42px;
        min-width: 160px;
        border: 1px solid #d0d7e2;
        border-radius: 8px;
        padding: 0 10px;
        font-size: 12px;
        background: #ffffff;
        color: #1e293b;
    }

    /* FILTROS SECUNDARIOS DESPLEGABLES */
    .advanced-filters-details {
        border-top: 1px dashed #e2e8f0;
        padding-top: 12px;
        margin-top: 2px;
    }
    .advanced-filters-summary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 650;
        color: #475569;
        cursor: pointer;
        user-select: none;
        padding: 4px 6px;
        border-radius: 6px;
    }
    .advanced-filters-summary:hover {
        color: var(--navy);
        background: #f1f5f9;
    }
    .active-filters-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--red);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
    }
    .advanced-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 12px;
        background: #f8fafc;
        padding: 14px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .advanced-grid .field label {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .advanced-grid .field select,
    .advanced-grid .field input {
        height: 38px;
        font-size: 12px;
    }

    /* =========================================================
       BARRA DE FILTROS ACTIVOS (TAGS)
       ========================================================= */
    .active-tags-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 10px 14px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 12px;
    }
    .active-tags-label {
        font-weight: 700;
        color: #475569;
        margin-right: 4px;
    }
    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 9px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        color: #1e293b;
        font-size: 11.5px;
        font-weight: 600;
    }
    .filter-tag-remove {
        color: #94a3b8;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-weight: 700;
        font-size: 13px;
        line-height: 1;
        cursor: pointer;
    }
    .filter-tag-remove:hover {
        color: #dc2626;
    }
    .clear-all-link {
        margin-left: auto;
        color: #b91c1c;
        font-weight: 650;
        font-size: 11.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 5px;
    }
    .clear-all-link:hover {
        background: #fee2e2;
        text-decoration: none;
    }

    /* =========================================================
       TABLA DE ESTUDIANTES Y DETALLES VISUALES
       ========================================================= */
    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        color: #ffffff;
        background: linear-gradient(135deg, #18283a 0%, #26394e 100%);
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(24, 40, 58, 0.15);
    }
    .student-name-block {
        display: flex;
        align-items: center;
        gap: 11px;
    }
    .student-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .student-link {
        color: #1e293b;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
    }
    .student-link:hover {
        color: var(--red);
        text-decoration: underline;
    }
    .student-date {
        font-size: 11px;
        color: #64748b;
    }
    .carnet-badge-wrap {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .carnet-badge {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 8px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        color: #1e293b;
        letter-spacing: 0.03em;
    }
    .copy-carnet-btn {
        background: transparent;
        border: none;
        padding: 3px;
        border-radius: 4px;
        cursor: pointer;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease, background 0.15s ease;
    }
    .copy-carnet-btn:hover {
        color: #1e293b;
        background: #e2e8f0;
    }
    .career-tag {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin: 2px 0;
    }
    .career-dept {
        display: block;
        font-size: 10.5px;
        color: #64748b;
        margin-top: 1px;
    }
    .project-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 650;
    }
    .project-pill-active {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }
    .project-pill-none {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
    }
    .project-pill-concluido {
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        color: #3730a3;
    }
    .status-dot-sm {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .dot-green { background: #10b981; }
    .dot-amber { background: #f59e0b; }
    .dot-indigo { background: #6366f1; }

    .project-titles-preview {
        font-size: 11px;
        color: #64748b;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-top: 3px;
    }
    .carnet-inline { display: flex; align-items: center; gap: 5px; margin-top: 2px; color: #64748b; font: 11px ui-monospace, SFMono-Regular, Menlo, monospace; }
    .carnet-inline .copy-carnet-btn { padding: 2px; }
    .row-more { margin-top: 4px; font-size: 11px; color: #64748b; }
    .row-more summary { cursor: pointer; width: fit-content; }
    .row-more-content { padding: 5px 0 0 8px; line-height: 1.5; color: #475569; overflow-wrap: anywhere; }
    .row-more-content > div + div { margin-top: 4px; }
    .table-wrap { overflow-x: hidden; }
    #tabla-estudiantes { width: 100%; min-width: 0; table-layout: fixed; }
    #tabla-estudiantes th, #tabla-estudiantes td { overflow-wrap: anywhere; }
    #tabla-estudiantes td { vertical-align: middle; }
    #tabla-estudiantes .student-name-block { gap: 9px; }
    #tabla-estudiantes .student-link { font-size: 12.5px; }

    /* ACCIONES DE TABLA */
    .actions-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-table-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 6px;
        text-decoration: none !important;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }
    .btn-table-primary {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    .btn-table-primary:hover {
        background: var(--navy);
        border-color: var(--navy);
        color: #ffffff;
    }
    .btn-table-icon {
        padding: 5px 7px;
        color: #64748b;
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .btn-table-icon:hover {
        color: var(--red);
        background: var(--pale);
        border-color: #fca5a5;
    }

    /* ESTADO VACÍO AMIGABLE */
    .empty-state-box {
        padding: 48px 24px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }
    .empty-state-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
    }
    .empty-state-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .empty-state-desc {
        font-size: 12.5px;
        color: #64748b;
        max-width: 440px;
        margin: 0;
        line-height: 1.5;
    }

    /* TOAST DE COPIADO */
    #copy-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #1e293b;
        color: #ffffff;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 0.2s ease, transform 0.2s ease;
        pointer-events: none;
    }
    #copy-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
    .student-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 16px;
        color: #64748b;
        font-size: 12px;
    }
    .student-pagination-nav { display: flex; align-items: center; gap: 6px; }
    .student-pagination-link, .student-pagination-current {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 34px; min-height: 34px; padding: 0 10px;
        border: 1px solid #d8dee8; border-radius: 7px;
        color: #334155; background: #fff; text-decoration: none;
    }
    .student-pagination-current { background: #1e293b; border-color: #1e293b; color: #fff; }
    .student-pagination-link[aria-disabled="true"] { color: #94a3b8; background: #f8fafc; }
    @media (max-width: 760px) {
        .omnibar-primary-row { flex-wrap: wrap; }
        .search-input-wrapper { flex-basis: 100%; }
        .omnibar-primary-row > .btn { flex: 1; justify-content: center; }
        #tabla-estudiantes thead { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
        #tabla-estudiantes, #tabla-estudiantes tbody { display: block; }
        #tabla-estudiantes tbody tr.estudiante-row { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 6px 12px; padding: 12px 14px; border-bottom: 1px solid #e7ebf0; }
        #tabla-estudiantes tbody tr.estudiante-row > td { display: block; min-width: 0; padding: 2px 0; border: 0; }
        #tabla-estudiantes tbody tr.estudiante-row > td:first-child { grid-column: 1 / -1; }
        #tabla-estudiantes tbody tr.estudiante-row > td:nth-child(2)::before,
        #tabla-estudiantes tbody tr.estudiante-row > td:nth-child(3)::before { display: block; margin-bottom: 4px; color: #718096; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        #tabla-estudiantes tbody tr.estudiante-row > td:nth-child(2)::before { content: 'Carrera'; }
        #tabla-estudiantes tbody tr.estudiante-row > td:nth-child(3)::before { content: 'Servicio social'; }
        #tabla-estudiantes tbody tr.estudiante-row > td:nth-child(4) { grid-column: 1 / -1; text-align: left !important; padding-top: 6px; border-top: 1px solid #eef1f4; }
        #tabla-estudiantes .actions-group { justify-content: flex-start !important; }
        .student-pagination { align-items: flex-start; flex-direction: column; }
    }
    @media (max-width: 420px) {
        .triaje-chips-container { gap: 5px; }
        .triaje-chip { padding: 7px 9px; }
        .triaje-chip-label { font-size: 11px; }
        .triaje-chip-count { font-size: 11px; }
        .omnibar-body { padding: 10px; }
        .order-select-container { flex-wrap: wrap; }
    }
</style>

<!-- ============================================================== -->
<!-- 1. PESTAÑAS / TARJETAS DE TRIAJE OPERATIVO (FILTRO VISUAL RÁPIDO) -->
<!-- ============================================================== -->
<div class="triaje-chips-container" role="tablist" aria-label="Filtro rápido por estado de servicio social">
    <a href="{{ route('ups.estudiantes.index', array_merge(request()->except(['page', 'estado_proyecto']), ['estado_proyecto' => 'todos'])) }}"
       class="triaje-chip chip-todos {{ ($estadoActivo ?? 'todos') === 'todos' ? 'active' : '' }}"
       role="tab"
       aria-selected="{{ ($estadoActivo ?? 'todos') === 'todos' ? 'true' : 'false' }}">
        <span class="triaje-chip-label">Todos</span>
        <span class="triaje-chip-count">{{ number_format($conteos['todos'] ?? 0) }}</span>
    </a>

    <!-- Con Proyecto -->
    <a href="{{ route('ups.estudiantes.index', array_merge(request()->except(['page', 'estado_proyecto']), ['estado_proyecto' => 'con_proyecto'])) }}"
       class="triaje-chip chip-con-proyecto {{ ($estadoActivo ?? '') === 'con_proyecto' ? 'active' : '' }}"
       role="tab"
       aria-selected="{{ ($estadoActivo ?? '') === 'con_proyecto' ? 'true' : 'false' }}">
        <span class="triaje-chip-label">En servicio</span>
        <span class="triaje-chip-count">{{ number_format($conteos['con_proyecto'] ?? 0) }}</span>
    </a>

    <!-- Sin Proyecto -->
    <a href="{{ route('ups.estudiantes.index', array_merge(request()->except(['page', 'estado_proyecto']), ['estado_proyecto' => 'sin_proyecto'])) }}"
       class="triaje-chip chip-sin-proyecto {{ ($estadoActivo ?? '') === 'sin_proyecto' ? 'active' : '' }}"
       role="tab"
       aria-selected="{{ ($estadoActivo ?? '') === 'sin_proyecto' ? 'true' : 'false' }}">
        <span class="triaje-chip-label">Sin proyecto</span>
        <span class="triaje-chip-count">{{ number_format($conteos['sin_proyecto'] ?? 0) }}</span>
    </a>

    <!-- Concluidos / Con Certificación -->
    <a href="{{ route('ups.estudiantes.index', array_merge(request()->except(['page', 'estado_proyecto']), ['estado_proyecto' => 'concluidos'])) }}"
       class="triaje-chip chip-concluidos {{ ($estadoActivo ?? '') === 'concluidos' ? 'active' : '' }}"
       role="tab"
       aria-selected="{{ ($estadoActivo ?? '') === 'concluidos' ? 'true' : 'false' }}">
        <span class="triaje-chip-label">Concluido</span>
        <span class="triaje-chip-count">{{ number_format($conteos['concluidos'] ?? 0) }}</span>
    </a>
</div>

<!-- ============================================================== -->
<!-- 2. PÍLDORAS DE ACCESO RÁPIDO POR CARRERA                       -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- 3. BARRA DE OMNIBÚSQUEDA Y CONTROLES SIMPLIFICADOS             -->
<!-- ============================================================== -->
<section class="card omnibar-card">
    <div class="omnibar-body">
        <form id="filtro-estudiantes-form" class="omnibar-form" method="GET" action="{{ route('ups.estudiantes.index') }}">
            <!-- Preservar estado_proyecto activo -->
            <input type="hidden" name="estado_proyecto" value="{{ $estadoActivo ?? 'todos' }}">

            <div class="omnibar-primary-row">
                <!-- Campo de búsqueda principal con icono y botón de limpieza rápida -->
                <div class="search-input-wrapper">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input id="search-input"
                           name="search"
                           type="text"
                           class="search-input"
                           value="{{ $filtros['search'] ?? '' }}"
                           maxlength="100"
                           placeholder="Buscar por nombre o carnet"
                           aria-label="Buscar por nombre o carnet"
                           autocomplete="off">
                    <button type="button" id="search-clear" class="search-clear-btn" title="Limpiar búsqueda" aria-label="Limpiar búsqueda">&times;</button>
                    <span class="search-shortcut-badge" title="Presiona '/' para buscar">/</span>
                </div>

                <!-- Botón Buscar -->
                <button type="submit" class="btn btn-primary" style="height: 42px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span>Buscar</span>
                </button>
            </div>

            <!-- Filtros Secundarios Desplegables -->
            @php
                $secondaryCount = 0;
                if (!empty($filtros['carrera_id'])) $secondaryCount++;
                if (!empty($filtros['departamento_id'])) $secondaryCount++;
                if (!empty($filtros['area_id'])) $secondaryCount++;
                if (!empty($filtros['fecha'])) $secondaryCount++;
            @endphp
            <details class="advanced-filters-details" @if($secondaryCount > 0) open @endif>
                <summary class="advanced-filters-summary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <span>Más filtros y orden</span>
                    @if($secondaryCount > 0)
                        <span class="active-filters-badge">{{ $secondaryCount }}</span>
                    @endif
                </summary>

                <div class="advanced-grid">
                    <div class="field">
                        <label for="orden-select">Ordenar por</label>
                        <select id="orden-select" name="orden" onchange="this.form.requestSubmit()">
                            <option value="nombre_asc" @selected(($filtros['orden'] ?? 'nombre_asc') === 'nombre_asc')>Nombre (A → Z)</option>
                            <option value="nombre_desc" @selected(($filtros['orden'] ?? '') === 'nombre_desc')>Nombre (Z → A)</option>
                            <option value="carnet_asc" @selected(($filtros['orden'] ?? '') === 'carnet_asc')>Carnet (menor a mayor)</option>
                            <option value="carnet_desc" @selected(($filtros['orden'] ?? '') === 'carnet_desc')>Carnet (mayor a menor)</option>
                            <option value="recientes" @selected(($filtros['orden'] ?? '') === 'recientes')>Registro más reciente</option>
                        </select>
                    </div>
                    <!-- Carrera -->
                    <div class="field">
                        <label for="carrera_id">Carrera Académica</label>
                        <select id="carrera_id" name="carrera_id" onchange="this.form.requestSubmit()">
                            <option value="">Todas las carreras</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}" @selected(($filtros['carrera_id'] ?? '') == $carrera->id_carrera)>{{ $carrera->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departamento -->
                    <div class="field">
                        <label for="departamento_id">Departamento</label>
                        <select id="departamento_id" name="departamento_id" onchange="this.form.requestSubmit()">
                            <option value="">Todos los departamentos</option>
                            @foreach($departamentos as $departamento)
                                <option value="{{ $departamento->id_departamento }}" @selected(($filtros['departamento_id'] ?? '') == $departamento->id_departamento)>{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Área de Proyecto -->
                    <div class="field">
                        <label for="area_id">Área de Proyecto</label>
                        <select id="area_id" name="area_id" onchange="this.form.requestSubmit()">
                            <option value="">Todas las áreas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id_area_conocimiento }}" @selected(($filtros['area_id'] ?? '') == $area->id_area_conocimiento)>{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha de Registro -->
                    <div class="field">
                        <label for="fecha">Fecha de Registro</label>
                        <input id="fecha" name="fecha" type="date" value="{{ $filtros['fecha'] ?? '' }}" onchange="this.form.requestSubmit()">
                    </div>
                </div>
            </details>
        </form>
    </div>
</section>

<!-- ============================================================== -->
<!-- 4. PÍLDORAS DE FILTROS ACTIVOS (CON BOTÓN DE REMOVER)          -->
<!-- ============================================================== -->
@php
    $carreraActiva = !empty($filtros['carrera_id']) ? $carreras->firstWhere('id_carrera', $filtros['carrera_id']) : null;
    $deptoActivo = !empty($filtros['departamento_id']) ? $departamentos->firstWhere('id_departamento', $filtros['departamento_id']) : null;
    $areaActiva = !empty($filtros['area_id']) ? $areas->firstWhere('id_area_conocimiento', $filtros['area_id']) : null;
    $hasActiveFilters = !empty($filtros['search']) || !empty($filtros['carrera_id']) || !empty($filtros['departamento_id']) || !empty($filtros['area_id']) || !empty($filtros['fecha']) || (($estadoActivo ?? 'todos') !== 'todos');
@endphp

@if($hasActiveFilters)
<div class="active-tags-bar" aria-label="Filtros aplicados actualmente">
    <span class="active-tags-label">Filtros aplicados:</span>

    @if(($estadoActivo ?? 'todos') !== 'todos')
        <div class="filter-tag">
            <span>Estado:
                @if($estadoActivo === 'con_proyecto') En servicio social
                @elseif($estadoActivo === 'sin_proyecto') Sin proyecto asignado
                @elseif($estadoActivo === 'concluidos') Servicio concluido
                @endif
            </span>
            <a href="{{ route('ups.estudiantes.index', array_merge(request()->except(['page', 'estado_proyecto']), ['estado_proyecto' => 'todos'])) }}" class="filter-tag-remove" title="Quitar filtro de estado">&times;</a>
        </div>
    @endif

    @if(!empty($filtros['search']))
        <div class="filter-tag">
            <span>Búsqueda: "{{ $filtros['search'] }}"</span>
            <a href="{{ route('ups.estudiantes.index', request()->except(['page', 'search'])) }}" class="filter-tag-remove" title="Quitar búsqueda">&times;</a>
        </div>
    @endif

    @if($carreraActiva)
        <div class="filter-tag">
            <span>Carrera: {{ $carreraActiva->nombre }}</span>
            <a href="{{ route('ups.estudiantes.index', request()->except(['page', 'carrera_id'])) }}" class="filter-tag-remove" title="Quitar carrera">&times;</a>
        </div>
    @endif

    @if($deptoActivo)
        <div class="filter-tag">
            <span>Departamento: {{ $deptoActivo->nombre }}</span>
            <a href="{{ route('ups.estudiantes.index', request()->except(['page', 'departamento_id'])) }}" class="filter-tag-remove" title="Quitar departamento">&times;</a>
        </div>
    @endif

    @if($areaActiva)
        <div class="filter-tag">
            <span>Área: {{ $areaActiva->nombre }}</span>
            <a href="{{ route('ups.estudiantes.index', request()->except(['page', 'area_id'])) }}" class="filter-tag-remove" title="Quitar área">&times;</a>
        </div>
    @endif

    @if(!empty($filtros['fecha']))
        <div class="filter-tag">
            <span>Registrado el: {{ \Carbon\Carbon::parse($filtros['fecha'])->format('d/m/Y') }}</span>
            <a href="{{ route('ups.estudiantes.index', request()->except(['page', 'fecha'])) }}" class="filter-tag-remove" title="Quitar fecha">&times;</a>
        </div>
    @endif

    <a href="{{ route('ups.estudiantes.index') }}" class="clear-all-link">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        <span>Limpiar todos los filtros</span>
    </a>
</div>
@endif

<!-- ============================================================== -->
<!-- 5. TABLA VISUAL DE ESTUDIANTES                                 -->
<!-- ============================================================== -->
<section class="card">
    <div class="card-head">
        <div>
            <h2>Directorio de estudiantes</h2>
            <p class="muted" style="margin: 3px 0 0; font-size: 12px;">{{ number_format($estudiantes->total()) }} {{ $estudiantes->total() === 1 ? 'resultado' : 'resultados' }}</p>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table" id="tabla-estudiantes">
            <thead>
                <tr>
                    <th style="width: 34%;">Estudiante</th>
                    <th style="width: 28%;">Carrera</th>
                    <th style="width: 23%;">Servicio social</th>
                    <th style="text-align: right; width: 15%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($estudiantes as $estudiante)
                @php
                    $proyectosCount = $estudiante->proyectos_count ?? $estudiante->proyectos->count();
                    $hasConcluido = $estudiante->proyectos->contains(fn($p) => !empty($p->fecha_finalizacion) || $p->estado_expediente === 'finalizado');
                    $carreraPrincipal = $estudiante->carreras->first();
                @endphp
                <tr class="estudiante-row" data-due="{{ strtolower($estudiante->due) }}" data-nombre="{{ strtolower($estudiante->nombre_completo) }}">
                    <!-- Estudiante con Avatar -->
                    <td>
                        <div class="student-name-block">
                            <div class="student-avatar" aria-hidden="true">
                                {{ $estudiante->iniciales }}
                            </div>
                            <div class="student-info">
                                <a href="{{ route('ups.estudiantes.show', $estudiante->id_estudiante) }}" class="student-link">
                                    {{ $estudiante->nombre_completo }}
                                </a>
                                <span class="student-date">Registrado {{ $estudiante->created_at?->format('d/m/Y') ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="carnet-inline">
                            <span>{{ $estudiante->due }}</span>
                            <button type="button" class="copy-carnet-btn" data-due="{{ $estudiante->due }}" title="Copiar carnet" aria-label="Copiar carnet {{ $estudiante->due }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                        </div>
                    </td>

                    <td>
                        @if($estudiante->carreras->isNotEmpty())
                            <span class="career-tag">{{ $carreraPrincipal->nombre }}</span>
                            @if($estudiante->carreras->count() > 1)
                                <details class="row-more">
                                    <summary>+{{ $estudiante->carreras->count() - 1 }} más</summary>
                                    <div class="row-more-content">
                                        @foreach($estudiante->carreras as $carrera)
                                            <div>{{ $carrera->nombre }}@if($carrera->departamentoRel) <span class="career-dept">{{ $carrera->departamentoRel->nombre }}</span>@endif</div>
                                        @endforeach
                                    </div>
                                </details>
                            @elseif($carreraPrincipal->departamentoRel)
                                <span class="career-dept">{{ $carreraPrincipal->departamentoRel->nombre }}</span>
                            @endif
                        @else
                            <span class="muted" style="font-size: 11px;">Sin carrera registrada</span>
                        @endif
                    </td>

                    <!-- Participación en Proyectos -->
                    <td>
                        @if($proyectosCount > 0)
                            @if($hasConcluido)
                                <span class="project-status-pill project-pill-concluido">
                                    <span class="status-dot-sm dot-indigo"></span>
                                    <span>{{ $proyectosCount }} {{ $proyectosCount === 1 ? 'proyecto (Concluido)' : 'proyectos' }}</span>
                                </span>
                            @else
                                <span class="project-status-pill project-pill-active">
                                    <span class="status-dot-sm dot-green"></span>
                                    <span>{{ $proyectosCount }} {{ $proyectosCount === 1 ? 'proyecto activo' : 'proyectos activos' }}</span>
                                </span>
                            @endif
                            <details class="row-more">
                                <summary>Ver proyectos</summary>
                                <div class="row-more-content">{{ $estudiante->proyectos->pluck('titulo')->join(', ') }}</div>
                            </details>
                        @else
                            <span class="project-status-pill project-pill-none">
                                <span class="status-dot-sm dot-amber"></span>
                                <span>Sin proyecto asignado</span>
                            </span>
                        @endif
                    </td>

                    <!-- Acciones -->
                    <td style="text-align: right;">
                        <div class="actions-group" style="justify-content: flex-end;">
                            <a href="{{ route('ups.estudiantes.show', $estudiante->id_estudiante) }}" class="btn-table-action btn-table-primary" title="Ver ficha integral">
                                <span>Ficha</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </a>
                            <a href="{{ route('ups.estudiantes.edit', $estudiante->id_estudiante) }}" class="btn-table-action btn-table-icon" title="Editar estudiante" aria-label="Editar {{ $estudiante->nombre_completo }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty">
                        <div class="empty-state-box">
                            <div class="empty-state-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                            </div>
                            <h3 class="empty-state-title">No se encontraron estudiantes</h3>
                            <p class="empty-state-desc">
                                No hay resultados que coincidan con los criterios de búsqueda o filtros seleccionados.
                                Intenta modificar los términos o restablecer los filtros para ver todos los estudiantes.
                            </p>
                            @if($hasActiveFilters)
                                <a href="{{ route('ups.estudiantes.index') }}" class="btn btn-primary" style="margin-top: 6px;">
                                    Restablecer todos los filtros
                                </a>
                            @else
                                <a href="{{ route('ups.estudiantes.create') }}" class="btn btn-primary" style="margin-top: 6px;">
                                    + Registrar el primer estudiante
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($estudiantes->hasPages())
        <div class="student-pagination" aria-label="Paginación de estudiantes">
            <span>{{ $estudiantes->firstItem() }}–{{ $estudiantes->lastItem() }} de {{ number_format($estudiantes->total()) }}</span>
            <nav class="student-pagination-nav" aria-label="Páginas">
                @if($estudiantes->onFirstPage())
                    <span class="student-pagination-link" aria-disabled="true">Anterior</span>
                @else
                    <a class="student-pagination-link" href="{{ $estudiantes->previousPageUrl() }}">Anterior</a>
                @endif
                <span class="student-pagination-current" aria-current="page">{{ $estudiantes->currentPage() }} / {{ $estudiantes->lastPage() }}</span>
                @if($estudiantes->hasMorePages())
                    <a class="student-pagination-link" href="{{ $estudiantes->nextPageUrl() }}">Siguiente</a>
                @else
                    <span class="student-pagination-link" aria-disabled="true">Siguiente</span>
                @endif
            </nav>
        </div>
    @endif
</section>

<!-- Notificación Flotante para Copiar Carnet -->
<div id="copy-toast" role="status" aria-live="polite">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
    <span id="copy-toast-text">Carnet copiado al portapapeles</span>
</div>

<!-- ============================================================== -->
<!-- SCRIPT DE INTERACTIVIDAD RÁPIDA (BÚSQUEDA EN VIVO Y COPIADO)  -->
<!-- ============================================================== -->
<script>
(() => {
    const searchInput = document.getElementById('search-input');
    const searchClear = document.getElementById('search-clear');
    const form = document.getElementById('filtro-estudiantes-form');
    const copyButtons = document.querySelectorAll('.copy-carnet-btn');
    const toast = document.getElementById('copy-toast');
    const toastText = document.getElementById('copy-toast-text');
    let toastTimeout = null;

    // Mostrar / Ocultar botón de limpieza rápida
    const updateClearButton = () => {
        if (!searchInput || !searchClear) return;
        searchClear.style.display = searchInput.value.trim().length > 0 ? 'inline-flex' : 'none';
    };

    if (searchInput) {
        updateClearButton();
        searchInput.addEventListener('input', updateClearButton);

        // Atajo de teclado: presionar '/' enfoca el buscador si no se está escribiendo en otro input
        window.addEventListener('keydown', (e) => {
            if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });
    }

    if (searchClear && form) {
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            updateClearButton();
            searchInput.focus();
            form.requestSubmit();
        });
    }

    // Copiar carnet con feedback toast
    copyButtons.forEach(btn => {
        btn.addEventListener('click', async () => {
            const due = btn.getAttribute('data-due');
            if (!due) return;

            try {
                await navigator.clipboard.writeText(due);
                toastText.textContent = `Carnet ${due} copiado al portapapeles`;
                toast.classList.add('show');
                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => {
                    toast.classList.remove('show');
                }, 2200);
            } catch (err) {
                // Fallback si clipboard API no está disponible
                const temp = document.createElement('input');
                temp.value = due;
                document.body.appendChild(temp);
                temp.select();
                document.execCommand('copy');
                document.body.removeChild(temp);
                toastText.textContent = `Carnet ${due} copiado`;
                toast.classList.add('show');
                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => {
                    toast.classList.remove('show');
                }, 2200);
            }
        });
    });
})();
</script>
@endsection
