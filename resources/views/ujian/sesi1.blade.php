@extends('layouts.ujian')

@section('title', 'Sesi 1 - Tes Pilihan Kata')

@section('styles')
<link rel="icon" href="https://gosyenpolinator.com/images/gosyen_logo.png">
<style>
    @for($i = 1; $i <= 20; $i++)
    .question-card:nth-of-type({{ $i }}) { animation-delay: {{ $i * 0.05 }}s; }
    @endfor
</style>
@endsection

@section('content')
<div class="main-wrapper">
    <div class="hero">
        <h1>{{ __('Ujian Psikologi Online') }}</h1>
        <p>{{ __('Sesi 1 - Tes Pilihan Kata (WA)') }}</p>
    </div>

    <div class="layout">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo-box">
                <img src="https://gosyenpolinator.com/images/gosyen_logo.png" alt="Logo">
                <div class="badge-custom">{{ __('Sesi 1 Aktif') }} ({{ 1 }}/{{ $totalSessions ?? 5 }})</div>
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
                    <li>{{ __('Durasi:') }} {{ $durasiMenit ?? 6 }} {{ __('menit') }}</li>
                    <li>{{ __('Pilih satu jawaban') }}</li>
                    <li>{{ __('Jawaban tersimpan otomatis') }}</li>
                </ul>
            </div>
        </div>

        <!-- CONTENT / FORM -->
        <div class="content">
            <form id="formUjian"
                  action="{{ route('ujian.submit', ['sesi' => 1]) }}"
                  method="POST"
                  data-duration="{{ $durasi }}"
                  data-critical-threshold="{{ $criticalThreshold ?? 60 }}"
                  data-progress-mode="radio"
                  data-question-prefix="jawab_sesi2_q"
                  data-total-questions="20">
                @csrf
                <input type="hidden" name="pelanggaran_sesi" id="pelanggaran_sesi" value="0">

                @foreach($soal as $s)
                @php $no = $s['no_tampil']; @endphp
                <div class="question-card">
                    <div class="question-number">{{ $no }}</div>
                    <p class="question-text">
                        {{ __('Pilih satu kata yang tidak memiliki kesamaan dengan keempat kata lainnya:') }}
                    </p>
                    @foreach($s['opsi'] as $label_tampil => $data)
                    <div class="option">
                        <input type="radio" 
                               id="q{{ $no }}_{{ $label_tampil }}" 
                               name="jawab_sesi2_q{{ $no }}" 
                               value="{{ $data['asli'] }}">
                        <label for="q{{ $no }}_{{ $label_tampil }}">
                            <span class="option-badge">{{ $label_tampil }}</span>
                            {{ __($data['teks']) }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @endforeach

                <button type="submit" class="submit-btn">
                    {{ __('Lanjut ke Sesi 2') }} &rarr;
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Timer & Progress driven centrally by window.ExamRuntime in layouts.ujian --}}
@endsection
