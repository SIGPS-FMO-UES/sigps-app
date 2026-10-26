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
            --ink: #202b39;
            --muted: #6a7481;
            --soft: #929aa4;
            --line: #e2e5e9;
            --paper: #fff;
            --canvas: #f5f6f7;
            --navy: #19283a;
            --wine: #8e2037;
            --wine-dark: #74182d;
            --sidebar-active: #991b32;
            --sidebar-width: 226px;
        }
        html.sidebar-collapsed { --sidebar-width: 68px; }
        * { box-sizing: border-box; }
        body { min-width: 320px; min-height: 100vh; margin: 0; color: var(--ink); background: var(--canvas); font-size: 13px; }
        a { color: inherit; }
        button, input { font: inherit; }
        button:focus-visible, a:focus-visible, input:focus-visible, summary:focus-visible { outline: 3px solid #8e2037; outline-offset: 3px; }
        .app-shell { min-height: 100vh; }

        .sidebar { position: fixed; z-index: 10; inset: 0 auto 0 0; display: flex; width: var(--sidebar-width); flex-direction: column; padding: 20px 12px; color: var(--ink); background: #fff; border-right: 1px solid #dee3ea; transition: width .2s ease, padding .2s ease, background-color .2s ease; }
        .sidebar-header { display: flex; width: 100%; align-items: center; }
        .brand-link { display: flex; min-width: 0; min-height: 50px; flex: 1; align-items: center; gap: 12px; padding: 0 4px; color: #fff; text-decoration: none; }
        .brand-link img { display: block; width: 42px; height: 42px; object-fit: contain; background: #fff; }
        .brand-copy { color: var(--ink); font-size: 10px; font-weight: 700; letter-spacing: .07em; line-height: 1.5; }
        .sidebar-toggle { position: absolute; z-index: 12; top: 27px; right: -28px; display: grid; width: 28px; height: 32px; flex: 0 0 28px; place-items: center; border: 1px solid #dee3ea; border-radius: 0 7px 7px 0; color: var(--sidebar-active); background: #fff; box-shadow: 2px 2px 6px #1725361a; cursor: pointer; transition: color .2s ease, background-color .2s ease, border-color .2s ease; }
        .sidebar-toggle:hover { color: #fff; background: var(--sidebar-active); border-color: var(--sidebar-active); }
        .sidebar-toggle svg { width: 16px; height: 16px; transition: transform .2s ease; }
        .side-nav { display: flex; width: 100%; min-height: 0; flex-direction: column; gap: 7px; margin-top: 24px; overflow-y: auto; scrollbar-width: thin; }
        .nav-item { display: flex; width: 100%; min-height: 42px; align-items: center; gap: 15px; padding: 0 10px; border: 0; border-radius: 7px; color: #6a7481; background: transparent; text-align: left; text-decoration: none; }
        .nav-item svg { width: 20px; height: 20px; flex: 0 0 auto; }
        .nav-item span { font-size: 12px; }
        .nav-item:hover, .nav-item.active { color: #fff; background: var(--sidebar-active); }
        .nav-item:hover svg, .nav-item.active svg { color: #fff; }
        .nav-item[aria-disabled="true"] { cursor: not-allowed; opacity: .75; }
        .nav-group { width: 100%; flex: 0 0 auto; }
        .nav-group-toggle { display: flex; min-height: 42px; align-items: center; gap: 15px; padding: 0 10px; border-radius: 7px; color: #6a7481; cursor: pointer; list-style: none; }
        .nav-group-toggle::-webkit-details-marker { display: none; }
        .nav-group-toggle::after { width: 7px; height: 7px; margin-left: auto; border-right: 1.5px solid currentColor; border-bottom: 1.5px solid currentColor; content: ""; transform: rotate(45deg) translateY(-2px); }
        .nav-group[open] > .nav-group-toggle::after { transform: rotate(225deg) translate(-1px, -1px); }
        .nav-group-toggle:hover, .nav-group-toggle.active, .nav-group[open] > .nav-group-toggle { color: #fff; background: var(--sidebar-active); }
        .nav-group-toggle svg { width: 20px; height: 20px; flex: 0 0 auto; }
        .nav-group-toggle span { font-size: 12px; }
        .nav-subnav { display: grid; gap: 3px; margin: 3px 0 4px 19px; padding-left: 9px; border-left: 1px solid #dee3ea; }
        .nav-child { display: flex; min-height: 33px; align-items: center; padding: 0 9px; border-radius: 7px; color: #6a7481; font-size: 11px; text-decoration: none; }
        .nav-child:hover, .nav-child.active { color: #fff; background: var(--sidebar-active); }
        .sidebar-spacer { flex: 1; }
        .user-avatar { display: flex; width: 100%; min-height: 44px; align-items: center; gap: 11px; margin: 0 0 6px; padding: 0 9px; border-top: 1px solid #dee3ea; color: var(--ink); font-size: 11px; font-weight: 600; }
        .user-avatar::before { display: grid; width: 29px; height: 29px; flex: 0 0 auto; place-items: center; border-radius: 50%; color: var(--navy); background: #dce3eb; content: attr(data-initials); font-size: 10px; }
        .user-name { min-width: 0; overflow: hidden; color: var(--ink); font-size: 10px; line-height: 1.5; text-overflow: ellipsis; }
        .sidebar-logout { margin-left: auto; padding: 6px 7px; border: 1px solid #dee3ea; border-radius: 6px; color: var(--sidebar-active); background: #fff; font-size: 10px; cursor: pointer; }
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
        .topbar { display: flex; min-height: 66px; align-items: center; justify-content: space-between; gap: 20px; padding: 10px clamp(18px, 3vw, 42px); border-bottom: 1px solid var(--line); background: #fff; }
        .topbar-title { margin: 0; color: var(--navy); font-size: 13px; font-weight: 750; letter-spacing: -.01em; }
        .topbar-tools { display: flex; align-items: center; gap: 10px; }
        .search-form { display: flex; align-items: center; gap: 6px; }
        .search-control { display: flex; width: clamp(190px, 24vw, 310px); height: 36px; align-items: center; gap: 8px; padding: 0 10px; border: 1px solid #dedfdc; border-radius: 8px; background: #fff; }
        .search-control svg { width: 15px; height: 15px; flex: 0 0 auto; color: var(--soft); }
        .search-control input { width: 100%; min-width: 0; border: 0; outline: 0; color: var(--ink); background: transparent; font-size: 11px; }
        .search-control input::placeholder { color: #7b8490; opacity: 1; }
        .search-submit { height: 36px; padding: 0 12px; border: 0; border-radius: 7px; color: #fff; background: var(--wine); font-size: 10px; font-weight: 700; cursor: pointer; }
        .search-submit:hover { background: var(--wine-dark); }
        .notification-menu, .account-menu { position: relative; }
        .notification-menu > summary { position: relative; cursor: pointer; list-style: none; }
        .notification-menu > summary::-webkit-details-marker, .account-menu summary::-webkit-details-marker { display: none; }
        .utility-button { display: grid; width: 36px; height: 36px; place-items: center; border: 1px solid #dedfdc; border-radius: 8px; color: #586474; background: #fff; }
        .utility-button svg { width: 17px; height: 17px; }
        .notification-badge { position: absolute; top: -5px; right: -5px; display: grid; min-width: 17px; height: 17px; padding: 0 4px; place-items: center; border: 2px solid #faf9f6; border-radius: 999px; color: #fff; background: #b33a48; font-size: 8px; font-weight: 800; }
        .notification-popover, .account-menu-popover { position: absolute; z-index: 50; top: calc(100% + 9px); right: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; background: #fff; box-shadow: 0 16px 36px #17253626; }
        .notification-popover { width: min(350px, calc(100vw - 24px)); }
        .notification-popover-head, .notification-popover-foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 14px; }
        .notification-popover-head { border-bottom: 1px solid var(--line); font-size: 11px; }
        .notification-popover-head small { color: var(--muted); font-size: 9px; }
        .notification-list { max-height: 320px; overflow-y: auto; }
        .notification-form { margin: 0; }
        .notification-entry { display: grid; width: 100%; grid-template-columns: 8px minmax(0,1fr); gap: 9px; padding: 11px 13px; border: 0; border-bottom: 1px solid #f0efeb; color: var(--ink); background: #fff; text-align: left; cursor: pointer; }
        .notification-entry:hover, .notification-entry.unread { background: #fbf8f5; }
        .notification-dot { width: 7px; height: 7px; margin-top: 4px; border-radius: 50%; background: transparent; }
        .notification-entry.unread .notification-dot { background: var(--wine); }
        .notification-copy { display: grid; min-width: 0; gap: 3px; }
        .notification-copy strong { font-size: 10px; }
        .notification-copy span { overflow: hidden; color: var(--muted); font-size: 9px; text-overflow: ellipsis; white-space: nowrap; }
        .notification-copy time { color: var(--soft); font-size: 8px; }
        .notification-empty { padding: 20px; color: var(--muted); font-size: 10px; text-align: center; }
        .notification-popover-foot { justify-content: flex-end; border-top: 1px solid var(--line); }
        .notification-popover-foot a { color: var(--wine); font-size: 10px; font-weight: 700; text-decoration: none; }
        .notification-mark-all { margin-right: auto; padding: 0; border: 0; color: var(--muted); background: transparent; font-size: 9px; cursor: pointer; }
        .account-menu summary { display: flex; min-height: 36px; align-items: center; gap: 8px; cursor: pointer; list-style: none; }
        .account-menu summary::after { width: 6px; height: 6px; border-right: 1.5px solid currentColor; border-bottom: 1.5px solid currentColor; content: ""; transform: rotate(45deg) translateY(-2px); }
        .account-menu[open] summary::after { transform: rotate(225deg) translate(-1px, -1px); }
        .user-summary { display: grid; gap: 2px; white-space: nowrap; }
        .user-summary strong { color: var(--navy); font-size: 10px; font-weight: 700; }
        .user-summary span { color: var(--muted); font-size: 9px; }
        .account-menu-popover { min-width: 150px; padding: 5px; }
        .account-menu-popover form { margin: 0; }
        .account-menu-popover button { width: 100%; padding: 9px; border: 0; border-radius: 6px; color: var(--ink); background: transparent; text-align: left; cursor: pointer; }
        .account-menu-popover button:hover { color: var(--wine); background: #f8f3ef; }

        .content { display: grid; max-width: 1680px; gap: 18px; margin: 0 auto; padding: 25px clamp(18px, 3vw, 42px) 36px; }
        .workbench { display: grid; gap: 0; padding: 19px 21px 0; border: 1px solid var(--line); border-radius: 8px; background: #fff; }
        .page-intro { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; }
        .page-intro h2 { margin: 0; color: #272c34; font-size: clamp(24px, 2.5vw, 30px); font-weight: 650; letter-spacing: -.035em; line-height: 1.15; }
        .page-date { display: flex; align-items: center; gap: 7px; margin-top: 9px; color: var(--muted); font-size: 10px; }
        .page-date svg { width: 14px; height: 14px; color: var(--muted); }
        .primary-action { display: inline-flex; min-height: 42px; flex: 0 0 auto; align-items: center; justify-content: center; gap: 9px; padding: 0 16px; border: 1px solid var(--wine); border-radius: 6px; color: #fff; background: var(--wine); font-size: 11px; font-weight: 750; text-decoration: none; transition: background .16s ease; }
        .primary-action:hover { border-color: var(--wine-dark); background: var(--wine-dark); }
        .primary-action svg { width: 17px; height: 17px; }
        .shortcut-row { display: flex; min-width: 0; align-items: center; gap: 8px; margin-top: 17px; padding: 11px 0 12px; border-top: 1px solid var(--line); }
        .shortcut-link { display: inline-flex; min-height: 42px; min-width: 0; align-items: center; gap: 9px; padding: 0 13px; border: 1px solid #dfe3e8; border-radius: 6px; color: #303c4b; background: #fff; font-size: 10px; font-weight: 650; text-decoration: none; transition: border-color .16s ease, color .16s ease, background .16s ease; }
        .shortcut-link:hover { border-color: #c99da6; color: var(--wine); background: #fbf8f9; }
        .shortcut-link svg { width: 16px; height: 16px; color: var(--wine); }
        .summary-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 8px; background: #fff; }
        .summary-card { position: relative; display: grid; min-height: 78px; grid-template-columns: minmax(0,1fr) auto; align-items: center; gap: 5px 12px; padding: 12px 16px; overflow: hidden; border: 0; border-radius: 0; color: var(--ink); background: #fff; text-decoration: none; transition: background .16s ease; }
        .summary-card + .summary-card { border-left: 1px solid var(--line); }
        .summary-card::before { position: absolute; inset: 0 auto 0 0; width: 3px; background: var(--metric-line); content: ""; }
        .summary-card:hover { background: #f8f9fa; }
        .summary-title { color: var(--muted); font-size: 10px; font-weight: 650; }
        .summary-value { grid-column: 2; grid-row: 1 / span 2; color: var(--metric); font-size: 29px; font-weight: 760; letter-spacing: -.055em; line-height: 1; }
        .summary-action { display: inline-flex; align-items: center; gap: 4px; color: var(--metric); font-size: 9px; font-weight: 750; }
        .summary-action svg { width: 11px; height: 11px; }
        .summary-urgent { --metric: var(--wine); --metric-line: var(--wine); }
        .summary-review, .summary-delivery { --metric: #394657; --metric-line: #e2e5e9; }

        .dashboard-layout { display: grid; min-width: 0; grid-template-columns: minmax(0, 1fr) 310px; align-items: start; gap: 18px; }
        .main-column, .side-column { display: grid; min-width: 0; gap: 16px; }
        .panel { min-width: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 11px; background: var(--paper); box-shadow: 0 3px 12px #17253608; }
        .panel-heading { display: flex; min-height: 52px; align-items: center; justify-content: space-between; gap: 10px; padding: 11px 15px; border-bottom: 1px solid #efeee9; }
        .panel-heading h3 { margin: 0; color: var(--navy); font-size: 12px; font-weight: 750; letter-spacing: -.01em; }
        .panel-heading p { margin: 4px 0 0; color: var(--muted); font-size: 9px; }
        .panel-heading a { display: inline-flex; align-items: center; gap: 5px; color: var(--wine); font-size: 9px; font-weight: 750; text-decoration: none; white-space: nowrap; }
        .panel-heading a:hover { text-decoration: underline; }
        .panel-heading a svg { width: 12px; height: 12px; }
        .panel-count { display: inline-flex; min-height: 22px; align-items: center; padding: 0 8px; border-radius: 4px; color: #56616d; background: #f1f3f5; font-size: 9px; font-weight: 700; white-space: nowrap; }
        .table-scroll { max-height: min(52vh, 490px); overflow: auto; overscroll-behavior: contain; scrollbar-color: #c5cbd2 #f1f3f5; scrollbar-width: thin; }
        .table-scroll:focus-visible { outline: 2px solid var(--wine); outline-offset: -2px; }
        .records-table { width: 100%; min-width: 560px; table-layout: fixed; border-collapse: collapse; text-align: left; }
        .records-table th { position: sticky; top: 0; z-index: 1; height: 34px; padding: 0 12px; border-bottom: 1px solid var(--line); color: #7c8490; background: #f5f6f7; font-size: 8px; font-weight: 750; letter-spacing: .07em; text-transform: uppercase; }
        .records-table th:first-child { width: 47%; }
        .records-table th:nth-child(2) { width: 22%; }
        .records-table th:last-child { width: 31%; }
        .records-table td { padding: 10px 12px; border-bottom: 1px solid #f0efeb; color: #485463; font-size: 10px; vertical-align: middle; }
        .records-table tbody tr:last-child td { border-bottom: 0; }
        .records-table tbody tr:hover td { background: #fcfbf8; }
        .project-summary { display: grid; min-width: 0; gap: 4px; }
        .project-title-line { display: flex; min-width: 0; align-items: center; gap: 5px; }
        .project-code { flex: 0 0 auto; padding: 3px 5px; border-radius: 4px; color: #fff; background: #29384a; font-size: 8px; font-weight: 800; }
        .demo-marker { flex: 0 0 auto; color: #78818c; font-size: 7px; font-weight: 750; letter-spacing: .04em; }
        .project-title { min-width: 0; overflow: hidden; color: #293849; font-size: 10px; font-weight: 700; text-overflow: ellipsis; text-decoration: none; white-space: nowrap; }
        .project-title:hover { color: var(--wine); text-decoration: underline; }
        .student-subline { overflow: hidden; color: #818994; font-size: 9px; text-overflow: ellipsis; white-space: nowrap; }
        .area-name { display: block; overflow: hidden; color: #626e7b; text-overflow: ellipsis; white-space: nowrap; }
        .priority-cell { display: flex; min-width: 0; align-items: center; gap: 8px; }
        .status-badge { display: inline-flex; min-height: 22px; flex: 0 0 auto; align-items: center; gap: 4px; padding: 0 7px; border: 1px solid #e8e4dc; border-radius: 999px; color: #56616d; background: #f8f7f4; font-size: 8px; font-weight: 750; white-space: nowrap; }
        .status-badge.urgent { border-color: #f0d1cf; color: #a63840; background: #fcf1f0; }
        .status-badge.review, .status-badge.ready { border-color: #dfe3e8; color: #56616d; background: #f5f6f7; }
        .priority-action { display: inline-flex; min-height: 26px; flex: 0 0 auto; align-items: center; gap: 4px; padding: 0 5px; border-radius: 5px; color: var(--wine); font-size: 9px; font-weight: 750; text-decoration: none; }
        .priority-action:hover { background: #f8f0f0; }
        .priority-action svg { width: 11px; height: 11px; }
        .empty-state { display: grid; justify-items: center; gap: 7px; padding: 34px 18px; text-align: center; }
        .empty-mark { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 50%; color: #697482; background: #f1f3f5; }
        .empty-mark svg { width: 19px; height: 19px; }
        .empty-state strong { color: var(--navy); font-size: 11px; }
        .empty-state p { max-width: 340px; margin: 0; color: var(--muted); font-size: 9px; line-height: 1.6; }
        .empty-state a { margin-top: 3px; color: var(--wine); font-size: 9px; font-weight: 750; text-decoration: none; }

        .alert-list { display: grid; margin: 0; padding: 3px 13px; list-style: none; }
        .alert-list li + li { border-top: 1px solid #f0efeb; }
        .alert-list a { display: grid; gap: 4px; padding: 11px 2px; text-decoration: none; }
        .alert-list a:hover strong { color: var(--wine); }
        .alert-list strong { overflow: hidden; color: #384656; font-size: 9px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .alert-list span { color: #a43d45; font-size: 9px; }
        .alert-empty { padding: 17px 8px; color: var(--muted); font-size: 9px; line-height: 1.6; text-align: center; }
        .alert-footer { display: flex; justify-content: flex-end; padding: 9px 14px 11px; border-top: 1px solid #efeee9; }
        .alert-footer a svg { width: 12px; height: 12px; }
        .month-switch { display: inline-flex; align-items: center; gap: 8px; color: #647080; font-size: 9px; font-weight: 650; text-transform: capitalize; }
        .month-switch a { display: grid; width: 23px; height: 23px; place-items: center; border: 1px solid var(--line); border-radius: 6px; color: var(--navy); background: #fff; font-size: 16px; line-height: 1; text-decoration: none; }
        .month-switch a:hover { border-color: #aeb5bd; color: var(--wine); }
        .calendar { width: 100%; padding: 8px 12px 11px; border-spacing: 3px; table-layout: fixed; text-align: center; }
        .calendar th { height: 23px; color: #8a929c; font-size: 8px; font-weight: 800; }
        .calendar td { height: 28px; color: #536070; font-size: 9px; }
        .calendar td span { display: grid; width: 25px; height: 25px; margin: auto; place-items: center; border-radius: 50%; }
        .calendar td.outside { color: transparent; }
        .calendar td.today span { color: #fff; background: var(--wine); font-weight: 800; box-shadow: 0 2px 7px #8e20372e; }

        @media (max-width: 1180px) {
            .dashboard-layout { grid-template-columns: minmax(0, 1fr) 290px; }
        }
        @media (max-width: 980px) {
            .dashboard-layout { grid-template-columns: 1fr; }
            .side-column { grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; }
        }
        @media (max-width: 720px) {
            .topbar { align-items: flex-start; flex-direction: column; gap: 9px; padding: 12px 16px; }
            .topbar-tools { width: 100%; gap: 7px; }
            .search-form { min-width: 0; flex: 1; }
            .search-control { width: auto; min-width: 0; flex: 1; }
            .search-submit { padding: 0 9px; }
            .account-menu { margin-left: auto; }
            .user-summary { display: none; }
            .content { gap: 14px; padding: 21px 15px 28px; }
            .workbench { padding: 16px 15px 0; }
            .summary-grid { gap: 8px; }
            .summary-card { min-height: 83px; padding: 11px 10px 11px 12px; }
            .summary-value { font-size: 24px; }
            .dashboard-layout { gap: 14px; }
            .side-column { gap: 14px; }
        }
        @media (max-width: 560px) {
            body { min-width: 320px; }
            .page-intro { align-items: stretch; flex-direction: column; gap: 12px; }
            .workbench { padding-right: 12px; padding-left: 12px; }
            .page-intro h2 { font-size: 22px; }
            .page-date { max-width: 230px; font-size: 9px; line-height: 1.5; }
            .primary-action { width: 100%; min-height: 39px; gap: 6px; padding: 0 10px; font-size: 9px; }
            .primary-action svg { width: 15px; height: 15px; }
            .shortcut-row { flex-wrap: wrap; gap: 6px; }
            .shortcut-row { margin-top: 13px; padding-top: 9px; }
            .shortcut-link { min-height: 42px; padding: 0 10px; font-size: 10px; }
            .shortcut-link svg { width: 15px; height: 15px; }
            .summary-grid { grid-template-columns: 1fr; gap: 0; }
            .summary-card + .summary-card { border-top: 1px solid #ece9e2; border-left: 0; }
            .summary-card { min-height: 62px; grid-template-columns: 1fr auto; padding: 9px 13px; }
            .summary-value { font-size: 24px; }
            .summary-title { align-self: end; }
            .summary-action { align-self: start; }
            .dashboard-layout { grid-template-columns: 1fr; }
            .side-column { grid-template-columns: 1fr; }
            .panel-heading { min-height: 48px; padding: 9px 11px; }
            .panel-heading h3 { font-size: 11px; }
            .records-table { min-width: 560px; }
            .table-scroll { max-height: min(48vh, 390px); }
            .account-menu summary { min-width: 30px; justify-content: center; }
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
                <a class="nav-item {{ request()->routeIs('ups.memorias.*', 'ups.auditoria.*') ? 'active' : '' }}" href="{{ route('ups.memorias.index', [], false) }}" aria-label="Memorias" title="Memorias">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h9l5 5v13H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path><path d="M14 3v6h6M8 13h8M8 17h8"></path></svg><span>Memorias</span>
                </a>
                <a class="nav-item {{ request()->routeIs('ups.certificaciones.*') ? 'active' : '' }}" href="{{ route('ups.certificaciones.index', [], false) }}" aria-label="Certificaciones" title="Certificaciones">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3h9l5 5v8H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path><path d="M14 3v6h6M8 11h8M8 14h5M9 16l-1 6 4-2 4 2-1-6"></path></svg><span>Certificaciones</span>
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
                    <a class="summary-card summary-review" href="{{ route('ups.proyectos.index', ['triaje' => 'tramite']) }}" aria-label="{{ $triaje['auditoriasPendientes'] }} revisiones pendientes. Abrir casos.">
                        <span class="summary-title">En revisión</span>
                        <strong class="summary-value">{{ $triaje['auditoriasPendientes'] }}</strong>
                        <span class="summary-action">Revisar casos <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg></span>
                    </a>
                    <a class="summary-card summary-delivery" href="{{ route('ups.proyectos.index', ['estado' => 'lista']) }}" aria-label="{{ $triaje['entregasPendientes'] }} entregas pendientes. Gestionar certificaciones listas.">
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
                                                            <a class="project-title" title="{{ $proyecto->titulo }}" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">{{ \Illuminate\Support\Str::limit($tituloVisible, 38) }}</a>
                                                        </div>
                                                        <span class="student-subline" title="{{ $primerEst?->nombre_completo ?? 'Sin estudiante' }}">
                                                            @if($primerEst){{ \Illuminate\Support\Str::limit($primerEst->nombre_completo, 26) }}@if($proyecto->estudiantes->count() > 1) · +{{ $proyecto->estudiantes->count() - 1 }}@endif @else Sin estudiante @endif
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
                                    <li><a href="{{ route('ups.proyectos.show', $alerta->id_proyecto) }}"><strong>#{{ $alerta->numero_proyecto ?? $alerta->id_proyecto }} · {{ \Illuminate\Support\Str::limit(\Illuminate\Support\Str::after($alerta->titulo, 'DEMO | '), 32) }}</strong><span>{{ $sem['plazo_humano'] }} · {{ $sem['etiqueta'] }}</span></a></li>
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
                if (savedPreference === 'true' || (savedPreference === null && window.matchMedia('(max-width: 650px)').matches)) {
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
