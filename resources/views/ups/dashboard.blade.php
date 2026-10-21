<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f7f8fa">
    <title>Dashboard Operativo UPS — SIGPS</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            --ink: #202a38;
            --muted: #5a6677;
            --soft-muted: #8a94a3;
            --maroon: #991b32;
            --canvas: #f7f8fa;
            --pale: #fbecef;
            --border: #dee3ea;
            --white: #fff;
            --navy: #18283a;
            --sidebar-width: 226px;
        }
        html.sidebar-collapsed { --sidebar-width: 68px; }
        * { box-sizing: border-box; }
        body { min-width: 360px; min-height: 100vh; margin: 0; color: var(--ink); background: var(--canvas); font-size: 13px; }
        button, input { font: inherit; }
        button:focus-visible, a:focus-visible, input:focus-visible { outline: 2px solid var(--maroon); outline-offset: 2px; }
        .app-shell { min-height: 100vh; }
        .sidebar { position: fixed; z-index: 10; inset: 0 auto 0 0; display: flex; width: var(--sidebar-width); flex-direction: column; padding: 20px 12px; background: var(--navy); transition: width .2s ease, padding .2s ease, background-color .2s ease; }
        .sidebar-header { display: flex; width: 100%; align-items: center; }
        .brand-link { display: flex; min-height: 50px; align-items: center; gap: 12px; padding: 0 4px; color: #fff; text-decoration: none; }
        .brand-link { min-width: 0; flex: 1; }
        .brand-link img { display: block; width: 42px; height: 42px; object-fit: contain; background: #fff; }
        .brand-copy { color: #f7f8fa; font-size: 10px; font-weight: 700; letter-spacing: .07em; line-height: 1.5; }
        .sidebar-toggle { position: absolute; z-index: 12; top: 27px; right: -28px; display: grid; width: 28px; height: 32px; flex: 0 0 28px; place-items: center; border: 1px solid #405064; border-radius: 0 7px 7px 0; color: #e9edf2; background: var(--navy); box-shadow: 2px 2px 6px #1725361a; cursor: pointer; transition: color .2s ease, background-color .2s ease, border-color .2s ease; }
        .sidebar-toggle:hover { background: #26394e; }
        .sidebar-toggle svg { width: 16px; height: 16px; transition: transform .2s ease; }
        .side-nav { display: flex; width: 100%; min-height: 0; flex-direction: column; gap: 7px; margin-top: 24px; overflow-y: auto; scrollbar-width: thin; }
        .nav-item { display: flex; width: 100%; min-height: 42px; align-items: center; gap: 15px; padding: 0 10px; border: 0; border-radius: 7px; color: #c2c9d2; background: transparent; text-align: left; text-decoration: none; }
        .nav-item svg { width: 20px; height: 20px; flex: 0 0 auto; }
        .nav-item span { font-size: 12px; }
        .nav-item.active { color: #fff; background: #26394e; }
        .nav-item.active svg { color: #fff; }
        .nav-item[aria-disabled="true"] { cursor: not-allowed; opacity: .75; }
        .nav-group { width: 100%; flex: 0 0 auto; }
        .nav-group-toggle { display: flex; min-height: 42px; align-items: center; gap: 15px; padding: 0 10px; border-radius: 7px; color: #c2c9d2; cursor: pointer; list-style: none; }
        .nav-group-toggle::-webkit-details-marker { display: none; }
        .nav-group-toggle::after { width: 7px; height: 7px; margin-left: auto; border-right: 1.5px solid currentColor; border-bottom: 1.5px solid currentColor; content: ""; transform: rotate(45deg) translateY(-2px); }
        .nav-group[open] > .nav-group-toggle::after { transform: rotate(225deg) translate(-1px, -1px); }
        .nav-group-toggle:hover, .nav-group-toggle.active { color: #fff; background: #26394e; }
        .nav-group-toggle svg { width: 20px; height: 20px; flex: 0 0 auto; }
        .nav-group-toggle span { font-size: 12px; }
        .nav-subnav { display: grid; gap: 3px; margin: 3px 0 4px 19px; padding-left: 9px; border-left: 1px solid #314154; }
        .nav-child { display: flex; min-height: 33px; align-items: center; padding: 0 9px; border-radius: 7px; color: #c2c9d2; font-size: 11px; text-decoration: none; }
        .nav-child:hover { color: #fff; background: #22364b; }
        .nav-child.active { color: #fff; background: #26394e; }
        .sidebar-spacer { flex: 1; }
        .user-avatar { display: flex; width: 100%; min-height: 44px; align-items: center; gap: 11px; margin: 0 0 6px; padding: 0 9px; border-top: 1px solid #314154; color: #fff; font-size: 11px; font-weight: 600; }
        .user-avatar::before { display: grid; width: 29px; height: 29px; flex: 0 0 auto; place-items: center; border-radius: 50%; color: var(--navy); background: #dce3eb; content: attr(data-initials); font-size: 10px; }
        .sidebar-logout { margin-left: auto; padding: 6px 7px; border: 1px solid #536276; border-radius: 6px; color: #fff; background: transparent; font-size: 10px; cursor: pointer; }
        .sidebar-logout:hover { border-color: #fff; background: #26394e; }
        .workspace { width: calc(100% - var(--sidebar-width)); min-height: 100vh; margin-left: var(--sidebar-width); padding: 18px 24px 28px 16px; transition: width .2s ease, margin-left .2s ease; }
        .topbar { display: flex; min-height: 52px; align-items: center; justify-content: space-between; gap: 20px; padding: 0 24px; }
        .topbar-title { margin: 0; color: var(--ink); font-size: 15px; font-weight: 700; }
        .topbar-tools { display: flex; align-items: center; gap: 12px; }
        .search-form { display: flex; align-items: center; gap: 6px; }
        .search-control { display: flex; width: 230px; height: 34px; align-items: center; gap: 8px; padding: 0 11px; border: 1px solid var(--border); border-radius: 8px; background: var(--white); }
        .search-control svg { width: 14px; height: 14px; flex: 0 0 auto; color: var(--soft-muted); }
        .search-control input { width: 100%; min-width: 0; border: 0; outline: 0; color: var(--muted); background: transparent; font-size: 11px; }
        .search-control input::placeholder { color: var(--muted); opacity: 1; }
        .search-submit { height: 34px; padding: 0 12px; border: 0; border-radius: 7px; color: #fff; background: var(--maroon); font-size: 11px; }
        .utility-button { display: grid; width: 32px; height: 32px; padding: 0; place-items: center; border: 0; border-radius: 7px; color: var(--muted); background: transparent; }
        .utility-button svg { width: 18px; height: 18px; }
        .user-summary { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
        .user-summary strong { color: var(--ink); font-size: 11px; font-weight: 650; }
        .user-summary span { color: var(--muted); font-size: 10px; }
        .account-menu { position: relative; }
        .account-menu summary { display: grid; grid-template-columns: minmax(0, auto) 12px; grid-template-rows: auto auto; align-items: center; column-gap: 9px; cursor: pointer; list-style: none; }
        .account-menu summary strong, .account-menu summary span { grid-column: 1; }
        .account-menu summary::after { grid-column: 2; grid-row: 1 / span 2; justify-self: center; }
        .account-menu summary::-webkit-details-marker { display: none; }
        .account-menu summary::after { width: 7px; height: 7px; border-right: 1.5px solid currentColor; border-bottom: 1.5px solid currentColor; content: ""; transform: rotate(45deg) translateY(-2px); }
        .account-menu[open] summary::after { transform: rotate(225deg) translate(-1px, -1px); }
        .account-menu-popover { position: absolute; z-index: 20; top: calc(100% + 10px); right: 0; min-width: 156px; padding: 5px; border: 1px solid var(--border); border-radius: 8px; background: var(--white); box-shadow: 0 8px 24px #17283a1f; }
        .account-menu-popover form { margin: 0; }
        .account-menu-popover button { width: 100%; padding: 9px 10px; border: 0; border-radius: 5px; color: var(--ink); background: transparent; text-align: left; cursor: pointer; }
        .account-menu-popover button:hover { color: var(--maroon); background: var(--pale); }
        .content { display: flex; flex-direction: column; gap: 18px; padding: 17px 24px 0; }
        .intro { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; }
        .intro p { margin: 0; color: var(--muted); font-size: 12px; line-height: 1.5; }
        .current-time { display: inline-flex; align-items: center; gap: 8px; color: var(--muted); font-size: 11px; white-space: nowrap; }
        .current-time svg { width: 14px; height: 14px; }
        .dashboard-grid { display: grid; min-width: 0; grid-template-columns: minmax(0, 1fr) 290px; align-items: start; gap: 18px; }
        .main-column, .side-column { display: flex; min-width: 0; flex-direction: column; gap: 18px; }
        .metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
        .metric-card { display: flex; min-width: 0; min-height: 112px; align-items: center; gap: 14px; padding: 16px; border: 1px solid var(--border); border-radius: 8px; background: var(--white); }
        .metric-icon { display: grid; width: 40px; height: 40px; flex: 0 0 auto; place-items: center; border-radius: 50%; color: var(--maroon); background: var(--pale); }
        .metric-icon svg { width: 20px; height: 20px; }
        .metric-info { min-width: 0; }
        .metric-info strong { display: block; margin-bottom: 1px; color: var(--ink); font-size: 26px; font-weight: 700; line-height: 1.1; }
        .metric-info span { display: block; color: var(--ink); font-size: 13px; font-weight: 650; line-height: 1.4; }
        .metric-info small { display: block; margin-top: 3px; color: var(--muted); font-size: 11px; line-height: 1.35; }
        .panel { min-width: 0; border: 1px solid var(--border); border-radius: 8px; background: var(--white); }
        .panel-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 15px 16px 12px; }
        .panel-heading h2 { margin: 0; color: var(--ink); font-size: 14px; font-weight: 700; }
        .panel-heading a, .panel-heading span { color: var(--maroon); font-size: 10px; text-decoration: none; white-space: nowrap; }
        .panel-heading .plain-label { color: var(--muted); }
        .empty-state { padding: 28px 16px !important; color: var(--muted) !important; text-align: center; white-space: normal !important; }
        .table-wrap { overflow-x: auto; }
        .records-table { width: 100%; border-collapse: collapse; text-align: left; white-space: nowrap; }
        .records-table th { height: 34px; padding: 0 10px; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); color: var(--muted); background: #fbfcfd; font-size: 10px; font-weight: 650; }
        .records-table td { height: 44px; padding: 5px 10px; border-bottom: 1px solid #e9edf2; color: var(--muted); font-size: 10px; }
        .records-table tbody tr:last-child td { border-bottom: 0; }
        .records-table td.code { color: var(--ink); font-weight: 650; }
        .review-button { padding: 5px 9px; border: 0; border-radius: 5px; color: var(--maroon); background: var(--pale); font-size: 10px; }
        .review-button:disabled { cursor: not-allowed; opacity: .86; }
        .alert-list { margin: 0; padding: 0; list-style: none; }
        .alert-list li { display: flex; min-height: 50px; flex-direction: column; justify-content: center; gap: 3px; padding: 7px 15px; border-top: 1px solid #e9edf2; }
        .alert-list strong { color: #cf3c48; font-size: 11px; font-weight: 600; }
        .alert-list span { color: var(--muted); font-size: 10px; }
        .calendar-panel { padding-bottom: 12px; }
        .calendar-head { padding-bottom: 12px; }
        .month-switch { display: inline-flex; align-items: center; gap: 8px; color: var(--maroon) !important; font-size: 10px !important; }
        .month-switch a { display: inline-flex; width: 22px; height: 22px; align-items: center; justify-content: center; border-radius: 5px; color: var(--maroon); background: var(--pale); font-size: 15px; text-decoration: none; }
        .calendar { width: calc(100% - 24px); margin: 0 12px; border-collapse: separate; border-spacing: 3px; table-layout: fixed; text-align: center; }
        .calendar th { height: 18px; color: var(--muted); font-size: 9px; font-weight: 600; }
        .calendar td { height: 24px; padding: 0; color: var(--ink); font-size: 9px; }
        .calendar td span { display: grid; width: 24px; height: 24px; margin: auto; place-items: center; border-radius: 4px; }
        .calendar td.today span { color: #fff; background: var(--maroon); }
        .calendar td.outside span { color: #aeb6c1; }
        .quick-actions { padding: 14px 10px 10px; }
        .quick-actions h2 { margin: 0 5px 10px; color: var(--ink); font-size: 14px; }
        .quick-action { display: flex; width: 100%; min-height: 35px; align-items: center; gap: 10px; margin-top: 7px; padding: 0 10px; border: 1px solid var(--border); border-radius: 7px; color: var(--ink); background: var(--white); font-size: 11px; text-align: left; text-decoration: none; }
        .quick-action svg { width: 16px; height: 16px; color: var(--maroon); }
        .quick-action.primary { background: var(--pale); }
        .quick-action.disabled { color: var(--muted); cursor: not-allowed; }
        .validation-message { margin: -7px 0 0; color: #b42335; font-size: 11px; }

        html.sidebar-collapsed .sidebar { padding-right: 8px; padding-left: 8px; border-right: 1px solid #e4e7ec; background: #fff; }
        html.sidebar-collapsed .brand-link { flex: 1; justify-content: center; padding: 0; }
        html.sidebar-collapsed .brand-link img { width: 36px; height: 36px; background: #fff; }
        html.sidebar-collapsed .brand-copy,
        html.sidebar-collapsed .nav-item span,
        html.sidebar-collapsed .nav-group-toggle span,
        html.sidebar-collapsed .user-name { display: none; }
        html.sidebar-collapsed .sidebar-toggle { color: var(--maroon); border-color: #e4e7ec; background: #fff; }
        html.sidebar-collapsed .sidebar-toggle:hover { background: var(--pale); }
        html.sidebar-collapsed .sidebar-toggle svg { transform: rotate(180deg); }
        html.sidebar-collapsed .side-nav { align-items: center; }
        html.sidebar-collapsed .nav-item { justify-content: center; padding: 0; color: #7d8795; }
        html.sidebar-collapsed .nav-item.active { color: var(--maroon); background: var(--pale); }
        html.sidebar-collapsed .nav-item.active svg { color: var(--maroon); }
        html.sidebar-collapsed .nav-group { width: 100%; }
        html.sidebar-collapsed .nav-group-toggle { justify-content: center; padding: 0; color: #7d8795; }
        html.sidebar-collapsed .nav-group-toggle::after,
        html.sidebar-collapsed .nav-subnav { display: none; }
        html.sidebar-collapsed .nav-group-toggle.active { color: var(--maroon); background: var(--pale); }
        html.sidebar-collapsed .user-avatar { justify-content: center; padding: 0; border-color: #e4e7ec; }
        html.sidebar-collapsed .sidebar-logout { display: none; }

        @media (max-width: 1180px) {
            .dashboard-grid { grid-template-columns: minmax(0, 1fr) 260px; gap: 14px; }
            .content { padding-right: 10px; padding-left: 10px; }
            .topbar { padding-right: 10px; padding-left: 10px; }
            .metric-card { gap: 10px; padding: 12px; }
            .metric-info strong { font-size: 23px; }
            .metric-info span { font-size: 12px; }
        }
        @media (max-width: 900px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .side-column { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; }
            .quick-actions { grid-column: 1 / -1; }
            .topbar { align-items: flex-start; flex-direction: column; gap: 10px; padding: 0 10px; }
            .topbar-tools { width: 100%; flex-wrap: wrap; }
            .search-form { min-width: 0; flex: 1; }
            .search-control { width: auto; flex: 1; }
            .metrics { grid-template-columns: repeat(auto-fit, minmax(185px, 1fr)); }
        }
        @media (max-width: 650px) {
            body { min-width: 360px; }
            .sidebar { padding-top: 10px; }
            .side-nav { gap: 4px; margin-top: 12px; }
            .nav-item { min-height: 42px; }
            .user-avatar { margin-bottom: 5px; }
            .workspace { padding: 8px 10px 18px 8px; }
            .topbar { align-items: flex-start; flex-direction: column; gap: 10px; padding: 8px 4px 12px; }
            .topbar-tools { width: 100%; gap: 8px; }
            .search-control { width: auto; flex: 1; }
            .search-form { width: 100%; }
            .user-summary { margin-left: auto; }
            .content { gap: 14px; padding: 12px 4px 0; }
            .intro { align-items: flex-start; flex-direction: column; gap: 8px; }
            .current-time { white-space: normal; }
            .metrics { grid-template-columns: 1fr; gap: 8px; }
            .metric-card { min-height: 84px; }
            .side-column { display: flex; }
            .panel-heading { padding-right: 12px; padding-left: 12px; }
            .panel-heading h2 { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" aria-label="Navegación principal">
            <div class="sidebar-header">
                <a class="brand-link" href="{{ route('ups.dashboard', [], false) }}" aria-label="Inicio UPS">
                    <img src="/images/auth/logo-fmo.jpeg" alt="FMO-UES">
                    <span class="brand-copy">UNIVERSIDAD DE EL SALVADOR<br>FACULTAD MULTIDISCIPLINARIA ORIENTAL</span>
                </a>
                <button class="sidebar-toggle" type="button" aria-controls="sidebar-nav" aria-expanded="true" aria-label="Contraer menú" title="Contraer menú">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m14 18-6-6 6-6"></path></svg>
                </button>
            </div>
            <nav class="side-nav" id="sidebar-nav" aria-label="Módulos UPS">
                <a class="nav-item {{ request()->routeIs('ups.dashboard') ? 'active' : '' }}" href="{{ route('ups.dashboard', [], false) }}" @if(request()->routeIs('ups.dashboard')) aria-current="page" @endif aria-label="Dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="1.5"></rect><rect x="13" y="3" width="8" height="5" rx="1.5"></rect><rect x="13" y="10" width="8" height="11" rx="1.5"></rect><rect x="3" y="13" width="8" height="8" rx="1.5"></rect></svg>
                    <span>Dashboard</span>
                </a>
                <a class="nav-item {{ request()->routeIs('ups.bandeja.*') ? 'active' : '' }}" href="{{ route('ups.bandeja.index', [], false) }}" aria-label="Bandeja UPS" title="Bandeja UPS">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v14H4zM4 13h4l2 3h4l2-3h4"></path></svg>
                    <span>Bandeja UPS</span>
                </a>
                <a class="nav-item {{ request()->routeIs('ups.estudiantes.*') ? 'active' : '' }}" href="{{ route('ups.estudiantes.index', [], false) }}" aria-label="Estudiantes" title="Estudiantes">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg>
                    <span>Estudiantes</span>
                </a>
                <details class="nav-group" @if(request()->routeIs('ups.proyectos.*', 'ups.prorrogas.*', 'ups.plazos.*')) open @endif>
                    <summary class="nav-group-toggle {{ request()->routeIs('ups.proyectos.*', 'ups.prorrogas.*', 'ups.plazos.*') ? 'active' : '' }}" aria-label="Proyectos y seguimiento" title="Proyectos y seguimiento">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"></path><path d="m3 8 9 5 9-5M3 8v9l9 5 9-5V8M12 13v9"></path></svg>
                        <span>Proyectos</span>
                    </summary>
                    <div class="nav-subnav">
                        <a class="nav-child {{ request()->routeIs('ups.proyectos.*') ? 'active' : '' }}" href="{{ route('ups.proyectos.index', [], false) }}">Proyectos</a>
                        <a class="nav-child {{ request()->routeIs('ups.prorrogas.*') ? 'active' : '' }}" href="{{ route('ups.prorrogas.index', [], false) }}">Prórrogas</a>
                        <a class="nav-child {{ request()->routeIs('ups.plazos.*') ? 'active' : '' }}" href="{{ route('ups.plazos.index', [], false) }}">Plazos</a>
                    </div>
                </details>
                <a class="nav-item {{ request()->routeIs('ups.memorias.*', 'ups.auditoria.*') ? 'active' : '' }}" href="{{ route('ups.memorias.index', [], false) }}" aria-label="Memorias" title="Memorias">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h9l5 5v13H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path><path d="M14 3v6h6M8 13h8M8 17h8"></path></svg>
                    <span>Memorias</span>
                </a>
                <a class="nav-item {{ request()->routeIs('ups.certificaciones.*') ? 'active' : '' }}" href="{{ route('ups.certificaciones.index', [], false) }}" aria-label="Certificaciones" title="Certificaciones">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h9l5 5v8H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path><path d="M14 3v6h6M8 11h8M8 14h5M9 16l-1 6 4-2 4 2-1-6"></path></svg>
                    <span>Certificaciones</span>
                </a>
                <a class="nav-item {{ request()->routeIs('ups.estadisticas.*') ? 'active' : '' }}" href="{{ route('ups.estadisticas.index', [], false) }}" aria-label="Estadísticas" title="Estadísticas">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20V10m8 10V4m8 16v-7M2 20h20"></path></svg>
                    <span>Estadísticas</span>
                </a>
            </nav>
            <div class="sidebar-spacer"></div>
            <div class="user-avatar" data-initials="{{ collect(explode(' ', $nombreUsuario))->filter()->take(2)->map(fn ($parte) => mb_substr($parte, 0, 1))->implode('') }}" aria-label="Usuario: {{ $nombreUsuario }}">
                <span class="user-name">{{ $nombreUsuario }} · {{ $rolUsuario }}</span>
                <form method="POST" action="{{ route('logout', [], false) }}">
                    @csrf
                    <button class="sidebar-logout" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </aside>

        <main class="workspace">
            <header class="topbar">
                <h1 class="topbar-title">Dashboard Operativo UPS</h1>
                <div class="topbar-tools">
                    <form class="search-form" method="GET" action="{{ route('ups.dashboard', [], false) }}">
                        <label class="search-control" aria-label="Buscar expedientes">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
                            <input type="search" name="buscar" value="{{ $busqueda }}" placeholder="Buscar expediente o estudiante..." maxlength="100" aria-label="Buscar por carnet, estudiante o proyecto">
                        </label>
                        <button class="search-submit" type="submit">Buscar</button>
                    </form>
                    <a class="utility-button" href="#alertas" aria-label="Ir a alertas de plazo" title="Ir a alertas de plazo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path></svg>
                    </a>
                    <details class="account-menu">
                        <summary class="user-summary">
                            <strong>{{ $nombreUsuario }}</strong>
                            <span>{{ $rolUsuario }}</span>
                        </summary>
                        <div class="account-menu-popover">
                            <form method="POST" action="{{ route('logout', [], false) }}">
                                @csrf
                                <button type="submit">Cerrar sesión</button>
                            </form>
                        </div>
                    </details>
                </div>
            </header>

            <section class="content" aria-label="Resumen del dashboard">
                <div class="intro">
                    <p>Resumen general del estado de expedientes, plazos y certificaciones.</p>
                    <div class="current-time">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg>
                        {{ $fechaActual }}
                    </div>
                </div>
                @if ($errors->has('buscar') || $errors->has('mes') || $errors->has('anio'))
                    <p class="validation-message" role="alert">Revisa los filtros ingresados. La búsqueda admite hasta 100 caracteres.</p>
                @endif

                <div class="dashboard-grid">
                    <div class="main-column">
                        <section class="metrics" aria-label="Indicadores principales">
                            @foreach ($metricas as $metrica)
                                <article class="metric-card">
                                    <div class="metric-icon" aria-hidden="true">
                                        @if ($metrica['icono'] === 'archivo')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h10l3 3v13H8z"></path><path d="M18 4v4h4M4 8v13h12M11 12h6M11 16h6"></path></svg>
                                        @elseif ($metrica['icono'] === 'alerta')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 10 18H2L12 3Z"></path><path d="M12 9v5M12 17h.01"></path></svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12v8a6 6 0 0 1-12 0V3ZM8 21h8M12 17v4M9 7h6"></path><path d="m8 13 2 2 5-5"></path></svg>
                                        @endif
                                    </div>
                                    <div class="metric-info">
                                        <strong>{{ $metrica['valor'] }}</strong>
                                        <span>{{ $metrica['titulo'] }}</span>
                                        <small>{{ $metrica['detalle'] }}</small>
                                    </div>
                                </article>
                            @endforeach
                        </section>

                        <section class="panel" aria-labelledby="expedientes-heading">
                            <div class="panel-heading">
                                <h2 id="expedientes-heading">Expedientes en espera de auditoría de sellos y firma azul</h2>
                                <span class="plain-label">{{ $metricas[0]['valor'] }} pendientes</span>
                            </div>
                            <div class="table-wrap">
                                <table class="records-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Carnet / Código</th>
                                            <th>Proyecto</th>
                                            <th>Área de conocimiento</th>
                                            <th>Fecha de revisión</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($expedientes as $indice => $expediente)
                                            <tr>
                                                <td>{{ str_pad($indice + 1, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td class="code">{{ $expediente['codigo'] }}</td>
                                                <td>{{ $expediente['proyecto'] }}</td>
                                                <td>{{ $expediente['area'] }}</td>
                                                <td>{{ $expediente['fecha'] }}</td>
                                                <td><span title="La auditoría se habilitará con el módulo de bandeja">{{ $expediente['estado'] }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="empty-state">{{ $busqueda ? 'No hay expedientes que coincidan con la búsqueda.' : 'Todavía no hay expedientes pendientes registrados.' }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <aside class="side-column" aria-label="Alertas y accesos rápidos">
                        <section class="panel" id="alertas" aria-labelledby="alerts-heading">
                            <div class="panel-heading">
                                <h2 id="alerts-heading">Alertas de plazo</h2>
                            </div>
                            <ul class="alert-list">
                                @forelse ($alertas as $alerta)
                                    <li><strong>Expediente {{ $alerta['codigo'] }}</strong><span>{{ $alerta['vence'] }}</span></li>
                                @empty
                                    <li><span>{{ $plazosCriticosTotal === 0 ? 'No hay plazos críticos registrados.' : 'Hay '.$plazosCriticosTotal.' proyectos con plazo crítico.' }}</span></li>
                                @endforelse
                            </ul>
                            <div class="panel-heading" style="justify-content:flex-end;padding-top:10px;padding-bottom:12px"><span class="plain-label">Basado en fechas de inicio registradas</span></div>
                        </section>

                        <section class="panel calendar-panel" aria-labelledby="calendar-heading">
                            <div class="panel-heading calendar-head">
                                <h2 id="calendar-heading">Calendario de plazos</h2>
                                <span class="month-switch">
                                    @if ($mesAnterior->year >= 2020)
                                        <a href="{{ route('ups.dashboard', ['mes' => $mesAnterior->month, 'anio' => $mesAnterior->year, 'buscar' => $busqueda ?: null], false) }}" aria-label="Mes anterior">‹</a>
                                    @endif
                                    {{ $mesActual }}
                                    @if ($mesSiguiente->year <= 2100)
                                        <a href="{{ route('ups.dashboard', ['mes' => $mesSiguiente->month, 'anio' => $mesSiguiente->year, 'buscar' => $busqueda ?: null], false) }}" aria-label="Mes siguiente">›</a>
                                    @endif
                                </span>
                            </div>
                            <table class="calendar" aria-label="Calendario de {{ $mesActual }}">
                                <thead><tr><th>L</th><th>M</th><th>X</th><th>J</th><th>V</th><th>S</th><th>D</th></tr></thead>
                                <tbody>
                                    @foreach ($diasCalendario as $semana)
                                        <tr>
                                            @foreach ($semana as $dia)
                                                <td class="{{ $dia !== null && $dia === $diaSeleccionado ? 'today' : ($dia === null ? 'outside' : '') }}"><span>{{ $dia ?? '' }}</span></td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </section>

                        <section class="panel quick-actions" aria-labelledby="quick-actions-heading">
                            <h2 id="quick-actions-heading">Acciones rápidas</h2>
                            <button class="quick-action primary disabled" type="button" disabled title="El módulo de certificaciones aún no está disponible">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="5"></circle><path d="m8.5 12-1 9 4.5-2.5 4.5 2.5-1-9"></path></svg>
                                Registrar certificado
                            </button>
                            <a class="quick-action" href="{{ route('perfil.edit', [], false) }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L9 17l-4 1 1-4Z"></path></svg>
                                Actualizar perfil
                            </a>
                            <button class="quick-action disabled" type="button" disabled title="Los reportes estarán disponibles cuando se implemente el módulo de estadísticas">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6M8 13h8M8 17h8"></path></svg>
                                Generar reporte
                            </button>
                        </section>
                    </aside>
                </div>
            </section>
        </main>
    </div>
    <script>
        (() => {
            const root = document.documentElement;
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const preferenceKey = 'sigps-ups-sidebar-collapsed';

            try {
                const savedPreference = localStorage.getItem(preferenceKey);
                if (savedPreference === 'true' || (savedPreference === null && window.matchMedia('(max-width: 650px)').matches)) {
                    root.classList.add('sidebar-collapsed');
                }
            } catch (error) {
                // The sidebar toggle still works for this page when storage is unavailable.
            }

            const updateToggle = () => {
                const collapsed = root.classList.contains('sidebar-collapsed');
                sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
                sidebarToggle.setAttribute('aria-label', collapsed ? 'Expandir menú' : 'Contraer menú');
                sidebarToggle.setAttribute('title', collapsed ? 'Expandir menú' : 'Contraer menú');
            };

            updateToggle();
            sidebarToggle.addEventListener('click', () => {
                root.classList.toggle('sidebar-collapsed');
                updateToggle();

                try {
                    localStorage.setItem(preferenceKey, String(root.classList.contains('sidebar-collapsed')));
                } catch (error) {
                    // Keep the current page state even when storage is unavailable.
                }
            });
        })();
    </script>
</body>
</html>
