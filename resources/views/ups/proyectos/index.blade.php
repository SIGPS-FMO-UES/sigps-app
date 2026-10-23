@extends('layouts.portal')
@section('title', 'Proyectos')
@section('heading', 'Proyectos de Servicio Social')
@section('subtitle', 'Seguimiento integral de expedientes institucionales ordenados por prioridad operativa.')
@section('page-actions')
<a class="btn btn-primary" href="{{ route('ups.proyectos.create') }}">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
    <span>Registrar proyecto</span>
</a>
@endsection

@section('content')
<style>
    /* Chips de Triaje Superior */
    .triaje-chips-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }
    .triaje-chip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 9px;
        text-decoration: none;
        color: #243347;
        box-shadow: 0 2px 8px rgba(24, 40, 58, 0.03);
        transition: all 0.2s ease;
    }
    .triaje-chip:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-1px);
    }
    .triaje-chip.active {
        border-color: var(--navy);
        background: #f1f5f9;
        box-shadow: 0 0 0 2px var(--navy);
    }
    .triaje-chip-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .triaje-chip-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--muted);
    }
    .triaje-chip.active .triaje-chip-title {
        color: var(--navy);
    }
    .triaje-chip-count {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
    }
    .chip-badge-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .chip-dot-urgente { background: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15); }
    .chip-dot-tramite { background: #d97706; box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15); }
    .chip-dot-al-dia { background: #16a34a; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15); }
    .chip-dot-concluido { background: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }

    /* Barra de Omnibúsqueda y Controles */
    .omnibar-card {
        margin-bottom: 18px;
    }
    .omnibar-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .omnibar-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .omnibar-input-wrap {
        position: relative;
        flex: 1;
        min-width: 280px;
        display: flex;
        align-items: center;
    }
    .omnibar-input-wrap input {
        width: 100%;
        height: 44px;
        padding-left: 38px;
        padding-right: 14px;
        border-radius: 8px;
        border: 1px solid #d0d7e2;
        font-size: 13px;
        background: #fff;
    }
    .omnibar-input-wrap input:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px var(--pale);
    }
    .omnibar-icon {
        position: absolute;
        left: 12px;
        width: 18px;
        height: 18px;
        color: var(--muted);
        pointer-events: none;
    }
    .order-select-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .order-select-wrap label {
        font-size: 12px;
        font-weight: 650;
        color: #475569;
        white-space: nowrap;
    }
    .order-select-wrap select {
        height: 44px;
        min-width: 195px;
        border-radius: 8px;
        border: 1px solid #d0d7e2;
        padding: 0 10px;
        font-size: 12px;
        background: #fff;
    }

    /* Filtros secundarios desplegables */
    .secondary-filters-box {
        margin-top: 4px;
        border-top: 1px dashed #e2e8f0;
        padding-top: 12px;
    }
    .secondary-filters-box summary {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        user-select: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .secondary-filters-box summary:hover {
        color: var(--navy);
    }
    .secondary-filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    /* Tabla Sintetizada */
    .project-num-badge {
        display: inline-block;
        padding: 2px 7px;
        background: #1e293b;
        color: #ffffff;
        font-weight: 700;
        font-size: 11px;
        border-radius: 5px;
        letter-spacing: 0.02em;
        margin-right: 6px;
        vertical-align: middle;
    }
    .project-title-link {
        color: #1e293b;
        font-weight: 700;
        text-decoration: none;
        display: inline;
        font-size: 13px;
        line-height: 1.4;
    }
    .project-title-link:hover {
        color: var(--red);
        text-decoration: underline;
    }
    .project-students-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 6px;
    }
    .student-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 7px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 11px;
        color: #334155;
    }
    .student-pill strong {
        color: #0f172a;
    }
    .subtext-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: var(--muted);
        margin-top: 3px;
    }
    .subtext-item svg {
        flex-shrink: 0;
        color: #94a3b8;
    }
    .priority-badge-wrap {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .priority-plazo {
        font-size: 11px;
        font-weight: 600;
    }
    .priority-plazo.urgente { color: #b91c1c; }
    .priority-plazo.tramite { color: #b45309; }
    .priority-plazo.al-dia { color: #15803d; }
    .priority-plazo.concluido { color: #475569; }

    /* ============================================== */
    /* ACCIONES DIRECTAS EN TABLA (ALTO CONTRASTE)    */
    /* ============================================== */
    .table a.btn-action-urgente,
    .table a.btn.btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 650 !important;
        border-radius: 6px;
        text-decoration: none !important;
        white-space: nowrap;
        background-color: #991b32 !important;
        color: #ffffff !important;
        border: 1px solid #991b32 !important;
        box-shadow: 0 1px 3px rgba(153, 27, 50, 0.25);
        transition: all 0.15s ease;
    }
    .table a.btn-action-urgente:hover,
    .table a.btn.btn-primary:hover {
        background-color: #801328 !important;
        border-color: #801328 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(153, 27, 50, 0.35);
    }
    .table a.btn-action-urgente svg,
    .table a.btn.btn-primary svg {
        stroke: #ffffff !important;
    }

    .table a.btn-action-tramite {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 650 !important;
        border-radius: 6px;
        text-decoration: none !important;
        white-space: nowrap;
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border: 1px solid #fde68a !important;
        transition: all 0.15s ease;
    }
    .table a.btn-action-tramite:hover {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border-color: #fcd34d !important;
        transform: translateY(-1px);
    }
    .table a.btn-action-tramite svg {
        stroke: #b45309 !important;
    }

    .table a.btn-action-normal {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 650 !important;
        border-radius: 6px;
        text-decoration: none !important;
        white-space: nowrap;
        background-color: #f8fafc !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
        transition: all 0.15s ease;
    }
    .table a.btn-action-normal:hover {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border-color: #94a3b8 !important;
        transform: translateY(-1px);
    }
    .table a.btn-action-normal svg {
        stroke: #334155 !important;
    }

    /* Estado Vacío Amigable */
    .empty-state-box {
        padding: 44px 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .empty-state-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        margin-bottom: 4px;
    }
    .empty-state-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .empty-state-desc {
        font-size: 12px;
        color: var(--muted);
        max-width: 420px;
        margin: 0 0 10px;
    }
</style>

<!-- ============================================== -->
<!-- 1. PESTAÑAS / CHIPS DE TRIAJE OPERATIVO        -->
<!-- ============================================== -->
<div class="triaje-chips-container" role="tablist" aria-label="Filtro rápido de triaje">
    <!-- Chip: Todos -->
    <a href="{{ route('ups.proyectos.index', array_merge(request()->except('page'), ['triaje' => 'todos'])) }}"
       class="triaje-chip {{ $triajeActivo === 'todos' ? 'active' : '' }}"
       role="tab"
       aria-selected="{{ $triajeActivo === 'todos' ? 'true' : 'false' }}">
        <div class="triaje-chip-info">
            <span class="triaje-chip-title">Total General</span>
            <span class="triaje-chip-count">{{ $conteos['todos'] }}</span>
        </div>
        <span class="badge">Todos</span>
    </a>

    <!-- Chip: Urgentes -->
    <a href="{{ route('ups.proyectos.index', array_merge(request()->except('page'), ['triaje' => 'urgentes'])) }}"
       class="triaje-chip {{ $triajeActivo === 'urgentes' ? 'active' : '' }}"
       style="{{ $triajeActivo === 'urgentes' ? 'box-shadow: 0 0 0 2px #dc2626; border-color:#dc2626' : '' }}"
       role="tab"
       aria-selected="{{ $triajeActivo === 'urgentes' ? 'true' : 'false' }}">
        <div class="triaje-chip-info">
            <span class="triaje-chip-title" style="color:#b91c1c">Atención Urgente</span>
            <span class="triaje-chip-count" style="color:#dc2626">{{ $conteos['urgentes'] }}</span>
        </div>
        <span class="chip-badge-dot chip-dot-urgente" title="Expedientes que superaron plazos normativos (18m o 3m sin memoria)"></span>
    </a>

    <!-- Chip: Trámite UPS -->
    <a href="{{ route('ups.proyectos.index', array_merge(request()->except('page'), ['triaje' => 'tramite'])) }}"
       class="triaje-chip {{ $triajeActivo === 'tramite' ? 'active' : '' }}"
       style="{{ $triajeActivo === 'tramite' ? 'box-shadow: 0 0 0 2px #d97706; border-color:#d97706' : '' }}"
       role="tab"
       aria-selected="{{ $triajeActivo === 'tramite' ? 'true' : 'false' }}">
        <div class="triaje-chip-info">
            <span class="triaje-chip-title" style="color:#b45309">En Trámite UPS</span>
            <span class="triaje-chip-count" style="color:#d97706">{{ $conteos['tramite'] }}</span>
        </div>
        <span class="chip-badge-dot chip-dot-tramite" title="Memorias radicadas en proceso de auditoría y certificación"></span>
    </a>

    <!-- Chip: Al Día -->
    <a href="{{ route('ups.proyectos.index', array_merge(request()->except('page'), ['triaje' => 'al_dia'])) }}"
       class="triaje-chip {{ $triajeActivo === 'al_dia' ? 'active' : '' }}"
       style="{{ $triajeActivo === 'al_dia' ? 'box-shadow: 0 0 0 2px #16a34a; border-color:#16a34a' : '' }}"
       role="tab"
       aria-selected="{{ $triajeActivo === 'al_dia' ? 'true' : 'false' }}">
        <div class="triaje-chip-info">
            <span class="triaje-chip-title" style="color:#15803d">En Curso Normal</span>
            <span class="triaje-chip-count" style="color:#16a34a">{{ $conteos['al_dia'] }}</span>
        </div>
        <span class="chip-badge-dot chip-dot-al-dia" title="Proyectos activos dentro del plazo reglamentario"></span>
    </a>

    <!-- Chip: Concluidos -->
    <a href="{{ route('ups.proyectos.index', array_merge(request()->except('page'), ['triaje' => 'concluidos'])) }}"
       class="triaje-chip {{ $triajeActivo === 'concluidos' ? 'active' : '' }}"
       role="tab"
       aria-selected="{{ $triajeActivo === 'concluidos' ? 'true' : 'false' }}">
        <div class="triaje-chip-info">
            <span class="triaje-chip-title">Concluidos</span>
            <span class="triaje-chip-count">{{ $conteos['concluidos'] }}</span>
        </div>
        <span class="chip-badge-dot chip-dot-concluido" title="Expedientes finalizados formalmente"></span>
    </a>
</div>

<!-- ============================================== -->
<!-- 2. OMNIBÚSQUEDA Y ORDENAMIENTO POR GRAVEDAD    -->
<!-- ============================================== -->
<section class="card omnibar-card">
    <div class="card-body">
        <form class="omnibar-form" method="GET" action="{{ route('ups.proyectos.index') }}">
            <!-- Conservar triaje activo al buscar -->
            <input type="hidden" name="triaje" value="{{ $triajeActivo }}">

            <div class="omnibar-row">
                <!-- Caja de Omnibúsqueda -->
                <div class="omnibar-input-wrap">
                    <svg class="omnibar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    <input type="search" name="search" value="{{ $filtros['search'] ?? '' }}" maxlength="100" placeholder="Escribe carnet (DUE), nombre del estudiante, título, tutor o # correlativo...">
                </div>

                <!-- Selector de Ordenamiento -->
                <div class="order-select-wrap">
                    <label for="orden">Ordenar por:</label>
                    <select id="orden" name="orden" onchange="this.form.requestSubmit()">
                        <option value="gravedad" @selected($ordenActivo === 'gravedad')>Mayor urgencia (Gravedad)</option>
                        <option value="recientes" @selected($ordenActivo === 'recientes')>Más recientes (Inicio)</option>
                        <option value="antiguos" @selected($ordenActivo === 'antiguos')>Más antiguos (Inicio)</option>
                        <option value="correlativo" @selected($ordenActivo === 'correlativo')>N° Correlativo (#)</option>
                    </select>
                </div>

                <!-- Botones de Acción -->
                <button type="submit" class="btn btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    <span>Buscar</span>
                </button>

                @php
                    $hayFiltrosActivos = !empty($filtros['search']) || !empty($filtros['area_id']) || !empty($filtros['fecha']) || !empty($filtros['estado']) || !empty($filtros['plazo']) || $triajeActivo !== 'todos' || $ordenActivo !== 'gravedad';
                    $conteoSecundarios = (!empty($filtros['area_id']) ? 1 : 0) + (!empty($filtros['fecha']) ? 1 : 0) + (!empty($filtros['estado']) ? 1 : 0) + (!empty($filtros['plazo']) ? 1 : 0);
                @endphp

                @if($hayFiltrosActivos)
                    <a href="{{ route('ups.proyectos.index') }}" class="btn" title="Limpiar todos los filtros y volver a la vista principal">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        <span>Restablecer</span>
                    </a>
                @endif
            </div>

            <!-- Filtros secundarios colapsados -->
            <details class="secondary-filters-box" @if($conteoSecundarios > 0) open @endif>
                <summary>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <span>Filtros secundarios avanzados por área temática, fecha o estado</span>
                    @if($conteoSecundarios > 0)
                        <span class="badge badge-info" style="font-size:10px;padding:1px 6px;">{{ $conteoSecundarios }} activo{{ $conteoSecundarios > 1 ? 's' : '' }}</span>
                    @endif
                </summary>
                <div class="secondary-filters-grid">
                    <div class="field">
                        <label for="area_id">Área de conocimiento</label>
                        <select id="area_id" name="area_id" onchange="this.form.requestSubmit()">
                            <option value="">Todas las áreas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id_area_conocimiento }}" @selected(($filtros['area_id'] ?? '') == $area->id_area_conocimiento)>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="fecha">Fecha de inicio</label>
                        <input id="fecha" name="fecha" type="date" value="{{ $filtros['fecha'] ?? '' }}" onchange="this.form.requestSubmit()">
                    </div>

                    <div class="field">
                        <label for="estado">Estado de memoria</label>
                        <select id="estado" name="estado" onchange="this.form.requestSubmit()">
                            <option value="">Todos los estados</option>
                            <option value="sin_memoria" @selected(($filtros['estado'] ?? '') === 'sin_memoria')>Sin memoria radicada</option>
                            <option value="recibido" @selected(($filtros['estado'] ?? '') === 'recibido')>Recibido</option>
                            <option value="en_revision" @selected(($filtros['estado'] ?? '') === 'en_revision')>En revisión</option>
                            <option value="observada" @selected(($filtros['estado'] ?? '') === 'observada')>Observada</option>
                            <option value="aprobada" @selected(($filtros['estado'] ?? '') === 'aprobada')>Aprobada</option>
                            <option value="esperando_firmas" @selected(($filtros['estado'] ?? '') === 'esperando_firmas')>Esperando firmas</option>
                            <option value="lista" @selected(($filtros['estado'] ?? '') === 'lista')>Lista para entrega</option>
                            <option value="entregada" @selected(($filtros['estado'] ?? '') === 'entregada')>Entregada</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="plazo">Filtro de plazo normativo</label>
                        <select id="plazo" name="plazo" onchange="this.form.requestSubmit()">
                            <option value="">Todos los plazos</option>
                            <option value="memoria" @selected(($filtros['plazo'] ?? '') === 'memoria')>Memoria: +3 meses sin entrega</option>
                            <option value="junta" @selected(($filtros['plazo'] ?? '') === 'junta')>Junta: +18 meses cumplidos</option>
                        </select>
                    </div>
                </div>
            </details>
        </form>
    </div>
</section>

<!-- ============================================== -->
<!-- 3. TABLA SINTETIZADA CON SEMÁFORO OPERATIVO    -->
<!-- ============================================== -->
<section class="card">
    <div class="card-head">
        <div>
            <h2>Expedientes de Proyectos</h2>
            <p class="muted" style="margin:3px 0 0;font-size:12px">
                @if($triajeActivo === 'urgentes')
                    Mostrando únicamente expedientes con plazos normativos críticos o vencidos.
                @elseif($triajeActivo === 'tramite')
                    Mostrando expedientes con memorias o certificaciones en proceso dentro de UPS.
                @elseif($triajeActivo === 'al_dia')
                    Mostrando expedientes activos dentro del calendario normal.
                @elseif($triajeActivo === 'concluidos')
                    Mostrando expedientes formalmente finalizados con entrega de certificación.
                @elseif($ordenActivo === 'gravedad')
                    Listado institucional ordenado por mayor urgencia operativa (Gravedad).
                @else
                    Listado ordenado según el criterio seleccionado.
                @endif
            </p>
        </div>
        <span class="badge">{{ $proyectos->total() }} expediente{{ $proyectos->total() === 1 ? '' : 's' }}</span>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="min-width:300px">Expediente y Estudiantes</th>
                    <th style="min-width:200px">Área y Tutor</th>
                    <th style="min-width:170px">Prioridad y Plazo</th>
                    <th style="min-width:140px">Estado Operativo</th>
                    <th style="width:130px;text-align:right">Siguiente Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proyectos as $proyecto)
                    @php($semaforo = $proyecto->semaforoOperativo())
                    <tr>
                        <!-- Columna 1: Expediente y Estudiantes -->
                        <td>
                            <div>
                                <span class="project-num-badge" title="Número institucional de expediente">#{{ $proyecto->numero_proyecto ?? $proyecto->id_proyecto }}</span>
                                <a class="project-title-link" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                    {{ $proyecto->titulo }}
                                </a>
                            </div>

                            @if($proyecto->estudiantes->isNotEmpty())
                                <div class="project-students-summary">
                                    @foreach($proyecto->estudiantes->take(3) as $est)
                                        <span class="student-pill" title="{{ $est->nombre_completo }}">
                                            <strong>{{ $est->due }}</strong> · {{ \Illuminate\Support\Str::limit($est->nombre_completo, 18) }}
                                        </span>
                                    @endforeach
                                    @if($proyecto->estudiantes->count() > 3)
                                        <span class="student-pill muted" title="{{ $proyecto->estudiantes->pluck('nombre_completo')->implode(', ') }}">
                                            +{{ $proyecto->estudiantes->count() - 3 }} más
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="subtext-item" style="font-style:italic">
                                    <span>Sin estudiantes vinculados</span>
                                </div>
                            @endif
                        </td>

                        <!-- Columna 2: Área y Tutor -->
                        <td>
                            <strong style="color:#1e293b;font-size:12px;">{{ $proyecto->areaConocimiento?->nombre ?? 'Sin área asignada' }}</strong>
                            <div class="subtext-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>Tutor: {{ $proyecto->tutor ?? 'No asignado' }}</span>
                            </div>
                            @if($proyecto->subcoordinador)
                                <div class="subtext-item">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                    <span>Subcoord: {{ $proyecto->subcoordinador }}</span>
                                </div>
                            @endif
                        </td>

                        <!-- Columna 3: Semáforo y Plazo Humano -->
                        <td>
                            <div class="priority-badge-wrap">
                                <div>
                                    <span class="badge {{ $semaforo['badge_class'] }}">
                                        @if($semaforo['nivel'] === 'urgente')
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        @elseif($semaforo['nivel'] === 'tramite')
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        @elseif($semaforo['nivel'] === 'al_dia')
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                        @else
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 12 2 2 4-4"/></svg>
                                        @endif
                                        {{ $semaforo['etiqueta'] }}
                                    </span>
                                </div>
                                <span class="priority-plazo {{ $semaforo['nivel'] === 'urgente' ? 'urgente' : ($semaforo['nivel'] === 'tramite' ? 'tramite' : ($semaforo['nivel'] === 'al_dia' ? 'al-dia' : 'concluido')) }}">
                                    {{ $semaforo['plazo_humano'] }}
                                </span>
                            </div>
                        </td>

                        <!-- Columna 4: Estado Operativo -->
                        <td>
                            @if($proyecto->memoria)
                                <span class="badge {{ $proyecto->memoria->estado === 'observada' ? 'badge-danger' : ($proyecto->memoria->estado === 'aprobada' ? 'badge-success' : 'badge-info') }}">
                                    {{ $proyecto->memoria->estado instanceof \App\Enums\EstadoMemoria ? $proyecto->memoria->estado->label() : ucfirst((string) $proyecto->memoria->estado) }}
                                </span>
                            @else
                                <span class="badge" style="background:#f1f5f9;color:#64748b;">Sin memoria</span>
                            @endif
                            <div class="subtext-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span>Inicio: {{ $proyecto->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
                            </div>
                        </td>

                        <!-- Columna 5: Acción Directa con Alto Contraste -->
                        <td style="text-align:right">
                            @if($semaforo['nivel'] === 'urgente')
                                <a class="btn-action-urgente" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                    <span>{{ $semaforo['siguiente_accion'] }}</span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            @elseif($semaforo['nivel'] === 'tramite')
                                <a class="btn-action-tramite" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                    <span>{{ $semaforo['siguiente_accion'] }}</span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <a class="btn-action-normal" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                    <span>{{ $semaforo['siguiente_accion'] }}</span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:0;">
                            <div class="empty-state-box">
                                <div class="empty-state-icon">
                                    @if($triajeActivo === 'urgentes')
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    @else
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                    @endif
                                </div>
                                <h3 class="empty-state-title">
                                    @if($triajeActivo === 'urgentes')
                                        No hay expedientes con atención urgente en este momento
                                    @elseif(!empty($filtros['search']))
                                        No se encontraron expedientes para «{{ $filtros['search'] }}»
                                    @else
                                        No se encontraron expedientes con los criterios seleccionados
                                    @endif
                                </h3>
                                <p class="empty-state-desc">
                                    @if($triajeActivo === 'urgentes')
                                        Todos los proyectos activos se encuentran dentro de sus plazos reglamentarios o en trámite regular.
                                    @else
                                        Verifica los términos de búsqueda o elimina los filtros aplicados para consultar el catálogo general.
                                    @endif
                                </p>
                                @if($hayFiltrosActivos)
                                    <a href="{{ route('ups.proyectos.index') }}" class="btn btn-primary" style="color:#ffffff!important;">
                                        Ver todos los expedientes
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proyectos->hasPages())
        <div class="pagination">
            <span>Mostrando {{ $proyectos->firstItem() }}–{{ $proyectos->lastItem() }} de {{ $proyectos->total() }} expedientes</span>
            {{ $proyectos->onEachSide(1)->links() }}
        </div>
    @endif
</section>
@endsection
