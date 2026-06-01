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

<style>
/* ========== APROPOS PREMIUM ========== */
.ap-page {
    overflow-x: hidden;
    background: #fff;
    color: #111;
}

/* HERO */
.ap-hero {
    position: relative;
    width: 100%;
    height: 100vh;
    min-height: 600px;
    overflow: hidden;
}
.ap-hero img.ap-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}
.ap-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.75) 40%, rgba(0,0,0,0.1) 100%);
}
.ap-hero-content {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 5% 6% 8%;
    color: #fff;
}
.ap-hero-tag {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.5);
    margin-bottom: 1.5rem;
}
.ap-hero-title {
    font-size: clamp(3.5rem, 9vw, 8rem);
    font-weight: 900;
    line-height: 0.9;
    text-transform: uppercase;
    letter-spacing: -0.03em;
    margin: 0 0 2rem;
}
.ap-hero-title em {
    font-style: normal;
    color: transparent;
    -webkit-text-stroke: 1px rgba(255,255,255,0.5);
}
.ap-hero-desc {
    max-width: 480px;
    font-size: 1.1rem;
    line-height: 1.7;
    color: rgba(255,255,255,0.7);
}

/* STATS BAR */
.ap-stats {
    background: #111;
    color: #fff;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}
.ap-stat {
    flex: 1;
    min-width: 180px;
    padding: 2.5rem 2rem;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,0.08);
}
.ap-stat:last-child { border-right: none; }
.ap-stat-num {
    display: block;
    font-size: 2.5rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 0.5rem;
}
.ap-stat-num sup { font-size: 1rem; color: #f97316; }
.ap-stat-lbl {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: rgba(255,255,255,0.4);
}

/* SECTION STORY */
.ap-story {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 80vh;
}
.ap-story-visual {
    position: relative;
    overflow: hidden;
}
.ap-story-visual img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.ap-story-visual-badge {
    position: absolute;
    bottom: 2rem;
    right: 2rem;
    background: #111;
    color: #fff;
    padding: 1.25rem 1.5rem;
    border-radius: 8px;
}
.ap-story-visual-badge strong {
    display: block;
    font-size: 1.75rem;
    font-weight: 900;
    color: #f97316;
    line-height: 1;
}
.ap-story-visual-badge span {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: rgba(255,255,255,0.5);
}
.ap-story-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 5rem 6%;
    background: #fafafa;
}
.ap-section-tag {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #f97316;
    margin-bottom: 1.5rem;
}
.ap-story-title {
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 900;
    line-height: 1.05;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    margin-bottom: 2rem;
}
.ap-story-body p {
    font-size: 1.05rem;
    line-height: 1.75;
    color: #4b5563;
    margin-bottom: 1.25rem;
}
.ap-story-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1.5rem;
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #111;
    text-decoration: none;
    border-bottom: 2px solid #111;
    padding-bottom: 0.25rem;
    width: fit-content;
    transition: color 0.2s, border-color 0.2s, gap 0.2s;
}
.ap-story-cta:hover {
    color: #f97316;
    border-color: #f97316;
    gap: 1.25rem;
}

/* VALUES */
.ap-values {
    background: #111;
    color: #fff;
    padding: 7rem 6%;
}
.ap-values-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 4rem;
    flex-wrap: wrap;
    gap: 2rem;
}
.ap-values-title {
    font-size: clamp(2.5rem, 4vw, 4rem);
    font-weight: 900;
    text-transform: uppercase;
    line-height: 1;
    letter-spacing: -0.02em;
    margin: 0;
}
.ap-values-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    border: 1px solid rgba(255,255,255,0.08);
}
.ap-value-card {
    padding: 3rem 2.5rem;
    border-right: 1px solid rgba(255,255,255,0.08);
    transition: background 0.3s;
}
.ap-value-card:last-child { border-right: none; }
.ap-value-card:hover { background: rgba(255,255,255,0.03); }
.ap-value-num {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    color: #f97316;
    display: block;
    margin-bottom: 1.5rem;
}
.ap-value-title {
    font-size: 1.25rem;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
}
.ap-value-text {
    font-size: 0.95rem;
    line-height: 1.7;
    color: rgba(255,255,255,0.45);
}

/* MARQUEE */
.ap-marquee-wrap {
    background: #f97316;
    overflow: hidden;
    padding: 1.25rem 0;
    white-space: nowrap;
}
.ap-marquee {
    display: inline-block;
    animation: ap-slide 18s linear infinite;
    font-size: 0.85rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: #fff;
}
@keyframes ap-slide {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}
.ap-marquee-sep { margin: 0 2rem; opacity: 0.5; }

/* FINAL CTA */
.ap-cta {
    background: #fff;
    padding: 8rem 6%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.ap-cta-title {
    font-size: clamp(2.5rem, 5vw, 5rem);
    font-weight: 900;
    text-transform: uppercase;
    line-height: 1;
    letter-spacing: -0.03em;
    margin-bottom: 3rem;
}
.ap-cta-title em {
    font-style: normal;
    color: #f97316;
}
.ap-cta-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}
.ap-btn-solid {
    padding: 1rem 2.5rem;
    background: #111;
    color: #fff;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.9rem;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s;
}
.ap-btn-solid:hover { background: #f97316; transform: scale(1.02); color:#fff; }
.ap-btn-ghost {
    padding: 1rem 2.5rem;
    background: transparent;
    color: #111;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.9rem;
    border: 2px solid #111;
    border-radius: 3px;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.ap-btn-ghost:hover { background: #111; color: #fff; }

/* SCROLL REVEAL */
[data-ap-reveal] {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.9s cubic-bezier(.2,.8,.2,1), transform 0.9s cubic-bezier(.2,.8,.2,1);
}
[data-ap-reveal].revealed {
    opacity: 1;
    transform: translateY(0);
}
[data-ap-reveal="delay1"] { transition-delay: 0.1s; }
[data-ap-reveal="delay2"] { transition-delay: 0.2s; }
[data-ap-reveal="delay3"] { transition-delay: 0.3s; }

/* RESPONSIVE */
@media (max-width: 900px) {
    .ap-story { grid-template-columns: 1fr; }
    .ap-story-visual { min-height: 60vw; max-height: 400px; }
    .ap-values-grid { grid-template-columns: 1fr; }
    .ap-value-card { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .ap-value-card:last-child { border-bottom: none; }
    .ap-values-header { flex-direction: column; align-items: flex-start; }
    .ap-stat { min-width: 140px; padding: 2rem 1rem; }
}
@media (max-width: 600px) {
    .ap-hero-title { font-size: clamp(2.5rem, 15vw, 5rem); }
    .ap-cta-title { font-size: clamp(2rem, 12vw, 4rem); }
    .ap-cta-actions { flex-direction: column; width: 100%; }
    .ap-btn-solid, .ap-btn-ghost { width: 100%; text-align: center; }
    .ap-hero-content { padding: 5% 6% 14%; }
}
</style>

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
