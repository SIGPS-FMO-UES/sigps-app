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
        .search-control { display: flex; width: 260px; height: 36px; align-items: center; gap: 8px; padding: 0 11px; border: 1px solid var(--border); border-radius: 8px; background: var(--white); }
        .search-control svg { width: 14px; height: 14px; flex: 0 0 auto; color: var(--soft-muted); }
        .search-control input { width: 100%; min-width: 0; border: 0; outline: 0; color: var(--ink); background: transparent; font-size: 11px; }
        .search-control input::placeholder { color: var(--muted); opacity: 1; }
        .search-submit { height: 36px; padding: 0 13px; border: 0; border-radius: 7px; color: #fff; background: var(--maroon); font-size: 11px; font-weight: 600; cursor: pointer; }
        .search-submit:hover { background: #801328; }
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

        /* ============================================== */
        /* TARJETAS DE TRIAJE DE INMEDIATEZ (FASE 3)      */
        /* ============================================== */
        .triaje-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .triaje-action-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 140px;
            padding: 16px 18px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            text-decoration: none;
            color: var(--ink);
            box-shadow: 0 2px 10px rgba(24, 40, 58, 0.04);
            transition: all 0.2s ease;
        }
        .triaje-action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(24, 40, 58, 0.08);
        }
        .triaje-action-card.card-urgente {
            border-color: #fecaca;
            background: linear-gradient(180deg, #ffffff 0%, #fff5f5 100%);
        }
        .triaje-action-card.card-urgente:hover {
            border-color: #dc2626;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.12);
        }
        .triaje-action-card.card-tramite {
            border-color: #fde68a;
            background: linear-gradient(180deg, #ffffff 0%, #fffdf5 100%);
        }
        .triaje-action-card.card-tramite:hover {
            border-color: #d97706;
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.12);
        }
        .triaje-action-card.card-entregas {
            border-color: #bfdbfe;
            background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%);
        }
        .triaje-action-card.card-entregas:hover {
            border-color: #2563eb;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.12);
        }

        .card-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .card-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-urgente .card-icon-badge { background: #fee2e2; color: #dc2626; }
        .card-tramite .card-icon-badge { background: #fef3c7; color: #d97706; }
        .card-entregas .card-icon-badge { background: #dbeafe; color: #2563eb; }

        .card-count-big {
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
        }
        .card-urgente .card-count-big { color: #dc2626; }
        .card-tramite .card-count-big { color: #d97706; }
        .card-entregas .card-count-big { color: #2563eb; }

        .card-title-text {
            font-size: 13px;
            font-weight: 750;
            margin-bottom: 3px;
            color: #1e293b;
        }
        .card-desc-text {
            font-size: 11px;
            color: var(--muted);
            line-height: 1.4;
            margin-bottom: 12px;
        }
        .card-action-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            margin-top: auto;
        }
        .card-urgente .card-action-link { color: #b91c1c; }
        .card-tramite .card-action-link { color: #b45309; }
        .card-entregas .card-action-link { color: #1d4ed8; }

        /* Panel Principal */
        .panel { min-width: 0; border: 1px solid var(--border); border-radius: 9px; background: var(--white); box-shadow: 0 2px 10px rgba(24, 40, 58, 0.03); }
        .panel-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 15px 18px 12px; border-bottom: 1px solid #edf0f4; }
        .panel-heading h2 { margin: 0; color: var(--ink); font-size: 14px; font-weight: 700; }
        .panel-heading a { color: var(--maroon); font-size: 11px; font-weight: 650; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
        .panel-heading a:hover { text-decoration: underline; }
        .panel-heading .plain-label { color: var(--muted); font-size: 11px; }

        /* Tabla de Prioridades */
        .table-wrap { overflow-x: auto; }
        .records-table { width: 100%; border-collapse: collapse; text-align: left; }
        .records-table th { height: 36px; padding: 0 14px; border-bottom: 1px solid var(--border); color: #64748b; background: #f8fafc; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
        .records-table td { padding: 10px 14px; border-bottom: 1px solid #edf0f4; color: #475569; font-size: 12px; vertical-align: middle; }
        .records-table tbody tr:last-child td { border-bottom: 0; }
        .records-table tr:hover td { background: #fafbfc; }

        .project-code-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #1e293b;
            color: #ffffff;
            font-weight: 700;
            font-size: 10px;
            border-radius: 4px;
            margin-right: 5px;
            vertical-align: middle;
        }
        .project-title-dash {
            font-weight: 700;
            color: #1e293b;
            text-decoration: none;
            font-size: 12px;
            line-height: 1.35;
        }
        .project-title-dash:hover {
            color: var(--maroon);
            text-decoration: underline;
        }
        .student-subline {
            font-size: 11px;
            color: #475569;
            margin-top: 3px;
        }
        .student-subline strong {
            color: #0f172a;
        }

        /* Badges de Semáforo */
        .badge { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; white-space: nowrap; gap: 4px; }
        .badge-danger { color: #991b32; background: #fee2e2; }
        .badge-warning { color: #92400e; background: #fef3c7; }
        .badge-success { color: #166534; background: #dcfce7; }
        .badge-info { color: #1e40af; background: #dbeafe; }

        /* Botones de Acción de Alto Contraste */
        .btn-action-urgente {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #991b32 !important;
            color: #ffffff !important;
            border: 1px solid #991b32 !important;
            font-weight: 650 !important;
            font-size: 11px;
            padding: 5px 11px;
            border-radius: 6px;
            text-decoration: none !important;
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(153, 27, 50, 0.25);
            transition: all 0.15s ease;
        }
        .btn-action-urgente:hover {
            background-color: #801328 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(153, 27, 50, 0.35);
        }
        .btn-action-urgente svg { stroke: #ffffff !important; }

        .btn-action-tramite {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #fffbeb !important;
            color: #b45309 !important;
            border: 1px solid #fde68a !important;
            font-weight: 650 !important;
            font-size: 11px;
            padding: 5px 11px;
            border-radius: 6px;
            text-decoration: none !important;
            white-space: nowrap;
            transition: all 0.15s ease;
        }
        .btn-action-tramite:hover {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fcd34d !important;
            transform: translateY(-1px);
        }
        .btn-action-tramite svg { stroke: #b45309 !important; }

        .btn-action-normal {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #f8fafc !important;
            color: #334155 !important;
            border: 1px solid #cbd5e1 !important;
            font-weight: 650 !important;
            font-size: 11px;
            padding: 5px 11px;
            border-radius: 6px;
            text-decoration: none !important;
            white-space: nowrap;
            transition: all 0.15s ease;
        }
        .btn-action-normal:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #94a3b8 !important;
            transform: translateY(-1px);
        }
        .btn-action-normal svg { stroke: #334155 !important; }

        /* Panel Lateral */
        .alert-list { margin: 0; padding: 0; list-style: none; }
        .alert-list li { display: flex; flex-direction: column; gap: 3px; padding: 10px 16px; border-bottom: 1px solid #edf0f4; text-decoration: none; }
        .alert-list li:last-child { border-bottom: 0; }
        .alert-list a { text-decoration: none; color: inherit; display: block; }
        .alert-list a:hover strong { color: var(--maroon); text-decoration: underline; }
        .alert-list strong { color: #dc2626; font-size: 11px; font-weight: 700; }
        .alert-list span { color: var(--muted); font-size: 11px; }

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

        .quick-actions { padding: 14px 12px 12px; }
        .quick-actions h2 { margin: 0 4px 10px; color: var(--ink); font-size: 13px; font-weight: 700; }
        .quick-action { display: flex; width: 100%; min-height: 38px; align-items: center; gap: 10px; margin-top: 6px; padding: 0 12px; border: 1px solid var(--border); border-radius: 7px; color: var(--ink); background: var(--white); font-size: 12px; font-weight: 600; text-align: left; text-decoration: none; transition: all 0.15s ease; }
        .quick-action:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateX(2px); }
        .quick-action svg { width: 16px; height: 16px; color: var(--maroon); flex-shrink: 0; }
        .quick-action.primary { background: #fff5f5; border-color: #fecaca; color: #991b32; }
        .quick-action.primary:hover { background: #fee2e2; border-color: #fca5a5; }

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
            .triaje-cards-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
            .card-count-big { font-size: 24px; }
        }
        @media (max-width: 900px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .side-column { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; }
            .quick-actions { grid-column: 1 / -1; }
            .topbar { align-items: flex-start; flex-direction: column; gap: 10px; padding: 0 10px; }
            .topbar-tools { width: 100%; flex-wrap: wrap; }
            .search-form { min-width: 0; flex: 1; }
            .search-control { width: auto; flex: 1; }
            .triaje-cards-grid { grid-template-columns: 1fr; }
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
            .triaje-cards-grid { grid-template-columns: 1fr; gap: 10px; }
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
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                            <input type="search" name="buscar" value="{{ $busqueda }}" placeholder="Escribe carnet, estudiante, título o #..." maxlength="100" aria-label="Buscar por carnet, estudiante o proyecto">
                        </label>
                        <button class="search-submit" type="submit">Buscar</button>
                    </form>
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
                    <div>
                        <p style="font-size:13px;color:#1e293b;font-weight:600;margin:0 0 2px;">Centro de Comando y Triaje de Inmediatez</p>
                        <p style="margin:0;font-size:12px;color:var(--muted);">Identifica los expedientes urgentes y trámites que requieren resolución durante la jornada de hoy.</p>
                    </div>
                    <div class="current-time">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg>
                        {{ $fechaActual }}
                    </div>
                </div>

                @if ($errors->has('buscar') || $errors->has('mes') || $errors->has('anio'))
                    <p class="validation-message" role="alert">Revisa los filtros ingresados. La búsqueda admite hasta 100 caracteres.</p>
                @endif

                <!-- ============================================== -->
                <!-- 1. LAS 3 TARJETAS DE TRIAJE DE IMPACTO INMEDIATO -->
                <!-- ============================================== -->
                <section class="triaje-cards-grid" aria-label="Triaje de Impacto Inmediato">
                    <!-- Tarjeta 1: Plazos Críticos (Rojo) -->
                    <a href="{{ route('ups.proyectos.index', ['triaje' => 'urgentes']) }}" class="triaje-action-card card-urgente" title="Ver proyectos con plazos normativos críticos">
                        <div>
                            <div class="card-top-row">
                                <div class="card-icon-badge">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m10.29 3.86-8.29 14.36A2 2 0 0 0 3.73 21h16.54a2 2 0 0 0 1.73-2.78L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                </div>
                                <span class="card-count-big">{{ $triaje['plazosCriticos'] }}</span>
                            </div>
                            <div class="card-title-text">Plazos Críticos</div>
                            <div class="card-desc-text">Expedientes que superaron 18 meses de ejecución o 3 meses sin radicar memoria final.</div>
                        </div>
                        <div class="card-action-link">
                            <span>Ver casos urgentes</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    <!-- Tarjeta 2: Auditorías Pendientes (Amarillo/Ámbar) -->
                    <a href="{{ route('ups.proyectos.index', ['triaje' => 'tramite']) }}" class="triaje-action-card card-tramite" title="Ver proyectos con memorias en revisión o trámite de certificación">
                        <div>
                            <div class="card-top-row">
                                <div class="card-icon-badge">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </div>
                                <span class="card-count-big">{{ $triaje['auditoriasPendientes'] }}</span>
                            </div>
                            <div class="card-title-text">Auditorías Pendientes</div>
                            <div class="card-desc-text">Memorias radicadas en espera de revisión documental, cotejo de sellos y firmas.</div>
                        </div>
                        <div class="card-action-link">
                            <span>Iniciar revisión</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </a>

                    <!-- Tarjeta 3: Entregas Pendientes (Azul) -->
                    <a href="{{ route('ups.proyectos.index', ['estado' => 'lista']) }}" class="triaje-action-card card-entregas" title="Ver certificaciones emitidas listas para entrega">
                        <div>
                            <div class="card-top-row">
                                <div class="card-icon-badge">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h9l5 5v8H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M14 3v6h6M8 11h8M8 14h5M9 16l-1 6 4-2 4 2-1-6"/></svg>
                                </div>
                                <span class="card-count-big">{{ $triaje['entregasPendientes'] }}</span>
                            </div>
                            <div class="card-title-text">Entregas Pendientes</div>
                            <div class="card-desc-text">Certificaciones oficiales autorizadas listas para firma de Jefatura y entrega a estudiantes.</div>
                        </div>
                        <div class="card-action-link">
                            <span>Gestionar entregas</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                </section>

                <div class="dashboard-grid">
                    <!-- ============================================== -->
                    <!-- 2. TABLA: PRIORIDADES QUE DEMANDAN ATENCIÓN HOY-->
                    <!-- ============================================== -->
                    <div class="main-column">
                        <section class="panel" aria-labelledby="prioridades-heading">
                            <div class="panel-heading">
                                <div>
                                    <h2 id="prioridades-heading">Prioridades del Día (Resolución Inmediata)</h2>
                                    <span class="plain-label">Expedientes ordenados por severidad normativa que requieren atención de la oficina.</span>
                                </div>
                                <a href="{{ route('ups.proyectos.index') }}" title="Ir al módulo expandido de proyectos">
                                    <span>Ver catálogo completo (500+)</span>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                            <div class="table-wrap">
                                <table class="records-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width:240px">Expediente y Estudiante</th>
                                            <th>Área Temática</th>
                                            <th style="min-width:160px">Diagnóstico de Plazo</th>
                                            <th style="width:130px;text-align:right">Acción Hoy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($casosPrioritarios as $proyecto)
                                            @php
                                                $semaforo = $proyecto->semaforoOperativo();
                                                $primerEst = $proyecto->estudiantes->first();
                                            @endphp
                                            <tr>
                                                <!-- Columna 1: Expediente & Estudiante -->
                                                <td>
                                                    <div>
                                                        <span class="project-code-badge">#{{ $proyecto->numero_proyecto ?? $proyecto->id_proyecto }}</span>
                                                        <a class="project-title-dash" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                                            {{ \Illuminate\Support\Str::limit($proyecto->titulo, 45) }}
                                                        </a>
                                                    </div>
                                                    @if($primerEst)
                                                        <div class="student-subline">
                                                            <strong>{{ $primerEst->due }}</strong> · {{ \Illuminate\Support\Str::limit($primerEst->nombre_completo, 24) }}
                                                            @if($proyecto->estudiantes->count() > 1)
                                                                <small style="color:var(--muted)"> (+{{ $proyecto->estudiantes->count() - 1 }})</small>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="student-subline" style="color:var(--muted);font-style:italic">Sin estudiante asignado</div>
                                                    @endif
                                                </td>

                                                <!-- Columna 2: Área Temática -->
                                                <td>
                                                    <span style="font-weight:600;color:#334155;">{{ $proyecto->areaConocimiento?->nombre ?? 'Sin área' }}</span>
                                                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">Tutor: {{ $proyecto->tutor ?? 'No asignado' }}</div>
                                                </td>

                                                <!-- Columna 3: Semáforo y Plazo Humano -->
                                                <td>
                                                    <div style="display:flex;flex-direction:column;gap:3px;">
                                                        <div>
                                                            <span class="badge {{ $semaforo['badge_class'] }}">
                                                                @if($semaforo['nivel'] === 'urgente')
                                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                                                @elseif($semaforo['nivel'] === 'tramite')
                                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                                @else
                                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                                                @endif
                                                                {{ $semaforo['etiqueta'] }}
                                                            </span>
                                                        </div>
                                                        <span style="font-size:11px;font-weight:600;{{ $semaforo['nivel'] === 'urgente' ? 'color:#b91c1c' : ($semaforo['nivel'] === 'tramite' ? 'color:#b45309' : 'color:#15803d') }}">
                                                            {{ $semaforo['plazo_humano'] }}
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- Columna 4: Botón de Acción Inmediata de Alto Contraste -->
                                                <td style="text-align:right">
                                                    @if($semaforo['nivel'] === 'urgente')
                                                        <a class="btn-action-urgente" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                                            <span>{{ $semaforo['siguiente_accion'] }}</span>
                                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                                        </a>
                                                    @elseif($semaforo['nivel'] === 'tramite')
                                                        <a class="btn-action-tramite" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                                            <span>{{ $semaforo['siguiente_accion'] }}</span>
                                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                                        </a>
                                                    @else
                                                        <a class="btn-action-normal" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">
                                                            <span>{{ $semaforo['siguiente_accion'] }}</span>
                                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" style="padding:32px 16px;text-align:center;">
                                                    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                                                        <div style="width:40px;height:40px;border-radius:50%;background:#eafaf1;display:flex;align-items:center;justify-content:center;color:#16a34a;">
                                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                        </div>
                                                        <strong style="color:#1e293b;font-size:13px;">¡Todo al día en la oficina!</strong>
                                                        <span style="color:var(--muted);font-size:12px;max-width:380px;">
                                                            {{ $busqueda ? 'No se encontraron expedientes con la búsqueda «'.$busqueda.'».' : 'No hay proyectos con plazos normativos vencidos ni trámites pendientes para hoy.' }}
                                                        </span>
                                                        <a href="{{ route('ups.proyectos.index') }}" style="margin-top:6px;font-size:12px;color:var(--maroon);font-weight:650;text-decoration:none;">
                                                            Ver listado general de proyectos &rarr;
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <!-- ============================================== -->
                    <!-- 3. COLUMNA LATERAL: ALERTAS & ACCESOS RÁPIDOS  -->
                    <!-- ============================================== -->
                    <aside class="side-column" aria-label="Alertas y accesos rápidos">
                        <!-- Alertas de Plazo Urgente -->
                        <section class="panel" id="alertas" aria-labelledby="alerts-heading">
                            <div class="panel-heading">
                                <h2 id="alerts-heading">Alertas de Vencimiento</h2>
                                <span class="badge badge-danger">{{ $alertas->count() }} activas</span>
                            </div>
                            <ul class="alert-list">
                                @forelse ($alertas as $alerta)
                                    @php($sem = $alerta->semaforoOperativo())
                                    <li>
                                        <a href="{{ route('ups.proyectos.show', $alerta->id_proyecto) }}">
                                            <strong>#{{ $alerta->numero_proyecto ?? $alerta->id_proyecto }} · {{ \Illuminate\Support\Str::limit($alerta->titulo, 30) }}</strong>
                                            <span>{{ $sem['plazo_humano'] }} ({{ $sem['etiqueta'] }})</span>
                                        </a>
                                    </li>
                                @empty
                                    <li style="padding:16px;text-align:center;color:var(--muted);font-size:11px;">
                                        No hay alertas de plazo vencido activas en este momento.
                                    </li>
                                @endforelse
                            </ul>
                            <div class="panel-heading" style="justify-content:flex-end;padding-top:8px;padding-bottom:10px;border-top:1px solid #edf0f4;border-bottom:0;">
                                <a href="{{ route('ups.proyectos.index', ['triaje' => 'urgentes']) }}">Ver todas las alertas &rarr;</a>
                            </div>
                        </section>

                        <!-- Calendario de Plazos -->
                        <section class="panel calendar-panel" aria-labelledby="calendar-heading">
                            <div class="panel-heading calendar-head">
                                <h2 id="calendar-heading">Calendario de Plazos</h2>
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

                        <!-- Acciones Rápidas -->
                        <section class="panel quick-actions" aria-labelledby="quick-actions-heading">
                            <h2 id="quick-actions-heading">Acciones Rápidas</h2>
                            
                            <a class="quick-action primary" href="{{ route('ups.proyectos.create') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                <span>Registrar nuevo proyecto</span>
                            </a>

                            <a class="quick-action" href="{{ route('ups.bandeja.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v14H4zM4 13h4l2 3h4l2-3h4"/></svg>
                                <span>Bandeja de auditoría UPS</span>
                            </a>

                            <a class="quick-action" href="{{ route('ups.proyectos.index') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 8 9 5 9-5M3 8v9l9 5 9-5V8M12 13v9"/></svg>
                                <span>Catálogo de proyectos (500+)</span>
                            </a>

                            <a class="quick-action" href="{{ route('perfil.edit') }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>Mi perfil institucional</span>
                            </a>
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
                    // Keep current page state even when storage is unavailable.
                }
            });
        })();
    </script>
</body>
</html>
