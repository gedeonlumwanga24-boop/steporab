@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════════
     HERO — Manifeste éditorial
═══════════════════════════════════════════════ --}}
<section class="apropos-hero">
    <div class="apropos-hero-bg" aria-hidden="true">
        <div class="apropos-hero-noise"></div>
    </div>
    <div class="apropos-hero-inner">
        <p class="apropos-eyebrow">Notre histoire</p>
        <h1 class="apropos-hero-title">
            <span class="apropos-line apropos-line--solid">Nous ne</span>
            <span class="apropos-line apropos-line--outline">vendons</span>
            <span class="apropos-line apropos-line--solid">pas des</span>
            <span class="apropos-line apropos-line--outline">chaussures.</span>
        </h1>
        <p class="apropos-hero-sub">
            Nous construisons une culture. Chaque paire que nous proposons est le reflet d'une identité,
            d'une rue, d'un mouvement. Bienvenue chez Stepora.
        </p>
    </div>
    <div class="apropos-hero-scroll" aria-hidden="true">
        <span></span>
    </div>
</section>


{{-- ═══════════════════════════════════════════════
     CHIFFRES CLÉS
═══════════════════════════════════════════════ --}}
<section class="apropos-stats" aria-label="Chiffres clés">
    <div class="apropos-stats-inner">
        <div class="apropos-stat">
            <span class="stat-number">2021</span>
            <span class="stat-label">Année de fondation</span>
        </div>
        <div class="apropos-stat-sep" aria-hidden="true"></div>
        <div class="apropos-stat">
            <span class="stat-number">500<sup>+</sup></span>
            <span class="stat-label">Références en stock</span>
        </div>
        <div class="apropos-stat-sep" aria-hidden="true"></div>
        <div class="apropos-stat">
            <span class="stat-number">12K<sup>+</sup></span>
            <span class="stat-label">Clients satisfaits</span>
        </div>
        <div class="apropos-stat-sep" aria-hidden="true"></div>
        <div class="apropos-stat">
            <span class="stat-number">100%</span>
            <span class="stat-label">Produits authentiques</span>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════
     HISTOIRE — Layout éditorial deux colonnes
═══════════════════════════════════════════════ --}}
<section class="apropos-story" aria-label="Notre histoire">
    <div class="story-col story-col--text">
        <p class="apropos-eyebrow">Depuis Lubumbashi</p>
        <h2 class="story-title">Né de la rue,<br>fait pour durer.</h2>
        <div class="story-body">
            <p>
                Stepora est né d'une obsession simple : rendre accessibles les meilleures paires de sneakers
                à ceux qui les portent au quotidien — pas seulement pour le style, mais pour ce qu'elles
                représentent.
            </p>
            <p>
                Fondé à Lubumbashi en 2021, nous avons commencé avec une idée et quelques cartons.
                Aujourd'hui, nous sommes le point de référence pour tous ceux qui cherchent une paire
                authentique, livrée rapidement, sans compromis sur la qualité.
            </p>
            <p>
                Notre sélection est pensée avec soin : Nike, Adidas, Jordan, New Balance, Puma.
                Chaque modèle est inspecté, vérifié, certifié avant de partir chez vous.
            </p>
        </div>
        <a href="{{ route('produits.index') }}" class="apropos-cta-link">
            Voir notre sélection <span aria-hidden="true">→</span>
        </a>
    </div>

    <div class="story-col story-col--visual" aria-hidden="true">
        <div class="story-visual-card story-visual-card--top">
            <img
                src="https://images.unsplash.com/photo-1552346154-21d32810baa3?q=80&w=900&auto=format&fit=crop"
                alt="Sneakers Stepora"
                loading="lazy"
                decoding="async"
            >
        </div>
        <div class="story-visual-card story-visual-card--bottom">
            <img
                src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=700&auto=format&fit=crop"
                alt="Détail sneaker"
                loading="lazy"
                decoding="async"
            >
            <div class="story-badge">
                <span class="story-badge-num">01</span>
                <span class="story-badge-text">Authenticité<br>certifiée</span>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════
     VALEURS — 3 colonnes typographiques
═══════════════════════════════════════════════ --}}
<section class="apropos-values" id="apropos-values" aria-label="Nos valeurs">
    <div class="apropos-values-header">
        <p class="apropos-eyebrow">Ce qui nous guide</p>
        <h2 class="apropos-values-title">Nos engagements,<br>sans exception.</h2>
    </div>

    <div class="apropos-values-grid">

        <article class="value-card">
            <div class="value-card-index" aria-hidden="true">01</div>
            <h3 class="value-card-title">Authenticité</h3>
            <p class="value-card-text">
                Chaque paire que nous vendons est 100 % originale. Nos experts contrôlent
                chaque article avant expédition. Zéro tolérance pour le faux.
            </p>
        </article>

        <article class="value-card">
            <div class="value-card-index" aria-hidden="true">02</div>
            <h3 class="value-card-title">Accessibilité</h3>
            <p class="value-card-text">
                Le style de la rue ne devrait pas être réservé à quelques-uns.
                Nous sélectionnons des prix justes et livrons partout en RDC.
            </p>
        </article>

        <article class="value-card">
            <div class="value-card-index" aria-hidden="true">03</div>
            <h3 class="value-card-title">Communauté</h3>
            <p class="value-card-text">
                Stepora, c'est aussi une famille de passionnés. Des sneakerheads,
                des étudiants, des athlètes — unis par l'amour du mouvement.
            </p>
        </article>

    </div>
</section>


{{-- ═══════════════════════════════════════════════
     TIMELINE — Jalons de l'histoire
═══════════════════════════════════════════════ --}}
<section class="apropos-timeline" aria-label="Notre parcours">
    <div class="timeline-header">
        <p class="apropos-eyebrow">Le chemin parcouru</p>
        <h2 class="timeline-title">Chaque étape<br>compte.</h2>
    </div>

    <div class="timeline-track">

        <div class="timeline-item">
            <div class="timeline-dot" aria-hidden="true"></div>
            <div class="timeline-content">
                <span class="timeline-year">2021</span>
                <h3 class="timeline-event">Fondation</h3>
                <p>Stepora ouvre ses portes à Lubumbashi avec une vingtaine de références et une ambition : changer la façon dont les Congolais achètent leurs sneakers.</p>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-dot" aria-hidden="true"></div>
            <div class="timeline-content">
                <span class="timeline-year">2022</span>
                <h3 class="timeline-event">100 commandes livrées</h3>
                <p>Le bouche-à-oreille fait son travail. Cent clients satisfaits, un catalogue qui grandit, et la conviction que nous sommes sur la bonne voie.</p>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-dot" aria-hidden="true"></div>
            <div class="timeline-content">
                <span class="timeline-year">2023</span>
                <h3 class="timeline-event">Lancement de la boutique en ligne</h3>
                <p>stepora.cd ouvre officiellement. Des milliers de visiteurs les premiers mois. La rue est désormais connectée.</p>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-dot" aria-hidden="true"></div>
            <div class="timeline-content">
                <span class="timeline-year">2024</span>
                <h3 class="timeline-event">500+ références & 12 000 clients</h3>
                <p>Le catalogue s'élargit. Jordan, New Balance, Puma rejoignent la sélection. La communauté Stepora grandit chaque jour.</p>
            </div>
        </div>

        <div class="timeline-item timeline-item--active">
            <div class="timeline-dot" aria-hidden="true"></div>
            <div class="timeline-content">
                <span class="timeline-year">Aujourd'hui</span>
                <h3 class="timeline-event">En route pour la suite</h3>
                <p>Livraison express, retours sans friction, nouvelles collaborations. Nous construisons la meilleure expérience sneaker d'Afrique centrale.</p>
            </div>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════════
     MARQUES PARTENAIRES
═══════════════════════════════════════════════ --}}
<section class="apropos-brands" id="apropos-brands" aria-label="Marques partenaires">
    <p class="apropos-eyebrow" style="text-align:center;">Nos marques</p>
    <div class="brands-marquee-wrap">
        <div class="brands-marquee" aria-hidden="true">
            <span>Nike</span>
            <span class="brands-sep">·</span>
            <span>Adidas</span>
            <span class="brands-sep">·</span>
            <span>Jordan</span>
            <span class="brands-sep">·</span>
            <span>New Balance</span>
            <span class="brands-sep">·</span>
            <span>Puma</span>
            <span class="brands-sep">·</span>
            <span>Reebok</span>
            <span class="brands-sep">·</span>
            <span>Converse</span>
            <span class="brands-sep">·</span>
            <span>Vans</span>
            <span class="brands-sep">·</span>
            {{-- Duplicate for seamless loop --}}
            <span>Nike</span>
            <span class="brands-sep">·</span>
            <span>Adidas</span>
            <span class="brands-sep">·</span>
            <span>Jordan</span>
            <span class="brands-sep">·</span>
            <span>New Balance</span>
            <span class="brands-sep">·</span>
            <span>Puma</span>
            <span class="brands-sep">·</span>
            <span>Reebok</span>
            <span class="brands-sep">·</span>
            <span>Converse</span>
            <span class="brands-sep">·</span>
            <span>Vans</span>
            <span class="brands-sep">·</span>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════
     CTA FINAL
═══════════════════════════════════════════════ --}}
<section class="apropos-final-cta" aria-label="Rejoindre Stepora">
    <div class="final-cta-inner">
        <p class="apropos-eyebrow" style="color:rgba(255,255,255,.4);">Rejoins-nous</p>
        <h2 class="final-cta-title">Prêt à trouver<br>ta prochaine paire&nbsp;?</h2>
        <div class="final-cta-actions">
            <a href="{{ route('produits.index') }}" class="final-cta-btn final-cta-btn--primary">
                Explorer la boutique
            </a>
            <a href="{{ route('contact.index') }}" class="final-cta-btn final-cta-btn--ghost">
                Nous contacter
            </a>
        </div>
    </div>
</section>

@endsection
