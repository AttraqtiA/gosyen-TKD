<?php

namespace App\Http\Middleware;

use App\Enums\AccessRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Enforce explicit AccessRole authorization on dashboard routes.
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,psikolog')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $current = AccessRole::current();

        if ($current === null || !in_array($current->value, $roles, true)) {
            abort(403, 'Akses Ditolak.');
        }

        return $next($request);
    }
}
