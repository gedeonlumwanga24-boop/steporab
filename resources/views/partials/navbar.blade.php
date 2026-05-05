<nav class="navbar">
    <div class="nav-inner">
        <div class="nav-brand">
            <a href="/" class="logo">STEPORA</a>
        </div>

        <div class="nav-links">
            @foreach($navCategories ?? collect() as $category)
                @php
                    $categoryName = data_get($category, 'nom');
                    $categorySlug = data_get($category, 'slug');
                    $children = collect(data_get($category, 'children', []));
                    $allLabel = match ($categorySlug) {
                        'chaussures' => 'Toutes les chaussures',
                        'vetements' => 'Tous les vêtements',
                        'accessoires' => 'Tous les accessoires',
                        default => 'Tout voir',
                    };
                @endphp

                <div class="nav-menu-item">
                    <a href="{{ route('produits.index', ['categorie' => $categorySlug]) }}" class="nav-menu-trigger">
                        {{ $categoryName }}
                    </a>

                    <div class="nav-mega-menu">
                        <div class="nav-mega-inner">
                            <div class="nav-mega-feature">
                                <span>Collection</span>
                                <strong>{{ $categoryName }}</strong>
                                <p>Découvre une sélection pensée pour le style, le confort et le quotidien.</p>
                            </div>

                            <div class="nav-mega-links">
                                <a href="{{ route('produits.index', ['categorie' => $categorySlug]) }}" class="nav-mega-link nav-mega-link--all">
                                    {{ $allLabel }}
                                </a>

                                @foreach($children as $child)
                                    <a href="{{ route('produits.index', ['categorie' => data_get($child, 'slug')]) }}" class="nav-mega-link">
                                        {{ data_get($child, 'nom') }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form action="{{ route('produits.index') }}" method="GET" class="nav-search" role="search">
            <label for="search" class="sr-only">Rechercher</label>
            <input id="search" name="q" type="search" placeholder="Rechercher des chaussures" class="search-input">
            <button type="submit" class="search-button" aria-label="Chercher">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </form>

        <div class="nav-actions">
            <a href="/panier" class="icon-btn" aria-label="Panier" style="position: relative;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                @php $cartCount = collect(session('cart', []))->sum('quantite'); @endphp
                @if($cartCount > 0)
                    <span style="position: absolute; top: -5px; right: -8px; background: #dc2626; color: white; font-size: 0.65rem; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <a href="{{ Auth::check() ? route('compte.show') : route('login') }}" class="icon-btn" aria-label="Compte">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </a>
        </div>
    </div>
</nav>
