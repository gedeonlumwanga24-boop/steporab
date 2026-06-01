@extends('layouts.app')

@section('content')
@php
    $heroImage = $configs['hero_image'] ?? 'https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=2000&auto=format&fit=crop';
    if (!str_starts_with($heroImage, 'http')) {
        $heroImage = asset('storage/' . $heroImage);
    }

    $heroVideo = $configs['hero_video'] ?? null;
    if ($heroVideo && !str_starts_with($heroVideo, 'http')) {
        $heroVideo = asset('storage/' . $heroVideo);
    }

    $trendingVideo   = $configs['trending_video']  ?? null;
    $trendingTagline = $configs['trending_tagline'] ?? 'Les essentiels de la saison';
    $trendingTitle   = $configs['trending_title']   ?? "Pour ceux\nqui bougent.";
    if ($trendingVideo && !str_starts_with($trendingVideo, 'http')) {
        $trendingVideo = asset('storage/' . $trendingVideo);
    }
@endphp

<style>
/* ============================
   HOME PREMIUM OVERRIDES
   ============================ */

/* ─── HERO ─── */
.hero-section {
    height: 100vh !important;
    min-height: 640px;
}
.hero-content {
    text-align: left !important;
    max-width: none !important;
    padding: 0 6% !important;
    padding-bottom: 8% !important;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    height: 100%;
}
.hero-overlay {
    background: linear-gradient(to right, rgba(0,0,0,0.78) 30%, rgba(0,0,0,0.1) 100%) !important;
}
.hero-tagline {
    font-size: 0.75rem !important;
    letter-spacing: 0.3em !important;
    color: rgba(255,255,255,0.5) !important;
    margin-bottom: 1.5rem !important;
}
.hero-title {
    font-size: clamp(3.5rem, 9vw, 8rem) !important;
    line-height: 0.9 !important;
    letter-spacing: -0.03em !important;
    margin-bottom: 2rem !important;
    max-width: 800px;
}
.hero-desc {
    font-size: 1.1rem !important;
    color: rgba(255,255,255,0.65) !important;
    max-width: 460px;
    margin: 0 0 2.5rem !important;
}
.hero-btn {
    border-radius: 3px !important;
    font-size: 0.85rem !important;
    letter-spacing: 0.1em !important;
    padding: 1rem 2.5rem !important;
    background: #fff !important;
    color: #111 !important;
    width: fit-content;
}
.hero-btn:hover {
    background: #f97316 !important;
    color: #fff !important;
    transform: none !important;
    box-shadow: none !important;
}

/* ─── SCROLL INDICATOR ─── */
.hero-scroll-hint {
    position: absolute;
    bottom: 3rem;
    left: 6%;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(255,255,255,0.4);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    z-index: 4;
}
.hero-scroll-line {
    width: 60px;
    height: 1px;
    background: rgba(255,255,255,0.25);
    position: relative;
    overflow: hidden;
}
.hero-scroll-line::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.6);
    animation: scrollPulse 2s ease-in-out infinite;
}
@keyframes scrollPulse {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(200%); }
}

/* ─── STATS BAR ─── */
.home-stats-bar {
    background: #111;
    color: #fff;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.home-stat {
    flex: 1;
    min-width: 140px;
    padding: 2rem 1.5rem;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,0.07);
}
.home-stat:last-child { border-right: none; }
.home-stat-num {
    display: block;
    font-size: clamp(1.8rem, 3vw, 2.5rem);
    font-weight: 900;
    line-height: 1;
    margin-bottom: 0.4rem;
}
.home-stat-num sup { font-size: 0.55em; color: #f97316; }
.home-stat-lbl {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: rgba(255,255,255,0.38);
}

/* ─── SECTION WRAPPER ─── */
.home-section {
    padding: 5rem 6% !important;
}
.home-section.bg-light { background: #f9f9f9 !important; }

/* ─── SECTION HEADER ─── */
.section-header {
    margin-bottom: 3rem !important;
    align-items: center !important;
}
.section-tag {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #f97316;
    display: block;
    margin-bottom: 0.75rem;
}
.section-title {
    font-size: clamp(2rem, 4vw, 3.5rem) !important;
    letter-spacing: -0.02em !important;
    line-height: 1 !important;
}

/* ─── CATEGORIES SLIDER ─── */
.category-card {
    border-radius: 4px !important;
    height: 520px !important;
}
.category-title {
    font-size: 1.75rem !important;
    letter-spacing: -0.01em !important;
}
.category-btn {
    font-size: 0.75rem !important;
    letter-spacing: 0.15em !important;
    border-bottom: 1px solid rgba(255,255,255,0.5) !important;
    padding-bottom: 3px !important;
}
.categories-slider.marquee-content {
    animation: marquee 30s linear infinite !important;
}

/* ─── VIDEO BANNER ─── */
.video-banner-section {
    height: 80vh !important;
}
.video-banner-overlay {
    background: linear-gradient(to right, rgba(0,0,0,0.78) 35%, rgba(0,0,0,0.1) 100%) !important;
}
.video-banner-content {
    text-align: left !important;
    position: absolute !important;
    left: 6% !important;
    bottom: 10% !important;
    max-width: none !important;
    padding: 0 !important;
}
.video-banner-tagline {
    color: rgba(255,255,255,0.45) !important;
    font-size: 0.72rem !important;
    letter-spacing: 0.25em !important;
}
.video-banner-title {
    font-size: clamp(2.5rem, 6vw, 5rem) !important;
    line-height: 0.95 !important;
    letter-spacing: -0.03em !important;
    margin-bottom: 2.5rem !important;
    max-width: 600px;
}
.video-banner-btn {
    border-radius: 3px !important;
    font-size: 0.82rem !important;
    letter-spacing: 0.1em !important;
    padding: 1rem 2.5rem !important;
}

/* ─── ICONIC (subcategories) ─── */
.iconic-product-card {
    flex: 0 0 340px !important;
    border-radius: 4px !important;
    overflow: hidden;
}
.iconic-img-wrapper {
    aspect-ratio: 1.2 !important;
    background: #e5e5e5 !important;
}
.iconic-product-pill {
    border-radius: 2px !important;
    font-size: 0.85rem !important;
    letter-spacing: 0.05em !important;
}

/* ─── BRAND STATEMENT ─── */
.brand-statement {
    background: #111 !important;
    color: #fff !important;
    border-top: none !important;
}
.brand-statement__label { color: #f97316 !important; }
.brand-statement__title {
    font-size: clamp(2rem, 4vw, 3.5rem) !important;
    font-weight: 900 !important;
    letter-spacing: -0.02em !important;
    line-height: 1.05 !important;
    color: #fff !important;
}
.brand-statement__grid {
    border-top-color: rgba(255,255,255,0.1) !important;
}
.brand-statement__item {
    border-right-color: rgba(255,255,255,0.1) !important;
}
.brand-statement__item-title {
    color: rgba(255,255,255,0.9) !important;
    font-size: 0.85rem !important;
    letter-spacing: 0.12em !important;
}
.brand-statement__item-text {
    color: rgba(255,255,255,0.42) !important;
    max-width: none !important;
}

/* ─── BRANDS MARQUEE ─── */
.home-brands-marquee {
    background: #f97316;
    overflow: hidden;
    padding: 1rem 0;
    white-space: nowrap;
}
.home-brands-inner {
    display: inline-block;
    animation: ap-slide 18s linear infinite;
    font-size: 0.8rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: #fff;
}
@keyframes ap-slide {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}
.home-brands-sep { margin: 0 2rem; opacity: 0.5; }

/* ─── SCROLL REVEAL ─── */
.reveal { transition-duration: 1s !important; }

/* ─── RESPONSIVE ─── */
@media (max-width: 768px) {
    .hero-content { padding: 0 5% !important; padding-bottom: 14% !important; }
    .home-section { padding: 4rem 5% !important; }
    .home-stat { min-width: 120px; padding: 1.5rem 1rem; }
    .video-banner-content { left: 5% !important; }
    .brand-statement__grid { grid-template-columns: 1fr !important; }
    .brand-statement__item { border-right: none !important; border-bottom: 1px solid rgba(255,255,255,.1) !important; }
    .brand-statement__item:last-child { border-bottom: none !important; }
}
</style>

{{-- ═══════════════════════════════ HERO ═══════════════════════════════ --}}
<section class="hero-section">
    <div class="hero-media">
        @if($heroVideo)
            <video class="hero-bg hero-bg-video animate-zoom" autoplay muted loop playsinline poster="{{ $heroImage }}">
                <source src="{{ $heroVideo }}" type="video/mp4">
            </video>
        @else
            <img src="{{ $heroImage }}" alt="Stepora Sneakers" class="hero-bg animate-zoom">
        @endif
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <p class="hero-tagline animate-fade-in">{{ strtoupper($configs['hero_tagline'] ?? 'Nouvelle Collection') }}</p>
        <h1 class="hero-title animate-slide-up">{{ strtoupper($configs['hero_title'] ?? "L'élégance\nà chaque pas.") }}</h1>
        <p class="hero-desc animate-fade-in-delay">Découvrez notre sélection de sneakers premium. Authenticité garantie. Style inégalé.</p>
        <a href="{{ route('produits.index') }}" class="hero-btn animate-slide-up-delay">
            Découvrir la collection
        </a>
    </div>
    <div class="hero-scroll-hint">
        <div class="hero-scroll-line"></div>
        Défiler
    </div>
</section>

{{-- ═══════════════════════════════ COLLECTIONS ═══════════════════════════════ --}}
<section class="home-section reveal">
    <div class="section-header">
        <div>
            <span class="section-tag">Explorer</span>
            <h2 class="section-title">Collections</h2>
        </div>
        <div class="slider-controls">
            <button class="slider-btn prev" onclick="scrollSlider(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="slider-btn next" onclick="scrollSlider(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
    <div class="categories-slider-wrapper">
        <div class="categories-slider marquee-content" id="categoriesSlider">
            @foreach($categories as $category)
                <a href="{{ route('produits.index', ['categorie' => $category->slug]) }}" class="category-card">
                    @php
                        $catImg = $category->image
                            ? asset('storage/categories/' . $category->image)
                            : match ($category->slug) {
                                'hommes'      => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=800&auto=format&fit=crop',
                                'femmes'      => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=800&auto=format&fit=crop',
                                'accessoires' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
                                default       => 'https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=800&auto=format&fit=crop'
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
            @foreach($categories as $category)
                <a href="{{ route('produits.index', ['categorie' => $category->slug]) }}" class="category-card">
                    @php
                        $catImg = $category->image
                            ? asset('storage/categories/' . $category->image)
                            : match ($category->slug) {
                                'hommes'      => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=800&auto=format&fit=crop',
                                'femmes'      => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=800&auto=format&fit=crop',
                                'accessoires' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
                                default       => 'https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=800&auto=format&fit=crop'
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

{{-- ═══════════════════════════════ BRANDS MARQUEE ═══════════════════════════════ --}}
<div class="home-brands-marquee">
    <div class="home-brands-inner">
        Nike <span class="home-brands-sep">·</span> Adidas <span class="home-brands-sep">·</span> Jordan <span class="home-brands-sep">·</span> New Balance <span class="home-brands-sep">·</span> Puma <span class="home-brands-sep">·</span> Reebok <span class="home-brands-sep">·</span> Converse <span class="home-brands-sep">·</span> Vans <span class="home-brands-sep">·</span>
        Nike <span class="home-brands-sep">·</span> Adidas <span class="home-brands-sep">·</span> Jordan <span class="home-brands-sep">·</span> New Balance <span class="home-brands-sep">·</span> Puma <span class="home-brands-sep">·</span> Reebok <span class="home-brands-sep">·</span> Converse <span class="home-brands-sep">·</span> Vans <span class="home-brands-sep">·</span>
    </div>
</div>

{{-- ═══════════════════════════════ VIDEO BANNER ═══════════════════════════════ --}}
<section class="video-banner-section reveal">
    <div class="video-banner-media">
        @if($trendingVideo)
            <video src="{{ $trendingVideo }}" autoplay loop muted playsinline class="video-banner-video"></video>
        @else
            <img src="https://images.unsplash.com/photo-1556906781-9a412961a28c?q=80&w=1600&auto=format&fit=crop"
                 alt="Tendances Stepora" class="video-banner-video" style="object-fit:cover;">
        @endif
        <div class="video-banner-overlay"></div>
    </div>
    <div class="video-banner-content">
        <p class="video-banner-tagline">{{ $trendingTagline }}</p>
        <h2 class="video-banner-title">{!! nl2br(e($trendingTitle)) !!}</h2>
        <a href="{{ route('produits.index') }}" class="video-banner-btn">
            Voir la collection <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

{{-- ═══════════════════════════════ MODÈLES ICONIQUES ═══════════════════════════════ --}}
<section class="home-section iconic-section reveal bg-light">
    <div class="section-header">
        <div>
            <span class="section-tag">Catégories</span>
            <h2 class="section-title">Nos modèles iconiques</h2>
        </div>
        <div class="iconic-controls">
            <button class="slider-btn prev" id="iconicPrev"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="slider-btn next" id="iconicNext"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
    <div class="iconic-slider-wrapper">
        <div class="iconic-slider" id="iconicSlider">
            @php
                $allSubcategories = collect();
                foreach($categories as $cat) {
                    $children = collect(data_get($cat, 'children', []));
                    if ($children->isNotEmpty()) $allSubcategories = $allSubcategories->merge($children);
                }
            @endphp
            @forelse($allSubcategories as $subcat)
                <a href="{{ route('produits.index', ['categorie' => data_get($subcat, 'slug')]) }}" class="iconic-product-card">
                    @php
                        $subcatImg = data_get($subcat, 'image')
                            ? asset('storage/categories/' . data_get($subcat, 'image'))
                            : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500&auto=format&fit=crop';
                    @endphp
                    <div class="iconic-img-wrapper">
                        <img src="{{ $subcatImg }}" alt="{{ data_get($subcat, 'nom') }}" class="iconic-product-img">
                    </div>
                    <span class="iconic-product-pill">{{ data_get($subcat, 'nom') }}</span>
                </a>
            @empty
                @for($i=1; $i<=4; $i++)
                    <a href="{{ route('produits.index') }}" class="iconic-product-card">
                        <div class="iconic-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500&auto=format&fit=crop" alt="Sneaker" class="iconic-product-img">
                        </div>
                        <span class="iconic-product-pill">Stepora Air {{ $i }}</span>
                    </a>
                @endfor
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════ BRAND STATEMENT ═══════════════════════════════ --}}
<section class="brand-statement reveal">
    <div class="brand-statement__inner">
        <header class="brand-statement__header">
            <p class="brand-statement__label">Stepora</p>
            <h2 class="brand-statement__title">
                Conçu pour la rue.<br>
                Pensé pour le confort.
            </h2>
        </header>
        <div class="brand-statement__grid">
            <article class="brand-statement__item">
                <h3 class="brand-statement__item-title">Livraison express</h3>
                <p class="brand-statement__item-text">Expédition rapide partout en RDC avec suivi dès la prise en charge de votre commande.</p>
            </article>
            <article class="brand-statement__item">
                <h3 class="brand-statement__item-title">Retours sous 30 jours</h3>
                <p class="brand-statement__item-text">Échange ou retour simplifié si la taille ou le modèle ne convient pas.</p>
            </article>
            <article class="brand-statement__item">
                <h3 class="brand-statement__item-title">Authenticité garantie</h3>
                <p class="brand-statement__item-text">Chaque paire est contrôlée avant expédition pour garantir qualité et conformité.</p>
            </article>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════ STATS BAR ═══════════════════════════════ --}}
<div class="home-stats-bar">
    <div class="home-stat">
        <span class="home-stat-num">2021</span>
        <span class="home-stat-lbl">Fondation</span>
    </div>
    <div class="home-stat">
        <span class="home-stat-num">500<sup>+</sup></span>
        <span class="home-stat-lbl">Références</span>
    </div>
    <div class="home-stat">
        <span class="home-stat-num">12K<sup>+</sup></span>
        <span class="home-stat-lbl">Clients</span>
    </div>
    <div class="home-stat">
        <span class="home-stat-num">100%</span>
        <span class="home-stat-lbl">Authentique</span>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    const iconicSlider = document.getElementById('iconicSlider');
    const iconicPrev   = document.getElementById('iconicPrev');
    const iconicNext   = document.getElementById('iconicNext');
    if (iconicSlider && iconicPrev && iconicNext) {
        const getAmt = () => {
            const card = iconicSlider.querySelector('.iconic-product-card');
            const gap  = parseFloat(window.getComputedStyle(iconicSlider).gap) || 24;
            return (card ? card.offsetWidth : 340) + gap;
        };
        iconicNext.addEventListener('click', () => iconicSlider.scrollBy({ left:  getAmt(), behavior: 'smooth' }));
        iconicPrev.addEventListener('click', () => iconicSlider.scrollBy({ left: -getAmt(), behavior: 'smooth' }));
    }
});

function scrollSlider(direction) {
    const slider = document.getElementById('categoriesSlider');
    const card   = slider.querySelector('.category-card');
    slider.scrollBy({ left: direction * ((card ? card.offsetWidth : 400) + 24), behavior: 'smooth' });
}
</script>
