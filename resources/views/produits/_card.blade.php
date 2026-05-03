{{-- produits/_card.blade.php --}}

<div class="product-card">
    <div class="product-card-image">
        @if(isset($p->created_at) && $p->created_at->gt(now()->subDays(30)))
            <span class="badge badge-new">Nouveau</span>
        @endif
        @php
            $imagePath = $p->image;
            $imageUrl = asset('images/2020-nike.jpg');
            if ($imagePath) {
                if (file_exists(public_path('storage/'.$imagePath))) {
                    $imageUrl = asset('storage/'.$imagePath);
                } elseif (file_exists(public_path('images/'.$imagePath))) {
                    $imageUrl = asset('images/'.$imagePath);
                }
            }
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