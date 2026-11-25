<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-head')
    <title>@yield('code', 'Error') — Gosyen Assessment</title>
    <link rel="icon" href="https://gosyenpolinator.com/images/gosyen_logo.png">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light dark;
            --bg-canvas: #f1f5f9;
            --bg-surface: #ffffff;
            --bg-subtle: #f8fafc;
            --border-subtle: #e2e8f0;
            --border-strong: #cbd5e1;
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --brand-primary: #1d4ed8;
            --brand-primary-hover: #1e40af;
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg-canvas: #0b1120;
                --bg-surface: #111827;
                --bg-subtle: #1e293b;
                --border-subtle: #1e293b;
                --border-strong: #334155;
                --text-primary: #f8fafc;
                --text-muted: #94a3b8;
                --brand-primary: #3b82f6;
                --brand-primary-hover: #2563eb;
            }
        }
        :root[data-theme="dark"] {
            color-scheme: dark;
            --bg-canvas: #0b1120;
            --bg-surface: #111827;
            --bg-subtle: #1e293b;
            --border-subtle: #1e293b;
            --border-strong: #334155;
            --text-primary: #f8fafc;
            --text-muted: #94a3b8;
            --brand-primary: #3b82f6;
            --brand-primary-hover: #2563eb;
        }
        :root[data-theme="light"] {
            color-scheme: light;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            margin: 0;
        }
        .error-card {
            width: 100%;
            max-width: 500px;
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        .error-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(29, 78, 216, 0.12);
            color: var(--brand-primary);
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
        }
        .error-code {
            font-size: 56px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            color: var(--text-primary);
            margin-bottom: 10px;
            font-variant-numeric: tabular-nums;
        }
        .btn-primary-err {
            background-color: var(--brand-primary);
            border: none;
            color: #ffffff;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
        }
        .btn-primary-err:hover {
            background-color: var(--brand-primary-hover);
            color: #ffffff;
        }
        .btn-secondary-err {
            background-color: var(--bg-subtle);
            border: 1px solid var(--border-strong);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <img src="https://gosyenpolinator.com/images/gosyen_logo.png" width="48" height="48" alt="Gosyen Logo" class="mb-3" style="object-fit: contain;">
        <div>
            <span class="error-badge">Gosyen Assessment</span>
        </div>
        <div class="error-code">@yield('code')</div>
        <h4 class="fw-bold mb-2" style="color: var(--text-primary);">@yield('title')</h4>
        <p class="mb-4" style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">@yield('message')</p>

        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="{{ url('/') }}" class="btn-primary-err">Ke Portal Ujian</a>
            @if(session()->has('role_akses'))
                <a href="{{ url('/dashboard') }}" class="btn-secondary-err">Ke Dashboard</a>
            @endif
            <button type="button" onclick="window.history.back()" class="btn-secondary-err">Kembali</button>
        </div>
    </div>
</body>
</html>
