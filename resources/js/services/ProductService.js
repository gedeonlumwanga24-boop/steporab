/**
 * Product API service
 * Wraps all /api/v1/products endpoints
 */

import api from '../api/axios.js';

export const ProductService = {
    /**
     * Get paginated products with optional filters
     * @param {{ page?, per_page?, category_id?, search?, sort? }} params
     */
    index(params = {}) {
        return api.get('/products', { params });
    },

    /**
     * Get single product by ID
     * @param {number} id
     */
    show(id) {
        return api.get(`/products/${id}`);
    },

    /**
     * Search products by query string
     * @param {string} q
     */
    search(q) {
        return api.get('/search/products', { params: { q } });
    },
};
