{{-- produits/_filters.blade.php --}}

<div id="filtersPanel" class="mt-6 grid gap-4 lg:grid-cols-3">

    <div class="filter-card">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-gray-500">Prix max</p>
                <p id="prixMaxValue" class="text-lg font-semibold text-black"></p>
            </div>
        </div>
        <input id="prixMax" type="range" name="prixMax" min="0" max="{{ $sliderMax ?? 80000 }}"
               value="{{ request('prixMax', $prixMax ?? $sliderMax ?? 80000) }}"
               onchange="this.form.submit()"
               class="w-full">
    </div>

    <div class="filter-card">
        <label class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-2 block">Catégorie</label>
        <select name="categorie" onchange="this.form.submit()"
            class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm">
            <option value="">Toutes</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-card">
        <label class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-2 block">Pointure</label>
        <select name="pointure" onchange="this.form.submit()"
            class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm">
            <option value="">Toutes</option>
            @foreach(['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'] as $size)
                <option value="{{ $size }}" {{ request('pointure') == $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
    </div>

</div>