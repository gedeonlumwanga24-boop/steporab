{{-- produits/_filters.blade.php --}}

<div class="filter-section">
    <div class="filter-group filter-group--categories">
        <p class="filter-label">Catégorie</p>
        @foreach($categories as $cat)
            <label class="filter-option">
                <input type="radio" name="categorie" value="{{ $cat->id }}" onchange="this.form.submit()" {{ request('categorie') == $cat->id ? 'checked' : '' }}>
                <span>{{ $cat->nom }}</span>
            </label>
        @endforeach
        <label class="filter-option">
            <input type="radio" name="categorie" value="" onchange="this.form.submit()" {{ request('categorie') ? '' : 'checked' }}>
            <span>Toutes</span>
        </label>
    </div>

    <div class="filter-group">
        <p class="filter-label">Prix max</p>
        <div class="price-value" id="prixMaxValue"></div>
        <input id="prixMax" type="range" name="prixMax" min="0" max="{{ $sliderMax ?? 80000 }}" value="{{ request('prixMax', $prixMax ?? $sliderMax ?? 80000) }}" onchange="this.form.submit()" class="filter-range">
    </div>

    <div class="filter-group">
        <p class="filter-label">Pointure</p>
        <div class="filter-tags">
            @foreach(['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'] as $size)
                <label class="filter-chip">
                    <input type="radio" name="pointure" value="{{ $size }}" onchange="this.form.submit()" {{ request('pointure') == $size ? 'checked' : '' }}>
                    <span>{{ $size }}</span>
                </label>
            @endforeach
            <label class="filter-chip filter-chip--all">
                <input type="radio" name="pointure" value="" onchange="this.form.submit()" {{ request('pointure') ? '' : 'checked' }}>
                <span>Toutes</span>
            </label>
        </div>
    </div>

    <div class="filter-group">
        <p class="filter-label">Filtres</p>
        <label class="filter-option filter-option--checkbox">
            <input type="checkbox" name="promotion" value="1" onchange="this.form.submit()" {{ request('promotion') ? 'checked' : '' }}>
            <span>En promotion</span>
        </label>
    </div>
</div>
