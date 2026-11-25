<?php

namespace App\Enums;

/**
 * Explicit dashboard authorization roles (replaces ad-hoc 'admin' / 'psikolog' strings).
 */
enum AccessRole: string
{
    case ADMIN = 'admin';
    case PSIKOLOG = 'psikolog';

    public const SESSION_KEY = 'role_akses';

    public static function current(): ?self
    {
        $value = session(self::SESSION_KEY);
        return is_string($value) ? self::tryFrom($value) : null;
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isPsikolog(): bool
    {
        return $this === self::PSIKOLOG;
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::PSIKOLOG => 'Psikolog',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-primary',
            self::PSIKOLOG => 'bg-success',
        };
    }
}
