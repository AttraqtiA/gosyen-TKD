@extends('layouts.ujian')

@section('title', 'Portal Ujian Psikologi')

@section('styles')
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .card-login {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow-card);
        width: 100%;
        max-width: 460px;
        padding: 36px 30px;
        animation: fadeInUp 0.35s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .logo-icon {
        width: 68px;
        height: 68px;
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        padding: 10px;
    }
    .logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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
        margin-top: 18px;
        line-height: 1.5;
    }
    .hidden {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="card-login">
    <div class="logo-icon">
        <img src="https://gosyenpolinator.com/images/gosyen_logo.png" alt="Gosyen Logo">
    </div>
    
    <h3 class="text-center fw-bold mb-1" style="color: var(--text-primary); font-size: 1.25rem;">{{ __('Portal Ujian Kompetensi') }}</h3>
    <p class="text-center mb-4" style="color: var(--text-muted); font-size: 13.5px;">{{ __('Silakan lengkapi data diri Anda sebelum memulai ujian.') }}</p>

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
            <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.2); border: 1px solid #dc2626; color: #00000; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center; font-weight: bold;">
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Perusahaan handling
        const perusahaanInput = document.getElementById('perusahaan');
        const perusahaanList = document.querySelectorAll('.perusahaan-item');
        const perusahaanText = document.getElementById('perusahaanSelectedText');
        const pContainer = document.getElementById('perusahaan_lainnya_container');
        const pInput = document.getElementById('perusahaan_lainnya');

        function togglePerusahaanLainnya() {
            if (perusahaanInput.value === 'Lainnya') {
                pContainer.classList.remove('hidden');
                pInput.setAttribute('required', 'required');
            } else {
                pContainer.classList.add('hidden');
                pInput.removeAttribute('required');
            }
        }

        let selectedPerusahaanId = '';

        function filterPosisiByPerusahaan() {
            const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
            let currentValid = false;
            positionsList.forEach(item => {
                const text = item.innerText.toLowerCase();
                const val = item.getAttribute('data-value');
                const itemPerusahaanId = item.getAttribute('data-perusahaan-id') || '';
                const matchesCompany = !selectedPerusahaanId || !itemPerusahaanId || itemPerusahaanId === selectedPerusahaanId;
                const matchesSearch = text.includes(term);

                if (matchesCompany && val === posisiInput.value) {
                    currentValid = true;
                }

                if (matchesCompany && matchesSearch) {
                    item.parentElement.style.display = 'block';
                } else {
                    item.parentElement.style.display = 'none';
                }
            });

            if (posisiInput.value && !currentValid) {
                posisiInput.value = '';
                positionsText.innerText = "{{ __('-- Pilih Posisi Pekerjaan --') }}";
                togglePosisiLainnya();
            }
        }

        perusahaanList.forEach(item => {
            item.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                const text = this.innerText;
                selectedPerusahaanId = this.getAttribute('data-perusahaan-id') || '';
                
                perusahaanInput.value = val;
                perusahaanText.innerText = text;
                
                togglePerusahaanLainnya();
                filterPosisiByPerusahaan();
                
                if (val === 'Lainnya') {
                    setTimeout(() => pInput.focus(), 100);
                } else {
                    pInput.value = '';
                }
            });
        });

        if (perusahaanInput.value) {
            const matchedP = Array.from(perusahaanList).find(item => item.getAttribute('data-value') === perusahaanInput.value);
            if (matchedP) {
                perusahaanText.innerText = matchedP.innerText;
                selectedPerusahaanId = matchedP.getAttribute('data-perusahaan-id') || '';
            } else {
                perusahaanText.innerText = perusahaanInput.value;
            }
            togglePerusahaanLainnya();
        }

        // Posisi handling
        const posisiInput = document.getElementById('posisi');
        const positionsList = document.querySelectorAll('.posisi-item');
        const positionsText = document.getElementById('posisiSelectedText');
        const container = document.getElementById('posisi_lainnya_container');
        const input = document.getElementById('posisi_lainnya');
        const searchInput = document.getElementById('searchPosisi');

        function togglePosisiLainnya() {
            if (posisiInput.value === 'Lainnya') {
                container.classList.remove('hidden');
                input.setAttribute('required', 'required');
            } else {
                container.classList.add('hidden');
                input.removeAttribute('required');
            }
        }

        positionsList.forEach(item => {
            item.addEventListener('click', function() {
                const val = this.getAttribute('data-value');
                const text = this.innerText;
                
                posisiInput.value = val;
                positionsText.innerText = text;
                
                togglePosisiLainnya();
                
                if (val === 'Lainnya') {
                    setTimeout(() => input.focus(), 100);
                } else {
                    input.value = '';
                }
            });
        });

        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            positionsList.forEach(item => {
                const text = item.textContent.toLowerCase();
                const parentLi = item.closest('li');
                if (text.includes(filter)) {
                    parentLi.style.display = "";
                } else {
                    parentLi.style.display = "none";
                }
            });
        });

        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        if (posisiInput.value) {
            const matchedItem = Array.from(positionsList).find(item => item.getAttribute('data-value') === posisiInput.value);
            if (matchedItem) {
                positionsText.innerText = matchedItem.innerText;
            } else {
                positionsText.innerText = posisiInput.value;
            }
            togglePosisiLainnya();
        }
    });
</script>
@endsection