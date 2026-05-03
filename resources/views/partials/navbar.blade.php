<nav class="navbar">
    <div class="nav-inner">
        <div class="nav-brand">
            <a href="/" class="logo">STEPORA</a>
        </div>

        <div class="nav-links">
            <a href="/produits?genre=homme">Homme</a>
            <a href="/produits?genre=femme">Femme</a>
            <a href="/produits?genre=enfant">Enfant</a>
            <a href="/produits?genre=nouveautes">Nouveautés</a>
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
            <a href="/panier" class="icon-btn" aria-label="Panier">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </a>
            <a href="/compte" class="icon-btn" aria-label="Compte">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </a>
        </div>
    </div>
</nav>