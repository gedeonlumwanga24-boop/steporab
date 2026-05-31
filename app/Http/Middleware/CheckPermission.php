<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request
     * Usage: middleware('permission:edit-products,delete-products')
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!Auth::user()->hasAnyPermission($permissions)) {
            abort(403, 'Unauthorized - Required permission: ' . implode(', ', $permissions));
        }

        return $next($request);
    }
}
