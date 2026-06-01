{{-- produits/index.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="catalog-page">
    <div class="catalog-header">
        <div>
            <p class="catalog-eyebrow">Catalogue chaussures</p>
            @if(request('q'))
                <h1 class="catalog-title">Résultats pour "{{ request('q') }}"</h1>
                <p class="catalog-copy">Nous avons trouvé {{ $produits->count() }} modèles correspondant à votre recherche. <a href="{{ route('produits.index') }}" style="text-decoration: underline; color: #000;">Effacer la recherche</a></p>
            @else
                <h1 class="catalog-title">Sélection premium de baskets et sneakers</h1>
                <p class="catalog-copy">Un assortiment trié sur le volet, conçu pour offrir une expérience claire, moderne et haut de gamme.</p>
            @endif
        </div>

        <div class="catalog-quick-info">
            <span>{{ $produits->count() }} modèles disponibles</span>
            <label for="tri" class="sr-only">Trier par</label>
            <select id="tri" name="tri" form="productFiltersForm" class="catalog-sort" onchange="submitCatalogFilters(document.getElementById('productFiltersForm'))">
                <option value="recent" {{ request('tri', 'recent') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                <option value="price_asc" {{ request('tri') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                <option value="price_desc" {{ request('tri') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
            </select>
        </div>
    </div>

    <div class="catalog-layout">
        <div class="filters-overlay" id="filtersOverlay" aria-hidden="true"></div>

        <button type="button" class="mobile-filter-btn" id="mobileFilterBtn">
            Filtres et tri
        </button>

        <aside class="catalog-sidebar" id="catalogSidebar">
            <button type="button" class="filters-close-btn" id="filtersCloseBtn" aria-label="Fermer les filtres">&times;</button>
            <form action="{{ route('produits.index') }}" method="GET" id="productFiltersForm" class="sidebar-form">
                @include('produits._filters')
            </form>
        </aside>

        <main class="catalog-main">
            <div class="produits-grid">
                @forelse($produits as $p)
                    @include('produits._card', ['p' => $p])
                @empty
                    <p class="catalog-empty">Aucun produit ne correspond à votre recherche. <a href="{{ route('produits.index') }}">Voir tout le catalogue</a></p>
                @endforelse
            </div>
        </main>
    </div>
</div>

<script>
    function updatePriceDisplay(value) {
        const element = document.getElementById('prixMaxValue');
        if (element) {
            element.textContent = new Intl.NumberFormat('fr-FR').format(value) + ' CDF';
        }
    }

    function submitCatalogFilters(form) {
        if (!form) return;
        form.submit();
        if (window.innerWidth <= 900) {
            closeFilters();
        }
    }

    function openFilters() {
        document.getElementById('catalogSidebar')?.classList.add('open');
        document.getElementById('filtersOverlay')?.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeFilters() {
        document.getElementById('catalogSidebar')?.classList.remove('open');
        document.getElementById('filtersOverlay')?.classList.remove('open');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('prixMax');
        if (slider) {
            updatePriceDisplay(slider.value);
            slider.addEventListener('input', function() {
                updatePriceDisplay(this.value);
            });
        }

        document.getElementById('mobileFilterBtn')?.addEventListener('click', openFilters);
        document.getElementById('filtersCloseBtn')?.addEventListener('click', closeFilters);
        document.getElementById('filtersOverlay')?.addEventListener('click', closeFilters);
    });
</script>

@endsection