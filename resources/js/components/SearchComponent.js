/**
 * SearchComponent — Composant de recherche dynamique avec autocomplete
 * Utilisation: ajouter à la navbar/header
 */

export function initDynamicSearch() {
    const searchContainer = document.getElementById("dynamic-search");

    if (!searchContainer) return;

    // État
    let debounceTimer;
    let currentQuery = "";
    let suggestions = [];
    let isLoading = false;

    const searchInput = searchContainer.querySelector('input[type="search"]');
    const suggestionsContainer = searchContainer.querySelector(
        ".search-suggestions",
    );
    const resultsContainer = searchContainer.querySelector(".search-results");
    const advancedFiltersBtn = searchContainer.querySelector(
        '[data-action="toggle-filters"]',
    );
    const advancedFiltersPanel =
        searchContainer.querySelector(".advanced-filters");

    // Listeners
    if (searchInput) {
        searchInput.addEventListener("input", handleSearchInput);
        searchInput.addEventListener("keydown", handleSearchKeydown);
        searchInput.addEventListener("focus", handleSearchFocus);
        searchInput.addEventListener("blur", () => {
            setTimeout(
                () => suggestionsContainer?.classList.add("hidden"),
                100,
            );
        });
    }

    if (advancedFiltersBtn) {
        advancedFiltersBtn.addEventListener("click", toggleAdvancedFilters);
    }

    /**
     * Gère l'input de recherche avec autocomplete
     */
    async function handleSearchInput(e) {
        currentQuery = e.target.value.trim();

        clearTimeout(debounceTimer);

        if (currentQuery.length < 2) {
            suggestionsContainer?.classList.add("hidden");
            return;
        }

        isLoading = true;
        suggestionsContainer?.classList.add("loading");

        debounceTimer = setTimeout(async () => {
            try {
                const { autocomplete } =
                    await import("./SearchService.js").then((m) => m.default);
                suggestions = await autocomplete(currentQuery, 8);
                renderSuggestions(suggestions);
                isLoading = false;
                suggestionsContainer?.classList.remove("loading");
            } catch (error) {
                console.error("Erreur autocomplete:", error);
                isLoading = false;
                suggestionsContainer?.classList.remove("loading");
            }
        }, 300);
    }

    /**
     * Gère les touches de clavier
     */
    function handleSearchKeydown(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            performSearch(currentQuery);
        } else if (e.key === "Escape") {
            suggestionsContainer?.classList.add("hidden");
        }
    }

    /**
     * Affiche les suggestions
     */
    function handleSearchFocus(e) {
        if (currentQuery.length >= 2 && suggestions.length > 0) {
            renderSuggestions(suggestions);
        }
    }

    /**
     * Rend les suggestions HTML
     */
    function renderSuggestions(items) {
        if (!suggestionsContainer) return;

        if (items.length === 0) {
            suggestionsContainer.innerHTML =
                '<div class="p-3 text-center text-gray-500">Aucun résultat</div>';
            suggestionsContainer.classList.remove("hidden");
            return;
        }

        const html = items
            .map(
                (item) => `
            <div class="suggestion-item p-3 cursor-pointer hover:bg-gray-100 flex items-center gap-3 border-b last:border-b-0" 
                 onclick="handleSuggestionClick('${item.id}', '${item.label}')">
                <img src="${item.image}" alt="${item.label}" class="w-12 h-12 object-cover rounded">
                <div class="flex-1">
                    <div class="font-medium text-sm">${item.label}</div>
                    <div class="text-xs text-gray-500">${item.price} TND</div>
                </div>
            </div>
        `,
            )
            .join("");

        suggestionsContainer.innerHTML = html;
        suggestionsContainer.classList.remove("hidden");
    }

    /**
     * Effectue une recherche
     */
    async function performSearch(query) {
        if (query.length < 2) return;

        suggestionsContainer?.classList.add("hidden");
        resultsContainer?.classList.add("loading");

        try {
            const SearchService = (await import("./SearchService.js")).default;
            const results = await SearchService.search(query);

            if (results && results.data) {
                renderResults(results.data);
                updatePagination(results.pagination);
            }
        } catch (error) {
            console.error("Erreur recherche:", error);
            if (resultsContainer) {
                resultsContainer.innerHTML =
                    '<div class="alert alert-error">Erreur lors de la recherche</div>';
            }
        } finally {
            resultsContainer?.classList.remove("loading");
        }
    }

    /**
     * Rend les résultats de recherche
     */
    function renderResults(items) {
        if (!resultsContainer) return;

        if (items.length === 0) {
            resultsContainer.innerHTML =
                '<div class="alert alert-info">Aucun produit trouvé</div>';
            return;
        }

        const html = items
            .map(
                (product) => `
            <div class="product-card">
                <a href="/produits/${product.id}">
                    <img src="${product.image_url}" alt="${product.nom}" class="w-full h-48 object-cover rounded">
                </a>
                <div class="p-3">
                    <h3 class="font-semibold text-sm"><a href="/produits/${product.id}">${product.nom}</a></h3>
                    <p class="text-xs text-gray-600 mt-1">${product.description?.substring(0, 50)}...</p>
                    <div class="flex justify-between items-center mt-3">
                        <span class="font-bold text-lg">${product.prix} TND</span>
                        <button class="btn btn-sm btn-primary" onclick="addToCart(${product.id})">
                            <span class="icon-shopping-cart"></span>
                        </button>
                    </div>
                </div>
            </div>
        `,
            )
            .join("");

        resultsContainer.innerHTML = html;
    }

    /**
     * Met à jour la pagination
     */
    function updatePagination(pagination) {
        const paginationContainer =
            resultsContainer?.querySelector(".pagination");
        if (!paginationContainer || !pagination) return;

        // Générer les liens de pagination si nécessaire
        let html = '<div class="flex justify-center gap-2 mt-6">';

        if (pagination.prev_page_url) {
            html += `<button class="btn btn-sm" onclick="previousPage()">← Précédent</button>`;
        }

        html += `<span class="px-3 py-2">Page ${pagination.current_page} / ${pagination.last_page}</span>`;

        if (pagination.next_page_url) {
            html += `<button class="btn btn-sm" onclick="nextPage()">Suivant →</button>`;
        }

        html += "</div>";
        paginationContainer.innerHTML = html;
    }

    /**
     * Gère le clic sur une suggestion
     */
    window.handleSuggestionClick = function (id, label) {
        searchInput.value = label;
        currentQuery = label;
        performSearch(label);
    };

    /**
     * Bascule le panneau de filtres avancés
     */
    function toggleAdvancedFilters() {
        advancedFiltersPanel?.classList.toggle("hidden");
    }

    /**
     * Lance une recherche avancée
     */
    window.performAdvancedSearch = async function () {
        const formData = new FormData(
            advancedFiltersPanel?.querySelector("form"),
        );
        const filters = {
            search: formData.get("search"),
            category_id: formData.get("category_id"),
            price_min: formData.get("price_min"),
            price_max: formData.get("price_max"),
            sort_by: formData.get("sort_by"),
        };

        resultsContainer?.classList.add("loading");

        try {
            const SearchService = (await import("./SearchService.js")).default;
            const results = await SearchService.advancedSearch(filters);

            if (results && results.data) {
                renderResults(results.data);
                updatePagination(results.pagination);
            }
        } catch (error) {
            console.error("Erreur recherche avancée:", error);
        } finally {
            resultsContainer?.classList.remove("loading");
        }
    };
}

// Initialiser lors du chargement du DOM
document.addEventListener("DOMContentLoaded", initDynamicSearch);
