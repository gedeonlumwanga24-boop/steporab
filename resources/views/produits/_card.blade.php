{{-- produits/_card.blade.php --}}

<a href="{{ route('produits.show', $p->id) }}" class="product-card">
    <div class="product-img-wrapper">
        <img src="{{ $p->image_url }}" alt="{{ $p->nom }}" onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500&auto=format&fit=crop'">
    </div>
    <div class="product-card-info">
        <p class="product-card-category">{{ $p->category->nom ?? 'Sneakers' }}</p>
        <h3 class="product-card-title">{{ $p->nom }}</h3>
        <div class="product-card-bottom">
            <span class="product-card-price">{{ number_format($p->prix, 0, ' ', ' ') }} CDF</span>
            <span class="product-card-action">Choisir</span>
        </div>
    </div>
</a>