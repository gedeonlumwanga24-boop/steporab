{{-- produits/_card.blade.php --}}

<div class="product-card group">

    <div class="overflow-hidden rounded-3xl">
        <img src="{{ asset('storage/'.$p->image) }}"
             class="product-image group-hover:scale-105 transition duration-300">
    </div>

    <div class="mt-4">
        <h3 class="product-name">{{ strtoupper($p->nom) }}</h3>
        <p class="product-desc">{{ Str::limit($p->description, 28) }}</p>
    </div>

    <div class="product-footer">
        <div>
            <span class="price">{{ number_format($p->prix, 0, ' ', ' ') }} CDF</span>
            <p class="text-[11px] text-gray-500 mt-1">{{ $p->category->nom ?? 'Catégorie' }}</p>
        </div>
        <span class="badge">{{ $p->category->nom ?? 'N/A' }}</span>
    </div>

</div>