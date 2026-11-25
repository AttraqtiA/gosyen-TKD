<?php

namespace App\Enums;

/**
 * Explicit lifecycle phases of a candidate's exam attempt.
 *
 * Allowed transitions:
 *   IDLE         --[start()]-->              INSTRUCTIONS (sesi 1)
 *   INSTRUCTIONS --[enterSession(N)]-->      IN_PROGRESS (sesi N)
 *   INSTRUCTIONS --[submitSession(N)]-->     INSTRUCTIONS (sesi N+1) | READY_TO_SAVE | DISQUALIFIED
 *   IN_PROGRESS  --[submitSession(N)]-->     INSTRUCTIONS (sesi N+1) | READY_TO_SAVE | DISQUALIFIED
 *   IN_PROGRESS  --[3rd violation]-->        DISQUALIFIED
 *   READY_TO_SAVE / DISQUALIFIED --[save]--> COMPLETED (session flushed -> IDLE)
 */
enum ExamPhase: string
{
    case IDLE = 'idle';
    case INSTRUCTIONS = 'instructions';
    case IN_PROGRESS = 'in_progress';
    case READY_TO_SAVE = 'ready_to_save';
    case DISQUALIFIED = 'disqualified';
    case COMPLETED = 'completed';

    public function canSave(): bool
    {
        return $this === self::READY_TO_SAVE || $this === self::DISQUALIFIED;
    }

    public function isTerminalPendingSave(): bool
    {
        return $this->canSave();
    }
}
