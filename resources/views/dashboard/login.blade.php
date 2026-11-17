<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('color-scheme');
                var meta = document.querySelector('meta[name="color-scheme"]');
                if (meta && (saved === 'light' || saved === 'dark')) {
                    meta.content = saved;
                    document.documentElement.setAttribute('data-theme', saved);
                    document.documentElement.setAttribute('data-bs-theme', saved);
                }
            } catch (e) {}
        })();
    </script>
    <title>Login Dashboard — Gosyen Assessment</title>
    <link rel="icon" href="https://gosyenpolinator.com/images/gosyen_logo.png">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light dark;
            --bg-canvas: #f1f5f9;
            --bg-surface: #ffffff;
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
                --border-subtle: #1e293b;
                --border-strong: #334155;
                --text-primary: #f8fafc;
                --text-muted: #94a3b8;
                --brand-primary: #3b82f6;
                --brand-primary-hover: #2563eb;
            }
        }
        :root:has(> head > meta[name="color-scheme"][content="dark"]),
        :root[data-theme="dark"] {
            color-scheme: dark;
            --bg-canvas: #0b1120;
            --bg-surface: #111827;
            --border-subtle: #1e293b;
            --border-strong: #334155;
            --text-primary: #f8fafc;
            --text-muted: #94a3b8;
            --brand-primary: #3b82f6;
            --brand-primary-hover: #2563eb;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 36px 32px;
            border-radius: 16px;
            border: 1px solid var(--border-subtle);
            background: var(--bg-surface);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
        .pin-input {
            background-color: var(--bg-canvas);
            border: 1px solid var(--border-strong);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 12px 15px;
            letter-spacing: 6px;
            font-size: 20px;
            font-weight: 700;
        }
        .pin-input:focus {
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        .btn-login {
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            background-color: var(--brand-primary);
            border: none;
            color: #ffffff;
        }
        .btn-login:hover {
            background-color: var(--brand-primary-hover);
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="https://gosyenpolinator.com/images/gosyen_logo.png" width="52" height="52" alt="Gosyen Logo" class="mb-3" style="object-fit: contain;">
            <h4 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1.25rem;">Portal Dashboard</h4>
            <p class="mb-0" style="color: var(--text-muted); font-size: 13px;">Gosyen Assessment &amp; Psikotes</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center py-2" style="border-radius: 10px; font-size: 13.5px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold" style="color: var(--text-muted); font-size: 13px;">Masukkan PIN Akses (Admin / Psikolog)</label>
                <input type="password" name="pin" class="form-control form-control-lg text-center pin-input" placeholder="••••••" required autofocus>
            </div>
            <button type="submit" class="btn btn-login btn-lg w-100">Masuk ke Dashboard</button>
        </form>
    </div>
</body>
</html>
