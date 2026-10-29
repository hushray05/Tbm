<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Ensure only the dedicated Super Administrator has access to the /admin control center.
     * Super Admin access requires: is_super_admin = true OR master email.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please sign in with Super Administrator credentials to access the Admin Console.');
        }

        if (!$user->isSuperAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Access Denied. Only the Super Administrator has authority to manage platform controls.');
        }

        return $next($request);
    }
}