@extends('layouts.ujian')

@section('title', 'Sesi 4 - Tes Logika Gambar')

@section('styles')
<link rel="icon" href="https://gosyenpolinator.com/images/gosyen_logo.png">
<style>
    @for($i = 1; $i <= 20; $i++)
    .question-card:nth-of-type({{ $i }}) { animation-delay: {{ $i * 0.05 }}s; }
    @endfor
</style>
@endsection

@section('content')
@php
    $isCustomPosisi = $isCustomPosisi ?? true;
@endphp
<div class="main-wrapper">
    <div class="hero">
        <h1>{{ __('Ujian Psikologi Online') }}</h1>
        <p>{{ __('Sesi 4 - Tes Logika Gambar (FA)') }}</p>
    </div>

    <div class="layout">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo-box">
                <img src="https://gosyenpolinator.com/images/gosyen_logo.png" alt="Logo">
                <div class="badge-custom">{{ __('Sesi 4 Aktif') }} (4/{{ $totalSessions ?? ($isCustomPosisi ? 4 : 5) }})</div>
            </div>
            <div class="timer-box" id="timerBox">
                <p>{{ __('Sisa Waktu') }}</p>
                <h2 id="countdown">{{ sprintf('%02d:%02d', intdiv($durasi, 60), $durasi % 60) }}</h2>
            </div>
            <div class="progress-wrapper">
                <div class="progress-header">
                    <span>{{ __('Kemajuan') }}</span>
                    <span id="progressText">0/20</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>
            <div class="info-card" style="margin-top:20px;">
                <h3>{{ __('Informasi Tes') }}</h3>
                <ul>
                    <li>{{ __('Total soal: 20') }}</li>
                    <li>{{ __('Durasi:') }} {{ $durasiMenit ?? 7 }} {{ __('menit') }}</li>
                    <li>{{ __('Pilih satu jawaban (A-E)') }}</li>
                    @if($isCustomPosisi)
                        <li>{{ __('Menekan Selesai akan menyimpan seluruh jawaban') }}</li>
                    @else
                        <li>{{ __('Jawaban tersimpan otomatis') }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- CONTENT / FORM -->
        <div class="content">
            <form id="formUjian"
                  action="{{ route('ujian.submit', ['sesi' => 4]) }}"
                  method="POST"
                  data-duration="{{ $durasi }}"
                  data-critical-threshold="{{ $criticalThreshold ?? 60 }}"
                  data-progress-mode="radio"
                  data-question-prefix="jawab_sesi5_q"
                  data-total-questions="20">
                @csrf
                <input type="hidden" name="pelanggaran_sesi" id="pelanggaran_sesi" value="0">

                @foreach($soalSesi4 as $nomor => $path_gambar)
                <div class="question-card">
                    <div class="question-number">{{ $nomor }}</div>
                    <p class="question-text">
                        {{ __('Pilihlah salah satu opsi (A, B, C, D, atau E) yang merupakan jawaban paling tepat:') }}
                    </p>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="{{ asset($path_gambar) }}" alt="{{ __('Soal') }} {{ $nomor }}" style="max-width: 100%; height: auto; border-radius: 12px; border: 1px solid var(--border-strong); background: #ffffff; padding: 8px;">
                    </div>

                    <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $opsi)
                        <div class="option" style="margin-bottom: 0;">
                            <input type="radio" id="q{{ $nomor }}_{{ $opsi }}" name="jawab_sesi5_q{{ $nomor }}" value="{{ $opsi }}">
                            <label for="q{{ $nomor }}_{{ $opsi }}" style="padding: 10px 20px;">
                                <span class="option-badge" style="margin-right: 0;">{{ $opsi }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <button type="submit" class="submit-btn">
                    @if($isCustomPosisi)
                        {{ __('Selesai Ujian & Simpan Jawaban') }} &check;
                    @else
                        {{ __('Lanjut ke Sesi 5') }} &rarr;
                    @endif
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Timer & Progress driven centrally by window.ExamRuntime in layouts.ujian --}}
@endsection
