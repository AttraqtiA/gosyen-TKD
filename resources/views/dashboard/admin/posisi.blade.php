@extends('layouts.admin')

@section('title', 'Kelola Posisi')

@section('content')
<div class="row">
    <!-- Form Tambah Posisi -->
    <div class="col-md-4">
        <div class="card-custom">
            <h5 class="text-white mb-3 fw-bold">➕ Tambah Posisi Baru</h5>
            <form action="{{ route('dashboard.posisi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Nama Posisi Pekerjaan</label>
                    <input type="text" name="nama" class="form-control form-control-custom" required placeholder="Contoh: BACKEND DEVELOPER" autofocus autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Perusahaan / Klien (Opsional)</label>
                    <select name="perusahaan_id" class="form-select form-select-custom">
                        <option value="">-- Semua Perusahaan / Umum --</option>
                        @foreach($perusahaanList ?? [] as $perusahaan)
                        <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-custom btn-custom-emerald w-100 fw-bold">Simpan Posisi</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Posisi -->
    <div class="col-md-8">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color: rgba(255,255,255,0.08) !important;">
                <h5 class="fw-bold text-white mb-0">💼 Daftar Posisi Pekerjaan</h5>
                <span class="badge bg-primary px-3 py-1.5" style="border-radius: 8px; font-weight: 700;">Total: {{ count($posisiList) }} Posisi</span>
            </div>

            <div class="table-responsive" style="border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); overflow: auto;">
                <table class="table-custom mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th>Nama Posisi</th>
                            <th>Perusahaan / Klien</th>
                            <th class="text-center" style="width: 190px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posisiList as $index => $posisi)
                        <tr>
                            <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-white">{{ $posisi->nama }}</td>
                            <td>
                                @if($posisi->perusahaan)
                                    <span class="badge bg-slate-800 text-emerald-400 border border-emerald-500/30 px-2.5 py-1">{{ $posisi->perusahaan->nama }}</span>
                                @else
                                    <span class="badge bg-secondary/40 text-muted px-2.5 py-1">Umum / Semua</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-custom btn-custom-blue py-1 px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $posisi->id }}">
                                        Edit
                                    </button>
                                    
                                    <!-- Delete Form -->
                                    <form action="{{ route('dashboard.posisi.delete', $posisi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus posisi ini? Menghapus posisi akan mematikan Sesi 5 bagi kandidat yang melamar posisi ini.');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-custom btn-custom-danger py-1 px-3">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data posisi pekerjaan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Loop -->
@foreach($posisiList as $posisi)
<div class="modal fade" id="editModal{{ $posisi->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $posisi->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #0f172a; border: 1px solid rgba(255,255,255,0.15); border-radius: 16px;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-white fw-bold" id="editModalLabel{{ $posisi->id }}">✏️ Edit Posisi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.posisi.update', $posisi->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Nama Posisi Pekerjaan</label>
                        <input type="text" name="nama" class="form-control form-control-custom" required value="{{ $posisi->nama }}" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Perusahaan / Klien (Opsional)</label>
                        <select name="perusahaan_id" class="form-select form-select-custom">
                            <option value="">-- Semua Perusahaan / Umum --</option>
                            @foreach($perusahaanList ?? [] as $perusahaan)
                            <option value="{{ $perusahaan->id }}" {{ $posisi->perusahaan_id == $perusahaan->id ? 'selected' : '' }}>{{ $perusahaan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-custom btn-custom-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom btn-custom-blue">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
