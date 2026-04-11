<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté et est admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Sinon, redirige vers la page d'accueil ou une page d'erreur
        return redirect('/')->with('error', 'Accès refusé');
    }
}
