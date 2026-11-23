<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
    <meta name="theme-color" content="#1d4ed8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Psikotes">
    
    <title>@yield('title', 'Portal Ujian Psikologi') — Gosyen Assessment</title>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/icon-192.png') }}">
    <link rel="icon" href="https://gosyenpolinator.com/images/gosyen_logo.png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            color-scheme: light dark;
            --primary: #1d4ed8; --primary-dark: #1e40af; --secondary: #0284c7;
            --success: #059669; --danger: #dc2626; --warning: #d97706;
            --dark: #0f172a; --gray: #64748b; --light: #f8fafc;
            --bg-canvas: #f1f5f9;
            --bg-surface: #ffffff;
            --bg-subtle: #f8fafc;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --hero-text: #0f172a;
            --shadow-card: 0 1px 3px rgba(15, 23, 42, 0.06), 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --primary: #3b82f6; --primary-dark: #2563eb; --secondary: #0ea5e9;
                --dark: #f8fafc; --gray: #94a3b8; --light: #1e293b;
                --bg-canvas: #0b1120;
                --bg-surface: #111827;
                --bg-subtle: #1e293b;
                --border: #1e293b;
                --border-strong: #334155;
                --card: #111827;
                --text-primary: #f8fafc;
                --text-secondary: #cbd5e1;
                --text-muted: #94a3b8;
                --hero-text: #f8fafc;
                --shadow-card: 0 4px 24px rgba(0, 0, 0, 0.4);
            }
        }

        :root:has(> head > meta[name="color-scheme"][content="dark"]),
        :root[data-theme="dark"] {
            color-scheme: dark;
            --primary: #3b82f6; --primary-dark: #2563eb; --secondary: #0ea5e9;
            --dark: #f8fafc; --gray: #94a3b8; --light: #1e293b;
            --bg-canvas: #0b1120;
            --bg-surface: #111827;
            --bg-subtle: #1e293b;
            --border: #1e293b;
            --border-strong: #334155;
            --card: #111827;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --hero-text: #f8fafc;
            --shadow-card: 0 4px 24px rgba(0, 0, 0, 0.4);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            min-height: 100vh; color: var(--text-primary); padding: 28px;
            overscroll-behavior-y: contain;
            -ms-overflow-style: none; scrollbar-width: none;
        }
        body::-webkit-scrollbar { width: 0; height: 0; }
        
        .main-wrapper { max-width: 1200px; margin: auto; padding-top: 24px; }
        .hero { text-align: center; color: var(--hero-text); margin-bottom: 28px; }
        .hero h1 { font-size: 32px; margin-bottom: 8px; font-weight: 800; letter-spacing: -0.5px; }
        .hero p { color: var(--text-secondary); font-size: 15px; font-weight: 500; }
        
        .layout { display: grid; grid-template-columns: 310px 1fr; gap: 24px; align-items: start; }
        .sidebar, .content {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 24px;
            box-shadow: var(--shadow-card);
        }
        .sidebar { position: sticky; top: 20px; }
        .logo-box { text-align: center; margin-bottom: 22px; }
        .logo-box img { width: 76px; height: 76px; object-fit: contain; margin-bottom: 10px; }
        
        .badge-custom {
            display: inline-block; background: rgba(37,99,235,0.14);
            color: var(--primary); padding: 6px 14px;
            border-radius: 999px; font-size: 12.5px; font-weight: 700;
            border: 1px solid rgba(37,99,235,0.28);
        }
        :root:has(> head > meta[name="color-scheme"][content="dark"]) .badge-custom,
        :root[data-theme="dark"] .badge-custom {
            background: rgba(59,130,246,0.2);
            color: #93c5fd;
            border-color: rgba(147,197,253,0.35);
        }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .badge-custom {
                background: rgba(59,130,246,0.2);
                color: #93c5fd;
                border-color: rgba(147,197,253,0.35);
            }
        }
        .info-card {
            background: var(--bg-subtle); border: 1px solid var(--border);
            border-radius: 14px; padding: 16px; margin-bottom: 16px;
        }
        .info-card h3 { font-size: 14.5px; font-weight: 700; margin-bottom: 8px; color: var(--primary); }
        .info-card p, .info-card li { font-size: 13.5px; line-height: 1.65; color: var(--text-secondary); }
        .info-card ul { padding-left: 18px; }
        
        .timer-box {
            background: linear-gradient(135deg, var(--danger), #991b1b);
            color: white; text-align: center; padding: 16px;
            border-radius: 14px; margin-top: 14px;
            box-shadow: 0 6px 18px rgba(220,38,38,0.2);
        }
        .timer-box p { margin: 0; font-size: 13px; opacity: 0.9; }
        .timer-box h2 { font-size: 32px; margin-top: 4px; margin-bottom: 0; letter-spacing: 2px; font-weight: 800; font-variant-numeric: tabular-nums; }
        
        .progress-wrapper { margin-top: 18px; }
        .progress-header {
            display: flex; justify-content: space-between;
            margin-bottom: 8px; font-size: 13.5px; font-weight: 600;
            color: var(--text-secondary);
        }
        .progress-track {
            width: 100%; height: 12px; background: var(--bg-subtle);
            border: 1px solid var(--border);
            border-radius: 999px; overflow: hidden;
        }
        .progress-fill {
            height: 100%; width: 0%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 999px; transition: width 0.4s ease;
        }
        
        .question-card {
            border: 1px solid var(--border); border-radius: 14px;
            padding: 22px; margin-bottom: 18px; transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: var(--bg-surface); opacity: 0; transform: translateY(14px);
            animation: fadeInUp 0.35s ease forwards;
        }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
        .question-card:hover { border-color: var(--border-strong); box-shadow: var(--shadow-card); }
        .question-number {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: var(--primary);
            color: white; font-weight: 700; font-size: 14px; margin-bottom: 14px;
        }
        .question-text {
            font-size: 15px; font-weight: 500; margin-bottom: 16px;
            color: var(--text-primary); line-height: 1.6;
        }
        
        /* Option inputs styling */
        .option { margin-bottom: 10px; }
        .option input[type="radio"], .option input[type="checkbox"] { display: none; }
        .option label {
            display: flex; align-items: center; gap: 12px; padding: 13px 15px;
            border-radius: 12px; border: 1px solid var(--border-strong);
            cursor: pointer; transition: all 0.2s ease;
            font-size: 14.5px; font-weight: 500;
            color: var(--text-primary);
            background: var(--bg-surface);
            width: 100%; margin-bottom: 0;
        }
        .option label:hover { background: var(--bg-subtle); border-color: var(--primary); }
        .option input:checked + label {
            background: rgba(37, 99, 235, 0.1);
            border-color: var(--primary); color: var(--primary); font-weight: 600;
        }
        .option-badge {
            width: 32px; height: 32px; border-radius: 8px;
            background: var(--bg-subtle); border: 1px solid var(--border); display: flex; align-items: center;
            justify-content: center; font-weight: 700; font-size: 13px;
            color: var(--text-secondary); flex-shrink: 0;
        }
        .option input:checked + label .option-badge {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .submit-btn {
            width: 100%; padding: 15px; border: none; border-radius: 12px;
            background: var(--primary);
            color: white; font-size: 15.5px; font-weight: 700;
            cursor: pointer; transition: background-color 0.2s ease; margin-top: 10px;
        }
        .submit-btn:hover { background: var(--primary-dark); }
        
        .warning-box {
            background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.35);
            color: var(--warning); padding: 16px; border-radius: 12px;
            margin-bottom: 20px; line-height: 1.7; font-size: 14px;
        }
        .agreement {
            display: flex; gap: 12px; align-items: flex-start;
            margin-top: 18px; background: var(--bg-subtle); border: 1px solid var(--border);
            padding: 15px; border-radius: 12px;
            color: var(--text-secondary);
        }
        .agreement input { transform: scale(1.25); margin-top: 4px; }
        
        .start-btn {
            margin-top: 18px; width: 100%; padding: 15px; border: none;
            border-radius: 12px; background: var(--success);
            color: white; font-weight: 700; cursor: pointer; transition: opacity 0.2s;
        }
        .start-btn:hover { opacity: 0.92; }
        
        .hidden { display: none !important; }
        @keyframes pulse { 0%{transform:scale(1)} 50%{transform:scale(1.05)} 100%{transform:scale(1)} }
        .critical { animation: pulse 1s infinite; }
        
        /* Anti-cheat Freeze CSS */
        #freeze-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.96);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: 30px;
            backdrop-filter: blur(10px);
        }
        .freeze-card {
            background: rgba(239, 68, 68, 0.12);
            border: 2px solid #ef4444;
            border-radius: 24px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(239, 68, 68, 0.2);
            animation: pulse 2s infinite;
        }
        .siren-icon {
            animation: pulse 0.5s infinite;
            color: #ef4444;
            margin-bottom: 20px;
        }
        
        /* Toast notifikasi PWA */
        #pwa-toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #198754;
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 9999;
            display: none;
            max-width: 90%;
            text-align: center;
            font-size: 14px;
        }
        #pwa-toast button {
            background: none;
            border: none;
            color: white;
            margin-left: 10px;
            font-weight: 600;
            text-decoration: underline;
        }
        
        @media (max-width: 960px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: relative; top: 0; }
        }
        @media (max-width: 600px) {
            body { padding: 15px; }
            .hero h1 { font-size: 28px; }
            .sidebar, .content { padding: 18px; border-radius: 20px; }
        }
        
        .utility-bar {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .language-switcher {
            background: var(--bg-surface);
            border: 1px solid var(--border-strong);
            padding: 4px;
            border-radius: 999px;
            box-shadow: var(--shadow-card);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .language-switcher a {
            text-decoration: none;
            color: var(--text-secondary);
            padding: 5px 11px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.2s, color 0.2s;
        }
        .language-switcher a:hover {
            background: var(--bg-subtle);
            color: var(--text-primary);
        }
        .language-switcher a.active {
            background: var(--primary);
            color: #ffffff;
        }
        .flag-svg {
            width: 18px;
            height: 13px;
            border-radius: 2px;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
        }
        .theme-toggle-pill {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            border: 1px solid var(--border-strong);
            background: var(--bg-surface);
            color: var(--text-secondary);
            box-shadow: var(--shadow-card);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        .theme-toggle-pill:hover {
            background: var(--bg-subtle);
            color: var(--text-primary);
        }
        #top-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            z-index: 99999;
            opacity: 0;
            transition: width 0.28s ease, opacity 0.2s ease;
            pointer-events: none;
        }
        #top-progress-bar.active { opacity: 1; }
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
        #app-loading-overlay.show { display: flex; }
        .loading-pill {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            color: var(--text-primary);
            padding: 14px 24px;
            border-radius: 14px;
            box-shadow: var(--shadow-card);
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
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spinLoader 0.7s linear infinite;
        }
        @keyframes spinLoader { to { transform: rotate(360deg); } }
        .theme-toggle-pill .icon-sun { display: none; }
        .theme-toggle-pill .icon-moon { display: block; }
        :root:has(> head > meta[name="color-scheme"][content="dark"]) .theme-toggle-pill .icon-sun,
        :root[data-theme="dark"] .theme-toggle-pill .icon-sun { display: block; }
        :root:has(> head > meta[name="color-scheme"][content="dark"]) .theme-toggle-pill .icon-moon,
        :root[data-theme="dark"] .theme-toggle-pill .icon-moon { display: none; }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .theme-toggle-pill .icon-sun { display: block; }
            :root:not([data-theme="light"]) .theme-toggle-pill .icon-moon { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div id="top-progress-bar" aria-hidden="true"></div>
    <div id="app-loading-overlay" role="status" aria-live="polite" aria-label="Memuat">
        <div class="loading-pill">
            <div class="loading-spinner" aria-hidden="true"></div>
            <span>{{ __('Memproses...') }}</span>
        </div>
    </div>
    <div class="utility-bar">
        <div class="language-switcher" role="group" aria-label="Pilih Bahasa">
            <a href="{{ route('lang.switch', 'id') }}"
               class="{{ App::getLocale() == 'id' ? 'active' : '' }}"
               aria-label="Bahasa Indonesia"
               @if(App::getLocale() == 'id') aria-current="true" @endif>
                <svg class="flag-svg" viewBox="0 0 3 2" aria-hidden="true">
                    <rect width="3" height="1" y="0" fill="#CE1126"/>
                    <rect width="3" height="1" y="1" fill="#FFFFFF"/>
                </svg>
                <span>ID</span>
            </a>
            <a href="{{ route('lang.switch', 'en') }}"
               class="{{ App::getLocale() == 'en' ? 'active' : '' }}"
               aria-label="English"
               @if(App::getLocale() == 'en') aria-current="true" @endif>
                <svg class="flag-svg" viewBox="0 0 60 30" aria-hidden="true">
                    <clipPath id="uk-clip"><rect width="60" height="30"/></clipPath>
                    <g clip-path="url(#uk-clip)">
                        <rect width="60" height="30" fill="#012169"/>
                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6"/>
                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#C8102E" stroke-width="2"/>
                        <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10"/>
                        <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6"/>
                    </g>
                </svg>
                <span>EN</span>
            </a>
        </div>

        <button type="button" class="theme-toggle-pill" id="ujianThemeToggle" aria-label="Ganti Tema Terang/Gelap" title="Ganti Tema (Terang / Gelap)">
            <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
            <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>
    </div>

    @yield('content')

    <!-- Anti-cheat Freeze Overlay -->
    <div id="freeze-overlay" class="hidden">
        <div class="freeze-card">
            <div class="siren-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                  <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
            </div>
            <h2 class="fw-bold mb-3" style="color: #ef4444;">{{ __('Peringatan!') }}</h2>
            <p class="mb-4" style="font-size: 15px; line-height: 1.6; color: #cbd5e1;">{{ __('Anda terdeteksi melakukan kecurangan! (Berpindah Tab / Window)') }}<br>{{ __('Mohon kembali ke halaman ujian. Ujian Anda akan dibekukan sementara.') }}</p>
            <h3 class="fw-bold" style="color: #06b6d4; margin: 0;">{{ __('Layar akan terbuka kembali dalam') }}: <span id="freeze-countdown">30</span> {{ __('detik') }}</h3>
            <p id="freeze-warning-text" class="fw-bold mt-4 mb-0" style="color: #fca5a5; font-size: 13px; padding: 12px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px;"></p>
        </div>
    </div>

    <!-- Toast untuk install PWA -->
    <div id="pwa-toast">
        📱 {{ __('Instal aplikasi untuk pengalaman ujian lebih baik') }}
        <button id="install-btn">{{ __('Instal') }}</button>
        <button id="close-toast" style="margin-left:5px;">✕</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Register Service Worker & Mobile tweaks -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}")
                    .then(reg => console.log('✅ SW registered:', reg.scope))
                    .catch(err => console.log('❌ SW failed:', err));
            });
        }

        let deferredPrompt;
        const toast = document.getElementById('pwa-toast');
        const installBtn = document.getElementById('install-btn');
        const closeToast = document.getElementById('close-toast');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (toast) toast.style.display = 'block';
        });

        installBtn?.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`Install ${outcome}`);
                deferredPrompt = null;
                toast.style.display = 'none';
            }
        });

        closeToast?.addEventListener('click', () => {
            toast.style.display = 'none';
        });

        // Anti double-tap zoom for iOS
        let lastTouchEnd = 0;
        document.addEventListener('touchend', (e) => {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) e.preventDefault();
            lastTouchEnd = now;
        }, { passive: false });

        // Global Anti-cheat Freeze & Alarm Sound
        document.addEventListener('DOMContentLoaded', function() {
            const formUjian = document.getElementById('formUjian');
            if (!formUjian) return;

            // Hide language switcher during active timed exam so candidate cannot lose answers mid-session
            const langSwitcher = document.querySelector('.language-switcher');
            if (langSwitcher) langSwitcher.style.display = 'none';

            // Block copy/cut/paste, right-click, and DevTools shortcuts during active exam
            document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
            ['copy', 'cut', 'paste'].forEach(function(evt) {
                document.addEventListener(evt, function(e) { e.preventDefault(); });
            });
            document.addEventListener('keydown', function(e) {
                const key = (e.key || '').toUpperCase();
                if (
                    e.key === 'F12' ||
                    ((e.ctrlKey || e.metaKey) && e.shiftKey && ['I', 'J', 'C'].includes(key)) ||
                    ((e.ctrlKey || e.metaKey) && key === 'U')
                ) {
                    e.preventDefault();
                }
            });

            const overlay = document.getElementById('freeze-overlay');
            const counter = document.getElementById('freeze-countdown');
            let isFrozen = false;
            let isSubmitting = false;
            let freezeTimer;
            let freezeLeft = 30;

            formUjian.addEventListener('submit', function() {
                isSubmitting = true;
            });

            let globalAudioCtx = null;
            function initAudio() {
                if (!globalAudioCtx) {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (AudioContext) {
                        globalAudioCtx = new AudioContext();
                        
                        // iOS Safari requires playing a sound during user interaction to unlock AudioContext
                        const osc = globalAudioCtx.createOscillator();
                        const gain = globalAudioCtx.createGain();
                        gain.gain.value = 0;
                        osc.connect(gain);
                        gain.connect(globalAudioCtx.destination);
                        osc.start(0);
                        osc.stop(globalAudioCtx.currentTime + 0.1);
                    }
                }
                if (globalAudioCtx && globalAudioCtx.state === 'suspended') {
                    globalAudioCtx.resume();
                }
            }
            document.addEventListener('click', initAudio, { once: true });
            document.addEventListener('touchstart', initAudio, { once: true });

            function playAlarmSound() {
                try {
                    if (!globalAudioCtx) {
                        initAudio();
                    }
                    const ctx = globalAudioCtx;
                    if (!ctx) return;
                    if (ctx.state === 'suspended') {
                        ctx.resume();
                    }
                    
                    const osc1 = ctx.createOscillator();
                    const osc2 = ctx.createOscillator();
                    const gainNode = ctx.createGain();
                    const modulationGain = ctx.createGain();
                    
                    osc1.type = 'sawtooth';
                    osc1.frequency.setValueAtTime(600, ctx.currentTime);
                    
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(4, ctx.currentTime);
                    
                    modulationGain.gain.setValueAtTime(200, ctx.currentTime);
                    
                    osc2.connect(modulationGain);
                    modulationGain.connect(osc1.frequency);
                    
                    osc1.connect(gainNode);
                    gainNode.connect(ctx.destination);
                    
                    gainNode.gain.setValueAtTime(0.3, ctx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 4);
                    
                    osc1.start();
                    osc2.start();
                    
                    osc1.stop(ctx.currentTime + 4);
                    osc2.stop(ctx.currentTime + 4);
                } catch(e) {
                    console.error("Audio Context Error: ", e);
                }
            }

            let flashInterval;
            function applyPenaltyEffects() {
                if (!document.hidden) {
                    // Bunyikan alarm dan getar
                    playAlarmSound();
                    
                    if (navigator.vibrate) {
                        // Getar untuk Android (iOS Safari tidak support API ini)
                        navigator.vibrate([1000, 500, 1000, 500, 1000]);
                    }

                    // Visual Strobe Alarm (Sangat efektif untuk iOS yang di-silent / tidak support haptic)
                    clearInterval(flashInterval);
                    let isRed = false;
                    const overlayEl = document.getElementById('freeze-overlay');
                    flashInterval = setInterval(() => {
                        overlayEl.style.backgroundColor = isRed ? 'rgba(15, 23, 42, 0.96)' : 'rgba(220, 38, 38, 0.95)';
                        isRed = !isRed;
                    }, 150);
                    
                    // Stop strobing after 4 seconds (sama dengan durasi audio alarm)
                    setTimeout(() => {
                        clearInterval(flashInterval);
                        if (overlayEl) overlayEl.style.backgroundColor = '';
                    }, 4000);
                }
            }

            function startFreezeCountdown(seconds, totalViolations) {
                overlay.classList.remove('hidden');
                freezeLeft = seconds;
                counter.innerText = freezeLeft;

                let remainingAttempts = 3 - totalViolations;
                let warningText = document.getElementById('freeze-warning-text');
                const isEn = {{ App::getLocale() === 'en' ? 'true' : 'false' }};
                if (warningText) {
                    if (remainingAttempts > 0) {
                        warningText.innerText = isEn
                            ? `Strict Warning! If you leave the exam tab ${remainingAttempts} more time(s), the exam will be automatically stopped and submitted with a score of 0.`
                            : `Peringatan Keras! Jika Anda keluar dari tab ujian ${remainingAttempts} kali lagi, ujian akan otomatis dihentikan dan disubmit dengan nilai 0.`;
                    } else {
                        warningText.innerText = isEn
                            ? `Violation limit reached. Processing exam submission...`
                            : `Batas pelanggaran telah tercapai. Ujian sedang diproses...`;
                    }
                }

                clearInterval(freezeTimer);
                freezeTimer = setInterval(function() {
                    freezeLeft--;
                    counter.innerText = freezeLeft;
                    if (freezeLeft <= 0) {
                        clearInterval(freezeTimer);
                        overlay.classList.add('hidden');
                        isFrozen = false;
                        clearInterval(flashInterval);
                        overlay.style.backgroundColor = '';
                    }
                }, 1000);
            }

            // Resume active freeze if candidate hit F5/Reload during penalty
            const serverFreezeLeft = {{ max(0, (int)session('freeze_until', 0) - now()->timestamp) }};
            if (serverFreezeLeft > 0) {
                isFrozen = true;
                startFreezeCountdown(serverFreezeLeft, {{ (int)session('total_pelanggaran', 0) }});
            }

            function triggerFreeze() {
                if (isFrozen || isSubmitting) return;
                isFrozen = true;

                // Increment local hidden input
                const pelanggaranInput = document.getElementById('pelanggaran_sesi');
                let currentSessionViolations = 0;
                if (pelanggaranInput) {
                    currentSessionViolations = parseInt(pelanggaranInput.value) || 0;
                    currentSessionViolations += 1;
                    pelanggaranInput.value = currentSessionViolations;
                }

                // Immediately persist violation to server so page reload (F5) cannot erase it
                const csrfInput = formUjian.querySelector('input[name="_token"]');
                if (csrfInput) {
                    fetch("{{ route('ujian.pelanggaran') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfInput.value,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({}),
                        keepalive: true
                    }).then(function(res) { return res.ok ? res.json() : null; })
                      .then(function(data) {
                          if (data && data.disqualified && !isSubmitting) {
                              isSubmitting = true;
                              formUjian.submit();
                          }
                      }).catch(function() {});
                }

                let previousViolations = {{ (int)session('total_pelanggaran', 0) }};
                let totalViolations = previousViolations + currentSessionViolations;

                // Auto-submit immediately without blocking alert() dialog
                if (totalViolations >= 3) {
                    isSubmitting = true;
                    overlay.classList.remove('hidden');
                    counter.innerText = '0';
                    formUjian.submit();
                    return;
                }

                startFreezeCountdown(30, totalViolations);
                applyPenaltyEffects();
            }

            // --- Screen Wake Lock API ---
            // Mencegah layar hp mati otomatis (sleep) yang bisa memicu pelanggaran palsu
            let wakeLock = null;
            async function requestWakeLock() {
                try {
                    if ('wakeLock' in navigator) {
                        wakeLock = await navigator.wakeLock.request('screen');
                    }
                } catch (err) {
                    console.log('Wake Lock error:', err);
                }
            }
            // Minta wake lock saat pengguna berinteraksi pertama kali (bersamaan dengan initAudio)
            document.addEventListener('click', requestWakeLock, { once: true });
            document.addEventListener('touchstart', requestWakeLock, { once: true });

            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    triggerFreeze();
                } else {
                    // Browser otomatis melepas wake lock saat tab disembunyikan, jadi kita minta lagi saat tab aktif
                    if (wakeLock !== null) {
                        requestWakeLock();
                    }
                    if (isFrozen) {
                        // Trigger kembali efek suara & visual saat user kembali ke tab ini
                        applyPenaltyEffects();
                    }
                }
            });
            window.addEventListener('blur', function() {
                triggerFreeze();
            });
            // Safety net for split-screen / docked DevTools / missed blur events
            setInterval(function() {
                if (!document.hasFocus() && !isFrozen && !isSubmitting) {
                    triggerFreeze();
                }
            }, 2000);
        });

        (function () {
            var btn = document.getElementById('ujianThemeToggle');
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

            var progressBar = document.getElementById('top-progress-bar');
            var loadingOverlay = document.getElementById('app-loading-overlay');

            function startProgress() {
                if (progressBar) {
                    progressBar.classList.add('active');
                    progressBar.style.width = '75%';
                }
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
                if (!href || href.startsWith('#') || link.target === '_blank') return;
                startProgress();
            });

            document.addEventListener('submit', function () {
                startProgress();
                if (loadingOverlay) loadingOverlay.classList.add('show');
            });

            window.addEventListener('pageshow', resetProgress);
        })();
    </script>
    @yield('scripts')
</body>
</html>
