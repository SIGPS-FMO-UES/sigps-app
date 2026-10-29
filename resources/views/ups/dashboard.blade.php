<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f5f6f7">
    <title>Panel UPS — SIGPS</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;

            /* Paleta institucional */
            --ink: #1f2937;
            --muted: #64748b;
            --soft: #94a3b8;
            --line: #e2e8f0;
            --paper: #ffffff;
            --canvas: #f8fafc;
            --navy: #0f172a;
            --wine: #8e2037;
            --wine-dark: #74182d;
            --sidebar-active: #991b32;

            /* Escala fluida y responsiva de dimensiones */
            --sidebar-width: 226px;
            --side-column-width: clamp(265px, 22vw, 340px);
            --topbar-height: clamp(52px, 5vh, 64px);
            --workbench-py: clamp(12px, 1.6vh, 20px);
            --workbench-px: clamp(14px, 1.8vw, 24px);

            /* Escala tipográfica fluida: clamp(mínimo-laptop, preferido-vw, máximo-24pulgadas) */
            --font-2xs: clamp(10px, 0.65rem + 0.12vw, 11.5px);
            --font-xs: clamp(11px, 0.72rem + 0.16vw, 12.5px);
            --font-sm: clamp(12px, 0.78rem + 0.22vw, 13.5px);
            --font-base: clamp(13px, 0.84rem + 0.28vw, 15px);
            --font-md: clamp(14px, 0.92rem + 0.35vw, 16.5px);
            --font-lg: clamp(16px, 1.05rem + 0.5vw, 19px);
            --font-xl: clamp(19px, 1.25rem + 0.7vw, 25px);
            --font-2xl: clamp(22px, 1.45rem + 1vw, 32px);
            --font-stat: clamp(26px, 1.7rem + 1.2vw, 40px);
        }
        html.sidebar-collapsed { --sidebar-width: 68px; }
        * { box-sizing: border-box; }
        body { min-width: 320px; min-height: 100vh; margin: 0; color: var(--ink); background: var(--canvas); font-size: var(--font-base); line-height: 1.5; }
        a { color: inherit; }
        button, input { font: inherit; }
        button:focus-visible, a:focus-visible, input:focus-visible, summary:focus-visible { outline: 3px solid var(--wine); outline-offset: 3px; }
        .app-shell { min-height: 100vh; }

        .sidebar { position: fixed; z-index: 10; inset: 0 auto 0 0; display: flex; width: var(--sidebar-width); flex-direction: column; padding: clamp(14px, 2vh, 20px) 12px; color: var(--ink); background: #fff; border-right: 1px solid var(--line); transition: width .2s ease, padding .2s ease, background-color .2s ease; }
        .sidebar-header { display: flex; width: 100%; align-items: center; }
        .brand-link { display: flex; min-width: 0; min-height: 48px; flex: 1; align-items: center; gap: 10px; padding: 0 4px; color: #fff; text-decoration: none; }
        .brand-link img { display: block; width: clamp(36px, 3.2vw, 42px); height: clamp(36px, 3.2vw, 42px); object-fit: contain; background: #fff; }
        .brand-copy { color: var(--ink); font-size: var(--font-xs); font-weight: 700; letter-spacing: .05em; line-height: 1.35; }
        .sidebar-toggle { position: absolute; z-index: 12; top: 25px; right: -28px; display: grid; width: 28px; height: 32px; flex: 0 0 28px; place-items: center; border: 1px solid var(--line); border-radius: 0 7px 7px 0; color: var(--sidebar-active); background: #fff; box-shadow: 2px 2px 6px #1725361a; cursor: pointer; transition: color .2s ease, background-color .2s ease, border-color .2s ease; }
        .sidebar-toggle:hover { color: #fff; background: var(--sidebar-active); border-color: var(--sidebar-active); }
        .sidebar-toggle svg { width: 16px; height: 16px; transition: transform .2s ease; }
        .side-nav { display: flex; width: 100%; min-height: 0; flex-direction: column; gap: 6px; margin-top: clamp(14px, 2vh, 22px); overflow-y: auto; scrollbar-width: thin; }
        .nav-item { display: flex; width: 100%; min-height: clamp(38px, 4vh, 44px); align-items: center; gap: 14px; padding: 0 10px; border: 0; border-radius: 7px; color: #5a6677; background: transparent; text-align: left; text-decoration: none; }
        .nav-item svg { width: clamp(18px, 1.4vw, 20px); height: clamp(18px, 1.4vw, 20px); flex: 0 0 auto; }
        .nav-item span { font-size: var(--font-sm); font-weight: 500; }
        .nav-item:hover, .nav-item.active { color: #fff; background: var(--sidebar-active); }
        .nav-item:hover svg, .nav-item.active svg { color: #fff; }
        .nav-item[aria-disabled="true"] { cursor: not-allowed; opacity: .75; }
        .nav-group { width: 100%; flex: 0 0 auto; }
        .nav-group-toggle { display: flex; min-height: clamp(38px, 4vh, 44px); align-items: center; gap: 14px; padding: 0 10px; border-radius: 7px; color: #5a6677; cursor: pointer; list-style: none; }
        .nav-group-toggle::-webkit-details-marker { display: none; }
        .nav-group-toggle::after { width: 7px; height: 7px; margin-left: auto; border-right: 1.5px solid currentColor; border-bottom: 1.5px solid currentColor; content: ""; transform: rotate(45deg) translateY(-2px); }
        .nav-group[open] > .nav-group-toggle::after { transform: rotate(225deg) translate(-1px, -1px); }
        .nav-group-toggle:hover, .nav-group-toggle.active, .nav-group[open] > .nav-group-toggle { color: #fff; background: var(--sidebar-active); }
        .nav-group-toggle svg { width: clamp(18px, 1.4vw, 20px); height: clamp(18px, 1.4vw, 20px); flex: 0 0 auto; }
        .nav-group-toggle span { font-size: var(--font-sm); font-weight: 500; }
        .nav-subnav { display: grid; gap: 3px; margin: 3px 0 4px 19px; padding-left: 9px; border-left: 1px solid var(--line); }
        .nav-child { display: flex; min-height: clamp(30px, 3.2vh, 34px); align-items: center; padding: 0 9px; border-radius: 7px; color: #5a6677; font-size: var(--font-xs); text-decoration: none; }
        .nav-child:hover, .nav-child.active { color: #fff; background: var(--sidebar-active); }
        .sidebar-spacer { flex: 1; }
        .user-avatar { display: flex; width: 100%; min-height: clamp(40px, 4.4vh, 46px); align-items: center; gap: 10px; margin: 0 0 6px; padding: 0 9px; border-top: 1px solid var(--line); color: var(--ink); font-size: var(--font-xs); font-weight: 600; }
        .user-avatar::before { display: grid; width: clamp(26px, 2.5vw, 30px); height: clamp(26px, 2.5vw, 30px); flex: 0 0 auto; place-items: center; border-radius: 50%; color: var(--navy); background: #dce3eb; content: attr(data-initials); font-size: var(--font-2xs); }
        .user-name { min-width: 0; overflow: hidden; color: var(--ink); font-size: var(--font-xs); line-height: 1.4; text-overflow: ellipsis; }
        .sidebar-logout { margin-left: auto; padding: 5px 8px; border: 1px solid var(--line); border-radius: 6px; color: var(--sidebar-active); background: #fff; font-size: var(--font-2xs); font-weight: 600; cursor: pointer; }
        .sidebar-logout:hover { border-color: var(--sidebar-active); color: #fff; background: var(--sidebar-active); }
        html.sidebar-collapsed .sidebar { padding-right: 8px; padding-left: 8px; border-right-color: #e4e7ec; background: #fff; }
        html.sidebar-collapsed .brand-link { justify-content: center; padding: 0; }
        html.sidebar-collapsed .brand-link img { width: 36px; height: 36px; background: #fff; }
        html.sidebar-collapsed .brand-copy,
        html.sidebar-collapsed .nav-item span,
        html.sidebar-collapsed .nav-group-toggle span,
        html.sidebar-collapsed .user-name { display: none; }
        html.sidebar-collapsed .sidebar-toggle { color: var(--sidebar-active); border-color: #e4e7ec; background: #fff; }
        html.sidebar-collapsed .sidebar-toggle:hover { background: #fbecef; }
        html.sidebar-collapsed .sidebar-toggle svg { transform: rotate(180deg); }
        html.sidebar-collapsed .side-nav { align-items: center; }
        html.sidebar-collapsed .nav-item { justify-content: center; padding: 0; color: #7d8795; }
        html.sidebar-collapsed .nav-item:hover, html.sidebar-collapsed .nav-item.active { color: #fff; background: var(--sidebar-active); }
        html.sidebar-collapsed .nav-item:hover svg, html.sidebar-collapsed .nav-item.active svg { color: #fff; }
        html.sidebar-collapsed .nav-group { width: 100%; }
        html.sidebar-collapsed .nav-group-toggle { justify-content: center; padding: 0; color: #7d8795; }
        html.sidebar-collapsed .nav-group-toggle::after, html.sidebar-collapsed .nav-subnav { display: none; }
        html.sidebar-collapsed .nav-group-toggle.active { color: #fff; background: var(--sidebar-active); }
        html.sidebar-collapsed .user-avatar { justify-content: center; padding: 0; border-color: #e4e7ec; }
        html.sidebar-collapsed .sidebar-logout { display: none; }

        .workspace { width: calc(100% - var(--sidebar-width)); min-height: 100vh; margin-left: var(--sidebar-width); transition: width .18s ease, margin-left .18s ease; }
        .topbar { display: flex; min-height: var(--topbar-height); align-items: center; justify-content: space-between; gap: 16px; padding: 8px clamp(16px, 2.5vw, 36px); border-bottom: 1px solid var(--line); background: #fff; }
        .topbar-title { margin: 0; color: var(--navy); font-size: var(--font-base); font-weight: 750; letter-spacing: -.01em; text-wrap: balance; }
        .topbar-tools { display: flex; align-items: center; gap: 10px; }
        .search-form { display: flex; align-items: center; gap: 6px; }
        .search-control { display: flex; width: clamp(180px, 22vw, 310px); height: clamp(34px, 3.8vh, 38px); align-items: center; gap: 8px; padding: 0 10px; border: 1px solid #d5d9df; border-radius: 8px; background: #fff; }
        .search-control svg { width: 15px; height: 15px; flex: 0 0 auto; color: var(--soft); }
        .search-control input { width: 100%; min-width: 0; border: 0; outline: 0; color: var(--ink); background: transparent; font-size: var(--font-sm); }
        .search-control input::placeholder { color: #7b8490; opacity: 1; }
        .search-submit { height: clamp(34px, 3.8vh, 38px); padding: 0 clamp(10px, 1vw, 14px); border: 0; border-radius: 7px; color: #fff; background: var(--wine); font-size: var(--font-xs); font-weight: 700; cursor: pointer; transition: background .15s ease; }
        .search-submit:hover { background: var(--wine-dark); }
        .notification-menu, .account-menu { position: relative; }
        .notification-menu > summary { position: relative; cursor: pointer; list-style: none; }
        .notification-menu > summary::-webkit-details-marker, .account-menu summary::-webkit-details-marker { display: none; }
        .utility-button { display: grid; width: clamp(34px, 3.8vh, 38px); height: clamp(34px, 3.8vh, 38px); place-items: center; border: 1px solid #d5d9df; border-radius: 8px; color: #586474; background: #fff; }
        .utility-button svg { width: 17px; height: 17px; }
        .notification-badge { position: absolute; top: -5px; right: -5px; display: grid; min-width: 17px; height: 17px; padding: 0 4px; place-items: center; border: 2px solid #faf9f6; border-radius: 999px; color: #fff; background: #b33a48; font-size: var(--font-2xs); font-weight: 800; }
        .notification-popover, .account-menu-popover { position: absolute; z-index: 50; top: calc(100% + 9px); right: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; background: #fff; box-shadow: 0 16px 36px #17253626; }
        .notification-popover { width: min(350px, calc(100vw - 24px)); }
        .notification-popover-head, .notification-popover-foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 14px; }
        .notification-popover-head { border-bottom: 1px solid var(--line); font-size: var(--font-sm); font-weight: 700; }
        .notification-popover-head small { color: var(--muted); font-size: var(--font-xs); font-weight: normal; }
        .notification-list { max-height: 320px; overflow-y: auto; }
        .notification-form { margin: 0; }
        .notification-entry { display: grid; width: 100%; grid-template-columns: 8px minmax(0,1fr); gap: 9px; padding: 11px 13px; border: 0; border-bottom: 1px solid #f0efeb; color: var(--ink); background: #fff; text-align: left; cursor: pointer; }
        .notification-entry:hover, .notification-entry.unread { background: #fbf8f5; }
        .notification-dot { width: 7px; height: 7px; margin-top: 4px; border-radius: 50%; background: transparent; }
        .notification-entry.unread .notification-dot { background: var(--wine); }
        .notification-copy { display: grid; min-width: 0; gap: 3px; }
        .notification-copy strong { font-size: var(--font-xs); }
        .notification-copy span { overflow: hidden; color: var(--muted); font-size: var(--font-xs); text-overflow: ellipsis; white-space: nowrap; }
        .notification-copy time { color: var(--soft); font-size: var(--font-2xs); }
        .notification-empty { padding: 20px; color: var(--muted); font-size: var(--font-xs); text-align: center; }
        .notification-popover-foot { justify-content: flex-end; border-top: 1px solid var(--line); }
        .notification-popover-foot a { color: var(--wine); font-size: var(--font-xs); font-weight: 700; text-decoration: none; }
        .notification-mark-all { margin-right: auto; padding: 0; border: 0; color: var(--muted); background: transparent; font-size: var(--font-xs); cursor: pointer; }
        .account-menu summary { display: flex; min-height: 36px; align-items: center; gap: 8px; cursor: pointer; list-style: none; }
        .account-menu summary::after { width: 6px; height: 6px; border-right: 1.5px solid currentColor; border-bottom: 1.5px solid currentColor; content: ""; transform: rotate(45deg) translateY(-2px); }
        .account-menu[open] summary::after { transform: rotate(225deg) translate(-1px, -1px); }
        .user-summary { display: grid; gap: 2px; white-space: nowrap; }
        .user-summary strong { color: var(--navy); font-size: var(--font-xs); font-weight: 700; }
        .user-summary span { color: var(--muted); font-size: var(--font-2xs); }
        .account-menu-popover { min-width: 150px; padding: 5px; }
        .account-menu-popover form { margin: 0; }
        .account-menu-popover button { width: 100%; padding: 9px; border: 0; border-radius: 6px; color: var(--ink); background: transparent; text-align: left; cursor: pointer; }
        .account-menu-popover button:hover { color: var(--wine); background: #f8f3ef; }

        .content { display: grid; max-width: 1720px; gap: clamp(12px, 1.5vh, 20px); margin: 0 auto; padding: clamp(14px, 1.8vh, 24px) clamp(16px, 2.5vw, 38px) clamp(20px, 2.5vh, 36px); }
        .workbench { display: grid; gap: 0; padding: var(--workbench-py) var(--workbench-px) 0; border: 1px solid var(--line); border-radius: 10px; background: #fff; box-shadow: 0 1px 3px rgba(15,23,42,0.03); }
        .page-intro { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; }
        .page-intro h2 { margin: 0; color: #1e293b; font-size: var(--font-2xl); font-weight: 700; letter-spacing: -.03em; line-height: 1.2; text-wrap: balance; }
        .page-date { display: flex; align-items: center; gap: 6px; margin-top: clamp(4px, 0.6vh, 8px); color: var(--muted); font-size: var(--font-xs); }
        .page-date svg { width: 14px; height: 14px; color: var(--muted); }
        .primary-action { display: inline-flex; min-height: clamp(36px, 4.2vh, 42px); flex: 0 0 auto; align-items: center; justify-content: center; gap: 8px; padding: 0 clamp(12px, 1.2vw, 18px); border: 1px solid var(--wine); border-radius: 7px; color: #fff; background: var(--wine); font-size: var(--font-sm); font-weight: 750; text-decoration: none; transition: background .16s ease; }
        .primary-action:hover { border-color: var(--wine-dark); background: var(--wine-dark); }
        .primary-action svg { width: 17px; height: 17px; }
        .shortcut-row { display: flex; min-width: 0; align-items: center; gap: clamp(6px, 0.8vw, 10px); margin-top: clamp(10px, 1.4vh, 16px); padding: clamp(8px, 1vh, 12px) 0 clamp(8px, 1vh, 12px); border-top: 1px solid var(--line); }
        .shortcut-link { display: inline-flex; min-height: clamp(34px, 3.8vh, 40px); min-width: 0; align-items: center; gap: 8px; padding: 0 clamp(10px, 1vw, 14px); border: 1px solid #d5d9df; border-radius: 7px; color: #303c4b; background: #fff; font-size: var(--font-xs); font-weight: 650; text-decoration: none; transition: border-color .16s ease, color .16s ease, background .16s ease; }
        .shortcut-link:hover { border-color: #c99da6; color: var(--wine); background: #fbf8f9; }
        .shortcut-link svg { width: 16px; height: 16px; color: var(--wine); }
        .summary-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; background: #fff; box-shadow: 0 1px 3px rgba(15,23,42,0.03); }
        .summary-card { position: relative; display: grid; min-height: clamp(64px, 7.5vh, 82px); grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: 4px 12px; padding: clamp(10px, 1.2vh, 14px) clamp(12px, 1.4vw, 18px); overflow: hidden; border: 0; border-radius: 0; color: var(--ink); background: #fff; text-decoration: none; transition: background .16s ease; }
        .summary-card + .summary-card { border-left: 1px solid var(--line); }
        .summary-card::before { position: absolute; inset: 0 auto 0 0; width: 3px; background: var(--metric-line); content: ""; }
        .summary-card:hover { background: #f8fafc; }
        .summary-title { color: var(--muted); font-size: var(--font-xs); font-weight: 650; }
        .summary-value { grid-column: 2; grid-row: 1 / span 2; color: var(--metric); font-size: var(--font-stat); font-weight: 800; letter-spacing: -.04em; line-height: 1; }
        .summary-action { display: inline-flex; align-items: center; gap: 4px; color: var(--metric); font-size: var(--font-xs); font-weight: 750; }
        .summary-action svg { width: 11px; height: 11px; }
        .summary-urgent { --metric: var(--wine); --metric-line: var(--wine); }
        .summary-review, .summary-delivery { --metric: #394657; --metric-line: #cbd5e1; }

        .dashboard-layout { display: grid; min-width: 0; grid-template-columns: minmax(0, 1fr) var(--side-column-width); align-items: start; gap: clamp(14px, 1.5vw, 20px); }
        .main-column { display: grid; min-width: 0; gap: clamp(14px, 1.5vh, 18px); container-type: inline-size; }
        .side-column { display: grid; min-width: 0; gap: clamp(14px, 1.5vh, 18px); }
        .panel { min-width: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; background: var(--paper); box-shadow: 0 2px 8px rgba(15,23,42,0.04); }
        .panel-heading { display: flex; min-height: clamp(44px, 5vh, 52px); align-items: center; justify-content: space-between; gap: 10px; padding: clamp(9px, 1vh, 13px) clamp(12px, 1.2vw, 16px); border-bottom: 1px solid var(--line); }
        .panel-heading h3 { margin: 0; color: var(--navy); font-size: var(--font-md); font-weight: 750; letter-spacing: -.01em; }
        .panel-heading p { margin: 3px 0 0; color: var(--muted); font-size: var(--font-xs); text-wrap: pretty; }
        .panel-heading a { display: inline-flex; align-items: center; gap: 5px; color: var(--wine); font-size: var(--font-xs); font-weight: 750; text-decoration: none; white-space: nowrap; }
        .panel-heading a:hover { text-decoration: underline; }
        .panel-heading a svg { width: 12px; height: 12px; }
        .panel-count { display: inline-flex; min-height: 24px; align-items: center; padding: 0 8px; border-radius: 5px; color: #56616d; background: #f1f5f9; font-size: var(--font-xs); font-weight: 700; white-space: nowrap; }
        .table-scroll { max-height: clamp(280px, 48vh, 580px); overflow: auto; overscroll-behavior: contain; scrollbar-color: #cbd5e1 #f8fafc; scrollbar-width: thin; }
        .table-scroll:focus-visible { outline: 2px solid var(--wine); outline-offset: -2px; }
        .records-table { width: 100%; min-width: 500px; table-layout: fixed; border-collapse: collapse; text-align: left; }
        .records-table th { position: sticky; top: 0; z-index: 1; height: clamp(30px, 3.6vh, 36px); padding: 0 clamp(10px, 1vw, 14px); border-bottom: 1px solid var(--line); color: #64748b; background: #f8fafc; font-size: var(--font-xs); font-weight: 750; letter-spacing: .06em; text-transform: uppercase; }
        .records-table th:first-child { width: 49%; }
        .records-table th:nth-child(2) { width: 23%; }
        .records-table th:last-child { width: 28%; }
        .records-table td { padding: clamp(9px, 1.2vh, 13px) clamp(10px, 1vw, 14px); border-bottom: 1px solid #f1f5f9; color: #334155; font-size: var(--font-base); vertical-align: middle; }
        .records-table tbody tr:last-child td { border-bottom: 0; }
        .records-table tbody tr:hover td { background: #fafaf9; }
        .project-summary { display: grid; min-width: 0; gap: 4px; }
        .project-title-line { display: flex; min-width: 0; align-items: center; gap: 6px; }
        .project-code { flex: 0 0 auto; padding: 2px 6px; border-radius: 4px; color: #fff; background: #29384a; font-size: var(--font-2xs); font-weight: 800; letter-spacing: .02em; }
        .demo-marker { flex: 0 0 auto; color: #94a3b8; font-size: var(--font-2xs); font-weight: 750; letter-spacing: .04em; }
        .project-title { min-width: 0; overflow: hidden; color: #1e293b; font-size: var(--font-base); font-weight: 650; text-overflow: ellipsis; text-decoration: none; white-space: nowrap; }
        .project-title:hover { color: var(--wine); text-decoration: underline; }
        .student-subline { overflow: hidden; color: #64748b; font-size: var(--font-xs); text-overflow: ellipsis; white-space: nowrap; }
        .area-name { display: block; overflow: hidden; color: #475569; font-size: var(--font-sm); text-overflow: ellipsis; white-space: nowrap; }
        .priority-cell { display: flex; min-width: 0; align-items: center; gap: 8px; }
        .status-badge { display: inline-flex; min-height: clamp(22px, 2.8vh, 26px); flex: 0 0 auto; align-items: center; gap: 4px; padding: 0 clamp(7px, 0.7vw, 10px); border: 1px solid #e2e8f0; border-radius: 999px; color: #475569; background: #f8fafc; font-size: var(--font-2xs); font-weight: 750; white-space: nowrap; }
        .status-badge.urgent { border-color: #fecdd3; color: #be123c; background: #fff1f2; }
        .status-badge.review, .status-badge.ready { border-color: #e2e8f0; color: #475569; background: #f1f5f9; }
        .priority-action { display: inline-flex; min-height: clamp(26px, 3.2vh, 30px); flex: 0 0 auto; align-items: center; gap: 4px; padding: 0 clamp(6px, 0.7vw, 10px); border-radius: 6px; color: var(--wine); font-size: var(--font-xs); font-weight: 750; text-decoration: none; transition: background .14s ease; }
        .priority-action:hover { background: #fdf2f4; }
        .priority-action svg { width: 11px; height: 11px; }
        .empty-state { display: grid; justify-items: center; gap: 7px; padding: 34px 18px; text-align: center; }
        .empty-mark { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 50%; color: #697482; background: #f1f5f9; }
        .empty-mark svg { width: 19px; height: 19px; }
        .empty-state strong { color: var(--navy); font-size: var(--font-md); }
        .empty-state p { max-width: 340px; margin: 0; color: var(--muted); font-size: var(--font-xs); line-height: 1.6; text-wrap: pretty; }
        .empty-state a { margin-top: 3px; color: var(--wine); font-size: var(--font-xs); font-weight: 750; text-decoration: none; }

        .alert-list { display: grid; margin: 0; padding: 4px clamp(10px, 1vw, 14px); list-style: none; }
        .alert-list li + li { border-top: 1px solid #f1f5f9; }
        .alert-list a { display: grid; gap: 3px; padding: clamp(9px, 1.1vh, 12px) 2px; text-decoration: none; }
        .alert-list a:hover strong { color: var(--wine); }
        .alert-list strong { overflow: hidden; color: #1e293b; font-size: var(--font-sm); font-weight: 650; text-overflow: ellipsis; white-space: nowrap; }
        .alert-list span { color: #be123c; font-size: var(--font-xs); }
        .alert-empty { padding: 18px 8px; color: var(--muted); font-size: var(--font-xs); line-height: 1.6; text-align: center; }
        .alert-footer { display: flex; justify-content: flex-end; padding: 9px 14px 11px; border-top: 1px solid var(--line); }
        .alert-footer a { font-size: var(--font-xs); font-weight: 750; }
        .alert-footer a svg { width: 12px; height: 12px; }
        .month-switch { display: inline-flex; align-items: center; gap: 8px; color: #64748b; font-size: var(--font-sm); font-weight: 650; text-transform: capitalize; }
        .month-switch a { display: grid; width: clamp(22px, 2.5vw, 26px); height: clamp(22px, 2.5vw, 26px); place-items: center; border: 1px solid var(--line); border-radius: 6px; color: var(--navy); background: #fff; font-size: 15px; line-height: 1; text-decoration: none; }
        .month-switch a:hover { border-color: #94a3b8; color: var(--wine); }
        .calendar { width: 100%; padding: 6px clamp(8px, 1vw, 12px) clamp(8px, 1vh, 12px); border-spacing: 3px; table-layout: fixed; text-align: center; }
        .calendar th { height: clamp(22px, 2.8vh, 26px); color: #64748b; font-size: var(--font-xs); font-weight: 800; }
        .calendar td { height: clamp(26px, 3.2vh, 30px); color: #475569; font-size: var(--font-sm); }
        .calendar td span { display: grid; width: clamp(24px, 2.8vw, 28px); height: clamp(24px, 2.8vw, 28px); margin: auto; place-items: center; border-radius: 50%; font-size: var(--font-sm); }
        .calendar td.outside { color: transparent; }
        .calendar td.today span { color: #fff; background: var(--wine); font-weight: 800; box-shadow: 0 2px 7px #8e20372e; }

        /* Pantallas grandes de escritorio / Monitores de 24 pulgadas o superiores (>= 1600px) */
        @media (min-width: 1600px) {
            :root {
                --font-2xs: 11px;
                --font-xs: 12.5px;
                --font-sm: 13.5px;
                --font-base: 15px;
                --font-md: 16.5px;
                --font-lg: 20px;
                --font-xl: 26px;
                --font-2xl: 32px;
                --font-stat: 40px;
                --side-column-width: 330px;
            }
            .records-table td { padding: 12px 16px; }
            .table-scroll { max-height: clamp(400px, 55vh, 660px); }
        }

        /* Pantallas de laptop compactas (1024px a 1280px) */
        @media (max-width: 1280px) and (min-width: 981px) {
            :root {
                --side-column-width: 270px;
            }
            .records-table { min-width: 480px; }
            .dashboard-layout { gap: 14px; }
        }

        /* Optimización de altura para mini laptops (<= 800px de altura de viewport) */
        @media (max-height: 800px) and (min-width: 651px) {
            :root {
                --topbar-height: 48px;
                --workbench-py: 10px;
                --workbench-px: 16px;
            }
            .topbar { min-height: 48px; padding: 6px clamp(12px, 2vw, 20px); }
            .content { gap: 11px; padding: 12px clamp(12px, 2vw, 20px) 18px; }
            .workbench { padding: 9px 13px 0; }
            .page-intro { gap: 10px; }
            .page-intro h2 { font-size: clamp(18px, 1.8vw, 22px); }
            .page-date { margin-top: 3px; font-size: 11px; }
            .primary-action { min-height: 34px; padding: 0 12px; font-size: 11.5px; }
            .shortcut-row { margin-top: 8px; padding: 6px 0 7px; gap: 6px; }
            .shortcut-link { min-height: 31px; padding: 0 9px; font-size: 11px; }
            .summary-card { min-height: 58px; padding: 7px 11px; }
            .summary-value { font-size: 24px; }
            .dashboard-layout { gap: 11px; }
            .panel-heading { min-height: 38px; padding: 7px 11px; }
            .table-scroll { max-height: clamp(220px, 36vh, 320px); }
            .records-table th { height: 28px; }
            .records-table td { padding: 7px 10px; }
            .calendar th { height: 19px; }
            .calendar td { height: 23px; }
            .calendar td span { width: 21px; height: 21px; }
            .alert-list a { padding: 6px 2px; }
        }

        /* Pantallas intermedias / Tablets (< 980px) */
        @media (max-width: 980px) {
            .dashboard-layout { grid-template-columns: 1fr; }
            .side-column { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); align-items: start; }
        }

        /* Móviles y pantallas angostas (< 720px) */
        @media (max-width: 720px) {
            .topbar { align-items: flex-start; flex-direction: column; gap: 8px; padding: 10px 14px; }
            .topbar-tools { width: 100%; gap: 6px; }
            .search-form { min-width: 0; flex: 1; }
            .search-control { width: auto; min-width: 0; flex: 1; }
            .search-submit { padding: 0 9px; }
            .account-menu { margin-left: auto; }
            .user-summary { display: none; }
            .content { gap: 12px; padding: 14px 12px 24px; }
            .workbench { padding: 12px 12px 0; }
            .summary-grid { gap: 6px; }
            .summary-card { min-height: 68px; padding: 9px 12px; }
            .summary-value { font-size: 24px; }
            .dashboard-layout { gap: 12px; }
            .side-column { gap: 12px; }
        }

        /* Móviles muy compactos (< 560px) */
        @media (max-width: 560px) {
            .page-intro { align-items: stretch; flex-direction: column; gap: 10px; }
            .workbench { padding-right: 10px; padding-left: 10px; }
            .page-intro h2 { font-size: 20px; }
            .primary-action { width: 100%; min-height: 38px; }
            .shortcut-row { flex-wrap: wrap; gap: 6px; margin-top: 10px; padding-top: 8px; }
            .shortcut-link { min-height: 36px; padding: 0 10px; font-size: 11px; }
            .summary-grid { grid-template-columns: 1fr; gap: 0; }
            .summary-card + .summary-card { border-top: 1px solid var(--line); border-left: 0; }
            .summary-card { min-height: 58px; grid-template-columns: 1fr auto; padding: 8px 12px; }
            .summary-value { font-size: 24px; }
            .dashboard-layout { grid-template-columns: 1fr; }
            .side-column { grid-template-columns: 1fr; }
            .panel-heading { min-height: 42px; padding: 8px 10px; }
            .table-scroll { max-height: clamp(240px, 45vh, 340px); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; animation-duration: .01ms !important; }
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="8" height="8" rx="1.5"></rect><rect x="13" y="3" width="8" height="5" rx="1.5"></rect><rect x="13" y="10" width="8" height="11" rx="1.5"></rect><rect x="3" y="13" width="8" height="8" rx="1.5"></rect></svg><span>Dashboard</span>
                </a>
                <a class="nav-item {{ request()->routeIs('ups.estudiantes.*') ? 'active' : '' }}" href="{{ route('ups.estudiantes.index', [], false) }}" aria-label="Estudiantes" title="Estudiantes">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M5 20a7 7 0 0 1 14 0"></path></svg><span>Estudiantes</span>
                </a>
                <details class="nav-group" @if(request()->routeIs('ups.proyectos.*', 'ups.prorrogas.*', 'ups.plazos.*')) open @endif>
                    <summary class="nav-group-toggle {{ request()->routeIs('ups.proyectos.*', 'ups.prorrogas.*', 'ups.plazos.*') ? 'active' : '' }}" aria-label="Proyectos y seguimiento" title="Proyectos y seguimiento">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"></path><path d="m3 8 9 5 9-5M3 8v9l9 5 9-5V8M12 13v9"></path></svg><span>Proyectos</span>
                    </summary>
                    <div class="nav-subnav">
                        <a class="nav-child {{ request()->routeIs('ups.proyectos.*') ? 'active' : '' }}" href="{{ route('ups.proyectos.index', [], false) }}">Proyectos</a>
                        <a class="nav-child {{ request()->routeIs('ups.prorrogas.*') ? 'active' : '' }}" href="{{ route('ups.prorrogas.index', [], false) }}">Prórrogas</a>
                        <a class="nav-child {{ request()->routeIs('ups.plazos.*') ? 'active' : '' }}" href="{{ route('ups.plazos.index', [], false) }}">Plazos</a>
                    </div>
                </details>
                <a class="nav-item {{ request()->routeIs('ups.memorias.*') ? 'active' : '' }}" href="{{ route('ups.memorias.index', [], false) }}" aria-label="Memorias" title="Memorias">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h9l5 5v13H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path><path d="M14 3v6h6M8 13h8M8 17h8"></path></svg><span>Memorias</span>
                </a>
                @canany(['estadisticas.consultar', 'estadisticas.exportar'])
                    <a class="nav-item {{ request()->routeIs('ups.estadisticas.*') ? 'active' : '' }}" href="{{ route('ups.estadisticas.index', [], false) }}" aria-label="Estadísticas" title="Estadísticas">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20V10m8 10V4m8 16v-7M2 20h20"></path></svg><span>Estadísticas</span>
                    </a>
                @endcanany
                @if(auth()->user()->hasRole('jefe_ups'))
                    <details class="nav-group" @if(request()->routeIs('admin.*')) open @endif>
                        <summary class="nav-group-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" aria-label="Administración" title="Administración">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3"></circle><path d="M5 20a7 7 0 0 1 14 0M19 8h3M20.5 6.5v3"></path></svg><span>Administración</span>
                        </summary>
                        <div class="nav-subnav">
                            <a class="nav-child {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" href="{{ route('admin.usuarios.index', [], false) }}">Usuarios</a>
                            <a class="nav-child {{ request()->routeIs('admin.auditoria.*') ? 'active' : '' }}" href="{{ route('admin.auditoria.index', [], false) }}">Auditoría administrativa</a>
                            <a class="nav-child {{ request()->routeIs('admin.areas.*') ? 'active' : '' }}" href="{{ route('admin.areas.index', [], false) }}">Áreas de conocimiento</a>
                            <a class="nav-child {{ request()->routeIs('admin.catalogos.*', 'admin.carreras.*', 'admin.departamentos.*') ? 'active' : '' }}" href="{{ route('admin.catalogos.index', [], false) }}">Carreras y departamentos</a>
                            <a class="nav-child {{ request()->routeIs('admin.plantillas.*') ? 'active' : '' }}" href="{{ route('admin.plantillas.index', [], false) }}">Plantillas oficiales</a>
                        </div>
                    </details>
                @endif
            </nav>
            <div class="sidebar-spacer"></div>
            <div class="user-avatar" data-initials="{{ collect(explode(' ', $nombreUsuario))->filter()->take(2)->map(fn ($parte) => mb_substr($parte, 0, 1))->implode('') }}" aria-label="Usuario: {{ $nombreUsuario }}">
                <span class="user-name">{{ $nombreUsuario }} · {{ $rolUsuario }}</span>
                <form method="POST" action="{{ route('logout', [], false) }}">@csrf<button class="sidebar-logout" type="submit">Cerrar sesión</button></form>
            </div>
        </aside>

        <main class="workspace">
            <header class="topbar">
                <h1 class="topbar-title">SIGPS · PROYECCIÓN SOCIAL</h1>
                <div class="topbar-tools">
                    <form class="search-form" method="GET" action="{{ route('ups.dashboard', [], false) }}">
                        <label class="search-control">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                            <input type="search" name="buscar" value="{{ $busqueda }}" placeholder="Buscar proyecto o estudiante" maxlength="100" aria-label="Buscar por carnet, estudiante o proyecto">
                        </label>
                        <button class="search-submit" type="submit">Buscar</button>
                    </form>
                    <details class="notification-menu">
                        <summary class="utility-button" aria-label="Notificaciones: {{ $notificacionesSinLeer }} sin leer" title="Notificaciones">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path></svg>
                            @if($notificacionesSinLeer > 0)<span class="notification-badge">{{ $notificacionesSinLeer > 99 ? '99+' : $notificacionesSinLeer }}</span>@endif
                        </summary>
                        <section class="notification-popover" aria-label="Notificaciones recibidas">
                            <header class="notification-popover-head"><strong>Notificaciones</strong><small>{{ $notificacionesSinLeer }} sin leer</small></header>
                            <div class="notification-list">
                                @forelse($notificaciones as $notificacion)
                                    <form class="notification-form" method="POST" action="{{ route('ups.notificaciones.leida', $notificacion->id, false) }}">@csrf
                                        <button class="notification-entry {{ $notificacion->read_at ? '' : 'unread' }}" type="submit">
                                            <span class="notification-dot" aria-hidden="true"></span>
                                            <span class="notification-copy"><strong>{{ $notificacion->data['title'] ?? 'Notificación' }}</strong><span>{{ $notificacion->data['message'] ?? '' }}</span><time>{{ $notificacion->created_at->locale('es')->diffForHumans() }}</time></span>
                                        </button>
                                    </form>
                                @empty<div class="notification-empty">No hay notificaciones todavía.</div>@endforelse
                            </div>
                            <footer class="notification-popover-foot">
                                @if($notificacionesSinLeer > 0)<form method="POST" action="{{ route('ups.notificaciones.leidas-todas', [], false) }}">@csrf<button class="notification-mark-all" type="submit">Marcar todo como leído</button></form>@endif
                                <a href="{{ route('ups.bandeja.index', [], false) }}">Abrir bandeja →</a>
                            </footer>
                        </section>
                    </details>
                    <details class="account-menu">
                        <summary>
                            <span class="user-summary"><strong>{{ $nombreUsuario }}</strong><span>{{ $rolUsuario }}</span></span>
                        </summary>
                        <div class="account-menu-popover"><form method="POST" action="{{ route('logout', [], false) }}">@csrf<button type="submit">Cerrar sesión</button></form></div>
                    </details>
                </div>
            </header>

            <section class="content" aria-label="Panel diario de la UPS">
                <section class="workbench" aria-label="Herramientas de gestión de proyectos">
                <header class="page-intro">
                    <div>
                        <h2>Seguimiento de proyectos</h2>
                        <div class="page-date">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg>
                            <time>{{ $fechaActual }}</time>
                        </div>
                    </div>
                    <a class="primary-action" href="{{ route('ups.proyectos.create') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"></path></svg>
                        Registrar proyecto
                    </a>
                </header>

                @if ($errors->has('buscar') || $errors->has('mes') || $errors->has('anio'))
                    <p role="alert" style="margin:0;color:#a63840;font-size:11px;">Revisa los filtros ingresados. La búsqueda admite hasta 100 caracteres.</p>
                @endif

                <nav class="shortcut-row" aria-label="Accesos directos">
                    <a class="shortcut-link" href="{{ route('ups.bandeja.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v14H4zM4 13h4l2 3h4l2-3h4"></path></svg>
                        Bandeja de trámites
                    </a>
                    <a class="shortcut-link" href="{{ route('ups.proyectos.index') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 4.5 4.5"></path></svg>
                        Proyectos
                    </a>
                    @canany(['estadisticas.consultar', 'estadisticas.exportar'])
                        <a class="shortcut-link" href="{{ route('ups.estadisticas.index') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19V5M4 19h17"></path><path d="m7 15 4-4 3 2 5-6"></path></svg>
                            Estadísticas
                        </a>
                    @endcanany
                </nav>
                </section>

                <section class="summary-grid" aria-label="Pendientes de atención">
                    <a class="summary-card summary-urgent" href="{{ route('ups.proyectos.index', ['triaje' => 'urgentes']) }}" aria-label="{{ $triaje['plazosCriticos'] }} plazos críticos. Ver casos urgentes.">
                        <span class="summary-title">Plazos críticos</span>
                        <strong class="summary-value">{{ $triaje['plazosCriticos'] }}</strong>
                        <span class="summary-action">Ver urgentes <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></span>
                    </a>
                    <a class="summary-card summary-review" href="{{ route('ups.proyectos.index', ['triaje' => 'tramite']) }}" aria-label="{{ $triaje['auditoriasPendientes'] }} memorias en trámite. Abrir casos.">
                        <span class="summary-title">Memorias en trámite</span>
                        <strong class="summary-value">{{ $triaje['auditoriasPendientes'] }}</strong>
                        <span class="summary-action">Revisar memorias <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></span>
                    </a>
                    <a class="summary-card summary-delivery" href="{{ route('ups.proyectos.index', ['estado' => 'lista']) }}" aria-label="{{ $triaje['entregasPendientes'] }} entregas pendientes. Gestionar expedientes listos.">
                        <span class="summary-title">Listas para entregar</span>
                        <strong class="summary-value">{{ $triaje['entregasPendientes'] }}</strong>
                        <span class="summary-action">Gestionar <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></span>
                    </a>
                </section>

                <div class="dashboard-layout">
                    <div class="main-column">
                        <section class="panel" aria-labelledby="prioridades-heading">
                            <div class="panel-heading">
                                <div><h3 id="prioridades-heading">Prioridades de hoy</h3><p>Casos ordenados por urgencia y siguiente paso</p></div>
                                <span class="panel-count">{{ $casosPrioritarios->count() }} casos</span>
                            </div>
                            <div class="table-scroll" role="region" aria-label="Lista desplazable de prioridades" tabindex="0">
                                <table class="records-table">
                                    <thead><tr><th>Proyecto y estudiante</th><th>Área</th><th>Estado y acción</th></tr></thead>
                                    <tbody>
                                        @forelse ($casosPrioritarios as $proyecto)
                                            @php
                                                $semaforo = $proyecto->semaforoOperativo();
                                                $primerEst = $proyecto->estudiantes->first();
                                                $tituloVisible = \Illuminate\Support\Str::after($proyecto->titulo, 'DEMO | ');
                                                $esDemo = $tituloVisible !== $proyecto->titulo;
                                                $estadoCorto = match($semaforo['etiqueta']) {
                                                    'Memoria observada' => 'Observada',
                                                    'Pendiente auditoría' => 'En revisión',
                                                    'Esperando firmas' => 'Por firmar',
                                                    'Certificación lista' => 'Lista',
                                                    'Remisión a Junta' => 'Junta',
                                                    'Memoria pendiente' => 'Sin memoria',
                                                    'En curso normal' => 'En curso',
                                                    'Concluido' => 'Cerrado',
                                                    default => $semaforo['etiqueta'],
                                                };
                                                $accionCorta = match($semaforo['siguiente_accion']) {
                                                    'Ver observaciones' => 'Abrir',
                                                    'Auditar sellos' => 'Revisar',
                                                    'Firmar certificación' => 'Firmar',
                                                    'Registrar entrega' => 'Entregar',
                                                    'Remitir a Junta' => 'Remitir',
                                                    'Notificar estudiante' => 'Notificar',
                                                    default => 'Abrir',
                                                };
                                                $estadoClase = $semaforo['nivel'] === 'urgente' ? 'urgent' : ($semaforo['nivel'] === 'tramite' ? 'review' : 'ready');
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="project-summary">
                                                        <div class="project-title-line">
                                                            <span class="project-code">#{{ $proyecto->numero_proyecto ?? $proyecto->id_proyecto }}</span>
                                                            @if($esDemo)<span class="demo-marker">DEMO</span>@endif
                                                            <a class="project-title" title="{{ $proyecto->titulo }}" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">{{ \Illuminate\Support\Str::limit($tituloVisible, 85) }}</a>
                                                        </div>
                                                        <span class="student-subline" title="{{ $primerEst?->nombre_completo ?? 'Sin estudiante' }}">
                                                            @if($primerEst){{ \Illuminate\Support\Str::limit($primerEst->nombre_completo, 50) }}@if($proyecto->estudiantes->count() > 1) · +{{ $proyecto->estudiantes->count() - 1 }}@endif @else Sin estudiante @endif
                                                        </span>
                                                    </div>
                                                </td>
                                                <td><span class="area-name" title="{{ $proyecto->areaConocimiento?->nombre ?? 'Sin área' }}">{{ $proyecto->areaConocimiento?->nombre ?? 'Sin área' }}</span></td>
                                                <td>
                                                    <div class="priority-cell">
                                                        <span class="status-badge {{ $estadoClase }}" aria-label="{{ $semaforo['etiqueta'] }}: {{ $semaforo['plazo_humano'] }}" title="{{ $semaforo['etiqueta'] }} · {{ $semaforo['plazo_humano'] }}">{{ $estadoCorto }}</span>
                                                        <a class="priority-action" aria-label="{{ $semaforo['siguiente_accion'] }} para el proyecto {{ $proyecto->numero_proyecto ?? $proyecto->id_proyecto }}" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">{{ $accionCorta }}<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3">
                                                <div class="empty-state">
                                                    <span class="empty-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></span>
                                                    <strong>{{ $busqueda ? 'Sin resultados para esta búsqueda' : 'No hay pendientes para hoy' }}</strong>
                                                    <p>{{ $busqueda ? 'Prueba con otro nombre, carnet o número de proyecto.' : 'Los casos que requieran atención aparecerán aquí ordenados por urgencia.' }}</p>
                                                    <a href="{{ route('ups.proyectos.index') }}">Abrir listado de proyectos →</a>
                                                </div>
                                            </td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <aside class="side-column" aria-label="Alertas y calendario">
                        <section class="panel" aria-labelledby="alerts-heading">
                            <div class="panel-heading">
                                <h3 id="alerts-heading">Alertas de vencimiento</h3>
                                <span class="panel-count">{{ $alertas->count() }} activas</span>
                            </div>
                            <ul class="alert-list">
                                @forelse ($alertas as $alerta)
                                    @php($sem = $alerta->semaforoOperativo())
                                    <li><a href="{{ route('ups.proyectos.show', $alerta->id_proyecto) }}"><strong>#{{ $alerta->numero_proyecto ?? $alerta->id_proyecto }} · {{ \Illuminate\Support\Str::limit(\Illuminate\Support\Str::after($alerta->titulo, 'DEMO | '), 65) }}</strong><span>{{ $sem['plazo_humano'] }} · {{ $sem['etiqueta'] }}</span></a></li>
                                @empty
                                    <li class="alert-empty">Sin vencimientos urgentes por atender.</li>
                                @endforelse
                            </ul>
                            <div class="alert-footer"><a href="{{ route('ups.proyectos.index', ['triaje' => 'urgentes']) }}">Ver alertas <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></a></div>
                        </section>

                        <section class="panel" aria-labelledby="calendar-heading">
                            <div class="panel-heading">
                                <h3 id="calendar-heading">Calendario de plazos</h3>
                                <span class="month-switch">
                                    @if ($mesAnterior->year >= 2020)<a href="{{ route('ups.dashboard', ['mes' => $mesAnterior->month, 'anio' => $mesAnterior->year, 'buscar' => $busqueda ?: null], false) }}" aria-label="Mes anterior">‹</a>@endif
                                    {{ $mesActual }}
                                    @if ($mesSiguiente->year <= 2100)<a href="{{ route('ups.dashboard', ['mes' => $mesSiguiente->month, 'anio' => $mesSiguiente->year, 'buscar' => $busqueda ?: null], false) }}" aria-label="Mes siguiente">›</a>@endif
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
                const prefersCollapsedByDefault = window.matchMedia('(max-width: 1180px)').matches;
                if (savedPreference === 'true' || (savedPreference === null && prefersCollapsedByDefault)) {
                    root.classList.add('sidebar-collapsed');
                }
            } catch (error) {
                // El menú sigue funcionando si el navegador no permite usar almacenamiento local.
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
                    // El estado visual actual se conserva aunque el almacenamiento esté deshabilitado.
                }
            });
        })();
    </script>
</body>
</html>
