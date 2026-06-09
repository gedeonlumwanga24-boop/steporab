{{-- produits/_filters.blade.php --}}
@if(request('q'))
    <input type="hidden" name="q" value="{{ request('q') }}">
@endif
@if(request('tri'))
    <input type="hidden" name="tri" value="{{ request('tri') }}">
@endif

<div class="filter-section">
    <div class="filter-panel-header">
        <span>Affiner</span>
        <strong>Filtres</strong>
    </div>

    <div class="filter-group filter-group--categories">
        <p class="filter-label">Catégorie</p>

        @foreach($categories as $cat)
            <label class="filter-option filter-option--parent">
                <input type="radio" name="categorie" value="{{ $cat->slug }}" onchange="this.form.submit()" {{ request('categorie') == $cat->slug || request('categorie') == $cat->id ? 'checked' : '' }}>
                <span>{{ $cat->nom }}</span>
            </label>

            @foreach($cat->children as $child)
                <label class="filter-option filter-option--child">
                    <input type="radio" name="categorie" value="{{ $child->slug }}" onchange="this.form.submit()" {{ request('categorie') == $child->slug || request('categorie') == $child->id ? 'checked' : '' }}>
                    <span>{{ $child->nom }}</span>
                </label>
            @endforeach
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
        <p class="filter-label">Taille / Pointure</p>
        <div class="filter-tags">
            @foreach(['38', '39', '40', '41', '42', '43', '44', '45', 'XS', 'S', 'M', 'L', 'XL'] as $size)
                <label class="filter-chip">
                    <input type="radio" name="taille" value="{{ $size }}" onchange="this.form.submit()" {{ request('taille') == $size ? 'checked' : '' }}>
                    <span>{{ $size }}</span>
                </label>
            @endforeach
            <label class="filter-chip filter-chip--all">
                <input type="radio" name="taille" value="" onchange="this.form.submit()" {{ request('taille') ? '' : 'checked' }}>
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
