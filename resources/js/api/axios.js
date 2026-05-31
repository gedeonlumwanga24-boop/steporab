/**
 * Centralized Axios instance for Stepora API
 *
 * Features:
 * - CSRF token injection (Sanctum SPA support)
 * - Bearer token for mobile/SPA
 * - Centralized error handling
 * - Request/Response interceptors
 */

import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true, // Required for Sanctum cookie-based SPA auth
});

// ── Request Interceptor ───────────────────────────────────────────────────────
api.interceptors.request.use((config) => {
    // Inject CSRF token from meta tag (Blade layouts)
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        config.headers['X-CSRF-TOKEN'] = csrfToken;
    }

    // Inject Bearer token if available (mobile / SPA with stored token)
    const bearerToken = localStorage.getItem('stepora_token');
    if (bearerToken) {
        config.headers['Authorization'] = `Bearer ${bearerToken}`;
    }

    return config;
}, (error) => Promise.reject(error));

// ── Response Interceptor ──────────────────────────────────────────────────────
api.interceptors.response.use(
    (response) => response,
    (error) => {
        const status = error.response?.status;

        if (status === 401) {
            // Token expired or invalid — clear and redirect to login
            localStorage.removeItem('stepora_token');
            if (!window.location.pathname.includes('/login')) {
                window.dispatchEvent(new CustomEvent('stepora:unauthenticated'));
            }
        }

        if (status === 403) {
            window.dispatchEvent(new CustomEvent('stepora:forbidden'));
        }

        if (status === 422) {
            // Validation errors — bubble up for form handling
            return Promise.reject(error);
        }

        if (status >= 500) {
            console.error('[API] Erreur serveur:', error.response?.data?.message ?? error.message);
        }

        return Promise.reject(error);
    }
);

export default api;
