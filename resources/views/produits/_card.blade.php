{{-- produits/_card.blade.php — style Nike --}}
@php
    $categoryLabel = $p->category->nom ?? 'Sneakers';
    $isRecent = $p->created_at?->gte(now()->subDays(30));
    $subtitle = match (true) {
        str_contains(strtolower($categoryLabel), 'vêtement'),
        str_contains(strtolower($categoryLabel), 'vetement') => 'Vêtement Stepora',
        str_contains(strtolower($categoryLabel), 'accessoire') => 'Accessoire Stepora',
        str_contains(strtolower($categoryLabel), 'sweat'),
        str_contains(strtolower($categoryLabel), 'pantalon') => 'Vêtement pour homme',
        default => 'Chaussure Stepora',
    };
@endphp

<a href="{{ route('produits.show', $p->id) }}" class="product-card">
    <div class="product-img-wrapper">
        <img
            src="{{ $p->image_url }}"
            alt="{{ $p->nom }}"
            loading="lazy"
            decoding="async"
            onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500&auto=format&fit=crop'"
        >
    </div>
    <div class="product-card-info">
        <p class="product-card-tag">{{ $isRecent ? 'Dernières sorties' : $categoryLabel }}</p>
        <h3 class="product-card-title">{{ $p->nom }}</h3>
        <p class="product-card-desc">{{ $subtitle }}</p>
        <p class="product-card-price">{{ number_format($p->prix, 0, ',', ' ') }} CDF</p>
    </div>
</a>
