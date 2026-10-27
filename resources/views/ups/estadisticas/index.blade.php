@extends('layouts.portal')

@section('title', 'Estadísticas y Reportes MINED')
@section('heading', 'Estadísticas UPS')
@section('subtitle', 'Proyectos, estudiantes y certificaciones de Proyección Social.')

@section('page-actions')
    <div style="display:flex;align-items:center;gap:10px;">
        <button type="button" class="btn btn-primary" onclick="modalExportar.open()" title="Exportar estadísticas y reportes oficiales en Excel (.xlsx) o CSV">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Exportar Información</span>
        </button>
    </div>
@endsection

@section('content')
<style>
    /* Estilos avanzados para el panel de estadísticas */
    .stats-hub {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Pestañas de modo (Segmented Control) */
    .tab-switcher {
        display: inline-flex;
        background: #eef2f6;
        padding: 4px;
        border-radius: 10px;
        border: 1px solid #dce3eb;
        gap: 4px;
        width: fit-content;
        margin-bottom: 4px;
    }
    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        color: #4b5869;
        transition: all 0.2s ease;
    }
    .tab-btn:hover {
        color: #1e293b;
        background: rgba(255, 255, 255, 0.6);
    }
    .tab-btn.active {
        background: #ffffff;
        color: var(--navy);
        box-shadow: 0 2px 8px rgba(24, 40, 58, 0.09);
        border: 1px solid rgba(222, 227, 234, 0.8);
    }
    .tab-btn.active-mined {
        background: #ffffff;
        color: var(--red);
        box-shadow: 0 2px 8px rgba(153, 27, 50, 0.12);
        border: 1px solid #f8c8d2;
    }
    .tab-btn .tab-badge {
        font-size: 10px;
        padding: 2px 7px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #475569;
    }
    .tab-btn.active-mined .tab-badge {
        background: var(--pale);
        color: var(--red);
    }

    /* Tarjeta de Filtros Avanzada */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(24, 40, 58, 0.04);
        padding: 20px 22px;
    }
    .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f4f8;
    }
    .filter-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }
    .filter-title svg {
        color: var(--red);
    }
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
        align-items: end;
    }
    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .filter-field label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
    }
    .filter-field select, .filter-field input {
        width: 100%;
        min-height: 38px;
        padding: 8px 12px;
        border: 1px solid #d4dce5;
        border-radius: 8px;
        background: #fff;
        color: #1e293b;
        font-size: 12px;
        font-family: inherit;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .filter-field select:focus, .filter-field input:focus {
        border-color: var(--red);
        outline: none;
        box-shadow: 0 0 0 3px rgba(153, 27, 50, 0.12);
    }
    .filter-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #f1f4f8;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }
    .chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 9px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #334155;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }
    .chip-clear {
        color: #94a3b8;
        text-decoration: none;
        font-size: 13px;
        line-height: 1;
        cursor: pointer;
    }
    .chip-clear:hover {
        color: #ef4444;
    }

    /* KPI Cards Especiales */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 16px;
    }
    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0 2px 10px rgba(24, 40, 58, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 130px;
        position: relative;
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(24, 40, 58, 0.07);
    }
    .kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .kpi-label {
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }
    .kpi-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: grid;
        place-items: center;
    }
    .kpi-number {
        font-size: 32px;
        font-weight: 850;
        line-height: 1;
        color: #0f172a;
        margin: 4px 0 6px;
    }
    .kpi-subtext {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Temas para KPI Cards */
    .kpi-primary { border-left: 4px solid var(--navy); }
    .kpi-primary .kpi-icon-wrap { background: #eef2f6; color: var(--navy); }

    .kpi-mined-hicieron {
        border-left: 4px solid #3b82f6;
        background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
    }
    .kpi-mined-hicieron .kpi-icon-wrap { background: #dbeafe; color: #1d4ed8; }
    .kpi-mined-hicieron .kpi-number { color: #1d4ed8; }

    .kpi-mined-entregaron {
        border-left: 4px solid #10b981;
        background: linear-gradient(180deg, #ffffff 0%, #f7fdfa 100%);
    }
    .kpi-mined-entregaron .kpi-icon-wrap { background: #d1fae5; color: #047857; }
    .kpi-mined-entregaron .kpi-number { color: #047857; }

    .kpi-mined-sin-entregar {
        border-left: 4px solid #f59e0b;
        background: linear-gradient(180deg, #ffffff 0%, #fffdf8 100%);
    }
    .kpi-mined-sin-entregar .kpi-icon-wrap { background: #fef3c7; color: #b45309; }
    .kpi-mined-sin-entregar .kpi-number { color: #b45309; }

    .kpi-rate {
        border-left: 4px solid var(--red);
        background: linear-gradient(180deg, #ffffff 0%, #fff9fa 100%);
    }
    .kpi-rate .kpi-icon-wrap { background: var(--pale); color: var(--red); }
    .kpi-rate .kpi-number { color: var(--red); }

    /* Gráficos Visuales y Métricas CSS */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }
    @media(max-width: 900px) {
        .charts-grid { grid-template-columns: 1fr; }
    }

    .visual-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(24, 40, 58, 0.03);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .visual-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .visual-card-head h3 {
        margin: 0;
        font-size: 14px;
        font-weight: 750;
        color: #1e293b;
    }
    .visual-card-head small {
        color: #64748b;
        font-size: 11px;
    }

    /* Barra visual segmentada de fases */
    .segmented-progress {
        display: flex;
        width: 100%;
        height: 24px;
        border-radius: 8px;
        overflow: hidden;
        background: #edf2f7;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.06);
    }
    .seg-slice {
        height: 100%;
        transition: width 0.3s ease;
        position: relative;
    }
    .seg-slice.fase-inscripcion { background: #38bdf8; }
    .seg-slice.fase-ejecucion { background: #6366f1; }
    .seg-slice.fase-memoria { background: #f59e0b; }
    .seg-slice.fase-finalizado { background: #10b981; }

    .phase-legend-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .phase-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #eef2f6;
        border-radius: 8px;
    }
    .phase-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .phase-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .phase-title {
        font-size: 12px;
        font-weight: 650;
        color: #334155;
    }

    /* Gráfico de Barras Horizontales de Área */
    .area-bar-row {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 12px;
    }
    .area-bar-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
    }
    .area-bar-track {
        width: 100%;
        height: 12px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
    }
    .area-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #991b32 0%, #c42947 100%);
        transition: width 0.4s ease;
    }

    /* Visual Donut / Gauge para MINED */
    .mined-gauge-box {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 16px;
        background: #fcfdfd;
        border: 1px solid #edf2f7;
        border-radius: 10px;
    }
    .mined-gauge-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Banner Oficial MINED */
    .mined-official-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 20px;
        background: linear-gradient(90deg, #18283a 0%, #22374e 100%);
        color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(24, 40, 58, 0.12);
    }
    .mined-banner-text h2 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 750;
        letter-spacing: -0.01em;
        color: #ffffff;
    }
    .mined-banner-text p {
        margin: 0;
        font-size: 12px;
        color: #cbd5e1;
    }

    /* Estado visual para entregas */
    .status-entregada {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #065f46;
        background: #d1fae5;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
    }
    .status-sin-entregar {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #92400e;
        background: #fef3c7;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
    }

    /* Estilos para Agrupación de Carreras (Optgroups) */
    optgroup {
        font-weight: 750;
        color: #0f172a;
        background: #f8fafc;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    optgroup option {
        font-weight: 500;
        color: #1e293b;
        background: #ffffff;
        font-size: 13px;
        text-transform: none;
        padding: 6px 10px;
    }

    /* Executive Insights Strip (Resumen Ejecutivo de Alto Nivel) */
    .insights-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }
    .insight-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(24, 40, 58, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .insight-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(24, 40, 58, 0.06);
    }
    .insight-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .insight-content {
        flex: 1;
        min-width: 0;
    }
    .insight-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 2px;
    }
    .insight-val {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .insight-desc {
        font-size: 11px;
        color: #64748b;
        margin-top: 1px;
    }

    /* Controles de Tabla y Selector de Densidad */
    .table-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 10px 16px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
    }
    .table-search-box {
        position: relative;
        display: flex;
        align-items: center;
        max-width: 300px;
        width: 100%;
    }
    .table-search-box svg {
        position: absolute;
        left: 10px;
        color: #94a3b8;
        pointer-events: none;
    }
    .table-search-input {
        width: 100%;
        padding: 6px 12px 6px 32px;
        border-radius: 8px;
        border: 1px solid var(--border);
        font-size: 12px;
        background: #ffffff;
        color: #1e293b;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .table-search-input:focus {
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(24, 40, 58, 0.08);
    }
    .density-switcher {
        display: inline-flex;
        background: #e2e8f0;
        padding: 2px;
        border-radius: 8px;
        gap: 2px;
    }
    .density-btn {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 650;
        color: #475569;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .density-btn.active {
        background: #ffffff;
        color: var(--navy);
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    /* Contenedor Scrollable y Encabezado Sticky */
    .scrollable-table-wrap {
        max-height: 460px;
        overflow-y: auto;
        overflow-x: auto;
        position: relative;
    }
    .sticky-header-table thead th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 5;
        box-shadow: 0 1px 0 var(--border);
    }
    .table-dense-top10 .row-beyond-top10 {
        display: none !important;
    }

    /* Medallas e Indicadores de Rango (Rank Badges) */
    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        font-size: 10px;
        font-weight: 800;
        border-radius: 6px;
        padding: 2px 7px;
        min-width: 28px;
        letter-spacing: -0.02em;
    }
    .rank-1 { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .rank-2 { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }
    .rank-3 { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .rank-n { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    /* Visual language shared with the UPS dashboard. */
    .stats-hub {
        --stats-ink: #202b39;
        --stats-muted: #6a7481;
        --stats-line: #e2e5e9;
        --stats-wine: #8e2037;
        display: grid;
        gap: 14px;
        color: var(--stats-ink);
    }
    .stats-hub > .tab-switcher { order: 0; }
    .stats-hub > .mined-official-banner { order: 1; }
    .stats-hub > .kpi-row { order: 2; }
    .stats-hub > .filter-card { order: 3; }
    .stats-hub > .charts-grid { order: 4; }
    .stats-hub > .insights-strip { order: 5; }
    .stats-hub > .card { order: 6; }
    .stats-hub > .stats-more { order: 5; }
    .stats-more {
        overflow: hidden;
        border: 1px solid var(--stats-line);
        border-radius: 8px;
        background: #fff;
    }
    .stats-more > summary {
        display: flex;
        min-height: 48px;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 15px;
        color: var(--stats-ink);
        cursor: pointer;
        list-style: none;
        font-size: 12px;
        font-weight: 700;
    }
    .stats-more > summary::-webkit-details-marker { display: none; }
    .stats-more > summary::after {
        content: '+';
        color: var(--stats-wine);
        font-size: 18px;
        font-weight: 500;
        line-height: 1;
    }
    .stats-more[open] > summary::after { content: '−'; }
    .stats-more > summary:hover { background: #fafafa; }
    .stats-more-content {
        display: grid;
        gap: 14px;
        padding: 0 12px 12px;
    }
    .stats-more-content > .card { margin: 0; }
    .filter-disclosure { padding: 0; }
    .filter-disclosure > summary {
        cursor: pointer;
        list-style: none;
    }
    .filter-disclosure > summary::-webkit-details-marker { display: none; }
    .filter-disclosure > summary::after {
        content: '+';
        margin-left: auto;
        color: var(--stats-wine);
        font-size: 18px;
        font-weight: 500;
        line-height: 1;
    }
    .filter-disclosure[open] > summary::after { content: '−'; }
    .filter-disclosure > summary:hover { background: #fafafa; }
    .filter-disclosure > form { padding: 0 15px 12px; }
    .stats-hub :focus-visible {
        outline: 3px solid #8e2037;
        outline-offset: 2px;
    }
    .tab-switcher {
        display: flex;
        width: 100%;
        gap: 5px;
        margin: 0;
        padding: 0 0 8px;
        border: 0;
        border-bottom: 1px solid var(--stats-line);
        border-radius: 0;
        background: transparent;
    }
    .tab-btn, .tab-btn.active, .tab-btn.active-mined {
        min-height: 36px;
        padding: 0 12px;
        border: 1px solid transparent;
        border-radius: 6px;
        box-shadow: none;
        color: #56616d;
        font-size: 11px;
        font-weight: 700;
        transition: background-color .15s ease, color .15s ease, border-color .15s ease;
    }
    .tab-btn svg { display: none; }
    .tab-btn:hover { color: var(--stats-ink); background: #f1f3f5; }
    .tab-btn.active, .tab-btn.active-mined {
        border-color: var(--stats-line);
        color: var(--stats-wine);
        background: #fff;
    }
    .tab-btn .tab-badge, .tab-btn.active-mined .tab-badge {
        padding: 3px 7px;
        border-radius: 4px;
        color: #596473;
        background: #f1f3f5;
        font-size: 9px;
        font-weight: 700;
    }
    .filter-card, .stats-hub .card, .visual-card {
        border: 1px solid var(--stats-line);
        border-radius: 8px;
        background: #fff;
        box-shadow: none;
    }
    .filter-card { padding: 12px 15px; }
    .filter-header { margin-bottom: 9px; padding-bottom: 8px; border-color: #eef0f2; }
    .filter-title { gap: 0; color: var(--stats-ink); font-size: 13px; }
    .filter-title svg { display: none; }
    .filter-title > span:last-child { margin-left: 10px; color: var(--stats-muted) !important; font-size: 10px !important; }
    .filter-grid { grid-template-columns: repeat(auto-fit, minmax(195px, 1fr)); gap: 8px 11px; }
    .filter-field { gap: 5px; }
    .filter-field label { color: #727c88; font-size: 9px; letter-spacing: .06em; }
    .filter-field select, .filter-field input {
        min-height: 36px;
        padding: 7px 10px;
        border-color: #dfe3e8;
        border-radius: 6px;
        color: #303b49;
        font-size: 11px;
    }
    .filter-field select:focus, .filter-field input:focus { border-color: var(--stats-wine); box-shadow: 0 0 0 2px #8e20371a; }
    .filter-actions { margin-top: 8px; padding-top: 8px; border-color: #eef0f2; }
    .chip { border-color: #e3e6e9; border-radius: 4px; color: #596473; background: #f5f6f7; font-size: 9px; }
    .kpi-row {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0;
        overflow: hidden;
        border: 1px solid var(--stats-line);
        border-radius: 8px;
        background: #fff;
    }
    .kpi-card, .kpi-card:hover {
        min-height: 86px;
        padding: 12px 15px;
        border: 0 !important;
        border-radius: 0;
        background: #fff !important;
        box-shadow: none;
        transform: none;
        transition: none;
    }
    .kpi-card + .kpi-card { border-left: 1px solid var(--stats-line) !important; }
    .kpi-top { margin-bottom: 5px; }
    .kpi-icon-wrap { display: none; }
    .kpi-label { color: var(--stats-muted); font-size: 10px; font-weight: 650; letter-spacing: 0; text-transform: none; }
    .kpi-number { margin: 0 0 3px; color: var(--stats-ink) !important; font-size: 27px; font-weight: 760; letter-spacing: -.045em; }
    .kpi-subtext { display: block; color: var(--stats-muted); font-size: 9px; line-height: 1.4; }
    .kpi-subtext .badge { margin-right: 4px; }
    .charts-grid { gap: 14px; }
    .visual-card { gap: 13px; padding: 16px; }
    .visual-card-head h3 { color: var(--stats-ink); font-size: 13px; font-weight: 700; }
    .visual-card-head small { color: var(--stats-muted); font-size: 9px; }
    .visual-card .badge { font-size: 9px; }
    .segmented-progress { height: 14px; border-radius: 4px; background: #eef0f2; box-shadow: none; }
    .seg-slice.fase-inscripcion { background: #aab2bc; }
    .seg-slice.fase-ejecucion { background: #536273; }
    .seg-slice.fase-memoria { background: #a55762; }
    .seg-slice.fase-finalizado { background: #687c70; }
    .phase-legend-grid { gap: 7px; }
    .phase-item { padding: 8px 10px; border-color: #e8eaed; border-radius: 6px; background: #fff; }
    .phase-dot { width: 8px; height: 8px; border-radius: 2px; }
    .phase-item:nth-child(1) .phase-dot { background: #aab2bc !important; }
    .phase-item:nth-child(2) .phase-dot { background: #536273 !important; }
    .phase-item:nth-child(3) .phase-dot { background: #a55762 !important; }
    .phase-item:nth-child(4) .phase-dot { background: #687c70 !important; }
    .phase-title { color: #4c5866; font-size: 10px; font-weight: 650; }
    .area-bar-row { gap: 5px; margin-bottom: 10px; }
    .area-bar-info { font-size: 10px; }
    .area-bar-track { height: 7px; border-radius: 4px; background: #eef0f2; }
    .area-bar-fill { border-radius: 4px; background: var(--stats-wine); }
    .insights-strip {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0;
        margin: 0;
        overflow: hidden;
        border: 1px solid var(--stats-line);
        border-radius: 8px;
        background: #fff;
    }
    .insight-card, .insight-card:hover {
        min-width: 0;
        padding: 12px 15px;
        border: 0 !important;
        border-radius: 0;
        box-shadow: none;
        transform: none;
        transition: none;
    }
    .insight-card + .insight-card { border-left: 1px solid var(--stats-line) !important; }
    .insight-icon { display: none; }
    .insight-label { margin-bottom: 3px; color: var(--stats-muted); font-size: 9px; font-weight: 650; letter-spacing: 0; text-transform: none; }
    .insight-val { color: var(--stats-ink); font-size: 13px; font-weight: 700; }
    .insight-desc { margin-top: 3px; color: var(--stats-muted); font-size: 9px; }
    .stats-hub .card-head { padding: 13px 15px !important; border-bottom: 1px solid #eef0f2; }
    .stats-hub .card-head h2 { color: var(--stats-ink); font-size: 13px; font-weight: 700; }
    .stats-hub .card-head small { color: var(--stats-muted) !important; font-size: 9px; }
    .table-controls { padding: 9px 13px; border-color: var(--stats-line); background: #fafafa; }
    .table-search-input { min-height: 34px; border-color: #dfe3e8; border-radius: 6px; font-size: 10px; }
    .density-switcher { border: 1px solid var(--stats-line); border-radius: 6px; background: #f1f3f5; }
    .density-btn { border-radius: 4px; color: #596473; font-size: 9px; }
    .density-btn.active { color: var(--stats-wine); box-shadow: none; }
    .density-btn[data-mode="top10"] svg { display: none; }
    .scrollable-table-wrap { max-height: min(50vh, 430px); }
    .sticky-header-table thead th, .stats-hub .table thead th { background: #f5f6f7; box-shadow: 0 1px 0 var(--stats-line); }
    .stats-hub .table th { color: #737d89; font-size: 8px; letter-spacing: .06em; }
    .stats-hub .table td { color: #4d5865; font-size: 10px; }
    .stats-hub .table tbody tr:hover td { background: #fafafa; }
    .stats-hub .badge, .rank-badge { border-color: #e2e5e9; border-radius: 4px; color: #596473; background: #f1f3f5; box-shadow: none; }
    .stats-hub .badge-success { border-color: #dce6df; color: #50685a; background: #eff4f0; }
    .stats-hub .badge-info { border-color: #dce2e8; color: #4c5d70; background: #f0f3f6; }
    .stats-hub .badge-warning { border-color: #e8e0d5; color: #755f43; background: #f5f2ed; }
    .rank-1, .rank-2, .rank-3, .rank-n { border-color: #e2e5e9; color: #596473; background: #f1f3f5; }
    .rank-badge svg { display: none; }
    .mined-official-banner { padding: 15px 17px; border: 0; border-radius: 8px; background: #263648; box-shadow: none; }
    .mined-banner-text h2 { font-size: 14px; font-weight: 700; }
    .mined-banner-text p { color: #d3d9df; font-size: 10px; }
    .status-entregada, .status-sin-entregar { border: 1px solid #e2e5e9; border-radius: 4px; color: #596473; background: #f1f3f5; font-size: 9px; }

    @media (max-width: 900px) {
        .kpi-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .kpi-card + .kpi-card { border-left: 0 !important; }
        .kpi-card:nth-child(even) { border-left: 1px solid var(--stats-line) !important; }
        .kpi-card:nth-child(n + 3) { border-top: 1px solid var(--stats-line) !important; }
    }
    @media (max-width: 620px) {
        .tab-switcher { overflow-x: auto; }
        .tab-btn, .tab-btn.active, .tab-btn.active-mined { flex: 0 0 auto; padding: 0 9px; }
        .filter-header { align-items: flex-start; }
        .filter-title { flex-wrap: wrap; gap: 3px 0; }
        .filter-title > span:last-child { width: 100%; margin: 2px 0 0; }
        .filter-grid { grid-template-columns: 1fr; }
        .kpi-row, .insights-strip { grid-template-columns: 1fr; }
        .kpi-card:nth-child(n), .insight-card + .insight-card { border: 0 !important; }
        .kpi-card + .kpi-card, .insight-card + .insight-card { border-top: 1px solid var(--stats-line) !important; }
        .mined-official-banner { align-items: flex-start; flex-direction: column; }
        .mined-gauge-box { align-items: flex-start; flex-direction: column; gap: 12px; }
        .table-controls { align-items: stretch; }
        .table-search-box { max-width: none; }
    }

    /* Statistics overview: a compact control bar and four clear, separate KPIs. */
    .stats-hub { gap: 16px; }
    .stats-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        position: relative;
        z-index: 5;
    }
    .stats-hub .tab-switcher {
        width: fit-content;
        flex: 1 1 auto;
        align-items: center;
        gap: 4px;
        padding: 4px;
        border: 1px solid #e1e4e8;
        border-radius: 9px;
        background: #f0f2f4;
    }
    .stats-hub .tab-btn, .stats-hub .tab-btn.active, .stats-hub .tab-btn.active-mined {
        min-height: 36px;
        padding: 0 14px;
        border: 1px solid transparent;
        border-radius: 6px;
        color: #56616d;
        font-size: 12px;
        font-weight: 650;
    }
    .stats-hub .tab-btn svg { display: inline-block; width: 15px; height: 15px; }
    .stats-hub .tab-btn:hover { color: var(--stats-ink); background: #fff; }
    .stats-hub .tab-btn.active, .stats-hub .tab-btn.active-mined {
        border-color: #e4e6e9;
        color: var(--stats-wine);
        background: #fff;
        box-shadow: 0 1px 2px #202b3910;
    }
    .stats-hub .tab-btn .tab-badge {
        color: #596473;
        background: #f1f3f5;
        font-size: 10px;
    }
    .stats-hub .filter-disclosure {
        flex: 0 0 auto;
        width: auto;
        border: 0;
        background: transparent;
    }
    .stats-hub .filter-disclosure > summary {
        display: flex;
        min-height: 42px;
        align-items: center;
        gap: 9px;
        padding: 0 13px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        background: #fff;
        color: #354152;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }
    .stats-hub .filter-disclosure > summary::after { margin-left: 4px; font-size: 16px; }
    .stats-hub .filter-disclosure > summary:hover { border-color: #cbd1d8; background: #fff; }
    .stats-hub .filter-disclosure[open] > summary { border-color: #c9a4ac; color: var(--stats-wine); }
    .stats-hub .filter-title { gap: 8px; font-size: 12px; }
    .stats-hub .filter-title svg { display: inline-block; color: var(--stats-wine); }
    .stats-hub .filter-title > span:last-child { display: none; }
    .stats-hub .filter-disclosure > form {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: min(720px, calc(100vw - 300px));
        padding: 16px;
        border: 1px solid #e1e4e8;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 14px 36px #202b3926;
    }
    .stats-hub .filter-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .stats-hub .filter-field label { font-size: 10px; }
    .stats-hub .filter-field select, .stats-hub .filter-field input { min-height: 40px; font-size: 12px; }
    .stats-hub .filter-actions { gap: 12px; }
    .stats-hub .kpi-row {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        overflow: visible;
        border: 0;
        border-radius: 0;
        background: transparent;
    }
    .stats-hub .kpi-card, .stats-hub .kpi-card:hover {
        min-height: 112px;
        padding: 15px 16px;
        border: 1px solid var(--stats-line) !important;
        border-top: 3px solid var(--stats-wine) !important;
        border-radius: 9px;
        background: #fff !important;
        box-shadow: 0 2px 8px #202b3908;
    }
    .stats-hub .kpi-card + .kpi-card { border-left: 1px solid var(--stats-line) !important; border-top: 3px solid var(--stats-wine) !important; }
    .stats-hub .kpi-label { color: #687382; font-size: 11px; font-weight: 650; }
    .stats-hub .kpi-number { margin: 5px 0 5px; color: #202b39 !important; font-size: 31px; font-weight: 760; }
    .stats-hub .kpi-subtext { color: #77818d; font-size: 10px; }
    .stats-hub .charts-grid { gap: 12px; }
    .stats-hub .visual-card { min-width: 0; padding: 17px 18px; border-radius: 9px; }
    .stats-hub .visual-card-head { align-items: flex-start; }
    .stats-hub .visual-card-head h3 { font-size: 14px; }
    .stats-hub .visual-card-head small { font-size: 10px; }
    .stats-hub .phase-item { min-height: 38px; padding: 8px 10px; }
    .stats-hub .phase-title, .stats-hub .phase-item strong { font-size: 11px; }
    .stats-hub .phase-item small { font-size: 10px; }
    .stats-hub .area-bar-info { font-size: 11px; }
    .stats-hub .area-bar-row { margin-bottom: 11px; }
    .stats-hub .stats-more {
        width: 100%;
        min-width: 0;
        border-radius: 9px;
        transition: border-color .15s ease;
    }
    .stats-hub .stats-more > summary {
        display: flex;
        min-height: 54px;
        min-width: 0;
        align-items: center;
        justify-content: space-between;
        gap: 8px 12px;
        padding: 12px 16px;
        font-size: 13px;
        white-space: normal;
    }
    .stats-hub .stats-more > summary span:first-child {
        flex: 1 1 12rem;
        min-width: 0;
        overflow-wrap: anywhere;
        line-height: 1.35;
    }
    .stats-hub .stats-more > summary span:nth-child(2) {
        min-width: 0;
        overflow-wrap: anywhere;
        text-align: right;
    }
    .stats-hub .stats-more > summary::after { flex: 0 0 auto; }
    .stats-hub .stats-more > summary:hover { color: var(--stats-wine); }
    .cert-metadata {
        display: block;
        margin-top: 4px;
        color: #77818d;
        font-size: 10px;
        line-height: 1.4;
    }
    .cert-extra-details { margin-top: 5px; }
    .cert-extra-details > summary {
        color: var(--stats-wine);
        cursor: pointer;
        font-size: 10px;
        font-weight: 650;
    }
    .cert-extra-details > div { margin-top: 4px; color: #687382; font-size: 10px; }

    @media (max-width: 760px) {
        .stats-toolbar { align-items: stretch; flex-wrap: wrap; }
        .stats-hub .tab-switcher { flex: 1 1 100%; width: 100%; }
        .stats-hub .filter-disclosure { margin-left: auto; }
        .stats-hub .filter-disclosure > form { width: min(620px, calc(100vw - 36px)); }
        .stats-hub .stats-more > summary { flex-wrap: wrap; }
        .stats-hub .stats-more > summary span:first-child { flex: 1 1 auto; }
        .stats-hub .stats-more > summary span:nth-child(2) { flex: 1 1 100%; margin-right: 22px; }
    }
    @media (max-width: 620px) {
        .stats-hub .tab-switcher { overflow-x: auto; }
        .stats-hub .tab-btn, .stats-hub .tab-btn.active, .stats-hub .tab-btn.active-mined { flex: 0 0 auto; }
        .stats-hub .filter-disclosure[open] { flex: 1 1 100%; width: 100%; }
        .stats-hub .filter-disclosure > form { position: static; width: 100%; margin-top: 8px; box-shadow: 0 6px 16px #202b3917; }
        .stats-hub .filter-grid { grid-template-columns: 1fr; }
        .stats-hub .kpi-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .stats-hub .kpi-card:nth-child(even) { border-left: 1px solid var(--stats-line) !important; }
        .stats-hub .kpi-card:nth-child(n + 3) { border-top: 3px solid var(--stats-wine) !important; }
        .stats-hub .kpi-card + .kpi-card { border-top: 3px solid var(--stats-wine) !important; }
        .stats-hub .filter-actions { align-items: flex-start; }
        .stats-hub .stats-more { width: 100%; }
        .stats-hub .stats-more > summary {
            min-height: 48px;
            flex-wrap: wrap;
            padding: 12px 14px;
        }
        .stats-hub .stats-more > summary span:first-child { flex: 1 1 calc(100% - 36px); }
        .stats-hub .stats-more > summary span:nth-child(2) { display: none; }
    }
</style>

<div class="stats-hub">

    <div class="stats-toolbar">
    {{-- SWITCHER PRINCIPAL DE MODOS (Analítica de Proyectos vs Reporte Oficial MINED) --}}
    <nav class="tab-switcher" aria-label="Selección de módulo estadístico">
        <a href="{{ route('ups.estadisticas.index', array_merge(request()->except('tab'), ['tab' => 'proyectos'])) }}" 
           class="tab-btn {{ ($filtros['tab'] ?? 'proyectos') === 'proyectos' ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 3v18h18"/>
                <path d="M18 17V9"/>
                <path d="M13 17V5"/>
                <path d="M8 17v-3"/>
            </svg>
                <span>Proyectos</span>
            @if(($filtros['tab'] ?? 'proyectos') === 'proyectos')
                <span class="tab-badge">{{ $totalProyectos ?? 0 }} registros</span>
            @endif
        </a>

        <a href="{{ route('ups.estadisticas.index', array_merge(request()->except('tab'), ['tab' => 'mined'])) }}" 
           class="tab-btn {{ ($filtros['tab'] ?? '') === 'mined' ? 'active-mined' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M4 22h16"/>
                <path d="M2 11h20"/>
                <path d="M12 2 2 7l10 5 10-5Z"/>
                <path d="M6 12v6"/>
                <path d="M10 12v6"/>
                <path d="M14 12v6"/>
                <path d="M18 12v6"/>
            </svg>
            <span>Reporte MINED</span>
            @if(($filtros['tab'] ?? '') === 'mined')
                <span class="tab-badge">{{ $minedHicieron ?? 0 }} hechas</span>
            @endif
        </a>
    </nav>

    {{-- PANEL DE FILTROS MULTIDIMENSIONAL --}}
    <details class="filter-card filter-disclosure" @if($filtros['buscar'] || $filtros['carrera_id'] || $filtros['departamento_id'] || $filtros['area_id'] || ($filtros['periodo'] !== 'todos') || $filtros['desde'] || $filtros['hasta'] || ($filtros['tab'] === 'proyectos' && ($filtros['fase_proyecto'] ?? 'todas') !== 'todas') || ($filtros['tab'] === 'mined' && ($filtros['estado_mined'] ?? 'todos') !== 'todos')) open @endif>
        <summary class="filter-header">
            <div class="filter-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                <span>Filtros</span>
                <span style="font-size:11px;font-weight:normal;color:#64748b;">
                    Carrera, área, período y fase
                </span>
            </div>
        </summary>

        <form method="GET" action="{{ route('ups.estadisticas.index') }}" id="stats-filter-form">
            <input type="hidden" name="tab" value="{{ $filtros['tab'] }}">

            <div class="filter-grid">
                <div class="filter-field">
                    <label for="buscar">Buscar en {{ $filtros['tab'] === 'proyectos' ? 'proyectos' : 'certificaciones' }}</label>
                    <input type="search" name="buscar" id="buscar" value="{{ $filtros['buscar'] }}"
                           placeholder="{{ $filtros['tab'] === 'proyectos' ? 'Título, número, estudiante o carnet' : 'Folio, proyecto, estudiante o carnet' }}"
                           maxlength="100">
                </div>

                {{-- 1. Departamento Académico --}}
                <div class="filter-field">
                    <label for="departamento_id">Departamento</label>
                    <select name="departamento_id" id="departamento_id">
                        <option value="">Todos los departamentos</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id_departamento }}" @selected($filtros['departamento_id'] == $depto->id_departamento)>
                                {{ $depto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. Carrera Universitaria (Agrupada jerárquicamente por departamento) --}}
                <div class="filter-field">
                    <label for="carrera_id">Carrera / Programa</label>
                    <select name="carrera_id" id="carrera_id">
                        <option value="">Todas las carreras</option>
                        @foreach($departamentos as $depto)
                            @php
                                $carrerasDepto = $carreras->where('departamento', $depto->id_departamento);
                            @endphp
                            @if($carrerasDepto->isNotEmpty())
                                <optgroup label="Depto. {{ $depto->nombre }}" data-departamento="{{ $depto->id_departamento }}">
                                    @foreach($carrerasDepto as $carrera)
                                        <option value="{{ $carrera->id_carrera }}" 
                                                data-departamento="{{ $carrera->departamento }}"
                                                @selected($filtros['carrera_id'] == $carrera->id_carrera)>
                                            {{ $carrera->nombre }} {{ $carrera->codigo ? '('.$carrera->codigo.')' : '' }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                        @php
                            $carrerasSinDepto = $carreras->whereNull('departamento');
                        @endphp
                        @if($carrerasSinDepto->isNotEmpty())
                            <optgroup label="Otras Carreras / Multidisciplinarias" data-departamento="">
                                @foreach($carrerasSinDepto as $carrera)
                                    <option value="{{ $carrera->id_carrera }}" 
                                            data-departamento=""
                                            @selected($filtros['carrera_id'] == $carrera->id_carrera)>
                                        {{ $carrera->nombre }} {{ $carrera->codigo ? '('.$carrera->codigo.')' : '' }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>

                {{-- 3. Área de Conocimiento --}}
                <div class="filter-field">
                    <label for="area_id">Área de Conocimiento</label>
                    <select name="area_id" id="area_id">
                        <option value="">Todas las áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area_conocimiento }}" @selected($filtros['area_id'] == $area->id_area_conocimiento)>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 4. Período / Ventana Temporal --}}
                <div class="filter-field">
                    <label for="periodo">Período o Fecha</label>
                    <select name="periodo" id="periodo">
                        <option value="todos" @selected(($filtros['periodo'] ?? 'todos') === 'todos')>Todo el histórico</option>
                        <option value="2026" @selected(($filtros['periodo'] ?? '') === '2026')>Año 2026 (En curso)</option>
                        <option value="2025" @selected(($filtros['periodo'] ?? '') === '2025')>Año 2025</option>
                        <option value="2024" @selected(($filtros['periodo'] ?? '') === '2024')>Año 2024</option>
                        <option value="mes_actual" @selected(($filtros['periodo'] ?? '') === 'mes_actual')>Mes en curso</option>
                        <option value="personalizado" @selected(($filtros['periodo'] ?? '') === 'personalizado')>Rango personalizado (Desde / Hasta)</option>
                    </select>
                </div>

                {{-- 5. Rango de Fechas (Visible o condicional) --}}
                <div class="filter-field" id="wrap-desde" style="{{ ($filtros['periodo'] ?? '') === 'personalizado' ? '' : 'display:none;' }}">
                    <label for="desde">Fecha Desde</label>
                    <input type="date" name="desde" id="desde" value="{{ $filtros['desde'] ?? '' }}">
                </div>

                <div class="filter-field" id="wrap-hasta" style="{{ ($filtros['periodo'] ?? '') === 'personalizado' ? '' : 'display:none;' }}">
                    <label for="hasta">Fecha Hasta</label>
                    <input type="date" name="hasta" id="hasta" value="{{ $filtros['hasta'] ?? '' }}">
                </div>

                {{-- Filtro Específico según pestaña --}}
                @if($filtros['tab'] === 'proyectos')
                    <div class="filter-field">
                        <label for="fase_proyecto">Fase del Proyecto</label>
                        <select name="fase_proyecto" id="fase_proyecto">
                            <option value="todas" @selected(($filtros['fase_proyecto'] ?? 'todas') === 'todas')>Todas las fases</option>
                            <option value="inscripcion" @selected(($filtros['fase_proyecto'] ?? '') === 'inscripcion')>Inscripción</option>
                            <option value="ejecucion" @selected(($filtros['fase_proyecto'] ?? '') === 'ejecucion')>Ejecución</option>
                            <option value="memoria" @selected(($filtros['fase_proyecto'] ?? '') === 'memoria')>Memoria en revisión</option>
                            <option value="finalizado" @selected(($filtros['fase_proyecto'] ?? '') === 'finalizado')>Finalizado</option>
                        </select>
                    </div>
                @else
                    <div class="filter-field">
                        <label for="estado_mined">Filtro Estado MINED</label>
                        <select name="estado_mined" id="estado_mined">
                            <option value="todos" @selected(($filtros['estado_mined'] ?? 'todos') === 'todos')>Todas (Hechas en período)</option>
                            <option value="entregadas" @selected(($filtros['estado_mined'] ?? '') === 'entregadas')>Entregadas a estudiantes</option>
                            <option value="sin_entregar" @selected(($filtros['estado_mined'] ?? '') === 'sin_entregar')>Sin entregar (En custodia)</option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="filter-actions">
                <div class="filter-chips">
                    @if($filtros['departamento_id'])
                        <span class="chip">
                            Depto: {{ $departamentos->firstWhere('id_departamento', $filtros['departamento_id'])?->nombre ?? $filtros['departamento_id'] }}
                        </span>
                    @endif

                    @if($filtros['carrera_id'])
                        <span class="chip">
                            Carrera: {{ $carreras->firstWhere('id_carrera', $filtros['carrera_id'])?->nombre ?? $filtros['carrera_id'] }}
                        </span>
                    @endif

                    @if($filtros['area_id'])
                        <span class="chip">
                            Área: {{ $areas->firstWhere('id_area_conocimiento', $filtros['area_id'])?->nombre ?? $filtros['area_id'] }}
                        </span>
                    @endif

                    @if($filtros['buscar'])
                        <span class="chip">Búsqueda: {{ \Illuminate\Support\Str::limit($filtros['buscar'], 32) }}</span>
                    @endif

                    @if(($filtros['periodo'] ?? 'todos') !== 'todos')
                        <span class="chip">
                            Período: {{ ucfirst(str_replace('_', ' ', $filtros['periodo'])) }}
                            @if($filtros['desde'] && $filtros['hasta'])
                                ({{ \Carbon\Carbon::parse($filtros['desde'])->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($filtros['hasta'])->format('d/m/Y') }})
                            @endif
                        </span>
                    @endif
                </div>

                <div style="display:flex;gap:8px;">
                    @if($filtros['buscar'] || $filtros['carrera_id'] || $filtros['departamento_id'] || $filtros['area_id'] || ($filtros['periodo'] !== 'todos') || $filtros['desde'] || $filtros['hasta'] || ($filtros['tab'] === 'proyectos' && ($filtros['fase_proyecto'] ?? 'todas') !== 'todas') || ($filtros['tab'] === 'mined' && ($filtros['estado_mined'] ?? 'todos') !== 'todos'))
                        <a href="{{ route('ups.estadisticas.index', ['tab' => $filtros['tab']]) }}" class="btn btn-small" title="Restablecer todos los filtros a su estado inicial">
                            Restablecer
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary" id="btn-submit-filters">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <span>Aplicar Filtros</span>
                    </button>
                </div>
            </div>
        </form>
    </details>
    </div>

    {{-- ========================================================================= --}}
    {{-- VISTA 1: ANALÍTICA DE PROYECTOS --}}
    {{-- ========================================================================= --}}
    @if(($filtros['tab'] ?? 'proyectos') === 'proyectos')
        {{-- KPI Cards de Proyectos --}}
        <section class="kpi-row" aria-label="Indicadores clave de proyectos">
            <div class="kpi-card kpi-primary">
                <div class="kpi-top">
                    <span class="kpi-label">Proyectos</span>
                    <div class="kpi-icon-wrap">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($totalProyectos) }}</div>
                <div class="kpi-subtext">
                    <span>En los filtros actuales</span>
                </div>
            </div>

            <div class="kpi-card" style="border-left:4px solid #6366f1;">
                <div class="kpi-top">
                    <span class="kpi-label">En trámite</span>
                    <div class="kpi-icon-wrap" style="background:#e0e7ff;color:#4338ca;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number" style="color:#4338ca;">{{ number_format($estados['en_tramite'] ?? 0) }}</div>
                <div class="kpi-subtext">
                    <span>En ejecución o revisión</span>
                </div>
            </div>

            <div class="kpi-card" style="border-left:4px solid #10b981;">
                <div class="kpi-top">
                    <span class="kpi-label">Finalizados</span>
                    <div class="kpi-icon-wrap" style="background:#d1fae5;color:#047857;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number" style="color:#047857;">{{ number_format($estados['finalizado'] ?? 0) }}</div>
                <div class="kpi-subtext">
                    <span>Servicio social concluido</span>
                </div>
            </div>

            <div class="kpi-card" style="border-left:4px solid var(--red);">
                <div class="kpi-top">
                    <span class="kpi-label">Estudiantes</span>
                    <div class="kpi-icon-wrap" style="background:var(--pale);color:var(--red);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number" style="color:var(--red);">{{ number_format($totalEstudiantes) }}</div>
                <div class="kpi-subtext">
                    <span>Participantes únicos</span>
                </div>
            </div>
        </section>

        {{-- Gráficos Visuales de Distribución --}}
        <section class="charts-grid">
            {{-- Gráfico 1: Fases Operativas --}}
            <div class="visual-card">
                <div class="visual-card-head">
                    <div>
                        <h3>Proyectos por fase</h3>
                        <small>Estado actual de los expedientes</small>
                    </div>
                    <span class="badge">{{ $totalProyectos }} proyectos</span>
                </div>

                @php
                    $pTotal = max(1, $totalProyectos);
                    $pctInscripcion = round(($fases['inscripcion'] / $pTotal) * 100, 1);
                    $pctEjecucion = round(($fases['ejecucion'] / $pTotal) * 100, 1);
                    $pctMemoria = round(($fases['memoria'] / $pTotal) * 100, 1);
                    $pctFinalizado = round(($fases['finalizado'] / $pTotal) * 100, 1);
                @endphp

                {{-- Barra Segmentada Proporcional --}}
                <div class="segmented-progress" title="Inscripción: {{ $pctInscripcion }}% | Ejecución: {{ $pctEjecucion }}% | Memoria: {{ $pctMemoria }}% | Finalizado: {{ $pctFinalizado }}%">
                    @if($fases['inscripcion'] > 0)<div class="seg-slice fase-inscripcion" style="width:{{ $pctInscripcion }}%"></div>@endif
                    @if($fases['ejecucion'] > 0)<div class="seg-slice fase-ejecucion" style="width:{{ $pctEjecucion }}%"></div>@endif
                    @if($fases['memoria'] > 0)<div class="seg-slice fase-memoria" style="width:{{ $pctMemoria }}%"></div>@endif
                    @if($fases['finalizado'] > 0)<div class="seg-slice fase-finalizado" style="width:{{ $pctFinalizado }}%"></div>@endif
                </div>

                {{-- Leyenda interactiva con contadores --}}
                <div class="phase-legend-grid">
                    <div class="phase-item">
                        <div class="phase-left">
                            <span class="phase-dot" style="background:#38bdf8;"></span>
                            <span class="phase-title">Inscripción</span>
                        </div>
                        <div>
                            <strong>{{ $fases['inscripcion'] }}</strong>
                            <small style="color:#64748b;">({{ $pctInscripcion }}%)</small>
                        </div>
                    </div>

                    <div class="phase-item">
                        <div class="phase-left">
                            <span class="phase-dot" style="background:#6366f1;"></span>
                            <span class="phase-title">Ejecución</span>
                        </div>
                        <div>
                            <strong>{{ $fases['ejecucion'] }}</strong>
                            <small style="color:#64748b;">({{ $pctEjecucion }}%)</small>
                        </div>
                    </div>

                    <div class="phase-item">
                        <div class="phase-left">
                            <span class="phase-dot" style="background:#f59e0b;"></span>
                            <span class="phase-title">Memoria</span>
                        </div>
                        <div>
                            <strong>{{ $fases['memoria'] }}</strong>
                            <small style="color:#64748b;">({{ $pctMemoria }}%)</small>
                        </div>
                    </div>

                    <div class="phase-item">
                        <div class="phase-left">
                            <span class="phase-dot" style="background:#10b981;"></span>
                            <span class="phase-title">Finalizado</span>
                        </div>
                        <div>
                            <strong>{{ $fases['finalizado'] }}</strong>
                            <small style="color:#64748b;">({{ $pctFinalizado }}%)</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráfico 2: Desglose por Área de Conocimiento --}}
            <div class="visual-card">
                <div class="visual-card-head">
                    <div>
                        <h3>Proyectos por área</h3>
                        <small>Distribución según los filtros actuales</small>
                    </div>
                    <span class="badge badge-info">{{ $desgloseAreas->count() }} áreas activas</span>
                </div>

                <div style="max-height: 200px; overflow-y: auto; padding-right: 4px;">
                    @forelse($desgloseAreas as $areaRow)
                        <div class="area-bar-row">
                            <div class="area-bar-info">
                                <span style="font-weight:650;color:#1e293b;">{{ $areaRow['nombre'] }}</span>
                                <span style="font-weight:700;color:var(--navy);">
                                    {{ $areaRow['total'] }} 
                                    <small style="font-weight:normal;color:#64748b;">({{ $areaRow['porcentaje'] }}%)</small>
                                </span>
                            </div>
                            <div class="area-bar-track">
                                <div class="area-bar-fill" style="width:{{ max(3, $areaRow['porcentaje']) }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p class="empty" style="padding:20px 0 !important;">No hay proyectos registrados en las áreas seleccionadas.</p>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Resumen Ejecutivo de Escala (Proyectos) --}}
        <details class="stats-more" {{ request()->query('detalle') === 'proyectos' || $proyectos->currentPage() > 1 ? 'open' : '' }}>
            <summary>
                <span>Ver desglose y lista completa</span>
                <span style="color:var(--stats-muted);font-size:10px;font-weight:500;">{{ $desgloseCarreras->count() }} carreras · {{ number_format($totalProyectos) }} proyectos</span>
            </summary>
            <div class="stats-more-content">
        @if($totalProyectos > 0)
            <section class="insights-strip" aria-label="Métricas ejecutivas de escala">
                <div class="insight-card">
                    <div class="insight-icon" style="background:#fef3c7;color:#b45309;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                            <path d="M4 22h16"/>
                            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <div class="insight-label">Carrera con más proyectos</div>
                        <div class="insight-val" title="{{ $carreraLider?->nombre ?? 'N/A' }}">
                            {{ $carreraLider?->nombre ? \Illuminate\Support\Str::limit($carreraLider->nombre, 28) : 'Sin datos' }}
                        </div>
                        <div class="insight-desc">
                            @if($carreraLider)
                                <strong>{{ $carreraLider->total_proyectos }} proyectos</strong> ({{ round(($carreraLider->total_proyectos / max(1, $totalProyectos)) * 100, 1) }}% del volumen)
                            @else
                                Sin proyectos en el corte actual
                            @endif
                        </div>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon" style="background:#e0e7ff;color:#4338ca;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <div class="insight-label">Promedio por carrera</div>
                        <div class="insight-val">{{ $promedioProyectosPorCarrera }}</div>
                        <div class="insight-desc">
                            Entre <strong>{{ $desgloseCarreras->count() }} carreras activas</strong> con proyectos
                        </div>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon" style="background:#d1fae5;color:#047857;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <div class="insight-label">Carreras con proyectos</div>
                        <div class="insight-val">
                            {{ round(($desgloseCarreras->count() / max(1, $carreras->count())) * 100) }}%
                        </div>
                        <div class="insight-desc">
                            {{ $desgloseCarreras->count() }} de {{ $carreras->count() }} carreras con proyectos activos
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Carreras con Mayor Actividad (Optimizado para 50+ carreras con Top 10 + Búsqueda instantánea + Scroll sticky) --}}
        @if($desgloseCarreras->isNotEmpty())
            <section class="card" style="padding:0; overflow:hidden;">
                <div class="card-head" style="padding:16px 20px; border-bottom:none;">
                    <div>
                        <h2 style="margin:0 0 2px;">Proyectos por carrera</h2>
                        <small style="color:#64748b;">Participación de estudiantes por carrera</small>
                    </div>
                    <span class="badge">{{ $desgloseCarreras->count() }} carreras con proyectos</span>
                </div>

                {{-- Controles de búsqueda rápida y densidad para escala masiva (50+ carreras) --}}
                <div class="table-controls">
                    <div class="table-search-box">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="search" id="search-carreras-proy" class="table-search-input" placeholder="Buscar carrera por nombre o código...">
                    </div>
                    <div class="density-switcher">
                        <button type="button" class="density-btn active" data-target="table-carreras-proy" data-mode="top10">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                                <path d="M4 22h16"/>
                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                            </svg>
                            <span>Top 10 carreras</span>
                        </button>
                        <button type="button" class="density-btn" data-target="table-carreras-proy" data-mode="all">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="8" y1="6" x2="21" y2="6"/>
                                <line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/>
                                <line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                            <span>Ver Todas ({{ $desgloseCarreras->count() }})</span>
                        </button>
                    </div>
                </div>

                <div class="table-wrap scrollable-table-wrap">
                    <table class="table sticky-header-table table-dense-top10" id="table-carreras-proy">
                        <thead>
                            <tr>
                                <th style="width:54px;text-align:center;">#</th>
                                <th style="width:90px;">Código</th>
                                <th>Carrera Universitaria</th>
                                <th style="text-align:center;width:140px;">Proyectos Activos</th>
                                <th style="text-align:center;width:160px;">Estudiantes Participantes</th>
                                <th style="text-align:right;width:140px;">Proporción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($desgloseCarreras as $index => $carrRow)
                                @php
                                    $prop = $totalProyectos > 0 ? round(($carrRow->total_proyectos / $totalProyectos) * 100, 1) : 0;
                                    $isBeyondTop10 = $index >= 10;
                                @endphp
                                <tr class="carrera-row {{ $isBeyondTop10 ? 'row-beyond-top10' : '' }}" 
                                    data-search="{{ strtolower($carrRow->nombre.' '.$carrRow->codigo) }}"
                                    data-rank="{{ $index + 1 }}">
                                    <td style="text-align:center;">
                                        @if($index === 0)
                                            <span class="rank-badge rank-1" title="Top 1">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                <span>#1</span>
                                            </span>
                                        @elseif($index === 1)
                                            <span class="rank-badge rank-2" title="Top 2">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                <span>#2</span>
                                            </span>
                                        @elseif($index === 2)
                                            <span class="rank-badge rank-3" title="Top 3">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                <span>#3</span>
                                            </span>
                                        @else
                                            <span class="rank-badge rank-n">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $carrRow->codigo ?? '—' }}</strong></td>
                                    <td><span style="font-weight:600;color:#1e293b;">{{ $carrRow->nombre }}</span></td>
                                    <td style="text-align:center;">
                                        <span class="badge badge-info" style="font-size:12px;font-weight:700;">{{ $carrRow->total_proyectos }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="badge" style="font-size:12px;">{{ $carrRow->total_estudiantes }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <div style="display:inline-flex;align-items:center;gap:8px;">
                                            <span style="font-size:11px;font-weight:700;color:var(--navy);">{{ $prop }}%</span>
                                            <div style="width:60px;height:7px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                                <div style="width:{{ $prop }}%;height:100%;background:var(--navy);"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="no-carreras-match" style="display:none;">
                                <td colspan="6" class="empty">No se encontraron carreras que coincidan con la búsqueda.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        {{-- Tabla Detallada de Proyectos Filtrados --}}
        <section class="card">
            <div class="card-head">
                <div>
                    <h2>Proyectos</h2>
                </div>
                <span class="badge">{{ number_format($proyectos->total()) }} registros</span>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="min-width:260px;">Proyecto</th>
                            <th style="min-width:115px;">Participantes</th>
                            <th style="min-width:150px;">Área</th>
                            <th style="min-width:105px;">Fase</th>
                            <th style="min-width:100px;">Inicio</th>
                            <th style="width:90px;text-align:right;">Abrir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proyectos as $proy)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:7px;min-width:0;">
                                        <span class="badge" style="background:#1e293b;color:#fff;font-size:9px;">#{{ $proy->numero_proyecto ?? $proy->id_proyecto }}</span>
                                        <strong title="{{ $proy->titulo }}" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ \Illuminate\Support\Str::limit($proy->titulo, 48) }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge">{{ $proy->estudiantes->count() }}</span>
                                    <span style="font-size:11px;color:#687382;">{{ $proy->estudiantes->count() === 1 ? 'estudiante' : 'estudiantes' }}</span>
                                </td>
                                <td>
                                    <span title="{{ $proy->areaConocimiento?->nombre ?? 'Sin área' }}" style="font-size:11px;color:#4d5865;">{{ \Illuminate\Support\Str::limit($proy->areaConocimiento?->nombre ?? 'Sin área', 26) }}</span>
                                </td>
                                <td>
                                    @if($proy->fase_actual === 'finalizado')
                                        <span class="badge badge-success">Finalizado</span>
                                    @elseif($proy->fase_actual === 'memoria')
                                        <span class="badge badge-warning">Memoria</span>
                                    @elseif($proy->fase_actual === 'ejecucion')
                                        <span class="badge badge-info">Ejecución</span>
                                    @else
                                        <span class="badge">Inscripción</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size:11px;color:#4d5865;">{{ $proy->fecha_inicio?->format('d/m/Y') ?? '—' }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <a class="btn btn-small" href="{{ route('ups.proyectos.show', $proy->id_proyecto) }}" title="Abrir expediente completo del proyecto {{ $proy->numero_proyecto ?? $proy->id_proyecto }}" aria-label="Abrir expediente del proyecto {{ $proy->numero_proyecto ?? $proy->id_proyecto }}">
                                        Abrir →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty">No se encontraron proyectos con los filtros especificados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($proyectos->hasPages())
                <div class="pagination">
                    <span>Mostrando {{ $proyectos->firstItem() }}–{{ $proyectos->lastItem() }} de {{ $proyectos->total() }} proyectos</span>
                    {{ $proyectos->links() }}
                </div>
            @endif
        </section>
            </div>
        </details>

    {{-- ========================================================================= --}}
    {{-- VISTA 2: REPORTE OFICIAL MINED (Certificaciones de Servicio Social)      --}}
    {{-- ========================================================================= --}}
    @else
        {{-- Banner Oficial MINED --}}
        <section class="mined-official-banner">
            <div class="mined-banner-text">
                <h2>Certificaciones de servicio social</h2>
                <p>Elaboradas, entregadas y en resguardo en la UPS.</p>
            </div>
            <div style="display:flex;gap:8px;">
                <span class="badge" style="background:#ffffff;color:var(--navy);font-weight:750;padding:6px 12px;font-size:11px;">
                    Reporte MINED
                </span>
            </div>
        </section>

        {{-- LAS 3 MÉTRICAS CLAVE SOLICITADAS POR EL USUARIO:
             1. Cuánto se hicieron
             2. Cuánto se entregaron
             3. Cuánto están ahí sin entregar --}}
        <section class="kpi-row" aria-label="Métricas ministeriales de certificaciones">
            {{-- 1. Cuánto se hicieron --}}
            <div class="kpi-card kpi-mined-hicieron">
                <div class="kpi-top">
                    <span class="kpi-label">Elaboradas</span>
                    <div class="kpi-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($minedHicieron) }}</div>
                <div class="kpi-subtext">
                    <strong>Total emitidas</strong> en el período
                </div>
            </div>

            {{-- 2. Cuánto se entregaron --}}
            <div class="kpi-card kpi-mined-entregaron">
                <div class="kpi-top">
                    <span class="kpi-label">Entregadas</span>
                    <div class="kpi-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($minedEntregaron) }}</div>
                <div class="kpi-subtext">
                    <span class="badge badge-success">{{ $tasaEntrega }}% del total</span>
                    <span>Con constancia de entrega</span>
                </div>
            </div>

            {{-- 3. Cuánto están ahí sin entregar --}}
            <div class="kpi-card kpi-mined-sin-entregar">
                <div class="kpi-top">
                    <span class="kpi-label">En resguardo</span>
                    <div class="kpi-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number">{{ number_format($minedSinEntregar) }}</div>
                <div class="kpi-subtext">
                    <span class="badge badge-warning">{{ $tasaSinEntregar }}% pendiente</span>
                    <span>En custodia en ventanilla UPS</span>
                </div>
            </div>

            {{-- 4. Tasa Global de Cumplimiento / Entrega --}}
            <div class="kpi-card kpi-rate">
                <div class="kpi-top">
                    <span class="kpi-label">Tasa de entrega</span>
                    <div class="kpi-icon-wrap">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                </div>
                <div class="kpi-number">{{ $tasaEntrega }}%</div>
                <div class="kpi-subtext">
                    <span>{{ $minedEntregaron }} entregadas de {{ $minedHicieron }} tramitadas</span>
                </div>
            </div>
        </section>

        <details class="stats-more" {{ request()->query('detalle') === 'mined' || $certificaciones->currentPage() > 1 ? 'open' : '' }}>
            <summary>
                <span>Ver desglose MINED</span>
                <span style="color:var(--stats-muted);font-size:10px;font-weight:500;">{{ $minedPorCarrera->count() }} carreras · {{ number_format($minedHicieron) }} certificaciones</span>
            </summary>
            <div class="stats-more-content">
        {{-- Resumen Ejecutivo de Escala (MINED) --}}
        @if($minedHicieron > 0)
            <section class="insights-strip" aria-label="Métricas ejecutivas de certificaciones">
                <div class="insight-card">
                    <div class="insight-icon" style="background:#d1fae5;color:#047857;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <div class="insight-label">Carrera con más trámites</div>
                        <div class="insight-val" title="{{ $carreraMinedLider?->nombre ?? 'N/A' }}">
                            {{ $carreraMinedLider?->nombre ? \Illuminate\Support\Str::limit($carreraMinedLider->nombre, 28) : 'Sin datos' }}
                        </div>
                        <div class="insight-desc">
                            @if($carreraMinedLider)
                                <strong>{{ $carreraMinedLider->total_hechas }} certificaciones</strong> ({{ $carreraMinedLider->tasa_entrega }}% entregadas)
                            @else
                                Sin emisiones registradas
                            @endif
                        </div>
                    </div>
                </div>

                <div class="insight-card" style="border-left:3px solid #f59e0b;">
                    <div class="insight-icon" style="background:#fef3c7;color:#b45309;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <div class="insight-label">Más documentos en resguardo</div>
                        <div class="insight-val" title="{{ $carreraMayorRezago?->nombre ?? 'N/A' }}">
                            {{ $carreraMayorRezago?->nombre ? \Illuminate\Support\Str::limit($carreraMayorRezago->nombre, 28) : 'Al día' }}
                        </div>
                        <div class="insight-desc">
                            @if($carreraMayorRezago && $carreraMayorRezago->total_sin_entregar > 0)
                                <strong style="color:#b45309;">{{ $carreraMayorRezago->total_sin_entregar }} sin retirar</strong> (Alerta de resguardo)
                            @else
                                Todas entregadas a egresados
                            @endif
                        </div>
                    </div>
                </div>

                <div class="insight-card">
                    <div class="insight-icon" style="background:#e0f2fe;color:#0284c7;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M7 7h10M7 12h10M7 17h10"/>
                        </svg>
                    </div>
                    <div class="insight-content">
                        <div class="insight-label">Departamentos incluidos</div>
                        <div class="insight-val">{{ $minedPorDepartamento->count() }} Departamentos</div>
                        <div class="insight-desc">
                            En {{ $minedPorCarrera->count() }} carreras de la facultad
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Desglose Comparativo MINED: Carreras y Departamentos --}}
        <section class="charts-grid">
            {{-- Desglose por Carreras con Escala (Top 10 + Búsqueda instantánea + Scroll sticky) --}}
            <div class="visual-card" style="padding:0; overflow:hidden;">
                <div class="visual-card-head" style="padding:16px 20px 10px;">
                    <div>
                        <h3>Certificaciones por carrera</h3>
                        <small>Emitidas, entregadas y en resguardo</small>
                    </div>
                    <span class="badge">{{ $minedPorCarrera->count() }} carreras</span>
                </div>

                {{-- Controles de búsqueda y densidad para carreras MINED --}}
                <div class="table-controls">
                    <div class="table-search-box">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="search" id="search-mined-carreras" class="table-search-input" placeholder="Buscar carrera en MINED...">
                    </div>
                    <div class="density-switcher">
                        <button type="button" class="density-btn active" data-target="table-carreras-mined" data-mode="top10">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                                <path d="M4 22h16"/>
                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                            </svg>
                            <span>Top 10</span>
                        </button>
                        <button type="button" class="density-btn" data-target="table-carreras-mined" data-mode="all">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="8" y1="6" x2="21" y2="6"/>
                                <line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/>
                                <line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                            <span>Todas ({{ $minedPorCarrera->count() }})</span>
                        </button>
                    </div>
                </div>

                <div class="table-wrap scrollable-table-wrap" style="max-height: 380px;">
                    @if($minedPorCarrera->isEmpty())
                        <p class="empty" style="padding: 24px !important;">No hay certificaciones para las carreras seleccionadas.</p>
                    @else
                        <table class="table sticky-header-table table-dense-top10" id="table-carreras-mined" style="font-size:11px;">
                            <thead>
                                <tr>
                                    <th style="width:44px;text-align:center;">#</th>
                                    <th>Carrera</th>
                                    <th style="text-align:center;">Hechas</th>
                                    <th style="text-align:center;">Entregadas</th>
                                    <th style="text-align:center;">En Custodia</th>
                                    <th style="text-align:right;">% Entrega</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($minedPorCarrera as $index => $row)
                                    @php $isBeyondTop10 = $index >= 10; @endphp
                                    <tr class="carrera-row {{ $isBeyondTop10 ? 'row-beyond-top10' : '' }}"
                                        data-search="{{ strtolower($row->nombre.' '.$row->codigo) }}"
                                        data-rank="{{ $index + 1 }}">
                                        <td style="text-align:center;">
                                            @if($index === 0)
                                                <span class="rank-badge rank-1" title="Top 1">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                    <span>#1</span>
                                                </span>
                                            @elseif($index === 1)
                                                <span class="rank-badge rank-2" title="Top 2">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                    <span>#2</span>
                                                </span>
                                            @elseif($index === 2)
                                                <span class="rank-badge rank-3" title="Top 3">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                    <span>#3</span>
                                                </span>
                                            @else
                                                <span class="rank-badge rank-n">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $row->codigo ?? 'S/C' }}</strong> · {{ \Illuminate\Support\Str::limit($row->nombre, 28) }}
                                        </td>
                                        <td style="text-align:center;"><strong>{{ $row->total_hechas }}</strong></td>
                                        <td style="text-align:center;"><span class="badge badge-success">{{ $row->total_entregadas }}</span></td>
                                        <td style="text-align:center;"><span class="badge badge-warning">{{ $row->total_sin_entregar }}</span></td>
                                        <td style="text-align:right;">
                                            <div style="display:inline-flex;align-items:center;gap:6px;">
                                                <span style="font-weight:700;">{{ $row->tasa_entrega }}%</span>
                                                <div style="width:45px;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden;">
                                                    <div style="width:{{ $row->tasa_entrega }}%;height:100%;background:#10b981;"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="no-carreras-match" style="display:none;">
                                    <td colspan="6" class="empty">No se encontraron carreras con ese nombre.</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Desglose por Departamento Académico --}}
            <div class="visual-card" style="padding:0; overflow:hidden;">
                <div class="visual-card-head" style="padding:16px 20px 10px;">
                    <div>
                        <h3>Certificaciones por departamento</h3>
                        <small>Estado de entrega por departamento</small>
                    </div>
                    <span class="badge">{{ $minedPorDepartamento->count() }} deptos</span>
                </div>

                <div class="table-wrap scrollable-table-wrap" style="max-height: 440px;">
                    @if($minedPorDepartamento->isEmpty())
                        <p class="empty" style="padding: 24px !important;">No hay certificaciones para los departamentos seleccionados.</p>
                    @else
                        <table class="table sticky-header-table" style="font-size:11px;">
                            <thead>
                                <tr>
                                    <th style="width:44px;text-align:center;">#</th>
                                    <th>Departamento</th>
                                    <th style="text-align:center;">Hechas</th>
                                    <th style="text-align:center;">Entregadas</th>
                                    <th style="text-align:center;">En Custodia</th>
                                    <th style="text-align:right;">% Entrega</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($minedPorDepartamento as $index => $deptoRow)
                                    <tr>
                                        <td style="text-align:center;">
                                            @if($index === 0)
                                                <span class="rank-badge rank-1" title="Top 1">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                    <span>#1</span>
                                                </span>
                                            @elseif($index === 1)
                                                <span class="rank-badge rank-2" title="Top 2">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                    <span>#2</span>
                                                </span>
                                            @elseif($index === 2)
                                                <span class="rank-badge rank-3" title="Top 3">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                    <span>#3</span>
                                                </span>
                                            @else
                                                <span class="rank-badge rank-n">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $deptoRow->nombre }}</strong></td>
                                        <td style="text-align:center;"><strong>{{ $deptoRow->total_hechas }}</strong></td>
                                        <td style="text-align:center;"><span class="badge badge-success">{{ $deptoRow->total_entregadas }}</span></td>
                                        <td style="text-align:center;"><span class="badge badge-warning">{{ $deptoRow->total_sin_entregar }}</span></td>
                                        <td style="text-align:right;">
                                            <div style="display:inline-flex;align-items:center;gap:6px;">
                                                <span style="font-weight:700;">{{ $deptoRow->tasa_entrega }}%</span>
                                                <div style="width:45px;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden;">
                                                    <div style="width:{{ $deptoRow->tasa_entrega }}%;height:100%;background:#10b981;"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </section>

        {{-- Tabla Detallada de Certificaciones para Auditoría y Exportación MINED --}}
        <section class="card">
            <div class="card-head">
                <div>
                    <h2>Certificaciones</h2>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="badge">{{ $certificaciones->total() }} registros</span>
                </div>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Estudiante</th>
                            <th>Carrera</th>
                            <th>Proyecto</th>
                            <th>Entrega</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificaciones as $cert)
                            @php $carr = $cert->estudiante?->carreras?->first(); @endphp
                            <tr>
                                <td>
                                    <strong style="color:var(--navy);font-size:13px;">{{ $cert->folio ?? 'S/F' }}</strong>
                                    <small class="cert-metadata">Emitida {{ $cert->fecha_emision?->format('d/m/Y') ?? '—' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $cert->estudiante?->nombre_completo ?? '—' }}</strong>
                                    <small class="cert-metadata">{{ $cert->estudiante?->due ?? 'Sin carnet' }}</small>
                                </td>
                                <td>
                                    <span style="font-weight:600;color:#334155;">{{ \Illuminate\Support\Str::limit($carr?->nombre ?? '—', 30) }}</span>
                                    @if($carr?->departamentoRel)
                                        <small class="cert-metadata">{{ \Illuminate\Support\Str::limit($carr->departamentoRel->nombre, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size:11px;max-width:260px;">
                                        <span class="badge" style="background:#1e293b;color:#fff;font-size:9px;">#{{ $cert->proyecto?->numero_proyecto ?? $cert->proyecto_id }}</span>
                                        <span title="{{ $cert->proyecto?->titulo }}">{{ \Illuminate\Support\Str::limit($cert->proyecto?->titulo, 38) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($cert->fecha_entrega)
                                        <span class="status-entregada">Entregada</span>
                                        <small class="cert-metadata">{{ $cert->fecha_entrega->format('d/m/Y') }}</small>
                                    @else
                                        <span class="status-sin-entregar">En resguardo</span>
                                    @endif
                                    <details class="cert-extra-details">
                                        <summary>Más datos</summary>
                                        <div>Emisor: {{ $cert->emisor?->nombre_completo ?? 'UPS' }}</div>
                                        @if($cert->firma_receptor)
                                            <div>Recibió: {{ $cert->firma_receptor }}</div>
                                        @endif
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">No se encontraron certificaciones con los filtros aplicados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($certificaciones->hasPages())
                <div class="pagination">
                    <span>Mostrando {{ $certificaciones->firstItem() }}–{{ $certificaciones->lastItem() }} de {{ $certificaciones->total() }} certificaciones</span>
                    {{ $certificaciones->links() }}
                </div>
            @endif
        </section>
            </div>
        </details>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectDepto = document.getElementById('departamento_id');
        const selectCarrera = document.getElementById('carrera_id');
        const selectPeriodo = document.getElementById('periodo');
        const wrapDesde = document.getElementById('wrap-desde');
        const wrapHasta = document.getElementById('wrap-hasta');

        // 1. Filtrado dinámico de Carreras (Optgroups) según Departamento seleccionado
        if (selectDepto && selectCarrera) {
            const filtrarCarreras = () => {
                const deptoId = selectDepto.value;
                const optgroups = selectCarrera.querySelectorAll('optgroup');

                if (optgroups.length > 0) {
                    optgroups.forEach(og => {
                        const ogDepto = og.getAttribute('data-departamento');
                        if (!deptoId || ogDepto === deptoId) {
                            og.style.display = '';
                            og.hidden = false;
                        } else {
                            og.style.display = 'none';
                            og.hidden = true;
                        }
                    });

                    // Si la opción seleccionada quedó en un grupo oculto, reiniciar el select
                    const selectedOpt = selectCarrera.selectedOptions[0];
                    if (selectedOpt && selectedOpt.value) {
                        const parentOg = selectedOpt.closest('optgroup');
                        if (parentOg && (parentOg.hidden || parentOg.style.display === 'none')) {
                            selectCarrera.value = '';
                        }
                    }
                }
            };

            selectDepto.addEventListener('change', filtrarCarreras);
            filtrarCarreras();
        }

        // 2. Mostrar / ocultar inputs de fechas según período
        if (selectPeriodo && wrapDesde && wrapHasta) {
            const sincronizarFechas = () => {
                const esPersonalizado = selectPeriodo.value === 'personalizado';
                wrapDesde.style.display = esPersonalizado ? 'flex' : 'none';
                wrapHasta.style.display = esPersonalizado ? 'flex' : 'none';
            };

            selectPeriodo.addEventListener('change', sincronizarFechas);
        }

        // 3. Interactividad para Tablas de Gran Escala (Top 10 vs Ver Todas + Búsqueda instantánea en cliente)
        const setupTableInteractivity = (tableId, searchInputId) => {
            const table = document.getElementById(tableId);
            const searchInput = document.getElementById(searchInputId);
            if (!table) return;

            const densityBtns = document.querySelectorAll(`.density-btn[data-target="${tableId}"]`);
            const rows = table.querySelectorAll('.carrera-row');
            const noMatchRow = table.querySelector('.no-carreras-match');

            let currentMode = 'top10'; // 'top10' o 'all'

            const aplicarFiltrosVisuales = () => {
                const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
                let visibleCount = 0;

                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    const matchesSearch = !query || searchData.includes(query);
                    const isBeyondTop10 = row.classList.contains('row-beyond-top10');

                    if (!matchesSearch) {
                        row.style.display = 'none';
                    } else if (query) {
                        // Si el usuario escribe una búsqueda, expandir para mostrar cualquier carrera que coincida
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        // Sin búsqueda: respetar la densidad activa (Top 10 vs Todas)
                        if (currentMode === 'top10' && isBeyondTop10) {
                            row.style.display = 'none';
                        } else {
                            row.style.display = '';
                            visibleCount++;
                        }
                    }
                });

                if (noMatchRow) {
                    noMatchRow.style.display = visibleCount === 0 ? '' : 'none';
                }
            };

            // Eventos para botones de cambio de densidad
            densityBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    densityBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentMode = btn.getAttribute('data-mode') || 'top10';
                    aplicarFiltrosVisuales();
                });
            });

            // Búsqueda en vivo (0ms lag, filtrado instantáneo en memoria)
            if (searchInput) {
                searchInput.addEventListener('input', aplicarFiltrosVisuales);
            }
        };

        setupTableInteractivity('table-carreras-proy', 'search-carreras-proy');
        setupTableInteractivity('table-carreras-mined', 'search-mined-carreras');
    });

    window.modalExportar = {
        open: function() {
            const modal = document.getElementById('modal-exportar-estadisticas');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        },
        close: function() {
            const modal = document.getElementById('modal-exportar-estadisticas');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        },
        selectType: function(type) {
            const cardAlumnos = document.getElementById('card-opt-alumnos');
            const cardArea = document.getElementById('card-opt-area');
            const cardCsv = document.getElementById('card-opt-csv');
            const radioAlumnos = document.getElementById('radio-tipo-alumnos');
            const radioArea = document.getElementById('radio-tipo-area');
            const radioCsv = document.getElementById('radio-tipo-csv');
            const deptGroup = document.getElementById('grupo-selector-departamento');
            const areaGroup = document.getElementById('grupo-selector-area');
            const deptSelect = document.getElementById('export-departamento-id');
            const areaSelect = document.getElementById('export-area-id');

            [cardAlumnos, cardArea, cardCsv].forEach(c => c?.classList.remove('selected'));

            if (type === 'alumnos_departamento') {
                cardAlumnos?.classList.add('selected');
                if (radioAlumnos) radioAlumnos.checked = true;
                if (deptGroup) deptGroup.style.display = 'block';
                if (areaGroup) areaGroup.style.display = 'none';
                if (deptSelect) deptSelect.disabled = false;
                if (areaSelect) areaSelect.disabled = true;
            } else if (type === 'alumnos_area') {
                cardArea?.classList.add('selected');
                if (radioArea) radioArea.checked = true;
                if (deptGroup) deptGroup.style.display = 'none';
                if (areaGroup) areaGroup.style.display = 'block';
                if (deptSelect) deptSelect.disabled = true;
                if (areaSelect) areaSelect.disabled = false;
            } else {
                cardCsv?.classList.add('selected');
                if (radioCsv) radioCsv.checked = true;
                if (deptGroup) deptGroup.style.display = 'none';
                if (areaGroup) areaGroup.style.display = 'none';
                if (deptSelect) deptSelect.disabled = true;
                if (areaSelect) areaSelect.disabled = true;
            }
        },
        submit: function(event) {
            setTimeout(() => {
                modalExportar.close();
            }, 600);
        }
    };
</script>

<!-- Modal de Exportación de Estadísticas -->
<div id="modal-exportar-estadisticas" class="modal-overlay" style="display:none" role="dialog" aria-modal="true" aria-labelledby="modal-exportar-titulo">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="modal-exportar-titulo">Exportar Información Estadística</h3>
            <button type="button" class="modal-close-btn" onclick="modalExportar.close()" aria-label="Cerrar ventana">&times;</button>
        </div>
        <form id="form-modal-exportar" method="GET" action="{{ route('ups.estadisticas.exportar') }}" onsubmit="modalExportar.submit(event)">
            <!-- Parámetros de filtros heredados de la vista -->
            <input type="hidden" name="periodo" value="{{ $filtros['periodo'] ?? 'todos' }}">
            <input type="hidden" name="desde" value="{{ $filtros['desde'] ?? '' }}">
            <input type="hidden" name="hasta" value="{{ $filtros['hasta'] ?? '' }}">
            <input type="hidden" name="fase_proyecto" value="{{ $filtros['fase_proyecto'] ?? 'todas' }}">
            <input type="hidden" name="area_id" value="{{ $filtros['area_id'] ?? '' }}">
            <input type="hidden" name="carrera_id" value="{{ $filtros['carrera_id'] ?? '' }}">
            <input type="hidden" name="estado_mined" value="{{ $filtros['estado_mined'] ?? 'todos' }}">
            <input type="hidden" name="tab" value="{{ $filtros['tab'] ?? 'proyectos' }}">

            <div class="modal-body">
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;text-transform:uppercase;color:#475569;margin-bottom:8px;">
                        Selecciona el Formato o Plantilla a Exportar
                    </label>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <label class="export-option-card selected" id="card-opt-alumnos" onclick="modalExportar.selectType('alumnos_departamento')">
                            <input type="radio" id="radio-tipo-alumnos" name="tipo" value="alumnos_departamento" checked onchange="modalExportar.selectType(this.value)">
                            <div>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <strong style="font-size:13px;color:#0f172a;">Lista de Estudiantes por Departamento</strong>
                                    <span style="font-size:10px;background:#dcfce7;color:#166534;font-weight:700;padding:2px 6px;border-radius:4px;">Excel .xlsx</span>
                                </div>
                                <p style="font-size:12px;color:#64748b;margin:4px 0 0 0;line-height:1.4;">
                                    Rellena la plantilla oficial institucional con alumnos, carreras, correos y proyectos de servicio social organizados por departamento.
                                </p>
                            </div>
                        </label>

                        <label class="export-option-card" id="card-opt-area" onclick="modalExportar.selectType('alumnos_area')">
                            <input type="radio" id="radio-tipo-area" name="tipo" value="alumnos_area" onchange="modalExportar.selectType(this.value)">
                            <div>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <strong style="font-size:13px;color:#0f172a;">Lista de Estudiantes por Área de Conocimiento</strong>
                                    <span style="font-size:10px;background:#dcfce7;color:#166534;font-weight:700;padding:2px 6px;border-radius:4px;">Excel .xlsx</span>
                                </div>
                                <p style="font-size:12px;color:#64748b;margin:4px 0 0 0;line-height:1.4;">
                                    Rellena la plantilla oficial institucional con alumnos, carreras, correos y proyectos de servicio social organizados por área de conocimiento.
                                </p>
                            </div>
                        </label>

                        <label class="export-option-card" id="card-opt-csv" onclick="modalExportar.selectType('certificaciones_csv')">
                            <input type="radio" id="radio-tipo-csv" name="tipo" value="certificaciones_csv" onchange="modalExportar.selectType(this.value)">
                            <div>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <strong style="font-size:13px;color:#0f172a;">Reporte de Certificaciones y MINED</strong>
                                    <span style="font-size:10px;background:#f1f5f9;color:#475569;font-weight:700;padding:2px 6px;border-radius:4px;">CSV Tabular</span>
                                </div>
                                <p style="font-size:12px;color:#64748b;margin:4px 0 0 0;line-height:1.4;">
                                    Conjunto de datos tabular de folios emitidos, entregas y firmas receptoras compatible con Excel.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="grupo-selector-departamento">
                    <label for="export-departamento-id" style="display:block;font-size:12px;font-weight:700;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                        Departamento Académico
                    </label>
                    <select id="export-departamento-id" name="departamento_id" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:#fff;color:#0f172a;">
                        <option value="todos">Todos los departamentos (una pestaña por departamento)</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto->id_departamento }}" @selected(($filtros['departamento_id'] ?? null) == $depto->id_departamento)>
                                {{ $depto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small style="display:block;color:#64748b;font-size:11px;margin-top:4px;">
                        Si seleccionas "Todos los departamentos", se creará una pestaña en el archivo Excel por cada departamento con estudiantes activos.
                    </small>
                </div>

                <div id="grupo-selector-area" style="display:none;">
                    <label for="export-area-id" style="display:block;font-size:12px;font-weight:700;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                        Área de Conocimiento
                    </label>
                    <select id="export-area-id" name="area_id" style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:13px;background:#fff;color:#0f172a;" disabled>
                        <option value="todas">Todas las áreas (una pestaña por área)</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area_conocimiento }}" @selected(($filtros['area_id'] ?? null) == $area->id_area_conocimiento)>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small style="display:block;color:#64748b;font-size:11px;margin-top:4px;">
                        Si seleccionas "Todas las áreas", se creará una pestaña en el archivo Excel por cada área de conocimiento con estudiantes activos.
                    </small>
                </div>

                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:12px;color:#475569;display:flex;align-items:center;gap:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <span>
                        Período activo: <strong>{{ $filtros['desde'] ? ($filtros['desde'] . ' a ' . $filtros['hasta']) : 'Histórico completo' }}</strong>
                    </span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" onclick="modalExportar.close()">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btn-modal-descargar">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    <span>Descargar Archivo</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 16px;
        animation: fadeInOverlay 0.2s ease;
    }
    .modal-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.25);
        width: 100%;
        max-width: 540px;
        max-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: slideDownModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid #e2e8f0;
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #edf1f6;
        background: #f8fafc;
        flex-shrink: 0;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }
    .modal-close-btn {
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 22px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        line-height: 1;
        transition: background 0.15s ease;
    }
    .modal-close-btn:hover {
        color: #0f172a;
        background: #e2e8f0;
    }
    .modal-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        overflow-y: auto;
        flex: 1 1 auto;
    }
    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 14px 20px;
        border-top: 1px solid #edf1f6;
        background: #f8fafc;
        flex-shrink: 0;
    }
    .export-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #ffffff;
    }
    .export-option-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .export-option-card.selected {
        border-color: #1b365d;
        background: #f0f4f9;
        box-shadow: 0 0 0 1px #1b365d;
    }
    .export-option-card input[type="radio"] {
        margin-top: 3px;
        accent-color: #1b365d;
    }
</style>
@endsection
