/**
 * SearchService — Axios service pour l'API de recherche
 * Fichier: resources/js/services/SearchService.js
 */

import axios from "axios";

const API_BASE_URL = `/api/v1/search`;

class SearchService {
    /**
     * Recherche générale avec pagination
     * @param {string} query - Terme de recherche
     * @param {number} perPage - Éléments par page
     * @param {number} page - Numéro de page
     * @returns {Promise}
     */
    async search(query, perPage = 12, page = 1) {
        try {
            const response = await axios.get(`${API_BASE_URL}`, {
                params: {
                    q: query,
                    per_page: perPage,
                    page: page,
                },
            });
            return response.data.data;
        } catch (error) {
            console.error("Erreur lors de la recherche:", error);
            throw error;
        }
    }

    /**
     * Autocomplete / Suggestions rapides
     * @param {string} query - Terme de recherche
     * @param {number} limit - Nombre de suggestions
     * @returns {Promise}
     */
    async autocomplete(query, limit = 10) {
        try {
            const response = await axios.get(`${API_BASE_URL}/autocomplete`, {
                params: {
                    q: query,
                    limit: limit,
                },
            });
            return response.data.data;
        } catch (error) {
            console.error("Erreur lors de l'autocomplete:", error);
            throw error;
        }
    }

    /**
     * Recherche avancée avec filtres
     * @param {object} filters - Filtres (search, category_id, price_min, price_max, sort_by)
     * @returns {Promise}
     */
    async advancedSearch(filters = {}) {
        try {
            const response = await axios.get(`${API_BASE_URL}/advanced`, {
                params: filters,
            });
            return response.data.data;
        } catch (error) {
            console.error("Erreur lors de la recherche avancée:", error);
            throw error;
        }
    }

    /**
     * Recherche par catégorie
     * @param {number} categoryId - ID de la catégorie
     * @param {string} query - Terme de recherche optionnel
     * @param {number} perPage - Éléments par page
     * @returns {Promise}
     */
    async searchByCategory(categoryId, query = null, perPage = 12) {
        try {
            const response = await axios.get(
                `${API_BASE_URL}/category/${categoryId}`,
                {
                    params: {
                        q: query,
                        per_page: perPage,
                    },
                },
            );
            return response.data.data;
        } catch (error) {
            console.error("Erreur lors de la recherche par catégorie:", error);
            throw error;
        }
    }

    /**
     * Récupère les suggestions populaires
     * @returns {Promise}
     */
    async getPopularSearches() {
        try {
            const response = await axios.get(`${API_BASE_URL}/popular`);
            return response.data.data;
        } catch (error) {
            console.error(
                "Erreur lors de la récupération des suggestions populaires:",
                error,
            );
            throw error;
        }
    }

    /**
     * Compte le nombre de résultats
     * @param {string} query - Terme de recherche
     * @returns {Promise}
     */
    async countResults(query) {
        try {
            const response = await axios.get(`${API_BASE_URL}/count`, {
                params: {
                    q: query,
                },
            });
            return response.data.data;
        } catch (error) {
            console.error("Erreur lors du comptage:", error);
            throw error;
        }
    }
}

export default new SearchService();
