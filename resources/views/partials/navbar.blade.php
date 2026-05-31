<nav class="navbar" id="mainNavbar">
    <div class="nav-inner">
        <!-- BRAND LOGO -->
        <div class="nav-brand">
            <a href="{{ route('home') }}" class="logo">STEPORA</a>
        </div>

        <!-- DESKTOP NAV LINKS -->
        <div class="desktop-nav-list">
            @foreach($navCategories ?? collect() as $category)
                @php
                    $categoryName = data_get($category, 'nom');
                    $categorySlug = data_get($category, 'slug');
                    $children = collect(data_get($category, 'children', []));
                    $allLabel = match ($categorySlug) {
                        'chaussures' => 'Toutes les chaussures',
                        'vetements'  => 'Tous les vêtements',
                        'accessoires'=> 'Tous les accessoires',
                        default      => 'Tout voir',
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
            <a href="{{ route('apropos') }}" class="nav-menu-trigger nav-link-simple {{ request()->routeIs('apropos') ? 'active' : '' }}">À propos</a>
        </div>

        <!-- SEARCH (desktop) -->
        <form action="{{ route('produits.index') }}" method="GET" class="nav-search desktop-search" role="search">
            <label for="nav-search-input" class="sr-only">Rechercher</label>
            <input id="nav-search-input" name="q" type="search" placeholder="Rechercher…" class="search-input" value="{{ request('q') }}">
            <button type="submit" class="search-button" aria-label="Chercher">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </form>

        <!-- NAV ACTIONS -->
        <div class="nav-actions">
            <!-- Mobile Search Button -->
            <button class="icon-btn mobile-search-btn" id="mobileSearchToggle" aria-label="Rechercher">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>

            <!-- Compte -->
            <a href="{{ Auth::check() ? route('compte.show') : route('login') }}" class="icon-btn" aria-label="Compte">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </a>

            <!-- Panier -->
            <a href="{{ route('panier.index') }}" class="icon-btn" aria-label="Panier" style="position: relative;" id="navCartLink">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                @php $cartCount = collect(session('cart', []))->sum('quantite'); @endphp
                <span class="cart-badge {{ $cartCount > 0 ? '' : 'cart-badge--hidden' }}" id="navCartBadge">{{ $cartCount }}</span>
            </a>

            <!-- Mobile Hamburger -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu" aria-expanded="false" aria-controls="mobileNavShell">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Search Bar (hidden by default) -->
    <div class="mobile-search-bar" id="mobileSearchBar">
        <form action="{{ route('produits.index') }}" method="GET" role="search">
            <input name="q" type="search" placeholder="Rechercher des sneakers…" class="mobile-search-input" value="{{ request('q') }}" autofocus>
            <button type="submit" aria-label="Chercher">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>
    </div>
</nav>

<!-- MOBILE NAV OVERLAY -->
<div class="mobile-nav-overlay" id="mobileNavOverlay" aria-hidden="true"></div>

<!-- MOBILE NAV SHELL (Drawer) -->
<div class="mobile-nav-shell" id="mobileNavShell" role="dialog" aria-modal="true" aria-label="Menu de navigation">

    <!-- VIEW: Main Menu -->
    <div class="mobile-nav-view" id="mobileViewMain">
        <div class="mobile-nav-header" style="justify-content: flex-end; padding: 1rem 1.5rem; border-bottom: none;">
            <button class="mobile-nav-icon" id="mobileNavClose" aria-label="Fermer le menu" style="width: 32px; height: 32px; font-size: 1.5rem; background: transparent;">✕</button>
        </div>

        <div style="padding: 0 1.5rem;">
            <!-- Links List -->
            <div class="mobile-links-list">
                @foreach($navCategories ?? collect() as $category)
                    @php
                        $catName  = data_get($category, 'nom');
                        $catSlug  = data_get($category, 'slug');
                        $children = collect(data_get($category, 'children', []));
                    @endphp
                    @if($children->isNotEmpty())
                        <button class="mobile-nav-item" data-target="{{ $catSlug }}" aria-expanded="false">
                            {{ $catName }}
                            <span class="mobile-nav-chevron" aria-hidden="true">›</span>
                        </button>
                    @else
                        <a href="{{ route('produits.index', ['categorie' => $catSlug]) }}" class="mobile-nav-item mobile-nav-link">
                            {{ $catName }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Promo Text -->
            <p class="mobile-promo-text">
                Deviens membre Stepora pour accéder au meilleur des produits et découvrir des contenus inspirants sur le sport. <strong>En savoir plus</strong>
            </p>

            <!-- Auth Buttons -->
            <div class="mobile-auth-buttons">
                <a href="{{ route('register') }}" class="mobile-btn-dark">S'inscrire</a>
                <a href="{{ route('login') }}" class="mobile-btn-light">S'identifier</a>
            </div>

            <!-- Footer Links -->
            <div class="mobile-bottom-links">
                <a href="{{ route('contact.index') }}" class="mobile-bottom-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    Aide
                </a>
                <a href="{{ route('panier.index') }}" class="mobile-bottom-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Panier
                </a>
                <a href="{{ Auth::check() ? route('compte.show') : route('login') }}" class="mobile-bottom-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Commandes
                </a>
            </div>
        </div>
    </div>

    <!-- SUB VIEWS (Categories) -->
    @foreach($navCategories ?? collect() as $category)
        @php
            $catName  = data_get($category, 'nom');
            $catSlug  = data_get($category, 'slug');
            $children = collect(data_get($category, 'children', []));
            $allLabel = match ($catSlug) {
                'chaussures' => 'Toutes les chaussures',
                'vetements'  => 'Tous les vêtements',
                'accessoires'=> 'Tous les accessoires',
                default      => 'Tout voir',
            };
        @endphp
        @if($children->isNotEmpty())
        <div class="mobile-nav-view mobile-nav-sub" id="mobileView{{ $catSlug }}" hidden>
            <div class="mobile-nav-header mobile-nav-header-sub">
                <button class="mobile-nav-icon mobile-nav-back" data-back="true" aria-label="Retour">‹</button>
                <strong>{{ $catName }}</strong>
                <button class="mobile-nav-icon" data-close="true" aria-label="Fermer">✕</button>
            </div>
            <nav class="mobile-nav-list">
                <a href="{{ route('produits.index', ['categorie' => $catSlug]) }}" class="mobile-sub-link mobile-sub-link-all">{{ $allLabel }}</a>
                @foreach($children as $child)
                    <a href="{{ route('produits.index', ['categorie' => data_get($child,'slug')]) }}" class="mobile-sub-link">
                        {{ data_get($child,'nom') }}
                    </a>
                @endforeach
            </nav>
        </div>
        @endif
    @endforeach

</div>

<script>
(function() {
    const btn       = document.getElementById('mobileMenuBtn');
    const overlay   = document.getElementById('mobileNavOverlay');
    const shell     = document.getElementById('mobileNavShell');
    const closeBtn  = document.getElementById('mobileNavClose');
    const mainView  = document.getElementById('mobileViewMain');
    const searchBtn = document.getElementById('mobileSearchToggle');
    const searchBar = document.getElementById('mobileSearchBar');

    function openMenu() {
        shell.classList.add('open');
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
        btn.setAttribute('aria-expanded', 'true');
        showView(mainView);
    }

    function closeMenu() {
        shell.classList.remove('open');
        overlay.classList.remove('open');
        document.body.style.overflow = '';
        btn.setAttribute('aria-expanded', 'false');
        searchBar.classList.remove('open');
    }

    function showView(view) {
        document.querySelectorAll('.mobile-nav-view').forEach(v => {
            v.hidden = true;
            v.setAttribute('aria-hidden', 'true');
        });
        view.hidden = false;
        view.removeAttribute('aria-hidden');
    }

    btn.addEventListener('click', openMenu);
    overlay.addEventListener('click', closeMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);

    // Category triggers
    document.querySelectorAll('.mobile-nav-item[data-target]').forEach(item => {
        item.addEventListener('click', function() {
            const target = this.dataset.target;
            const subView = document.getElementById('mobileView' + target);
            if (subView) showView(subView);
        });
    });

    // Back buttons
    document.querySelectorAll('[data-back="true"]').forEach(btn => {
        btn.addEventListener('click', () => showView(mainView));
    });

    // Close buttons inside sub-views
    document.querySelectorAll('[data-close="true"]').forEach(b => {
        b.addEventListener('click', closeMenu);
    });

    // Mobile search toggle
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            searchBar.classList.toggle('open');
            if (searchBar.classList.contains('open')) {
                searchBar.querySelector('input').focus();
            }
        });
    }

    // Show mobile search btn + hide desktop search on mobile
    function handleResize() {
        if (window.innerWidth <= 900) {
            searchBtn.style.display = 'inline-flex';
        } else {
            searchBtn.style.display = 'none';
            searchBar.classList.remove('open');
            closeMenu();
        }
    }
    handleResize();
    window.addEventListener('resize', handleResize);

    // Navbar scroll shadow
    const navbar = document.getElementById('mainNavbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });
})();
</script>
