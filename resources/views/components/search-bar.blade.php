<!-- Search Bar Component — À ajouter dans le header/navbar -->

<div id="dynamic-search" class="dynamic-search-container">
    <!-- Barre de recherche -->
    <div class="search-bar flex items-center gap-2">
        <input 
            type="search" 
            id="search-input"
            placeholder="Rechercher des produits..." 
            class="input input-bordered flex-1"
            autocomplete="off"
        >
        <button class="btn btn-primary" onclick="document.getElementById('search-input').form?.submit()">
            <span class="icon-search">🔍</span>
        </button>
        <button 
            class="btn btn-outline" 
            data-action="toggle-filters"
            title="Filtres avancés"
        >
            <span class="icon-filter">⚙️</span>
        </button>
    </div>

    <!-- Suggestions (Autocomplete) -->
    <div class="search-suggestions hidden absolute top-full left-0 right-0 bg-white border rounded shadow-lg z-50 max-h-96 overflow-y-auto">
        <!-- Suggestions seront insérées ici par JavaScript -->
    </div>

    <!-- Panneau de filtres avancés -->
    <div class="advanced-filters hidden p-4 bg-gray-50 border rounded mt-2">
        <form class="space-y-3" onsubmit="event.preventDefault(); performAdvancedSearch();">
            
            <!-- Recherche par texte -->
            <div>
                <label class="label">
                    <span class="label-text">Recherche</span>
                </label>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher..." 
                    class="input input-bordered w-full"
                >
            </div>

            <!-- Filtrer par catégorie -->
            <div>
                <label class="label">
                    <span class="label-text">Catégorie</span>
                </label>
                <select name="category_id" class="select select-bordered w-full">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}">{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtrer par prix -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="label">
                        <span class="label-text">Prix min (TND)</span>
                    </label>
                    <input 
                        type="number" 
                        name="price_min" 
                        placeholder="0" 
                        class="input input-bordered w-full"
                        min="0"
                        step="0.01"
                    >
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Prix max (TND)</span>
                    </label>
                    <input 
                        type="number" 
                        name="price_max" 
                        placeholder="999999" 
                        class="input input-bordered w-full"
                        min="0"
                        step="0.01"
                    >
                </div>
            </div>

            <!-- Tri -->
            <div>
                <label class="label">
                    <span class="label-text">Trier par</span>
                </label>
                <select name="sort_by" class="select select-bordered w-full">
                    <option value="latest">Plus récent</option>
                    <option value="price_asc">Prix croissant</option>
                    <option value="price_desc">Prix décroissant</option>
                    <option value="popular">Les plus populaires</option>
                    <option value="name_asc">Nom (A-Z)</option>
                    <option value="name_desc">Nom (Z-A)</option>
                </select>
            </div>

            <!-- Inclure les produits en rupture de stock -->
            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">Inclure les produits en rupture</span>
                    <input 
                        type="checkbox" 
                        name="include_out_of_stock" 
                        value="1" 
                        class="checkbox checkbox-sm"
                    >
                </label>
            </div>

            <!-- Boutons -->
            <div class="flex gap-2 justify-end pt-3">
                <button 
                    type="button" 
                    class="btn btn-outline" 
                    onclick="document.querySelector('.advanced-filters form').reset()"
                >
                    Réinitialiser
                </button>
                <button type="submit" class="btn btn-primary">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Résultats de recherche -->
    <div class="search-results mt-6">
        <!-- Les résultats seront insérés ici par JavaScript -->
    </div>
</div>

<style>
.dynamic-search-container {
    position: relative;
}

.search-bar {
    position: relative;
}

.search-suggestions {
    list-style: none;
    margin: 0;
    padding: 0;
}

.suggestion-item {
    transition: background-color 0.15s ease;
}

.search-results {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.search-results.loading {
    opacity: 0.6;
    pointer-events: none;
}

.product-card {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
    transition: box-shadow 0.2s;
}

.product-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.product-card img {
    width: 100%;
    height: 12rem;
    object-fit: cover;
}

.hidden {
    display: none !important;
}
</style>

<script type="module">
    import { initDynamicSearch } from '@/components/SearchComponent.js';
    // Déjà initialisé au DOMContentLoaded dans SearchComponent.js
</script>
