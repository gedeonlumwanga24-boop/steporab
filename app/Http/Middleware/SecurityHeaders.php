<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Ajoute les headers de sécurité HTTP sur toutes les réponses web.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Empêche le clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Empêche le MIME-sniffing (XSS via type de fichier)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Protection XSS legacy (navigateurs anciens)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Contrôle du referrer pour limiter la fuite d'infos
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions API désactivées par défaut
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content Security Policy de base (ajustez src selon vos CDN)
        // Autorise Vite en local via ::1, localhost et 127.0.0.1.
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://accounts.google.com https://cdn.jsdelivr.net http://[::1]:5173 http://localhost:5173 http://127.0.0.1:5173; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com http://[::1]:5173 http://localhost:5173 http://127.0.0.1:5173; " .
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
            "img-src 'self' data: https: http:; " .
            "connect-src 'self' ws://localhost:5173 http://localhost:5173 ws://127.0.0.1:5173 http://127.0.0.1:5173 ws://[::1]:5173 http://[::1]:5173; " .
            "frame-src https://accounts.google.com;"
        );

        // En production uniquement : forcer HTTPS
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
