/**
 * Cart API service
 * Wraps all /api/v1/cart endpoints
 */

import api from "../api/axios.js";

/**
 * Émettre un événement personnalisé pour les mises à jour du panier
 * @param {number} count - Nombre d'articles
 */
function emitCartUpdate(count) {
    window.dispatchEvent(new CustomEvent('cart:updated', {
        detail: { count }
    }));
}

export const CartService = {
    /**
     * Get current cart contents
     * @returns {Promise}
     */
    async get() {
        try {
            const response = await api.get("/cart");
            return response.data?.data || response.data;
        } catch (error) {
            console.error("Erreur lors de la récupération du panier:", error);
            throw error;
        }
    },

    /**
     * Add a product to cart
     * @param {{ product_id: number, quantite?: number, taille?: string }} data
     * @returns {Promise}
     */
    async addItem(data) {
        try {
            const response = await api.post("/cart/items", {
                product_id: data.product_id || data.productId,
                quantite: data.quantite || 1,
                taille: data.taille || null,
            });
            
            // Émettre l'événement de mise à jour
            const count = await this.getItemCount();
            emitCartUpdate(count);
            
            return response.data?.data || response.data;
        } catch (error) {
            if (error.response?.status === 422) {
                throw new Error(
                    error.response.data?.message || "Stock insuffisant",
                );
            }
            throw error;
        }
    },

    /**
     * Update item quantity (pass 0 to remove)
     * @param {number} productId
     * @param {number} quantite
     * @param {string} taille
     * @returns {Promise}
     */
    async updateItem(productId, quantite, taille = null) {
        try {
            const response = await api.patch(`/cart/items/${productId}`, {
                quantite,
                taille,
            });
            
            // Émettre l'événement de mise à jour
            const count = await this.getItemCount();
            emitCartUpdate(count);
            
            return response.data?.data || response.data;
        } catch (error) {
            if (error.response?.status === 422) {
                throw new Error(
                    error.response.data?.message ||
                        "Erreur lors de la mise à jour",
                );
            }
            throw error;
        }
    },

    /**
     * Remove an item from cart
     * @param {number} productId
     * @param {string} taille
     * @returns {Promise}
     */
    async removeItem(productId, taille = null) {
        try {
            const response = await api.delete(`/cart/items/${productId}`, {
                data: { taille },
            });
            
            // Émettre l'événement de mise à jour
            const count = await this.getItemCount();
            emitCartUpdate(count);
            
            return response.data?.data || response.data;
        } catch (error) {
            console.error("Erreur lors de la suppression:", error);
            throw error;
        }
    },

    /**
     * Clear the entire cart
     * @returns {Promise}
     */
    async clear() {
        try {
            const response = await api.delete("/cart");
            
            // Émettre l'événement de mise à jour (compteur à 0)
            emitCartUpdate(0);
            
            return response.data?.data || response.data;
        } catch (error) {
            console.error("Erreur lors du vidage du panier:", error);
            throw error;
        }
    },

    /**
     * Get the number of items in cart
     * @returns {Promise<number>}
     */
    async getItemCount() {
        try {
            const cart = await this.get();
            return cart?.count || 0;
        } catch {
            return 0;
        }
    },

    /**
     * Get the total of the cart
     * @returns {Promise<number>}
     */
    async getTotal() {
        try {
            const cart = await this.get();
            return cart?.total || 0;
        } catch {
            return 0;
        }
    },

    /**
     * Check if cart is empty
     * @returns {Promise<boolean>}
     */
    async isEmpty() {
        try {
            const cart = await this.get();
            return cart?.is_empty || cart?.count === 0 || false;
        } catch {
            return true;
        }
    },
};
