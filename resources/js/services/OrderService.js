/**
 * Order API service
 * Wraps all /api/v1/orders endpoints
 */

import api from '../api/axios.js';

export const OrderService = {
    /**
     * Get user's orders (paginated)
     * @param {{ page? }} params
     */
    index(params = {}) {
        return api.get('/orders', { params });
    },

    /**
     * Get single order by ID
     * @param {number} id
     */
    show(id) {
        return api.get(`/orders/${id}`);
    },

    /**
     * Place a new order
     * @param {{ adresse: string, items: Array<{ product_id, quantite, taille? }> }} data
     */
    store(data) {
        return api.post('/orders', data);
    },
};
