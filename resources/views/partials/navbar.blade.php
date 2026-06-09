@php
    $panier = \App\Models\Panier::forUserOrSession(\Illuminate\Support\Facades\Auth::id(), session()->getId());
    $cartCount = $panier ? $panier->countItems() : 0;

    $unreadAccountCount = 0;
    if (Auth::check()) {
        $unreadAccountCount = \App\Models\Message::where('email', Auth::user()->email)
            ->whereNotNull('reply')
            ->where('client_read', false)
            ->count();
    }
@endphp

<nav class="navbar" id="mainNavbar">
    <div class="nav-inner">
        <div class="nav-brand">
            <a href="{{ route('home') }}" class="logo" style="display: inline-flex; align-items: center;">
                <img src="{{ asset('logo.jpg') }}" alt="The Box" style="height: 92px; width: 57px; object-fit: contain;">
            </a>
        </div>

        {{-- Desktop nav --}}
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
                            @php
                                $megaImg = data_get($category, 'image')
                                    ? asset('storage/categories/' . data_get($category, 'image'))
                                    : match ($categorySlug) {
                                        'chaussures'  => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=600&auto=format&fit=crop',
                                        'vetements'   => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=600&auto=format&fit=crop',
                                        'accessoires' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=600&auto=format&fit=crop',
                                        default       => 'https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=600&auto=format&fit=crop',
                                    };
                            @endphp
                            <div class="nav-mega-feature"
                                 style="background-image: url('{{ $megaImg }}'); background-size: cover; background-position: center; position: relative; overflow: hidden;">
                                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(15,23,42,0.55) 0%, rgba(15,23,42,0.85) 100%);"></div>
                                <span style="position: relative; z-index: 1;">Collection</span>
                                <strong style="position: relative; z-index: 1;">{{ $categoryName }}</strong>
                                <p style="position: relative; z-index: 1;">Découvre une sélection pensée pour le style, le confort et le quotidien.</p>
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

        <form action="{{ route('produits.index') }}" method="GET" class="nav-search desktop-search" role="search">
            <label for="nav-search-input" class="sr-only">Rechercher</label>
            <input id="nav-search-input" name="q" type="search" placeholder="Rechercher…" class="search-input" value="{{ request('q') }}">
            <button type="submit" class="search-button" aria-label="Chercher">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>

        <div class="nav-actions">
            <button class="icon-btn icon-btn--pill mobile-search-btn" id="mobileSearchToggle" aria-label="Rechercher">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>



            <a href="{{ Auth::check() ? route('messagerie.index') : route('login') }}" class="icon-btn icon-btn--pill" aria-label="Messagerie">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                <span class="account-badge {{ $unreadAccountCount > 0 ? '' : 'account-badge--hidden' }}" id="navMessageBadge" style="background-color: #dc2626;">{{ $unreadAccountCount > 9 ? '9+' : $unreadAccountCount }}</span>
            </a>

            <a href="{{ Auth::check() ? route('compte.show') : route('login') }}" class="icon-btn icon-btn--pill nav-account-btn" aria-label="Compte" id="navAccountLink">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>

            <a href="{{ route('panier.index') }}" class="icon-btn icon-btn--pill nav-cart-btn" aria-label="Panier" id="navCartLink">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <span class="cart-badge {{ $cartCount > 0 ? '' : 'cart-badge--hidden' }}" id="navCartBadge">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
            </a>

            <button class="icon-btn icon-btn--pill mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu" aria-expanded="false" aria-controls="mobileNavShell">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round">
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="mobile-search-bar" id="mobileSearchBar">
        <form action="{{ route('produits.index') }}" method="GET" role="search">
            <input name="q" type="search" placeholder="Rechercher des sneakers…" class="mobile-search-input" value="{{ request('q') }}">
            <button type="submit" aria-label="Chercher">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>
        </form>
    </div>
</nav>

<div class="mobile-nav-overlay" id="mobileNavOverlay" aria-hidden="true"></div>

<div class="mobile-nav-shell" id="mobileNavShell" role="dialog" aria-modal="true" aria-label="Menu de navigation">
    <div class="mobile-nav-topbar">
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Fermer le menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    <div class="mobile-nav-body">
        <div class="mobile-category-list">
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
                    <div class="mobile-category-card" data-accordion>
                        <button class="mobile-category-trigger" type="button" aria-expanded="false">
                            <span>{{ $catName }}</span>
                            <svg class="mobile-category-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        <div class="mobile-accordion-panel" hidden>
                            <a href="{{ route('produits.index', ['categorie' => $catSlug]) }}" class="mobile-sub-link mobile-sub-link--all">{{ $allLabel }}</a>
                            @foreach($children as $child)
                                <a href="{{ route('produits.index', ['categorie' => data_get($child, 'slug')]) }}" class="mobile-sub-link">
                                    {{ data_get($child, 'nom') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('produits.index', ['categorie' => $catSlug]) }}" class="mobile-category-card mobile-category-card--link">
                        <span>{{ $catName }}</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                @endif
            @endforeach
        </div>

        <p class="mobile-promo-text">
            Deviens membre Stepora pour accéder au meilleur des produits et découvrir des contenus inspirants sur le sport. <strong>En savoir plus</strong>
        </p>

        <div class="mobile-auth-buttons">
            <a href="{{ route('register') }}" class="mobile-btn-dark">S'inscrire</a>
            <a href="{{ route('login') }}" class="mobile-btn-light">S'identifier</a>
        </div>

        <div class="mobile-bottom-links">
            <a href="{{ route('contact.index') }}" class="mobile-bottom-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Aide
            </a>
            <a href="{{ route('panier.index') }}" class="mobile-bottom-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Panier
            </a>
            @auth
            <a href="{{ route('messagerie.index') }}" class="mobile-bottom-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Messagerie
            </a>
            <a href="{{ route('compte.show') }}" class="mobile-bottom-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Compte & Commandes
            </a>
            @endauth

            <a href="{{ url('/contact') }}" class="mobile-bottom-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 8v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8" />
                <polyline points="3 8 12 13 21 8" />
                </svg>
                Contact
            </a>
            <a href="{{ route('apropos') }}" class="mobile-bottom-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.75"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                À propos
            </a>

        </div>
    </div>
</div>

<script>
(function () {
    const btn       = document.getElementById('mobileMenuBtn');
    const overlay   = document.getElementById('mobileNavOverlay');
    const shell     = document.getElementById('mobileNavShell');
    const closeBtn  = document.getElementById('mobileNavClose');
    const searchBtn = document.getElementById('mobileSearchToggle');
    const searchBar = document.getElementById('mobileSearchBar');
    const navbar    = document.getElementById('mainNavbar');

    function openMenu() {
        shell.classList.add('open');
        overlay.classList.add('open');
        document.body.classList.add('mobile-menu-open');
        btn?.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
        shell.classList.remove('open');
        overlay.classList.remove('open');
        document.body.classList.remove('mobile-menu-open');
        btn?.setAttribute('aria-expanded', 'false');
        searchBar?.classList.remove('open');
        document.querySelectorAll('[data-accordion].is-open').forEach(el => {
            el.classList.remove('is-open');
            el.querySelector('.mobile-category-trigger')?.setAttribute('aria-expanded', 'false');
            const panel = el.querySelector('.mobile-accordion-panel');
            if (panel) panel.hidden = true;
        });
    }

    btn?.addEventListener('click', openMenu);
    overlay?.addEventListener('click', closeMenu);
    closeBtn?.addEventListener('click', closeMenu);

    document.querySelectorAll('[data-accordion]').forEach(card => {
        const trigger = card.querySelector('.mobile-category-trigger');
        const panel   = card.querySelector('.mobile-accordion-panel');
        if (!trigger || !panel) return;

        trigger.addEventListener('click', () => {
            const isOpen = card.classList.contains('is-open');
            document.querySelectorAll('[data-accordion].is-open').forEach(other => {
                if (other === card) return;
                other.classList.remove('is-open');
                other.querySelector('.mobile-category-trigger')?.setAttribute('aria-expanded', 'false');
                const p = other.querySelector('.mobile-accordion-panel');
                if (p) p.hidden = true;
            });
            card.classList.toggle('is-open', !isOpen);
            trigger.setAttribute('aria-expanded', String(!isOpen));
            panel.hidden = isOpen;
        });
    });

    searchBtn?.addEventListener('click', () => {
        searchBar?.classList.toggle('open');
        if (searchBar?.classList.contains('open')) {
            searchBar.querySelector('input')?.focus();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) closeMenu();
    }, { passive: true });

    window.addEventListener('scroll', () => {
        navbar?.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });
})();
</script>
