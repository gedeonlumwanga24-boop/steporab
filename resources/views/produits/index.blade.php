{{-- produits/index.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="page-hero">
    <div class="hero-card">
        <p class="text-sm uppercase tracking-[0.4em] text-slate-400">Collection</p>
        <h1>La sélection STE<span class="text-white/70">PORA</span> — silhouette moderne, look sport.</h1>
        <p class="mt-4 text-base leading-7 text-slate-300">Filtre par prix, catégorie et pointure. Choisis ta paire et passe directement à l’action avec un design inspiré des grandes marques de sport.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <form action="{{ route('produits.index') }}" method="GET" id="productFiltersForm">
        @include('produits._toolbar')
        @include('produits._filters')
    </form>

    @include('produits._grid')
</div>

<script>
    function toggleFilters() {
        const panel = document.getElementById('filtersPanel');
        panel.classList.toggle('hidden');
    }

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