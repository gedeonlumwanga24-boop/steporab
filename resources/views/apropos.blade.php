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

@endphp

<style>
/* ==================================================
   A PROPOS - DESIGN PREMIUM
   ================================================== */
.ap-page {
    background: #000;
    color: #fff;
    font-family: 'Inter', -apple-system, sans-serif;
    overflow-x: hidden;
}

/* ─── HERO ─── */
.ap-hero {
    position: relative;
    height: 85vh;
    min-height: 600px;
    display: flex;
    align-items: flex-end;
    padding: 0 6% 8%;
    overflow: hidden;
}
.ap-hero-img {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    z-index: 0;
    transform: scale(1.05);
    animation: ap-hero-zoom 15s ease-out forwards;
}
@keyframes ap-hero-zoom { to { transform: scale(1); } }

.ap-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.4) 40%, rgba(0,0,0,0.1) 100%);
    z-index: 1;
}

.ap-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
}
.ap-hero-tag {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: #f97316;
    margin-bottom: 1.5rem;
    opacity: 0;
    animation: ap-fade-up 1s forwards 0.5s;
}
.ap-hero-title {
    font-size: clamp(3.5rem, 8vw, 7rem);
    font-weight: 900;
    line-height: 0.9;
    letter-spacing: -0.04em;
    margin-bottom: 2rem;
    opacity: 0;
    animation: ap-fade-up 1s forwards 0.7s;
}
.ap-hero-title em {
    font-style: normal;
    color: transparent;
    -webkit-text-stroke: 1px rgba(255,255,255,0.8);
}
.ap-hero-desc {
    font-size: 1.1rem;
    line-height: 1.6;
    color: rgba(255,255,255,0.7);
    max-width: 500px;
    opacity: 0;
    animation: ap-fade-up 1s forwards 0.9s;
}
@keyframes ap-fade-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ─── STATS ─── */
.ap-stats {
    display: flex;
    background: #111;
    border-top: 1px solid rgba(255,255,255,0.05);
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.ap-stat {
    flex: 1;
    padding: 3rem 2rem;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,0.05);
    opacity: 0; transform: translateY(20px); transition: 0.8s ease;
}
.ap-stat:last-child { border-right: none; }
.ap-stat.revealed { opacity: 1; transform: translateY(0); }

.ap-stat-num {
    display: block;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    margin-bottom: 0.5rem;
}
.ap-stat-num sup { color: #f97316; font-size: 0.5em; }
.ap-stat-lbl {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: rgba(255,255,255,0.5);
}

/* ─── STORY SECTION ─── */
.ap-story {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 80vh;
    background: #fafafa;
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
    filter: grayscale(20%);
    transition: 0.5s ease;
}
.ap-story-visual:hover img { filter: grayscale(0%); }

.ap-story-visual-badge {
    position: absolute;
    bottom: 2rem; right: 2rem;
    background: #111;
    color: #fff;
    padding: 1.5rem;
    text-align: center;
}
.ap-story-visual-badge strong { font-size: 1.75rem; font-weight: 900; color: #f97316; line-height: 1; display: block; }
.ap-story-visual-badge span { display: block; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.1em; opacity: 0.8; margin-top: 0.5rem; }

.ap-story-text {
    display: flex; flex-direction: column; justify-content: center; padding: 5rem 8%;
}
.ap-story-text [data-ap-reveal] {
    opacity: 0; transform: translateY(30px); transition: 0.8s ease;
}
.ap-story-text [data-ap-reveal].revealed { opacity: 1; transform: translateY(0); }

.ap-section-tag {
    font-size: 0.75rem; font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase; color: #f97316; margin-bottom: 1rem; display: block;
}
.ap-story-title {
    font-size: clamp(2.5rem, 4vw, 3.5rem); font-weight: 900; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 2rem; color: #111;
}
.ap-story-body p {
    font-size: 1.1rem; line-height: 1.7; color: #4b5563; margin-bottom: 1.5rem;
}
.ap-story-cta {
    display: inline-flex; align-items: center; gap: 0.75rem; margin-top: 1rem; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #111; text-decoration: none; border-bottom: 2px solid #111; padding-bottom: 0.25rem; width: fit-content; transition: color 0.2s, border-color 0.2s, gap 0.2s;
}
.ap-story-cta:hover { color: #f97316; border-color: #f97316; gap: 1.25rem; }


/* ─── VALUES SECTION ─── */
.ap-values {
    background: #0a0a0a;
    padding: 8rem 6%;
}
.ap-values-header {
    display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;
}
.ap-values-title {
    font-size: clamp(2.5rem, 4vw, 4rem); font-weight: 900; text-transform: uppercase; line-height: 1; letter-spacing: -0.02em; margin: 0;
}
.ap-values-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;
}
.ap-value-card {
    background: #111;
    padding: 3rem;
    border: 1px solid rgba(255,255,255,0.05);
    transition: 0.4s ease;
}
.ap-value-card:hover {
    background: #1a1a1a;
    transform: translateY(-10px);
    border-color: rgba(249,115,22,0.3);
}
.ap-value-num {
    font-size: 0.8rem; font-weight: 700; color: #f97316; margin-bottom: 2rem; display: block;
}
.ap-value-title {
    font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; letter-spacing: -0.01em;
}
.ap-value-text {
    font-size: 0.95rem; line-height: 1.6; color: rgba(255,255,255,0.5);
}

/* ─── MARQUEE ─── */
.ap-marquee-wrap {
    background: #f97316;
    padding: 1.5rem 0;
    overflow: hidden;
    white-space: nowrap;
}
.ap-marquee {
    display: inline-block;
    animation: ap-slide 20s linear infinite;
    font-size: 1.5rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; color: #000;
}
.ap-marquee-sep { opacity: 0.3; margin: 0 2rem; }
@keyframes ap-slide { from { transform: translateX(0); } to { transform: translateX(-50%); } }

/* ─── CTA SECTION ─── */
.ap-cta {
    padding: 10rem 6%; text-align: center; background: #fff;
}
.ap-cta-title {
    font-size: clamp(3rem, 6vw, 5.5rem); font-weight: 900; line-height: 1; letter-spacing: -0.03em; margin-bottom: 3rem; color: #111;
}
.ap-cta-title em { font-style: normal; color: #f97316; }
.ap-cta-actions {
    display: flex; justify-content: center; gap: 1.5rem;
}
.ap-btn-solid {
    background: #111; color: #fff; padding: 1.2rem 3rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.85rem; border-radius: 2px; transition: 0.3s;
}
.ap-btn-solid:hover { background: #f97316; color: #fff; transform: scale(1.02); }
.ap-btn-ghost {
    background: transparent; color: #111; border: 2px solid #111; padding: 1.2rem 3rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.85rem; border-radius: 2px; transition: 0.3s;
}
.ap-btn-ghost:hover { border-color: #111; background: #111; color: #fff; }

/* DELAYS FOR REVEAL */
[data-ap-reveal="delay1"] { transition-delay: 0.2s; }
[data-ap-reveal="delay2"] { transition-delay: 0.4s; }
[data-ap-reveal="delay3"] { transition-delay: 0.6s; }

/* RESPONSIVE */
@media(max-width: 900px) {
    .ap-story { grid-template-columns: 1fr; }
    .ap-story-visual { min-height: 60vw; max-height: 400px; }
    .ap-values-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    .ap-values-grid { grid-template-columns: 1fr; }
    .ap-stats { flex-wrap: wrap; }
    .ap-stat { flex: 50%; border-bottom: 1px solid rgba(255,255,255,0.1); border-right: none; }
    .ap-cta-actions { flex-direction: column; }
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
