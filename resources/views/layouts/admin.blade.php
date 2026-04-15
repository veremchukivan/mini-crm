<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mini CRM Admin')</title>
    <style>
        :root {
            color-scheme: light;
            --bg: linear-gradient(180deg, #f4efe7 0%, #fbfaf7 100%);
            --panel: rgba(255, 255, 255, 0.88);
            --panel-solid: #fffdf8;
            --line: rgba(22, 39, 52, 0.1);
            --text: #162734;
            --muted: #667985;
            --accent: #c45534;
            --accent-soft: rgba(196, 85, 52, 0.12);
            --success: #dff6e6;
            --success-text: #17603a;
            --shadow: 0 24px 56px rgba(22, 39, 52, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Manrope, "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        a { color: inherit; text-decoration: none; }

        .page-shell {
            width: min(1280px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 40px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 26px;
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .brand small {
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            font-weight: 700;
        }

        .brand strong {
            font-size: 28px;
            line-height: 1.1;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chip,
        .button,
        .button-secondary,
        select,
        input,
        textarea {
            font: inherit;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.74);
            border: 1px solid var(--line);
            border-radius: 999px;
            color: var(--muted);
        }

        .button,
        .button-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 999px;
            padding: 12px 18px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .button {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 16px 30px rgba(196, 85, 52, 0.22);
        }

        .button-secondary {
            background: rgba(255, 255, 255, 0.78);
            color: var(--text);
            border: 1px solid var(--line);
        }

        .button:hover,
        .button-secondary:hover {
            transform: translateY(-1px);
        }

        .panel {
            background: var(--panel);
            border: 1px solid rgba(255, 255, 255, 0.62);
            border-radius: 26px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
        }

        .flash {
            margin-bottom: 18px;
            padding: 14px 18px;
            border-radius: 18px;
            background: var(--success);
            color: var(--success-text);
        }

        .stats-grid,
        .filter-grid,
        .ticket-grid {
            display: grid;
            gap: 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 18px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-card span {
            display: block;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .stat-card strong {
            font-size: 36px;
            line-height: 1;
        }

        .filter-panel,
        .content-panel {
            padding: 22px;
            margin-bottom: 18px;
        }

        .filter-grid {
            grid-template-columns: repeat(5, minmax(0, 1fr));
            align-items: end;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 14px;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: var(--panel-solid);
            color: var(--text);
        }

        .tickets-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tickets-table th,
        .tickets-table td {
            text-align: left;
            padding: 16px 12px;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }

        .tickets-table th {
            color: var(--muted);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-badge {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 13px;
            font-weight: 700;
        }

        .status-badge[data-status="processed"] {
            background: rgba(32, 153, 85, 0.12);
            color: #167646;
        }

        .status-badge[data-status="in_progress"] {
            background: rgba(18, 113, 175, 0.12);
            color: #115c90;
        }

        .meta-text {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 18px;
        }

        .detail-card {
            padding: 22px;
        }

        .detail-card h2 {
            margin: 0 0 18px;
        }

        .detail-list {
            display: grid;
            gap: 14px;
        }

        .detail-list div {
            padding-bottom: 14px;
            border-bottom: 1px solid var(--line);
        }

        .detail-list strong {
            display: block;
            margin-bottom: 6px;
        }

        .attachment-list {
            display: grid;
            gap: 12px;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.7);
        }

        .pagination {
            margin-top: 20px;
        }

        .empty-state {
            padding: 42px 18px;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 960px) {
            .stats-grid,
            .filter-grid,
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .topbar-actions {
                width: 100%;
                justify-content: space-between;
            }

            .tickets-table,
            .tickets-table thead,
            .tickets-table tbody,
            .tickets-table th,
            .tickets-table td,
            .tickets-table tr {
                display: block;
            }

            .tickets-table thead {
                display: none;
            }

            .tickets-table tr {
                border-bottom: 1px solid var(--line);
                padding: 12px 0;
            }

            .tickets-table td {
                border-bottom: none;
                padding: 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="topbar">
            <div class="brand">
                <small>Mini CRM</small>
                <strong>@yield('page_title', 'Панель менеджера')</strong>
            </div>

            <div class="topbar-actions">
                <span class="chip">{{ auth()->user()?->name }} · {{ auth()->user()?->email }}</span>

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="button-secondary" type="submit">Вийти</button>
                </form>
            </div>
        </div>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
