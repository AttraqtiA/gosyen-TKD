<?php

namespace App\Enums;

use Illuminate\Support\Facades\DB;

/**
 * Single source of truth for the 5 exam sessions and their legacy +1 DB table mapping.
 *
 * UI Session   | Code  | Question Bank Table | Answer Table  | Kunci Sesi | Default Duration
 * -------------|-------|---------------------|---------------|------------|-----------------
 * 1 (WA)       | WA    | bank_soal_sesi2     | jawaban_sesi2 | sesi2      | 6 min
 * 2 (AN)       | AN    | bank_soal_sesi3     | jawaban_sesi3 | sesi3      | 7 min
 * 3 (ZR)       | ZR    | bank_soal_sesi4     | jawaban_sesi4 | sesi4      | 10 min
 * 4 (FA)       | FA    | (static images)     | jawaban_sesi5 | sesi5      | 7 min
 * 5 (Essay)    | ESSAY | bank_soal_sesi5     | jawaban_sesi6 | (manual)   | 45 min
 */
enum ExamSession: int
{
    case WA = 1;
    case AN = 2;
    case ZR = 3;
    case FA = 4;
    case ESSAY = 5;

    public function code(): string
    {
        return match ($this) {
            self::WA => 'WA',
            self::AN => 'AN',
            self::ZR => 'ZR',
            self::FA => 'FA',
            self::ESSAY => 'ESSAY',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::WA => 'Tes Pilihan Kata (WA)',
            self::AN => 'Tes Hubungan Kata (AN)',
            self::ZR => 'Tes Deret Angka (ZR)',
            self::FA => 'Tes Logika Gambar (FA)',
            self::ESSAY => 'Tes Esai & Studi Kasus',
        };
    }

    public function viewName(): string
    {
        return 'ujian.sesi' . $this->value;
    }

    public function settingsKey(): string
    {
        return 'durasi_sesi' . $this->value;
    }

    public function defaultDurationMinutes(): int
    {
        return match ($this) {
            self::WA => 6,
            self::AN => 7,
            self::ZR => 10,
            self::FA => 7,
            self::ESSAY => 45,
        };
    }

    public function durationMinutes(): int
    {
        $configured = DB::table('pengaturan')
            ->where('key', $this->settingsKey())
            ->value('value');

        return $configured !== null ? (int) $configured : $this->defaultDurationMinutes();
    }

    public function criticalThresholdSeconds(): int
    {
        return $this === self::ESSAY ? 180 : 60;
    }

    public function questionCount(): int
    {
        return $this === self::ESSAY ? 25 : 20;
    }

    public function answerSessionPrefix(): string
    {
        return match ($this) {
            self::WA => 'jawab_sesi2_q',
            self::AN => 'jawab_sesi3_q',
            self::ZR => 'jawab_sesi4_q',
            self::FA => 'jawab_sesi5_q',
            self::ESSAY => 'jawab_sesi6_q',
        };
    }

    public function next(): ?self
    {
        return self::tryFrom($this->value + 1);
    }
}
