<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request
     * Usage: middleware('role:admin,manager')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!Auth::user()->hasAnyRole($roles)) {
            abort(403, 'Unauthorized - Required role: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
