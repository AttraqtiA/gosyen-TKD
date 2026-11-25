@extends('layouts.ujian')

@section('title', 'Sesi 5 - Tes Esai & Studi Kasus')

@section('styles')
<link rel="icon" href="https://gosyenpolinator.com/images/gosyen_logo.png">
<style>
    @php
        $totalQuestions = count($soalSesi5['bagian_a']);
        foreach ($soalSesi5['bagian_b'] as $case) {
            $totalQuestions += count($case['pertanyaan']);
        }
    @endphp
    @for($i = 1; $i <= $totalQuestions; $i++)
    .question-card:nth-of-type({{ $i }}) { animation-delay: {{ $i * 0.03 }}s; }
    @endfor
    
    .essay-input:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15) !important;
    }
    .case-container {
        background: var(--bg-subtle);
        border: 1px solid var(--border-strong);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 28px;
    }
    .case-title {
        font-size: 16.5px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 12px;
        border-bottom: 1px solid var(--border-strong);
        padding-bottom: 10px;
    }
    .case-desc {
        font-size: 14.5px;
        color: var(--text-secondary);
        line-height: 1.7;
        margin-bottom: 20px;
        background: var(--bg-surface);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid var(--border);
    }
</style>
@endsection

@section('content')
<div class="main-wrapper">
    <div class="hero">
        <h1>{{ __('Ujian Kompetensi Posisi') }}</h1>
        <p>{{ __('Sesi 5 - Tes Esai & Soal Analisis') }} ({{ $posisi }})</p>
    </div>

    <div class="layout">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo-box">
                <img src="https://gosyenpolinator.com/images/gosyen_logo.png" alt="Logo">
                <div class="badge-custom">{{ __('Sesi 5 Aktif') }} (5/{{ $totalSessions ?? 5 }})</div>
            </div>
            <div class="timer-box" id="timerBox">
                <p>{{ __('Sisa Waktu') }}</p>
                <h2 id="countdown">{{ sprintf('%02d:%02d', intdiv($durasi, 60), $durasi % 60) }}</h2>
            </div>
            <div class="progress-wrapper">
                <div class="progress-header">
                    <span>{{ __('Kemajuan') }}</span>
                    <span id="progressText">0/{{ $totalQuestions }}</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>
            <div class="info-card" style="margin-top:20px;">
                <h3>{{ __('Informasi Tes') }}</h3>
                <ul>
                    <li>{{ __('Total soal:') }} {{ $totalQuestions }}</li>
                    <li>{{ __('Durasi:') }} {{ $durasiMenit ?? 45 }} {{ __('menit') }}</li>
                    <li>{{ __('Ketik jawaban secara terpisah') }}</li>
                    <li>{{ __('Waktu habis otomatis menyimpan') }}</li>
                </ul>
            </div>
        </div>

        <!-- CONTENT / FORM -->
        <div class="content">
            <form id="formUjian"
                  action="{{ route('ujian.submit', ['sesi' => 5]) }}"
                  method="POST"
                  data-duration="{{ $durasi }}"
                  data-critical-threshold="{{ $criticalThreshold ?? 180 }}"
                  data-progress-mode="textarea"
                  data-total-questions="{{ $totalQuestions }}">
                @csrf
                <input type="hidden" name="pelanggaran_sesi" id="pelanggaran_sesi" value="0">

                @php $qCount = 1; @endphp

                <!-- BAGIAN A (Dihilangkan, hanya muncul jika ada data) -->
                @if(count($soalSesi5['bagian_a']) > 0)
                <h4 class="fw-bold mb-4 mt-2 text-primary" style="font-size: 18px;">{{ __('A. Pengetahuan Dasar & Pemahaman Konsep') }}</h4>
                @foreach($soalSesi5['bagian_a'] as $num => $qText)
                <div class="question-card">
                    <div class="question-number">{{ $qCount }}</div>
                    <p class="question-text" style="font-size: 16px; font-weight: 600; color: var(--text-primary); line-height: 1.6;">
                        {{ __($qText) }}
                    </p>
                    <textarea name="jawab_sesi6_q{{ $qCount }}" 
                              class="form-control essay-input" 
                              rows="4" 
                              placeholder="{{ __('Ketik jawaban Anda di sini secara jelas dan detail...') }}" 
                              style="border-radius: 12px; border: 1px solid var(--border-strong); background: var(--bg-subtle); color: var(--text-primary); padding: 14px; font-size: 15px; width: 100%; transition: all 0.2s ease;"></textarea>
                </div>
                @php $qCount++; @endphp
                @endforeach
                @endif

                <!-- BAGIAN B (Studi Kasus & Problem Solving) -->
                @if(count($soalSesi5['bagian_b']) > 0)
                <h4 class="fw-bold mb-4 mt-4 text-primary" style="font-size: 18px;">{{ __('B. Studi Kasus & Analisis Masalah') }}</h4>
                @foreach($soalSesi5['bagian_b'] as $caseIdx => $case)
                <div class="case-container">
                    <div class="case-title">📌 {{ __($case['judul']) }}</div>
                    <div class="case-desc">
                        {!! __($case['deskripsi']) !!}
                    </div>

                    @foreach($case['pertanyaan'] as $subNum => $subQText)
                    <div class="question-card" style="background: var(--bg-surface); border: 1px solid var(--border-strong); margin-bottom: 20px;">
                        <div class="question-number">{{ $qCount }}</div>
                        <p class="question-text" style="font-size: 15px; font-weight: 600; color: var(--text-primary); line-height: 1.6;">
                            {{ __($subQText) }}
                        </p>
                        <textarea name="jawab_sesi6_q{{ $qCount }}" 
                                  class="form-control essay-input" 
                                  rows="4" 
                                  placeholder="{{ __('Ketik analisis & solusi Anda di sini...') }}" 
                                  style="border-radius: 12px; border: 1px solid var(--border-strong); background: var(--bg-subtle); color: var(--text-primary); padding: 14px; font-size: 15px; width: 100%; transition: all 0.2s ease;"></textarea>
                    </div>
                    @php $qCount++; @endphp
                    @endforeach
                </div>
                @endforeach
                @endif

                <button type="submit" class="submit-btn" style="background: linear-gradient(135deg, #10b981, #059669);">
                    {{ __('Selesai & Kirim Jawaban') }} &check;
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Timer & Progress driven centrally by window.ExamRuntime in layouts.ujian --}}
@endsection
