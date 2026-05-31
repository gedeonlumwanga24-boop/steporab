/**
 * Auth API service
 * Wraps all /api/v1/auth endpoints
 */

import api from '../api/axios.js';

export const AuthService = {
    /**
     * Register a new customer
     * @param {{ name, email, password, password_confirmation }} data
     */
    register(data) {
        return api.post('/auth/register', data);
    },

    /**
     * Login and retrieve Sanctum token
     * @param {{ email, password }} data
     */
    async login(data) {
        const response = await api.post('/auth/login', data);
        const token = response.data?.data?.token;
        if (token) {
            localStorage.setItem('stepora_token', token);
        }
        return response;
    },

    /**
     * Logout and revoke current token
     */
    async logout() {
        const response = await api.post('/auth/logout');
        localStorage.removeItem('stepora_token');
        return response;
    },

    /**
     * Get authenticated user profile
     */
    me() {
        return api.get('/auth/me');
    },

    /**
     * Update profile
     * @param {{ name?, email?, password?, password_confirmation?, current_password? }} data
     */
    updateProfile(data) {
        return api.patch('/auth/profile', data);
    },

    /**
     * Check if a token is stored (client-side only)
     */
    isAuthenticated() {
        return !!localStorage.getItem('stepora_token');
    },
};
