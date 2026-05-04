{{-- produits/_card.blade.php --}}

<div class="product-card">
    <div class="product-card-image">
        @php
            $imageUrl = $p->image_url;
        @endphp
        <a href="{{ route('produits.show', $p->id) }}" class="product-card-image-link" aria-label="Voir le produit {{ $p->nom }}">
            <img src="{{ $imageUrl }}" class="product-image" alt="{{ $p->nom }}">
        </a>
    </div>

    <div class="product-card-body">
        <p class="product-category">{{ $p->category->nom ?? 'Chaussure' }}</p>
        <h3 class="product-name">{{ $p->nom }}</h3>
        <p class="product-desc">{{ Str::limit($p->description, 60) }}</p>
    </div>

    <div class="product-card-footer">
        <span class="price">{{ number_format($p->prix, 0, ' ', ' ') }} CDF</span>
    </div>
</div>