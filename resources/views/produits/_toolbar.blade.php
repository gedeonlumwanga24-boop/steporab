{{-- produits/_toolbar.blade.php --}}

<div class="filters-panel">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-700">
            <button type="button" onclick="toggleFilters()"
                class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-3 font-medium text-gray-800 shadow-sm hover:bg-gray-50 transition">
                <span>⚙️</span>
                <span>Filtres</span>
            </button>
            <span class="text-xs uppercase tracking-[0.25em] text-gray-500">Filtrer la collection</span>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            <label class="text-xs uppercase tracking-[0.25em] text-gray-500">Trier par</label>
            <select name="tri" onchange="this.form.submit()"
                class="rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm">
                <option value="recent" {{ request('tri', 'recent') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                <option value="price_asc" {{ request('tri') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                <option value="price_desc" {{ request('tri') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
            </select>
        </div>
    </div>
</div>