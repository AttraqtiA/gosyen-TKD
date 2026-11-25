<?php

namespace App\Services;

use App\Enums\ExamPhase;
use App\Enums\ExamSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Explicit State Machine for the Candidate Exam Lifecycle & Anti-Cheat Penalty State.
 *
 * Preserves 100% backward compatibility with legacy PHP session keys
 * (`nama`, `posisi`, `perusahaan`, `total_pelanggaran`, `soal_sesi2`, `jawab_sesi2..6_q*`)
 * while providing a single, auditable, type-safe transition engine.
 */
class ExamStateMachine
{
    public const MAX_VIOLATIONS = 3;
    public const FREEZE_DURATION_SECONDS = 30;

    public function hasActiveAttempt(): bool
    {
        return session()->has('nama');
    }

    public function phase(): ExamPhase
    {
        if (!$this->hasActiveAttempt()) {
            return ExamPhase::IDLE;
        }

        $raw = session('exam_phase');
        if (is_string($raw) && ($parsed = ExamPhase::tryFrom($raw)) !== null) {
            return $parsed;
        }

        // Backward-compatible inference for in-flight sessions created before this refactor
        if (session('ujian_ready_to_save', false) || (int) session('current_sesi', 1) > 5) {
            return $this->totalViolations() >= self::MAX_VIOLATIONS
                ? ExamPhase::DISQUALIFIED
                : ExamPhase::READY_TO_SAVE;
        }

        $sesi = $this->currentSession();
        if (session()->has('sesi_started_at_' . $sesi->value)) {
            return ExamPhase::IN_PROGRESS;
        }

        return ExamPhase::INSTRUCTIONS;
    }

    public function currentSession(): ExamSession
    {
        $num = (int) session('current_sesi', 1);
        return ExamSession::tryFrom(max(1, min(5, $num))) ?? ExamSession::WA;
    }

    public function hasSesi5(?string $posisi = null): bool
    {
        $posisi = $posisi ?? (string) session('posisi', '');
        if ($posisi === '' || !Schema::hasTable('bank_soal_sesi5')) {
            return false;
        }

        return DB::table('bank_soal_sesi5')->where('posisi', $posisi)->exists();
    }

    public function totalSessionsFor(?string $posisi = null): int
    {
        return $this->hasSesi5($posisi) ? 5 : 4;
    }

    public function isLastSession(ExamSession $session, ?string $posisi = null): bool
    {
        return $session->value === $this->totalSessionsFor($posisi);
    }

    /**
     * Transition: IDLE / ANY -> INSTRUCTIONS (Sesi 1).
     */
    public function start(string $nama, string $posisi, string $perusahaan): void
    {
        $this->clearAttemptSession();

        session([
            'nama' => $nama,
            'posisi' => $posisi,
            'perusahaan' => $perusahaan,
            'total_pelanggaran' => 0,
            'current_sesi' => ExamSession::WA->value,
            'exam_phase' => ExamPhase::INSTRUCTIONS->value,
            'ujian_ready_to_save' => false,
        ]);
    }

    /**
     * Guard for GET /ujian/petunjuk/{sesi}.
     * Returns a RedirectResponse if the candidate should be redirected, or null if allowed.
     */
    public function guardInstructions(int $requestedSesi): ?RedirectResponse
    {
        if (!$this->hasActiveAttempt()) {
            return redirect()->route('ujian.index');
        }

        $session = ExamSession::tryFrom($requestedSesi);
        if (!$session) {
            return redirect()->route('ujian.index');
        }

        $phase = $this->phase();
        if ($phase->isTerminalPendingSave()) {
            return redirect()->route('ujian.simpan');
        }

        $current = $this->currentSession();
        if ($session !== $current) {
            return $this->redirectToCurrentState();
        }

        // Once the candidate has entered the timed session, do not let them return to instructions
        if ($phase === ExamPhase::IN_PROGRESS) {
            return redirect()->route('ujian.sesi', ['sesi' => $current->value]);
        }

        // Skip Sesi 5 if position has no essay/case-study bank
        if ($session === ExamSession::ESSAY && !$this->hasSesi5()) {
            $this->transitionToReadyToSave();
            return redirect()->route('ujian.simpan');
        }

        return null;
    }

    /**
     * Guard & Transition for GET /ujian/sesi/{sesi}: INSTRUCTIONS(N) -> IN_PROGRESS(N).
     * Returns a RedirectResponse if out-of-order/invalid, or null when allowed to render.
     */
    public function enterSession(int $requestedSesi): ?RedirectResponse
    {
        if (!$this->hasActiveAttempt()) {
            return redirect()->route('ujian.index');
        }

        $session = ExamSession::tryFrom($requestedSesi);
        if (!$session) {
            return redirect()->route('ujian.index');
        }

        $phase = $this->phase();
        if ($phase->isTerminalPendingSave()) {
            return redirect()->route('ujian.simpan');
        }

        $current = $this->currentSession();
        if ($session !== $current) {
            return $this->redirectToCurrentState();
        }

        if ($session === ExamSession::ESSAY && !$this->hasSesi5()) {
            $this->transitionToReadyToSave();
            return redirect()->route('ujian.simpan');
        }

        $timerKey = 'sesi_started_at_' . $session->value;
        if (!session()->has($timerKey)) {
            session([$timerKey => now()->timestamp]);
        }

        session(['exam_phase' => ExamPhase::IN_PROGRESS->value]);

        return null;
    }

    /**
     * Remaining server-authoritative seconds for the given session.
     */
    public function remainingSeconds(ExamSession $session): int
    {
        $totalSeconds = $session->durationMinutes() * 60;
        $startedAt = (int) session('sesi_started_at_' . $session->value, now()->timestamp);
        $elapsed = max(0, now()->timestamp - $startedAt);

        return max(0, $totalSeconds - $elapsed);
    }

    /**
     * Remaining server-authoritative freeze penalty seconds (survives F5 reload).
     */
    public function remainingFreezeSeconds(): int
    {
        $freezeUntil = (int) session('freeze_until', 0);
        return max(0, $freezeUntil - now()->timestamp);
    }

    public function totalViolations(): int
    {
        $sum = 0;
        for ($i = 1; $i <= 5; $i++) {
            $sum += (int) session('pelanggaran_sesi_' . $i, 0);
        }

        return max($sum, (int) session('total_pelanggaran', 0));
    }

    /**
     * Record a live anti-cheat violation server-side (POST /ujian/pelanggaran).
     */
    public function recordViolation(): array
    {
        $session = $this->currentSession();
        $sesiCount = (int) session('pelanggaran_sesi_' . $session->value, 0) + 1;
        session(['pelanggaran_sesi_' . $session->value => $sesiCount]);

        $total = $this->recalculateTotalViolations();
        $disqualified = $total >= self::MAX_VIOLATIONS;

        if ($disqualified) {
            $this->disqualify();
        } else {
            session(['freeze_until' => now()->timestamp + self::FREEZE_DURATION_SECONDS]);
        }

        return [
            'ok' => true,
            'sesi_pelanggaran' => $sesiCount,
            'total_pelanggaran' => $total,
            'remaining_attempts' => max(0, self::MAX_VIOLATIONS - $total),
            'freeze_seconds' => $disqualified ? 0 : self::FREEZE_DURATION_SECONDS,
            'disqualified' => $disqualified,
        ];
    }

    /**
     * Guard for POST /ujian/sesi/{sesi} and sync violations.
     * Returns RedirectResponse if invalid/disqualified, or null if caller should save session answers and call advanceAfterSubmit().
     */
    public function guardAndSyncSubmit(int $submittedSesi, int $clientViolations): ?RedirectResponse
    {
        if (!$this->hasActiveAttempt()) {
            return redirect()->route('ujian.index');
        }

        $session = ExamSession::tryFrom($submittedSesi);
        if (!$session) {
            return redirect()->route('ujian.index');
        }

        $phase = $this->phase();
        if ($phase->isTerminalPendingSave()) {
            return redirect()->route('ujian.simpan');
        }

        if ($session !== $this->currentSession()) {
            return $this->redirectToCurrentState();
        }

        // Take max(server, client) so page reload or DevTools tampering can never erase violations
        $serverViolations = (int) session('pelanggaran_sesi_' . $session->value, 0);
        session(['pelanggaran_sesi_' . $session->value => max($serverViolations, max(0, $clientViolations))]);
        session()->forget('freeze_until');

        $total = $this->recalculateTotalViolations();
        if ($total >= self::MAX_VIOLATIONS) {
            $this->disqualify();
            return redirect()->route('ujian.simpan');
        }

        return null;
    }

    /**
     * Transition after valid session answer submission:
     * IN_PROGRESS(N) -> INSTRUCTIONS(N+1) OR READY_TO_SAVE.
     */
    public function advanceAfterSubmit(ExamSession $completedSession): RedirectResponse
    {
        $next = $completedSession->next();

        if ($next === null || ($next === ExamSession::ESSAY && !$this->hasSesi5())) {
            $this->transitionToReadyToSave();
            return redirect()->route('ujian.simpan');
        }

        session([
            'current_sesi' => $next->value,
            'exam_phase' => ExamPhase::INSTRUCTIONS->value,
        ]);

        return redirect()->route('ujian.petunjuk', ['sesi' => $next->value]);
    }

    /**
     * Guard for GET /ujian/simpan.
     */
    public function guardSave(): ?RedirectResponse
    {
        if (!$this->hasActiveAttempt()) {
            return redirect()->route('ujian.index');
        }

        if (!$this->phase()->canSave()) {
            return $this->redirectToCurrentState();
        }

        return null;
    }

    /**
     * Redirect the candidate to whichever URL corresponds to their exact current state.
     */
    public function redirectToCurrentState(): RedirectResponse
    {
        if (!$this->hasActiveAttempt()) {
            return redirect()->route('ujian.index');
        }

        $phase = $this->phase();
        if ($phase->isTerminalPendingSave()) {
            return redirect()->route('ujian.simpan');
        }

        $current = $this->currentSession();
        if ($phase === ExamPhase::IN_PROGRESS) {
            return redirect()->route('ujian.sesi', ['sesi' => $current->value]);
        }

        return redirect()->route('ujian.petunjuk', ['sesi' => $current->value]);
    }

    /**
     * Transition: ANY -> DISQUALIFIED (wipe all answers, mark ready to save with 0 score).
     */
    public function disqualify(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            session([
                'jawab_sesi2_q' . $i => '',
                'jawab_sesi3_q' . $i => '',
                'jawab_sesi4_q' . $i => '',
                'jawab_sesi5_q' . $i => '',
            ]);
        }
        for ($i = 1; $i <= 30; $i++) {
            session(['jawab_sesi6_q' . $i => '']);
        }

        session()->forget('freeze_until');
        session([
            'exam_phase' => ExamPhase::DISQUALIFIED->value,
            'ujian_ready_to_save' => true,
            'current_sesi' => 6,
        ]);
    }

    /**
     * Wipe all candidate attempt keys from session (returning to IDLE).
     */
    public function clearAttemptSession(): void
    {
        session()->forget([
            'nama',
            'posisi',
            'perusahaan',
            'total_pelanggaran',
            'soal_sesi2',
            'current_sesi',
            'exam_phase',
            'ujian_ready_to_save',
            'freeze_until',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            session()->forget(['sesi_started_at_' . $i, 'pelanggaran_sesi_' . $i]);
        }
        for ($i = 1; $i <= 20; $i++) {
            session()->forget([
                'jawab_sesi2_q' . $i,
                'jawab_sesi3_q' . $i,
                'jawab_sesi4_q' . $i,
                'jawab_sesi5_q' . $i,
            ]);
        }
        for ($i = 1; $i <= 30; $i++) {
            session()->forget('jawab_sesi6_q' . $i);
        }
    }

    private function transitionToReadyToSave(): void
    {
        session()->forget('freeze_until');
        session([
            'exam_phase' => ExamPhase::READY_TO_SAVE->value,
            'ujian_ready_to_save' => true,
            'current_sesi' => 6,
        ]);
    }

    private function recalculateTotalViolations(): int
    {
        $total = 0;
        for ($i = 1; $i <= 5; $i++) {
            $total += (int) session('pelanggaran_sesi_' . $i, 0);
        }
        session(['total_pelanggaran' => $total]);

        return $total;
    }
}
