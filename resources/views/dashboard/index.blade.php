@extends('layouts.admin')

@section('title', 'Dashboard ' . ucfirst($role))

@section('styles')
<style>
    .module-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        border-radius: 12px;
        background-color: var(--bg-surface-subtle);
        border: 1px solid var(--border-subtle);
        text-decoration: none;
        transition: border-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
        height: 100%;
    }
    .module-card:hover {
        border-color: var(--brand-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-card);
    }
    .module-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
    }
    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-muted);
    }
    .filter-label {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 12.5px;
        margin-bottom: 6px;
        display: block;
    }
    .table-wrapper {
        border-radius: 12px;
        border: 1px solid var(--border-subtle);
        overflow: auto;
        max-height: 70vh;
    }
    .excel-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
        color: var(--text-secondary);
        font-variant-numeric: tabular-nums;
        margin: 0;
    }
    .excel-table th {
        background-color: var(--bg-table-head);
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 1px solid var(--border-strong);
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .excel-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border-subtle);
        vertical-align: middle;
        white-space: nowrap;
    }
    .excel-table tbody tr:hover {
        background-color: var(--bg-hover);
    }
    .badge-status {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }
    .bg-rec-green { background-color: rgba(16, 185, 129, 0.14); color: #059669; border: 1px solid rgba(16, 185, 129, 0.35); }
    .bg-rec-yellow { background-color: rgba(245, 158, 11, 0.14); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.35); }
    .bg-rec-orange { background-color: rgba(249, 115, 22, 0.14); color: #ea580c; border: 1px solid rgba(249, 115, 22, 0.35); }
    .bg-rec-red { background-color: rgba(239, 68, 68, 0.14); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.35); }
    .btn-action-sm {
        border-radius: 7px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        color: #ffffff !important;
        border: none;
        transition: opacity 0.15s ease;
    }
    .btn-action-sm:hover { opacity: 0.9; color: #ffffff !important; }
</style>
@endsection

@section('content')
@if ($role == 'psikolog')

    <!-- Filter Controls Card -->
    <div class="card-custom">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom" style="border-color: var(--border-subtle) !important;">
            <div>
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Filter Data Ujian
                </h5>
                <span class="small text-muted">Saring kandidat berdasarkan tanggal, nama, perusahaan, atau posisi</span>
            </div>
        </div>

        <form action="{{ route('dashboard.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="filter-label">Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control form-control-custom" value="{{ $tgl_awal ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control form-control-custom" value="{{ $tgl_akhir ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="filter-label">Cari Nama Kandidat</label>
                <input type="text" name="nama" class="form-control form-control-custom" placeholder="Ketik nama kandidat..." value="{{ $search_nama ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Perusahaan / Klien</label>
                <select name="perusahaan" class="form-select form-select-custom">
                    <option value="">-- Semua Perusahaan --</option>
                    @if(isset($perusahaanList))
                        @foreach($perusahaanList as $p)
                            <option value="{{ $p }}" {{ ($selected_perusahaan ?? '') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label class="filter-label">Posisi / Divisi</label>
                <select name="posisi" class="form-select form-select-custom">
                    <option value="">-- Semua Posisi --</option>
                    @if(isset($posisiList))
                        @foreach($posisiList as $pos)
                            <option value="{{ $pos }}" {{ ($selected_posisi ?? '') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-12 d-flex flex-wrap gap-2 justify-content-end mt-3 pt-3 border-top" style="border-color: var(--border-subtle) !important;">
                <a href="{{ route('dashboard.index') }}" class="btn btn-custom btn-custom-secondary">Reset Filter</a>
                <button type="submit" class="btn btn-custom btn-custom-blue">Terapkan Filter</button>

                @if(!empty($tgl_awal) && !empty($tgl_akhir))
                    <a href="{{ route('dashboard.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir, 'nama' => $search_nama ?? '', 'perusahaan' => $selected_perusahaan ?? '', 'posisi' => $selected_posisi ?? '']) }}" class="btn btn-custom btn-custom-emerald d-inline-flex align-items-center gap-1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel (.xls)
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="card-custom">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Hasil Ujian Kandidat
            </h5>
            <span class="badge bg-primary px-3 py-2" style="border-radius: 8px; font-weight: 700;">Total: {{ count($peserta) }} Data</span>
        </div>

        <div class="table-wrapper" tabindex="0" role="region" aria-label="Tabel Hasil Ujian Kandidat">
            <table class="excel-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu Ujian</th>
                        <th>Nama Kandidat</th>
                        <th>Perusahaan</th>
                        <th>Posisi Dilamar</th>
                        <th>Pelanggaran</th>
                        <th>WA</th>
                        <th>AN</th>
                        <th>ZR</th>
                        <th>FA</th>
                        <th>Rata SW</th>
                        <th>Kategori IST</th>
                        <th>Nilai Kasus</th>
                        <th>Skor Akhir</th>
                        <th>Kategori Akhir</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $index => $p)
                    @php
                        $a = $p->analysis;
                        $badgeClass = $a['recommendation_badge_class'] ?? 'bg-rec-yellow';
                        $katIstClass = $a['ist_badge_class'] ?? 'bg-secondary text-white';
                    @endphp
                    <tr>
                        <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->waktu_mulai)->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold" style="color: var(--text-primary);">{{ $p->nama }}</td>
                        <td><span class="badge bg-slate-800 border border-secondary px-2 py-1">{{ $p->perusahaan ?: '-' }}</span></td>
                        <td><span class="badge bg-slate-800 border border-secondary px-2 py-1">{{ $p->posisi }}</span></td>
                        <td>
                            @if($p->total_pelanggaran > 0)
                                <span class="badge bg-danger fw-bold">{{ $p->total_pelanggaran }}x Fraud</span>
                            @else
                                <span class="text-muted fw-semibold">0</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $a['sw_wa'] }}</td>
                        <td class="fw-semibold">{{ $a['sw_an'] }}</td>
                        <td class="fw-semibold">{{ $a['sw_zr'] }}</td>
                        <td class="fw-semibold">{{ $a['sw_fa'] }}</td>
                        <td class="fw-bold" style="color: var(--brand-primary);">{{ $a['total_sw'] }}</td>
                        <td>
                            <span class="badge {{ $katIstClass }} fw-bold px-2.5 py-1">{{ $a['kategori_ist'] }}</span>
                        </td>
                        <td>
                            @if($a['studi_kasus']['has_case'])
                                <span class="fw-bold" style="color: var(--brand-primary);">{{ $a['studi_kasus']['total_skor'] }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fw-bold" style="color: var(--text-primary);">{{ $a['skor_akhir'] }}</td>
                        <td>
                            <span class="badge-status {{ $badgeClass }}">{{ $a['kategori_akhir'] }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('dashboard.peserta.edit', $p->id_peserta) }}" class="btn-action-sm" style="background-color: #d97706;">Edit</a>
                                <a href="{{ route('dashboard.pdf', $p->id_peserta) }}" target="_blank" class="btn-action-sm" style="background-color: #dc2626;">PDF</a>
                                <a href="{{ url('/dashboard/word/' . $p->id_peserta) }}" target="_blank" class="btn-action-sm" style="background-color: #2563eb;">Word</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="16" class="text-center py-5 text-muted">
                            Tidak ada data hasil ujian yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@elseif ($role == 'admin')

    <!-- Summary Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label mb-1">Perusahaan / Klien Terdaftar</div>
                    <div class="stat-value">{{ $adminStats['perusahaan'] ?? 0 }}</div>
                </div>
                <div class="module-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9v.01"/><path d="M9 12v.01"/><path d="M9 15v.01"/><path d="M9 18v.01"/></svg>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label mb-1">Posisi Pekerjaan Aktif</div>
                    <div class="stat-value">{{ $adminStats['posisi'] ?? 0 }}</div>
                </div>
                <div class="module-icon" style="background: rgba(29, 78, 216, 0.12); color: #2563eb;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label mb-1">Item Studi Kasus &amp; Esai (Sesi 5)</div>
                    <div class="stat-value">{{ $adminStats['soal_sesi5'] ?? 0 }}</div>
                </div>
                <div class="module-icon" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Side: CRUD Menu Grid -->
        <div class="col-lg-8">
            <div class="card-custom h-100 mb-0">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom" style="border-color: var(--border-subtle) !important;">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--text-primary);">Panel Pengelolaan Portal Psikotes</h5>
                        <p class="text-muted small mb-0">Pilih modul data master atau bank soal yang ingin dikelola:</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.perusahaan') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Kelola Perusahaan</div>
                                <div class="text-muted" style="font-size: 12.5px;">Tambah, ubah, atau hapus data klien</div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.posisi') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(29, 78, 216, 0.12); color: #2563eb;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Kelola Posisi</div>
                                <div class="text-muted" style="font-size: 12.5px;">Atur posisi pelamar &amp; relasi klien</div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.sesi1') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Sesi 1 (WA)</div>
                                <div class="text-muted" style="font-size: 12.5px;">Wortauswahl (Pilihan Kata)</div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.sesi2') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Sesi 2 (AN)</div>
                                <div class="text-muted" style="font-size: 12.5px;">Analogi (Hubungan Kata)</div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.sesi3') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Sesi 3 (ZR)</div>
                                <div class="text-muted" style="font-size: 12.5px;">Zahlenreihen (Deret Angka)</div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.sesi4') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Sesi 4 (FA)</div>
                                <div class="text-muted" style="font-size: 12.5px;">Figurenauswahl (Kunci Gambar)</div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.sesi5') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(236, 72, 153, 0.12); color: #db2777;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Sesi 5 (Essay)</div>
                                <div class="text-muted" style="font-size: 12.5px;">Esai &amp; Studi Kasus Posisi</div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6">
                        <a href="{{ route('dashboard.rubrik') }}" class="module-card">
                            <div class="module-icon" style="background: rgba(99, 102, 241, 0.12); color: #4f46e5;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            </div>
                            <div>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 14.5px;">Rubrik Penilaian</div>
                                <div class="text-muted" style="font-size: 12.5px;">Panduan Penilaian Sesi 5</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Security & Quick Links -->
        <div class="col-lg-4 d-flex flex-column gap-4">
            <div class="card-custom mb-0">
                <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom" style="border-color: var(--border-subtle) !important;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Keamanan &amp; PIN Akses</h5>
                </div>
                <p class="text-muted small mb-3">Ganti PIN akses yang digunakan oleh Tim Psikolog untuk masuk ke dashboard evaluasi kandidat.</p>

                <form action="{{ route('dashboard.pin') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Masukkan PIN Psikolog Baru</label>
                        <input type="text" name="pin_baru" class="form-control form-control-custom text-center" required placeholder="Contoh: 123456" autocomplete="off" style="letter-spacing: 2px;">
                    </div>
                    <button type="submit" class="btn btn-custom btn-custom-blue w-100 fw-bold">Simpan PIN Baru</button>
                </form>
            </div>

            <div class="card-custom mb-0 flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom" style="border-color: var(--border-subtle) !important;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Informasi Sistem &amp; Akses</h5>
                </div>
                <ul class="list-unstyled mb-3 small text-muted d-flex flex-column gap-2">
                    <li class="d-flex justify-content-between">
                        <span>Relasi Posisi &rarr; Klien:</span>
                        <span class="fw-bold" style="color: var(--accent-emerald);">SET NULL (Aman)</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Anti-Cheat Monitor:</span>
                        <span class="fw-bold" style="color: var(--brand-primary);">Aktif (Maks. 3x)</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Portal Ujian Kandidat:</span>
                        <a href="{{ route('ujian.index') }}" target="_blank" class="fw-bold text-decoration-none">Buka Portal &nearr;</a>
                    </li>
                </ul>
                <div class="p-3 rounded-3" style="background: var(--bg-surface-subtle); border: 1px solid var(--border-subtle); font-size: 12.5px; color: var(--text-muted);">
                    Menghapus perusahaan tidak akan menghapus posisi atau data hasil tes kandidat.
                </div>
            </div>
        </div>
    </div>

@endif
@endsection
