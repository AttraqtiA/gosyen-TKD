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
                } else {
                    var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
                }
            } catch (e) {}
        })();
    </script>
    <title>@yield('title', 'Admin Dashboard') — Gosyen Assessment</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="https://gosyenpolinator.com/images/gosyen_logo.png">

    <style>
        :root {
            color-scheme: light dark;

            /* Corporate Gosyen HRIS Tokens */
            --bg-canvas: #f1f5f9;
            --bg-surface: #ffffff;
            --bg-surface-subtle: #f8fafc;
            --bg-header: rgba(255, 255, 255, 0.92);
            --bg-input: #ffffff;
            --bg-table-head: #f8fafc;
            --bg-hover: #f1f5f9;

            --border-subtle: #e2e8f0;
            --border-strong: #cbd5e1;

            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;

            --brand-primary: #1d4ed8;
            --brand-primary-hover: #1e40af;
            --brand-soft: rgba(29, 78, 216, 0.09);
            --brand-border: rgba(29, 78, 216, 0.25);

            --accent-emerald: #059669;
            --accent-emerald-hover: #047857;
            --accent-danger: #dc2626;
            --accent-danger-hover: #b91c1c;

            --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.05);
            --shadow-card: 0 1px 3px rgba(15, 23, 42, 0.06), 0 4px 16px rgba(15, 23, 42, 0.04);
            --shadow-dropdown: 0 10px 30px rgba(15, 23, 42, 0.12);
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg-canvas: #0b1120;
                --bg-surface: #111827;
                --bg-surface-subtle: #1e293b;
                --bg-header: rgba(17, 24, 39, 0.92);
                --bg-input: #1e293b;
                --bg-table-head: #162032;
                --bg-hover: rgba(30, 41, 59, 0.65);

                --border-subtle: #1e293b;
                --border-strong: #334155;

                --text-primary: #f8fafc;
                --text-secondary: #cbd5e1;
                --text-muted: #94a3b8;

                --brand-primary: #3b82f6;
                --brand-primary-hover: #2563eb;
                --brand-soft: rgba(59, 130, 246, 0.16);
                --brand-border: rgba(59, 130, 246, 0.35);

                --accent-emerald: #10b981;
                --accent-emerald-hover: #059669;
                --accent-danger: #ef4444;
                --accent-danger-hover: #dc2626;

                --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.25);
                --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.35);
                --shadow-dropdown: 0 12px 32px rgba(0, 0, 0, 0.5);
            }
        }

        :root:has(> head > meta[name="color-scheme"][content="dark"]),
        :root[data-theme="dark"] {
            color-scheme: dark;
            --bg-canvas: #0b1120;
            --bg-surface: #111827;
            --bg-surface-subtle: #1e293b;
            --bg-header: rgba(17, 24, 39, 0.92);
            --bg-input: #1e293b;
            --bg-table-head: #162032;
            --bg-hover: rgba(30, 41, 59, 0.65);

            --border-subtle: #1e293b;
            --border-strong: #334155;

            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;

            --brand-primary: #3b82f6;
            --brand-primary-hover: #2563eb;
            --brand-soft: rgba(59, 130, 246, 0.16);
            --brand-border: rgba(59, 130, 246, 0.35);

            --accent-emerald: #10b981;
            --accent-emerald-hover: #059669;
            --accent-danger: #ef4444;
            --accent-danger-hover: #dc2626;

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.25);
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.35);
            --shadow-dropdown: 0 12px 32px rgba(0, 0, 0, 0.5);
        }

        :root:has(> head > meta[name="color-scheme"][content="light"]),
        :root[data-theme="light"] {
            color-scheme: light;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Theme-aware compatibility overrides for existing sub-pages */
        .text-white { color: var(--text-primary) !important; }
        .text-muted { color: var(--text-muted) !important; }
        .text-slate-200, .text-slate-300, .text-slate-350 { color: var(--text-secondary) !important; }
        .text-slate-400, .text-slate-500 { color: var(--text-muted) !important; }
        .bg-slate-900, .bg-slate-800 { background-color: var(--bg-surface-subtle) !important; color: var(--text-primary) !important; }
        .border-secondary { border-color: var(--border-strong) !important; }

        .btn-custom-blue, .btn-custom-emerald, .btn-custom-danger, .badge.bg-primary, .badge.bg-success, .badge.bg-danger {
            color: #ffffff !important;
        }

        /* Unified Sticky Corporate Header */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1030;
            background-color: var(--bg-header);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand-custom {
            font-weight: 800;
            font-size: 16px;
            letter-spacing: -0.3px;
            color: var(--text-primary) !important;
            text-decoration: none;
        }

        .brand-subtitle {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            display: block;
            line-height: 1.1;
        }

        .nav-link-custom {
            color: var(--text-secondary) !important;
            font-weight: 600;
            font-size: 13.5px;
            padding: 8px 13px !important;
            border-radius: 8px;
            transition: background-color 0.15s ease, color 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link-custom:hover {
            color: var(--text-primary) !important;
            background-color: var(--bg-hover);
        }

        .nav-link-custom.active,
        .nav-link-custom[aria-current="page"] {
            color: var(--brand-primary) !important;
            background-color: var(--brand-soft) !important;
            font-weight: 700;
        }

        .dropdown-menu-custom {
            background-color: var(--bg-surface) !important;
            border: 1px solid var(--border-subtle) !important;
            border-radius: 12px !important;
            box-shadow: var(--shadow-dropdown) !important;
            padding: 6px !important;
            min-width: 230px;
        }

        .dropdown-item-custom {
            color: var(--text-secondary) !important;
            font-size: 13.5px;
            font-weight: 600;
            padding: 9px 12px !important;
            border-radius: 8px !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-item-custom:hover {
            background-color: var(--bg-hover) !important;
            color: var(--text-primary) !important;
        }

        .dropdown-item-custom.active {
            background-color: var(--brand-soft) !important;
            color: var(--brand-primary) !important;
            font-weight: 700;
        }

        /* Top Progress Bar & Subtle Loading Overlay */
        #top-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, var(--brand-primary), #06b6d4);
            z-index: 99999;
            opacity: 0;
            transition: width 0.28s ease, opacity 0.2s ease;
            pointer-events: none;
        }
        #top-progress-bar.active {
            opacity: 1;
        }
        #app-loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(3px);
            z-index: 99998;
            display: none;
            align-items: center;
            justify-content: center;
        }
        #app-loading-overlay.show {
            display: flex;
        }
        .loading-pill {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            padding: 14px 24px;
            border-radius: 14px;
            box-shadow: var(--shadow-dropdown);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 600;
        }
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2.5px solid var(--border-strong);
            border-top-color: var(--brand-primary);
            border-radius: 50%;
            animation: spinLoader 0.7s linear infinite;
        }
        @keyframes spinLoader {
            to { transform: rotate(360deg); }
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border-strong);
            background-color: var(--bg-surface);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }
        .theme-toggle-btn:hover {
            background-color: var(--bg-hover);
            color: var(--text-primary);
        }
        .theme-toggle-btn .icon-sun { display: none; }
        .theme-toggle-btn .icon-moon { display: block; }
        :root:has(> head > meta[name="color-scheme"][content="dark"]) .theme-toggle-btn .icon-sun,
        :root[data-theme="dark"] .theme-toggle-btn .icon-sun { display: block; }
        :root:has(> head > meta[name="color-scheme"][content="dark"]) .theme-toggle-btn .icon-moon,
        :root[data-theme="dark"] .theme-toggle-btn .icon-moon { display: none; }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .theme-toggle-btn .icon-sun { display: block; }
            :root:not([data-theme="light"]) .theme-toggle-btn .icon-moon { display: none; }
        }

        .nav-utility-group {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 12px;
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--bg-surface-subtle);
            border: 1px solid var(--border-strong);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        .btn-logout-nav {
            padding: 7px 16px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Surface Cards */
        .card-custom {
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            background: var(--bg-surface);
            box-shadow: var(--shadow-card);
            padding: 24px;
            margin-bottom: 24px;
        }

        /* Forms */
        .form-control-custom, .form-select-custom {
            background-color: var(--bg-input) !important;
            border: 1px solid var(--border-strong) !important;
            color: var(--text-primary) !important;
            border-radius: 9px !important;
            padding: 9px 13px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            border-color: var(--brand-primary) !important;
            box-shadow: 0 0 0 3px var(--brand-soft) !important;
        }

        /* Buttons */
        .btn-custom {
            border-radius: 9px;
            padding: 9px 18px;
            font-weight: 600;
            font-size: 13.5px;
            transition: all 0.15s ease;
        }

        .btn-custom-blue {
            background-color: var(--brand-primary);
            color: #fff;
            border: none;
        }
        .btn-custom-blue:hover {
            background-color: var(--brand-primary-hover);
            color: #fff;
        }

        .btn-custom-emerald {
            background-color: var(--accent-emerald);
            color: #fff;
            border: none;
        }
        .btn-custom-emerald:hover {
            background-color: var(--accent-emerald-hover);
            color: #fff;
        }

        .btn-custom-danger {
            background-color: var(--accent-danger);
            color: #fff;
            border: none;
        }
        .btn-custom-danger:hover {
            background-color: var(--accent-danger-hover);
            color: #fff;
        }

        .btn-custom-secondary {
            background-color: var(--bg-surface);
            color: var(--text-secondary);
            border: 1px solid var(--border-strong);
        }
        .btn-custom-secondary:hover {
            background-color: var(--bg-hover);
            color: var(--text-primary);
        }

        /* Tables */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13.5px;
            color: var(--text-secondary);
            font-variant-numeric: tabular-nums;
        }
        .table-custom th {
            background-color: var(--bg-table-head);
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-strong);
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .table-custom td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-subtle);
            vertical-align: middle;
        }
        .table-custom tbody tr:hover {
            background-color: var(--bg-hover);
        }

        .badge-kunci {
            background-color: rgba(16, 185, 129, 0.12);
            color: var(--accent-emerald);
            border: 1px solid rgba(16, 185, 129, 0.3);
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
        }

        /* Modal theming override */
        .modal-content {
            background-color: var(--bg-surface) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-subtle) !important;
            border-radius: 14px !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div id="top-progress-bar" aria-hidden="true"></div>
    <div id="app-loading-overlay" role="status" aria-live="polite" aria-label="Memuat">
        <div class="loading-pill">
            <div class="loading-spinner" aria-hidden="true"></div>
            <span>Memproses data...</span>
        </div>
    </div>

    <header class="site-header mb-4">
        <nav class="navbar navbar-expand-lg" aria-label="Primary">
            <div class="container-fluid px-3 px-lg-4">
                <a class="navbar-brand navbar-brand-custom d-flex align-items-center gap-3" href="{{ route('dashboard.index') }}">
                    <img src="https://gosyenpolinator.com/images/gosyen_logo.png" width="34" height="34" alt="Gosyen Logo" style="object-fit: contain;">
                    <div>
                        <span>Gosyen Assessment</span>
                        <span class="brand-subtitle">Portal Psikologi &amp; TKD</span>
                    </div>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="adminNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-1">
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom {{ Route::is('dashboard.index') ? 'active' : '' }}"
                               href="{{ route('dashboard.index') }}"
                               @if(Route::is('dashboard.index')) aria-current="page" @endif>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                Beranda
                            </a>
                        </li>

                        @if (session('role_akses') === 'admin')
                            @php
                                $isMasterActive = Route::is('dashboard.perusahaan', 'dashboard.posisi', 'dashboard.rubrik');
                                $isSoalActive = Route::is('dashboard.sesi1', 'dashboard.sesi2', 'dashboard.sesi3', 'dashboard.sesi4', 'dashboard.sesi5');
                            @endphp
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-link-custom dropdown-toggle {{ $isMasterActive ? 'active' : '' }}"
                                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                    Master Data
                                </a>
                                <ul class="dropdown-menu dropdown-menu-custom">
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.perusahaan') ? 'active' : '' }}"
                                           href="{{ route('dashboard.perusahaan') }}"
                                           @if(Route::is('dashboard.perusahaan')) aria-current="page" @endif>
                                            Kelola Perusahaan
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.posisi') ? 'active' : '' }}"
                                           href="{{ route('dashboard.posisi') }}"
                                           @if(Route::is('dashboard.posisi')) aria-current="page" @endif>
                                            Kelola Posisi
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1" style="border-color: var(--border-subtle);"></li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.rubrik') ? 'active' : '' }}"
                                           href="{{ route('dashboard.rubrik') }}"
                                           @if(Route::is('dashboard.rubrik')) aria-current="page" @endif>
                                            Rubrik Penilaian
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link nav-link-custom dropdown-toggle {{ $isSoalActive ? 'active' : '' }}"
                                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    Bank Soal
                                </a>
                                <ul class="dropdown-menu dropdown-menu-custom">
                                    <li><a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.sesi1') ? 'active' : '' }}" href="{{ route('dashboard.sesi1') }}">Sesi 1 — WA (Pilihan Kata)</a></li>
                                    <li><a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.sesi2') ? 'active' : '' }}" href="{{ route('dashboard.sesi2') }}">Sesi 2 — AN (Analogi)</a></li>
                                    <li><a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.sesi3') ? 'active' : '' }}" href="{{ route('dashboard.sesi3') }}">Sesi 3 — ZR (Deret Angka)</a></li>
                                    <li><a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.sesi4') ? 'active' : '' }}" href="{{ route('dashboard.sesi4') }}">Sesi 4 — FA (Kunci Gambar)</a></li>
                                    <li><a class="dropdown-item dropdown-item-custom {{ Route::is('dashboard.sesi5') ? 'active' : '' }}" href="{{ route('dashboard.sesi5') }}">Sesi 5 — Essay &amp; Studi Kasus</a></li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link nav-link-custom {{ Route::is('dashboard.rubrik') ? 'active' : '' }}"
                                   href="{{ route('dashboard.rubrik') }}"
                                   @if(Route::is('dashboard.rubrik')) aria-current="page" @endif>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                    Rubrik Penilaian
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="nav-utility-group mt-3 mt-lg-0">
                        <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Ganti Tema Terang/Gelap" title="Ganti Tema (Terang / Gelap)">
                            <svg class="icon-sun" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                            </svg>
                            <svg class="icon-moon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                            </svg>
                        </button>

                        <div class="role-pill">
                            <span>Role:</span>
                            <span class="badge {{ session('role_akses') === 'admin' ? 'bg-primary' : 'bg-success' }} px-2 py-1" style="font-size: 11px;">
                                {{ session('role_akses') === 'admin' ? 'Admin' : 'Psikolog' }}
                            </span>
                        </div>

                        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-logout-nav">Keluar</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-fluid px-4 pb-5" style="max-width: 1440px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function () {
            var btn = document.getElementById('themeToggleBtn');
            var meta = document.querySelector('meta[name="color-scheme"]');
            var mql = window.matchMedia('(prefers-color-scheme: dark)');

            function applyScheme(scheme) {
                if (scheme === 'light' || scheme === 'dark') {
                    if (meta) meta.content = scheme;
                    document.documentElement.setAttribute('data-theme', scheme);
                    document.documentElement.setAttribute('data-bs-theme', scheme);
                } else {
                    if (meta) meta.content = 'light dark';
                    document.documentElement.removeAttribute('data-theme');
                    document.documentElement.setAttribute('data-bs-theme', mql.matches ? 'dark' : 'light');
                }
            }

            if (btn) {
                btn.addEventListener('click', function () {
                    var stored = null;
                    try { stored = localStorage.getItem('color-scheme'); } catch (e) {}
                    var current = stored || (mql.matches ? 'dark' : 'light');
                    var target = current === 'dark' ? 'light' : 'dark';
                    var sysTheme = mql.matches ? 'dark' : 'light';

                    try {
                        if (target === sysTheme) {
                            localStorage.removeItem('color-scheme');
                            applyScheme(null);
                        } else {
                            localStorage.setItem('color-scheme', target);
                            applyScheme(target);
                        }
                    } catch (e) {
                        applyScheme(target);
                    }
                });
            }

            window.addEventListener('storage', function (e) {
                if (e.key === 'color-scheme') {
                    applyScheme(e.newValue);
                }
            });

            mql.addEventListener('change', function () {
                var stored = null;
                try { stored = localStorage.getItem('color-scheme'); } catch (e) {}
                if (!stored) applyScheme(null);
            });

            // Subtle Top Progress Bar & Loading Overlay
            var progressBar = document.getElementById('top-progress-bar');
            var loadingOverlay = document.getElementById('app-loading-overlay');

            function startProgress() {
                if (!progressBar) return;
                progressBar.classList.add('active');
                progressBar.style.width = '70%';
            }

            function resetProgress() {
                if (progressBar) {
                    progressBar.style.width = '100%';
                    setTimeout(function () {
                        progressBar.classList.remove('active');
                        progressBar.style.width = '0%';
                    }, 200);
                }
                if (loadingOverlay) loadingOverlay.classList.remove('show');
            }

            document.addEventListener('click', function (e) {
                var link = e.target.closest('a[href]');
                if (!link) return;
                var href = link.getAttribute('href');
                if (!href || href.startsWith('#') || link.target === '_blank' || link.hasAttribute('download')) return;
                if (href.includes('export') || href.includes('/pdf/') || href.includes('/word/')) return;
                startProgress();
            });

            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (form && form.method && form.method.toLowerCase() === 'post') {
                    startProgress();
                    if (loadingOverlay) loadingOverlay.classList.add('show');
                } else {
                    startProgress();
                }
            });

            window.addEventListener('pageshow', resetProgress);
        })();
    </script>
    @yield('scripts')
</body>
</html>
