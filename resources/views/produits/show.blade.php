@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="product-show-hero">
        <div class="product-show-media">
            <span class="product-badge">Nouveauté</span>
            @php
                $imagePath = $produit->image;
                $productImageUrl = asset('images/2020-nike.jpg');
                if ($imagePath) {
                    if (file_exists(public_path('storage/'.$imagePath))) {
                        $productImageUrl = asset('storage/'.$imagePath);
                    } elseif (file_exists(public_path('images/'.$imagePath))) {
                        $productImageUrl = asset('images/'.$imagePath);
                    }
                }
            @endphp
            <img src="{{ $productImageUrl }}" alt="{{ $produit->nom }}" class="w-full h-full object-cover">
        </div>

        <div class="product-show-summary">
            <span class="product-label">{{ $produit->category->nom ?? 'Catégorie' }}</span>
            <h1 class="product-title">{{ $produit->nom }}</h1>
            <p class="text-sm uppercase tracking-[0.25em] text-gray-500 mb-4">Collection STE<span class="text-gray-900">PORA</span></p>
            <p class="product-lead">{{ $produit->description }}</p>

            <div class="product-meta-grid">
                <div>
                    <span class="meta-title">Prix</span>
                    <p class="meta-value">{{ number_format($produit->prix, 0, ' ', ' ') }} CDF</p>
                </div>
                <div>
                    <span class="meta-title">Disponibilité</span>
                    <p class="meta-value">{{ $produit->stock > 0 ? 'En stock' : 'Rupture' }}</p>
                </div>
                <div>
                    <span class="meta-title">Référence</span>
                    <p class="meta-value">#{{ $produit->id }}</p>
                </div>
            </div>

            <div class="product-action-group">
                <form action="{{ route('panier.ajouter', $produit->id) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="btn-cart w-full">Ajouter au panier</button>
                </form>
                <a href="{{ route('produits.index') }}" class="btn-secondary w-full">Retour aux produits</a>
            </div>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.6fr_0.9fr] mt-10">
        <div class="bg-white rounded-3xl p-8 shadow-soft">
            <h2 class="section-title">Présentation détaillée</h2>
            <p class="section-copy">Découvrez tous les éléments qui font de ce produit un article professionnel et incontournable de votre garde-robe.</p>

            <div class="product-detail-block">
                <h3>Design et finition</h3>
                <p>Ce modèle associe une silhouette moderne à une fabrication soignée, des finitions premium et un rendu visuel irréprochable.</p>
            </div>

            <div class="product-detail-grid">
                <div class="detail-card">
                    <span class="detail-icon">🧵</span>
                    <div>
                        <strong>Matériaux qualitatifs</strong>
                        <p>Tissus résistants, confort optimal et maintien renforcé.</p>
                    </div>
                </div>
                <div class="detail-card">
                    <span class="detail-icon">⚡</span>
                    <div>
                        <strong>Performance</strong>
                        <p>Conçu pour un usage quotidien, sportif et lifestyle.</p>
                    </div>
                </div>
                <div class="detail-card">
                    <span class="detail-icon">🏁</span>
                    <div>
                        <strong>Look affirmé</strong>
                        <p>Un style premium adapté aux tendances actuelles et aux grandes enseignes.</p>
                    </div>
                </div>
            </div>

            <div class="product-specs">
                <h3>Caractéristiques</h3>
                <ul>
                    <li>Catégorie : {{ $produit->category->nom ?? 'N/A' }}</li>
                    <li>Stock disponible : {{ $produit->stock }}</li>
                    <li>Code produit : #{{ $produit->id }}</li>
                    <li>Prix : {{ number_format($produit->prix, 0, ' ', ' ') }} CDF</li>
                </ul>
            </div>
        </div>

        <aside class="bg-slate-950 text-white rounded-3xl p-8 shadow-soft">
            <div class="aside-card">
                <h2 class="aside-title">Pourquoi choisir ce modèle ?</h2>
                <p class="aside-copy">Une présentation soignée, un parcours d'achat haut de gamme et des informations conçues pour rassurer vos clients.</p>
            </div>

            <div class="aside-feature">
                <span class="aside-icon">🚚</span>
                <div>
                    <strong>Livraison express</strong>
                    <p>Expédition rapide et suivi premium.</p>
                </div>
            </div>
            <div class="aside-feature">
                <span class="aside-icon">🔒</span>
                <div>
                    <strong>Paiement sécurisé</strong>
                    <p>Transactions garanties sur toute la plateforme.</p>
                </div>
            </div>
            <div class="aside-feature">
                <span class="aside-icon">⭐</span>
                <div>
                    <strong>Service client dédié</strong>
                    <p>Un accompagnement fiable pour chaque commande.</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
