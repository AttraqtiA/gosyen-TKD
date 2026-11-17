@extends('layouts.admin')

@section('title', 'Kelola Perusahaan')

@section('content')
<div class="row">
    <!-- Form Tambah Perusahaan -->
    <div class="col-md-4">
        <div class="card-custom">
            <h5 class="text-white mb-3 fw-bold">🏢 Tambah Perusahaan / Klien</h5>
            <form action="{{ route('dashboard.perusahaan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Nama Perusahaan / Klien</label>
                    <input type="text" name="nama" class="form-control form-control-custom" required placeholder="Contoh: PT. MAJU BERSAMA" autofocus autocomplete="off">
                </div>
                <button type="submit" class="btn btn-custom btn-custom-emerald w-100 fw-bold">Simpan Perusahaan</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Perusahaan -->
    <div class="col-md-8">
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color: rgba(255,255,255,0.08) !important;">
                <h5 class="fw-bold text-white mb-0">🏢 Daftar Perusahaan / Klien</h5>
                <span class="badge bg-primary px-3 py-1.5" style="border-radius: 8px; font-weight: 700;">Total: {{ count($perusahaanList) }} Perusahaan</span>
            </div>

            <div class="table-responsive" style="border-radius: 12px; border: 1px solid rgba(255,255,255,0.08); overflow: auto;">
                <table class="table-custom mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th>Nama Perusahaan</th>
                            <th class="text-center" style="width: 140px;">Posisi Terkait</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perusahaanList as $index => $perusahaan)
                        <tr>
                            <td class="fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-white">{{ $perusahaan->nama }}</td>
                            <td class="text-center">
                                <span class="badge bg-slate-800 text-info border border-info/30 px-2.5 py-1">{{ $perusahaan->posisi_count ?? 0 }} Posisi</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-custom btn-custom-blue py-1 px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $perusahaan->id }}">
                                        Edit
                                    </button>
                                    
                                    <!-- Delete Form (ON DELETE SET NULL, safe for positions & candidate data) -->
                                    <form action="{{ route('dashboard.perusahaan.delete', $perusahaan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini? Posisi terkait tidak akan terhapus (otomatis menjadi Umum), dan data hasil tes kandidat tetap aman.');">
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
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data perusahaan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Loop -->
@foreach($perusahaanList as $perusahaan)
<div class="modal fade" id="editModal{{ $perusahaan->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $perusahaan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #0f172a; border: 1px solid rgba(255,255,255,0.15); border-radius: 16px;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-white fw-bold" id="editModalLabel{{ $perusahaan->id }}">✏️ Edit Perusahaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.perusahaan.update', $perusahaan->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 13px;">Nama Perusahaan / Klien</label>
                        <input type="text" name="nama" class="form-control form-control-custom" required value="{{ $perusahaan->nama }}" autocomplete="off">
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
