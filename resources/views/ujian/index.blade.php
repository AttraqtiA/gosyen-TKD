@extends('layouts.ujian')

@section('title', 'Portal Ujian Psikologi')

@section('styles')
<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 72px 20px 28px;
    }
    .portal-shell {
        width: 100%;
        max-width: 980px;
        animation: fadeInUp 0.35s ease-out;
    }
    .portal-grid {
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        gap: 24px;
        align-items: stretch;
    }
    @media (max-width: 860px) {
        .portal-grid { grid-template-columns: 1fr; }
    }
    .card-info-panel, .card-login {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow-card);
        padding: 32px 28px;
    }
    .card-info-panel {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .brand-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
    }
    .logo-icon {
        width: 54px;
        height: 54px;
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        flex-shrink: 0;
    }
    .logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .step-list {
        list-style: none;
        padding: 0;
        margin: 16px 0 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .step-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 13px;
        color: var(--text-secondary);
    }
    .step-num {
        width: 24px;
        height: 24px;
        border-radius: 7px;
        background: rgba(29, 78, 216, 0.12);
        color: var(--primary);
        font-weight: 800;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .portal-notice {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 12.5px;
        color: var(--text-secondary);
        line-height: 1.55;
    }
    .portal-footer {
        text-align: center;
        margin-top: 20px;
        font-size: 12.5px;
        color: var(--text-muted);
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .portal-dropdown-btn {
        border-radius: 10px !important;
        height: 54px;
        font-size: 14.5px;
        border: 1px solid var(--border-strong) !important;
        color: var(--text-primary) !important;
        background: var(--bg-surface) !important;
        padding: 0 16px !important;
        font-weight: 500;
    }
    .portal-dropdown-menu {
        border-radius: 12px !important;
        box-shadow: var(--shadow-card) !important;
        border: 1px solid var(--border) !important;
        background: var(--bg-surface) !important;
        padding: 6px 0 !important;
    }
    .portal-dropdown-menu .dropdown-item {
        color: var(--text-primary);
        font-size: 14px;
    }
    .portal-dropdown-menu .dropdown-item:hover {
        background: var(--bg-subtle);
    }
    .form-floating .form-control {
        border-radius: 10px;
        border: 1px solid var(--border-strong);
        background-color: var(--bg-surface);
        color: var(--text-primary);
        font-size: 14.5px;
    }
    .form-floating .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
    }
    .btn-mulai {
        border-radius: 10px;
        padding: 13px;
        font-weight: 700;
        font-size: 15px;
        transition: background-color 0.2s ease;
        min-height: 48px;
        background: var(--primary);
        border: none;
        color: #ffffff;
    }
    .btn-mulai:hover {
        background: var(--primary-dark);
        color: #ffffff;
    }
    .form-text-custom {
        font-size: 12.5px;
        color: var(--text-muted);
        text-align: center;
        margin-top: 16px;
        line-height: 1.5;
    }
    .hidden {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="portal-shell">
    <div class="portal-grid">
        <!-- Left Info Panel -->
        <div class="card-info-panel">
            <div>
                <div class="brand-row">
                    <div class="logo-icon">
                        <img src="https://gosyenpolinator.com/images/gosyen_logo.png" alt="Gosyen Logo">
                    </div>
                    <div>
                        <span class="badge-custom mb-1">{{ __('Sistem Evaluasi Kandidat') }}</span>
                        <h3 class="fw-bold mb-0" style="color: var(--text-primary); font-size: 1.25rem;">Gosyen Assessment</h3>
                    </div>
                </div>

                <p style="color: var(--text-muted); font-size: 13.5px; line-height: 1.6;">
                    {{ __('Selamat datang di portal resmi Tes Kemampuan Dasar (TKD) & Psikologi IST. Ujian terdiri dari tahapan terstruktur berikut:') }}
                </p>

                <ul class="step-list">
                    <li class="step-item">
                        <span class="step-num">1</span>
                        <span><strong>{{ __('Sesi 1 (WA):') }}</strong> {{ __('Wortauswahl — Pemilihan Kata') }}</span>
                    </li>
                    <li class="step-item">
                        <span class="step-num">2</span>
                        <span><strong>{{ __('Sesi 2 (AN):') }}</strong> {{ __('Analogi — Hubungan Kata') }}</span>
                    </li>
                    <li class="step-item">
                        <span class="step-num">3</span>
                        <span><strong>{{ __('Sesi 3 (ZR):') }}</strong> {{ __('Zahlenreihen — Deret Angka') }}</span>
                    </li>
                    <li class="step-item">
                        <span class="step-num">4</span>
                        <span><strong>{{ __('Sesi 4 (FA):') }}</strong> {{ __('Figurenauswahl — Logika Gambar') }}</span>
                    </li>
                    <li class="step-item">
                        <span class="step-num">5</span>
                        <span><strong>{{ __('Sesi 5:') }}</strong> {{ __('Esai Teknis & Studi Kasus Posisi') }}</span>
                    </li>
                </ul>
            </div>

            <div class="portal-notice">
                <strong>{{ __('Perhatian:') }}</strong> {{ __('Pastikan koneksi internet stabil. Sistem anti-cheat aktif — berpindah tab atau keluar jendela ujian sebanyak 3 kali akan mendiskualifikasi sesi Anda secara otomatis.') }}
            </div>
        </div>

        <!-- Right Registration Form Card -->
        <div class="card-login">
            <h4 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 1.15rem;">{{ __('Portal Ujian Kompetensi') }}</h4>
            <p class="mb-4" style="color: var(--text-muted); font-size: 13.5px;">{{ __('Silakan lengkapi data diri Anda sebelum memulai ujian.') }}</p>

    @if ($errors->any())
        <div class="alert alert-danger mb-3" style="border-radius: 12px; font-size: 14px;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ujian.start') }}" method="POST" autocomplete="off">
        @csrf
        <!-- Dropdown Perusahaan -->
        <div class="dropdown mb-3">
            <button class="btn dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center portal-dropdown-btn" type="button" id="perusahaanDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span id="perusahaanSelectedText">{{ __('-- Pilih Perusahaan --') }}</span>
            </button>
            
            <ul class="dropdown-menu w-100 portal-dropdown-menu" aria-labelledby="perusahaanDropdown">
                @foreach($perusahaanList as $per)
                <li><button class="dropdown-item py-2 perusahaan-item" type="button" data-value="{{ $per->nama }}" data-perusahaan-id="{{ $per->id ?? '' }}">{{ $per->nama }}</button></li>
                @endforeach
                <li><button class="dropdown-item py-2 perusahaan-item fw-bold text-primary" type="button" data-value="Lainnya" data-perusahaan-id="">{{ __('Lainnya (Sebutkan)') }}</button></li>
            </ul>
            <input type="hidden" name="perusahaan" id="perusahaan" value="{{ old('perusahaan') }}" required>
        </div>

        <div class="form-floating mb-3 hidden" id="perusahaan_lainnya_container">
            <input type="text" class="form-control" id="perusahaan_lainnya" name="perusahaan_lainnya" placeholder="{{ __('Masukkan Perusahaan Lainnya') }}" value="{{ old('perusahaan_lainnya') }}">
            <label for="perusahaan_lainnya">{{ __('Masukkan Perusahaan Lainnya') }}</label>
        </div>

        <!-- Dropdown Posisi -->
        <div class="dropdown mb-3">
            <button class="btn dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center portal-dropdown-btn" type="button" id="posisiDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span id="posisiSelectedText">{{ __('-- Pilih Posisi Pekerjaan --') }}</span>
            </button>
            
            <ul class="dropdown-menu w-100 portal-dropdown-menu" aria-labelledby="posisiDropdown" style="max-height: 260px; overflow-y: auto; padding-top: 0;">
                <li class="p-2 sticky-top" style="background: var(--bg-surface); border-bottom: 1px solid var(--border); z-index: 2;">
                    <input type="text" id="searchPosisi" class="form-control form-control-sm" placeholder="{{ __('Cari posisi pekerjaan...') }}" autocomplete="off" style="border-radius: 8px; background: var(--bg-subtle); color: var(--text-primary); border-color: var(--border-strong);">
                </li>
                
                @foreach($posisiList as $p)
                <li><button class="dropdown-item py-2 posisi-item" type="button" data-value="{{ $p->nama }}" data-perusahaan-id="{{ $p->perusahaan_id ?? '' }}">{{ $p->nama }}</button></li>
                @endforeach
                <li><button class="dropdown-item py-2 posisi-item fw-bold text-primary" type="button" data-value="Lainnya" data-perusahaan-id="">{{ __('Lainnya (Sebutkan)') }}</button></li>
            </ul>
            <input type="hidden" name="posisi" id="posisi" value="{{ old('posisi') }}" required>
        </div>
        
        @if(session('banned'))
            <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.14); border: 1px solid #dc2626; color: var(--text-primary); padding: 14px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-weight: 700;">
                ⚠️ {{ session('banned') }}
            </div>
        @endif
        
        <div class="form-floating mb-3 hidden" id="posisi_lainnya_container">
            <input type="text" class="form-control" id="posisi_lainnya" name="posisi_lainnya" placeholder="{{ __('Masukkan posisi lainnya') }}" value="{{ old('posisi_lainnya') }}">
            <label for="posisi_lainnya">{{ __('Masukkan posisi lainnya') }}</label>
        </div>
        
        <div class="form-floating mb-4">
            <input type="text" class="form-control" id="nama" name="nama" placeholder="{{ __('Nama Lengkap') }}" required autocomplete="name" value="{{ old('nama') }}">
            <label for="nama">{{ __('Nama Lengkap') }}</label>
        </div>

        <button type="submit" class="btn btn-primary btn-mulai w-100">{{ __('Mulai Ujian') }} &rarr;</button>
        
        <div class="form-text-custom">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16" style="margin-bottom: 2px;">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg>
            {{ __('Pastikan koneksi internet stabil. Jangan tutup aplikasi saat ujian berlangsung.') }}
        </div>
    </form>
        </div>
    </div>

    <div class="portal-footer">
        &copy; {{ date('Y') }} PT. Gosyen Polinator Indonesia &bull; Gosyen Assessment System
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Explicit Registration Form State Machine
        // Single source of truth for Company -> Position filtering, Search, and "Lainnya" inputs
        const dom = {
            perusahaanInput: document.getElementById('perusahaan'),
            perusahaanItems: Array.from(document.querySelectorAll('.perusahaan-item')),
            perusahaanText: document.getElementById('perusahaanSelectedText'),
            perusahaanOtherBox: document.getElementById('perusahaan_lainnya_container'),
            perusahaanOtherInput: document.getElementById('perusahaan_lainnya'),
            posisiInput: document.getElementById('posisi'),
            posisiItems: Array.from(document.querySelectorAll('.posisi-item')),
            posisiText: document.getElementById('posisiSelectedText'),
            posisiOtherBox: document.getElementById('posisi_lainnya_container'),
            posisiOtherInput: document.getElementById('posisi_lainnya'),
            searchInput: document.getElementById('searchPosisi'),
        };

        const PLACEHOLDERS = {
            perusahaan: "{{ __('-- Pilih Perusahaan --') }}",
            posisi: "{{ __('-- Pilih Posisi Pekerjaan --') }}",
        };

        const state = {
            perusahaan: dom.perusahaanInput.value || '',
            perusahaanId: '',
            posisi: dom.posisiInput.value || '',
            searchTerm: '',
        };

        function findPerusahaanItem(value) {
            return dom.perusahaanItems.find(item => item.getAttribute('data-value') === value) || null;
        }

        function findPosisiItem(value) {
            return dom.posisiItems.find(item => item.getAttribute('data-value') === value) || null;
        }

        function render(focusTarget) {
            // 1. Sync Perusahaan display & "Lainnya" input
            dom.perusahaanInput.value = state.perusahaan;
            if (state.perusahaan) {
                const matchedCompany = findPerusahaanItem(state.perusahaan);
                dom.perusahaanText.innerText = matchedCompany ? matchedCompany.innerText : state.perusahaan;
            } else {
                dom.perusahaanText.innerText = PLACEHOLDERS.perusahaan;
            }

            if (state.perusahaan === 'Lainnya') {
                dom.perusahaanOtherBox.classList.remove('hidden');
                dom.perusahaanOtherInput.setAttribute('required', 'required');
                if (focusTarget === 'perusahaan') setTimeout(() => dom.perusahaanOtherInput.focus(), 100);
            } else {
                dom.perusahaanOtherBox.classList.add('hidden');
                dom.perusahaanOtherInput.removeAttribute('required');
                if (focusTarget === 'perusahaan') dom.perusahaanOtherInput.value = '';
            }

            // 2. Filter Posisi list by BOTH selected company AND search term
            const term = state.searchTerm.toLowerCase().trim();
            let currentPosisiStillAllowed = false;

            dom.posisiItems.forEach(item => {
                const val = item.getAttribute('data-value') || '';
                const itemCompanyId = item.getAttribute('data-perusahaan-id') || '';
                const matchesCompany = !state.perusahaanId || !itemCompanyId || itemCompanyId === state.perusahaanId;
                const matchesSearch = !term || item.textContent.toLowerCase().includes(term);

                if (matchesCompany && val === state.posisi) {
                    currentPosisiStillAllowed = true;
                }

                const li = item.closest('li');
                if (li) {
                    li.style.display = (matchesCompany && matchesSearch) ? '' : 'none';
                }
            });

            // Reset stale position if company switched to one where the previously picked position is disallowed
            if (state.posisi && !currentPosisiStillAllowed) {
                state.posisi = '';
            }

            // 3. Sync Posisi display & "Lainnya" input
            dom.posisiInput.value = state.posisi;
            if (state.posisi) {
                const matchedPosisi = findPosisiItem(state.posisi);
                dom.posisiText.innerText = matchedPosisi ? matchedPosisi.innerText : state.posisi;
            } else {
                dom.posisiText.innerText = PLACEHOLDERS.posisi;
            }

            if (state.posisi === 'Lainnya') {
                dom.posisiOtherBox.classList.remove('hidden');
                dom.posisiOtherInput.setAttribute('required', 'required');
                if (focusTarget === 'posisi') setTimeout(() => dom.posisiOtherInput.focus(), 100);
            } else {
                dom.posisiOtherBox.classList.add('hidden');
                dom.posisiOtherInput.removeAttribute('required');
                if (focusTarget === 'posisi') dom.posisiOtherInput.value = '';
            }
        }

        // Hydrate initial company ID if old('perusahaan') is present
        if (state.perusahaan) {
            const matched = findPerusahaanItem(state.perusahaan);
            if (matched) state.perusahaanId = matched.getAttribute('data-perusahaan-id') || '';
        }

        dom.perusahaanItems.forEach(item => {
            item.addEventListener('click', function () {
                state.perusahaan = this.getAttribute('data-value') || '';
                state.perusahaanId = this.getAttribute('data-perusahaan-id') || '';
                render('perusahaan');
            });
        });

        dom.posisiItems.forEach(item => {
            item.addEventListener('click', function () {
                state.posisi = this.getAttribute('data-value') || '';
                render('posisi');
            });
        });

        if (dom.searchInput) {
            dom.searchInput.addEventListener('input', function () {
                state.searchTerm = this.value;
                render();
            });
            dom.searchInput.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        render();
    });
</script>
@endsection