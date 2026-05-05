@extends('layouts.app')

@section('content')
<!-- HERO SECTION -->
<section class="hero-section">
    @php
        $heroImage = $configs['hero_image'] ?? 'https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=2000&auto=format&fit=crop';
        if (!str_starts_with($heroImage, 'http')) {
            $heroImage = asset('storage/' . $heroImage);
        }
    @endphp
    <img src="{{ $heroImage }}" alt="Stepora Sneakers" class="hero-bg animate-zoom">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <p class="hero-tagline animate-fade-in">{{ $configs['hero_tagline'] ?? 'Nouvelle Collection' }}</p>
        <h1 class="hero-title animate-slide-up">{{ $configs['hero_title'] ?? 'L\'élégance à chaque pas.' }}</h1>
        <p class="hero-desc animate-fade-in-delay">Découvrez notre nouvelle gamme de sneakers premium. Conçues pour le confort, pensées pour le style urbain.</p>
        <a href="{{ route('produits.index') }}" class="hero-btn animate-slide-up-delay">
            Découvrir la collection <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- COLLECTIONS / CATEGORIES -->
<section class="home-section reveal">
    <div class="section-header">
        <h2 class="section-title">Collections</h2>
        <div class="slider-controls">
            <button class="slider-btn prev" onclick="scrollSlider(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="slider-btn next" onclick="scrollSlider(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
    
    <div class="categories-slider-wrapper">
        <div class="categories-slider" id="categoriesSlider">
            @foreach($categories as $category)
                <a href="{{ route('produits.index', ['categorie' => $category->slug]) }}" class="category-card">
                    @php
                        $catImg = $category->image 
                            ? asset('storage/categories/' . $category->image) 
                            : match ($category->slug) {
                                'hommes' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=800&auto=format&fit=crop',
                                'femmes' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=800&auto=format&fit=crop',
                                'accessoires' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
                                default => 'https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=800&auto=format&fit=crop'
                            };
                    @endphp
                    <img src="{{ $catImg }}" alt="{{ $category->nom }}">
                    <div class="category-overlay"></div>
                    <div class="category-content">
                        <h3 class="category-title">{{ $category->nom }}</h3>
                        <span class="category-btn">Acheter</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- TRENDING / LATEST PRODUCTS -->
<section class="home-section reveal" style="background: #f9fafb;">
    <div class="section-header">
        <h2 class="section-title">Tendances du moment</h2>
        <a href="{{ route('produits.index') }}" class="section-link">Tout voir</a>
    </div>

    <div class="trending-grid">
        @forelse($trendingProducts ?? [] as $produit)
            <a href="{{ route('produits.show', $produit->id) }}" class="product-card">
                <div class="product-img-wrapper">
                    @if($loop->first)
                        <span class="product-badge-new">Nouveau</span>
                    @endif
                    @php
                        $productImageUrl = $produit->image ? asset('storage/produits/' . $produit->image) : asset('images/2020-nike.jpg');
                    @endphp
                    <img src="{{ $productImageUrl }}" alt="{{ $produit->nom }}" onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500&auto=format&fit=crop'">
                </div>
                <div class="product-card-info">
                    <p class="product-card-category">{{ $produit->category->nom ?? 'Sneakers' }}</p>
                    <h3 class="product-card-title">{{ $produit->nom }}</h3>
                    <p class="product-card-price">{{ number_format($produit->prix, 0, ' ', ' ') }} CDF</p>
                </div>
            </a>
        @empty
            <!-- Fallback if database is empty -->
            @for($i=1; $i<=4; $i++)
            <a href="{{ route('produits.index') }}" class="product-card">
                <div class="product-img-wrapper">
                    @if($i==1) <span class="product-badge-new">Nouveau</span> @endif
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500&auto=format&fit=crop" alt="Sneaker">
                </div>
                <div class="product-card-info">
                    <p class="product-card-category">Sneakers</p>
                    <h3 class="product-card-title">Stepora Air Max</h3>
                    <p class="product-card-price">120 000 CDF</p>
                </div>
            </a>
            @endfor
        @endforelse
    </div>
</section>

<!-- BRAND VALUES -->
<section class="values-section reveal">
    <h2 class="values-title">Conçu pour la rue.<br>Pensé pour le confort.</h2>
    
    <div class="values-grid">
        <div class="value-item">
            <i class="fa-solid fa-truck-fast"></i>
            <h3>Livraison Express</h3>
            <p>Recevez vos sneakers rapidement partout avec notre réseau de livraison premium.</p>
        </div>
        
        <div class="value-item">
            <i class="fa-solid fa-arrow-rotate-left"></i>
            <h3>Retours Gratuits</h3>
            <p>La taille ne convient pas ? Retournez vos articles facilement sous 30 jours.</p>
        </div>
        
        <div class="value-item">
            <i class="fa-solid fa-check-double"></i>
            <h3>Authenticité Garantie</h3>
            <p>Tous nos produits sont 100% authentiques et vérifiés par nos experts avant expédition.</p>
        </div>
    </div>
</section>

@endsection

<script>
    // Reveal animations on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => {
            observer.observe(el);
        });
    });

    function scrollSlider(direction) {
        const slider = document.getElementById('categoriesSlider');
        const scrollAmount = 400; // Ajuster selon la largeur de la carte + gap
        slider.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
</script>
