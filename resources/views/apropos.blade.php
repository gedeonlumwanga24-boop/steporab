@extends('layouts.app')

@section('content')

@php
    $heroImg = isset($configs['about_hero_image'])
        ? (str_starts_with($configs['about_hero_image'], 'http') ? $configs['about_hero_image'] : asset('storage/' . $configs['about_hero_image']))
        : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1920&auto=format&fit=crop';

    $img1 = isset($configs['about_image_1'])
        ? (str_starts_with($configs['about_image_1'], 'http') ? $configs['about_image_1'] : asset('storage/' . $configs['about_image_1']))
        : 'https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=900&auto=format&fit=crop';

    $img2 = isset($configs['about_image_2'])
        ? (str_starts_with($configs['about_image_2'], 'http') ? $configs['about_image_2'] : asset('storage/' . $configs['about_image_2']))
        : 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=700&auto=format&fit=crop';
@endphp



<div class="ap-page">

    {{-- HERO --}}
    <section class="ap-hero">
        <img class="ap-hero-img" src="{{ $heroImg }}" alt="Stepora – L'esprit de la rue">
        <div class="ap-hero-overlay"></div>
        <div class="ap-hero-content">
            <p class="ap-hero-tag">Depuis 2021 · Lubumbashi, RDC</p>
            <h1 class="ap-hero-title">
                La rue<br>nous<br><em>définit.</em>
            </h1>
            <p class="ap-hero-desc">Plus qu'une boutique, un mouvement. Chaque paire que nous proposons raconte une histoire d'authenticité, de style et de culture urbaine.</p>
        </div>
    </section>

    {{-- STATS BAR --}}
    <div class="ap-stats">
        <div class="ap-stat" data-ap-reveal>
            <span class="ap-stat-num">2021</span>
            <span class="ap-stat-lbl">Année de fondation</span>
        </div>
        <div class="ap-stat" data-ap-reveal="delay1">
            <span class="ap-stat-num">500<sup>+</sup></span>
            <span class="ap-stat-lbl">Références en stock</span>
        </div>
        <div class="ap-stat" data-ap-reveal="delay2">
            <span class="ap-stat-num">12K<sup>+</sup></span>
            <span class="ap-stat-lbl">Clients satisfaits</span>
        </div>
        <div class="ap-stat" data-ap-reveal="delay3">
            <span class="ap-stat-num">100%</span>
            <span class="ap-stat-lbl">Produits authentiques</span>
        </div>
    </div>

    {{-- STORY SECTION --}}
    <section class="ap-story">
        <div class="ap-story-visual">
            <img src="{{ $img1 }}" alt="Notre histoire Stepora">
            <div class="ap-story-visual-badge">
                <strong>01</strong>
                <span>Authenticité<br>certifiée</span>
            </div>
        </div>
        <div class="ap-story-text">
            <p class="ap-section-tag" data-ap-reveal>Nos Racines</p>
            <h2 class="ap-story-title" data-ap-reveal="delay1">Né de la rue,<br>fait pour durer.</h2>
            <div class="ap-story-body" data-ap-reveal="delay2">
                <p>Stepora est né d'une obsession simple : rendre accessibles les meilleures paires de sneakers à ceux qui les portent au quotidien — pas seulement pour le style, mais pour ce qu'elles représentent.</p>
                <p>Fondé à Lubumbashi en 2021, nous avons commencé avec une idée et quelques cartons. Aujourd'hui, nous sommes le point de référence pour tous ceux qui cherchent une paire authentique, livrée rapidement, sans compromis sur la qualité.</p>
            </div>
            <a href="{{ route('produits.index') }}" class="ap-story-cta" data-ap-reveal="delay3">
                Explorer la boutique <span>→</span>
            </a>
        </div>
    </section>

    {{-- SECOND VISUAL --}}
    <section class="ap-story">
        <div class="ap-story-text" style="background: #fff;">
            <p class="ap-section-tag" data-ap-reveal>Notre Sélection</p>
            <h2 class="ap-story-title" data-ap-reveal="delay1">Une curation<br>sans compromis.</h2>
            <div class="ap-story-body" data-ap-reveal="delay2">
                <p>Notre sélection est pensée avec soin : Nike, Adidas, Jordan, New Balance, Puma et bien d'autres. Chaque modèle est inspecté, vérifié, certifié avant de partir chez vous.</p>
                <p>Nous travaillons uniquement avec des fournisseurs officiels pour garantir 100% d'authenticité sur chaque commande.</p>
            </div>
            <a href="{{ route('contact.index') }}" class="ap-story-cta" data-ap-reveal="delay3">
                Nous contacter <span>→</span>
            </a>
        </div>
        <div class="ap-story-visual">
            <img src="{{ $img2 }}" alt="Sélection Stepora">
        </div>
    </section>

    {{-- VALUES --}}
    <section class="ap-values">
        <div class="ap-values-header">
            <h2 class="ap-values-title" data-ap-reveal>Nos<br>Engagements</h2>
            <p style="max-width:320px; color: rgba(255,255,255,.45); font-size:1rem; line-height:1.6;" data-ap-reveal="delay1">Ces principes guident chacune de nos décisions, chaque jour, sans exception.</p>
        </div>
        <div class="ap-values-grid">
            <div class="ap-value-card" data-ap-reveal>
                <span class="ap-value-num">01</span>
                <h3 class="ap-value-title">Authenticité</h3>
                <p class="ap-value-text">Chaque paire que nous vendons est 100% originale. Nos experts contrôlent chaque article avant expédition. Zéro tolérance pour le faux.</p>
            </div>
            <div class="ap-value-card" data-ap-reveal="delay1">
                <span class="ap-value-num">02</span>
                <h3 class="ap-value-title">Accessibilité</h3>
                <p class="ap-value-text">Le style de la rue ne devrait pas être réservé à quelques-uns. Nous sélectionnons des prix justes et livrons partout en RDC.</p>
            </div>
            <div class="ap-value-card" data-ap-reveal="delay2">
                <span class="ap-value-num">03</span>
                <h3 class="ap-value-title">Communauté</h3>
                <p class="ap-value-text">Stepora, c'est aussi une famille de passionnés. Des sneakerheads, des étudiants, des athlètes — unis par l'amour du mouvement.</p>
            </div>
        </div>
    </section>

    {{-- MARQUEE --}}
    <div class="ap-marquee-wrap">
        <div class="ap-marquee">
            Nike <span class="ap-marquee-sep">·</span> Adidas <span class="ap-marquee-sep">·</span> Jordan <span class="ap-marquee-sep">·</span> New Balance <span class="ap-marquee-sep">·</span> Puma <span class="ap-marquee-sep">·</span> Reebok <span class="ap-marquee-sep">·</span> Converse <span class="ap-marquee-sep">·</span> Vans <span class="ap-marquee-sep">·</span>
            Nike <span class="ap-marquee-sep">·</span> Adidas <span class="ap-marquee-sep">·</span> Jordan <span class="ap-marquee-sep">·</span> New Balance <span class="ap-marquee-sep">·</span> Puma <span class="ap-marquee-sep">·</span> Reebok <span class="ap-marquee-sep">·</span> Converse <span class="ap-marquee-sep">·</span> Vans <span class="ap-marquee-sep">·</span>
        </div>
    </div>

    {{-- FINAL CTA --}}
    <section class="ap-cta">
        <h2 class="ap-cta-title" data-ap-reveal>Prêt à trouver<br>ta <em>prochaine paire</em>&nbsp;?</h2>
        <div class="ap-cta-actions" data-ap-reveal="delay1">
            <a href="{{ route('produits.index') }}" class="ap-btn-solid">Explorer la boutique</a>
            <a href="{{ route('contact.index') }}" class="ap-btn-ghost">Nous contacter</a>
        </div>
    </section>

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });

    document.querySelectorAll('[data-ap-reveal]').forEach(el => observer.observe(el));
});
</script>

@endsection
