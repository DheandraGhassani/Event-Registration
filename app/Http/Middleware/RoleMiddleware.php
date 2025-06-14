<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $roleMap = [
            'guest' => 0,
            'member' => 1,
            'administrator' => 2,
            'finance' => 3,
            'comitee' => 4,
        ];

        $allowedRoleIds = array_map(function ($roleName) use ($roleMap) {
            return $roleMap[$roleName] ?? null;
        }, $roles);

        $user = Auth::user();

        if (!$user || !in_array((int)$user->role, $allowedRoleIds, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
