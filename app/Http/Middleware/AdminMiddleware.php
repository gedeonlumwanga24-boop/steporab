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
        // 1. Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à l\'administration.');
        }

        // 2. Vérifie si l'utilisateur est admin
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Sinon, redirige vers la page d'accueil avec un message d'erreur
        return redirect('/')->with('error', 'Accès refusé : vous n\'avez pas les droits d\'administrateur.');
    }
}
