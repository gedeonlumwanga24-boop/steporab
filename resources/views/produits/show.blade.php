@extends('layouts.app')

@section('content')
<div class="product-show-container">
    @if(session('success'))
        <div style="background: #10b981; color: white; padding: 1rem; text-align: center; font-weight: bold; margin-bottom: 1rem; border-radius: 8px; animation: slideDown 0.3s ease-out;">
            <i class="fa-solid fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
            <a href="{{ route('panier.index') }}" style="text-decoration: underline; margin-left: 1rem;">Voir le panier</a>
        </div>
    @endif

    <div class="product-show-layout">
        <!-- LEFT: Image Gallery -->
        <div class="product-gallery">
            <div class="product-main-image">
                @if($produit->stock > 0)
                    <span class="product-badge">En stock</span>
                @else
                    <span class="product-badge product-badge--outofstock">Rupture</span>
                @endif
                @php
                    $productImageUrl = $produit->image_url;
                @endphp
                <img id="mainImage" src="{{ $productImageUrl }}" alt="{{ $produit->nom }}" class="product-image-main">
            </div>

            <!-- Thumbnails -->
            <div class="product-thumbnails">
                <div class="product-thumbnail active" onclick="changeImage(this)">
                    <img src="{{ $productImageUrl }}" alt="{{ $produit->nom }}">
                </div>
                @if($produit->galerie && is_array($produit->galerie))
                    @foreach($produit->galerie as $miniature)
                        <div class="product-thumbnail" onclick="changeImage(this)">
                            <img src="{{ asset('storage/produits/'.$miniature) }}" alt="{{ $produit->nom }}">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- RIGHT: Product Info -->
        <div class="product-info-panel">
            <!-- Category & Badge -->
            <span class="product-category-tag">{{ $produit->category->nom ?? 'Chaussure' }}</span>
            
            <!-- Title -->
            <h1 class="product-show-title">{{ $produit->nom }}</h1>
            
            <!-- Tagline -->
            <p class="product-tagline">Chaussure pour homme</p>

            <!-- Price -->
            <div class="product-price-section">
                <span class="product-price">{{ number_format($produit->prix, 0, ' ', ' ') }} CDF</span>
            </div>

            <!-- Size Selection -->
            <div class="product-size-section">
                <label class="size-label">Sélectionner la taille</label>
                <div class="size-grid">
                    @php $sizes = ['EU 36', 'EU 37', 'EU 38', 'EU 39', 'EU 40', 'EU 41', 'EU 42', 'EU 43', 'EU 44', 'EU 45']; @endphp
                    @foreach($sizes as $size)
                        <button class="size-btn" onclick="selectSize(this)">{{ $size }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Color Selection -->
            <div class="product-color-section">
                <label class="color-label">Couleur</label>
                <div class="color-options">
                    <button class="color-btn active" style="background-color: #333;" title="Noir"></button>
                    <button class="color-btn" style="background-color: #4682B4;" title="Bleu"></button>
                    <button class="color-btn" style="background-color: #DC143C;" title="Rouge"></button>
                    <button class="color-btn" style="background-color: #FFD700;" title="Or"></button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="product-action-buttons">
                <form action="{{ route('panier.ajouter', $produit->id) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="btn-add-to-cart">Ajouter au panier</button>
                </form>
                <button class="btn-wishlist">
                    <i class="fa-solid fa-heart"></i> Ajouter aux favoris
                </button>
            </div>

            <!-- Free Delivery Info -->
            <div class="product-info-box">
                <p><strong>Livraison gratuite</strong><br>Traceur en magasin</p>
            </div>

            <!-- Return Info -->
            <div class="product-info-box">
                <p><strong>Retour gratuit</strong><br>Traceur en magasin</p>
            </div>

            <!-- Description -->
            <div class="product-description-section">
                <p>{{ $produit->description }}</p>
            </div>

            <!-- Product Details -->
            <details class="product-details-accordion">
                <summary class="details-summary">Afficher les détails du produit</summary>
                <div class="details-content">
                    <ul>
                        <li><strong>Catégorie :</strong> {{ $produit->category->nom ?? 'N/A' }}</li>
                        <li><strong>Code produit :</strong> #{{ $produit->id }}</li>
                        <li><strong>Stock disponible :</strong> {{ $produit->stock }} unités</li>
                    </ul>
                </div>
            </details>
        </div>
    </div>

    <!-- Additional Content Below -->
    <div class="product-additional-section">
        <h2>À propos de ce produit</h2>
        <p>Découvrez une sélection premium de baskets et chaussures pour tous les styles. Confortable, durable et à la mode.</p>
    </div>
</div>

<script>
    function changeImage(el) {
        document.querySelectorAll('.product-thumbnail').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('mainImage').src = el.querySelector('img').src;
    }

    function selectSize(el) {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
    }
</script>
@endsection
