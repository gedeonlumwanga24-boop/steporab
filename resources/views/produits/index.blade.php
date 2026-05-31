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
            <select id="tri" name="tri" form="productFiltersForm" class="catalog-sort" onchange="document.getElementById('productFiltersForm').submit()">
                <option value="recent" {{ request('tri', 'recent') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                <option value="price_asc" {{ request('tri') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                <option value="price_desc" {{ request('tri') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
            </select>
        </div>
    </div>

    <div class="catalog-layout">
        <button type="button" class="mobile-filter-btn" onclick="document.querySelector('.catalog-sidebar').classList.toggle('open')">
            Filtres et tri
        </button>

        <aside class="catalog-sidebar">
            <form action="{{ route('produits.index') }}" method="GET" id="productFiltersForm" class="sidebar-form">
                @include('produits._filters')
            </form>
        </aside>

        <main class="catalog-main">
            <div class="produits-grid">
                @forelse($produits as $p)
                    @include('produits._card', ['p' => $p])
                @empty
                    <p>Aucun produit trouvé.</p>
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

    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('prixMax');
        if (slider) {
            updatePriceDisplay(slider.value);
            slider.addEventListener('input', function() {
                updatePriceDisplay(this.value);
            });
        }
    });
</script>

@endsection