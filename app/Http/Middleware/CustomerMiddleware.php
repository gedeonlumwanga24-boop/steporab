<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request - Customer must be authenticated
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!Auth::user()->hasRole('customer')) {
            abort(403, 'Unauthorized - Customer access required');
        }

        return $next($request);
    }
}
