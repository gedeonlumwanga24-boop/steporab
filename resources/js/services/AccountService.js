/**
 * Account/Messages Service
 * Gère les notifications et compteurs pour les réponses clients
 */

import api from "../api/axios.js";

/**
 * Émettre un événement personnalisé pour les mises à jour du compte
 * @param {number} count - Nombre de réponses non lues
 */
function emitAccountUpdate(count) {
    window.dispatchEvent(
        new CustomEvent("account:updated", {
            detail: { count },
        }),
    );
}

/**
 * Émettre un événement personnalisé pour les mises à jour du contact (réponses admin)
 * @param {number} count
 */
function emitContactUpdate(count) {
    window.dispatchEvent(
        new CustomEvent("contact:updated", {
            detail: { count },
        }),
    );
}

export const AccountService = {
    /**
     * Récupérer les messages non lus
     * @returns {Promise<number>}
     */
    async getUnreadMessagesCount() {
        try {
            const response = await api.get("/account/messages/unread-count");
            const count = response.data?.data?.count || 0;

            // Émettre l'événement pour mettre à jour le badge
            emitAccountUpdate(count);

            return count;
        } catch (error) {
            console.error(
                "Erreur lors de la récupération des messages non lus:",
                error,
            );
            return 0;
        }
    },

    /**
     * Récupérer les réponses du vendeur non lues
     * @returns {Promise<number>}
     */
    async getVendorRepliesCount() {
        try {
            const response = await api.get("/account/vendor/replies-count");
            const count = response.data?.data?.count || 0;

            // Émettre l'événement pour mettre à jour le badge contact (réponses admin)
            emitContactUpdate(count);

            // Également émettre update générique au besoin
            emitAccountUpdate(count);

            return count;
        } catch (error) {
            console.error(
                "Erreur lors de la récupération des réponses:",
                error,
            );
            return 0;
        }
    },

    /**
     * Marquer tous les messages comme lus
     * @returns {Promise}
     */
    async markAllAsRead() {
        try {
            const response = await api.post("/account/messages/mark-all-read");

            // Réinitialiser le compteur
            emitAccountUpdate(0);
            emitContactUpdate(0);

            return response.data;
        } catch (error) {
            console.error(
                "Erreur lors du marquage des messages comme lus:",
                error,
            );
            throw error;
        }
    },

    /**
     * Vérifier les mises à jour du compte (utiliser régulièrement)
     * @returns {Promise<number>}
     */
    async checkUpdates() {
        try {
            const count = await this.getUnreadMessagesCount();
            return count;
        } catch {
            return 0;
        }
    },
};
