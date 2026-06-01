@extends('layouts.app')

@section('content')
<div class="product-show-container">
    @if(session('success'))
        <div style="background: #10b981; color: white; padding: 1rem; text-align: center; font-weight: bold; margin-bottom: 1rem; border-radius: 8px; animation: slideDown 0.3s ease-out;">
            <i class="fa-solid fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
            <a href="{{ route('panier.index') }}" style="text-decoration: underline; margin-left: 1rem;">Voir le panier</a>
        </div>
    @endif

    <div class="product-show-layout">
        <!-- LEFT: Image Gallery -->
        <div class="product-gallery">
            @php
                $productImageUrl = $produit->image_url;
            @endphp
            <!-- Thumbnails on the left -->
            <div class="product-thumbnails-container" id="thumbnailsContainer">
                <div class="product-thumbnail active" onclick="changeImage(this, 0)">
                    <img src="{{ $productImageUrl }}" alt="{{ $produit->nom }}">
                </div>
                @if($produit->galerie && is_array($produit->galerie))
                    @foreach($produit->galerie as $index => $miniature)
                        <div class="product-thumbnail" onclick="changeImage(this, {{ $index + 1 }})">
                            <img src="{{ asset('storage/produits/'.$miniature) }}" alt="{{ $produit->nom }}">
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Main Image Wrapper -->
            <div class="product-main-image-wrapper">
                <div class="product-main-image">
                    @if($produit->stock <= 0)
                        <span class="product-badge product-badge--outofstock">Rupture de stock</span>
                    @elseif($produit->prix > 150000)
                        <span class="product-badge">Premium</span>
                    @else
                        <span class="product-badge">En stock</span>
                    @endif

                    <button class="gallery-nav-btn prev" onclick="navigateGallery(-1)" aria-label="Image précédente">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    
                    <div class="product-image-slider" id="imageSlider">
                        <img src="{{ $productImageUrl }}" alt="{{ $produit->nom }}" class="product-image-slide">
                        @if($produit->galerie && is_array($produit->galerie))
                            @foreach($produit->galerie as $miniature)
                                <img src="{{ asset('storage/produits/'.$miniature) }}" alt="{{ $produit->nom }}" class="product-image-slide">
                            @endforeach
                        @endif
                    </div>
                    
                    <button class="gallery-nav-btn next" onclick="navigateGallery(1)" aria-label="Image suivante">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
                
                <!-- Pagination Dots for Mobile -->
                <div class="product-gallery-dots">
                    <span class="gallery-dot active" onclick="goToSlide(0)"></span>
                    @if($produit->galerie && is_array($produit->galerie))
                        @foreach($produit->galerie as $index => $miniature)
                            <span class="gallery-dot" onclick="goToSlide({{ $index + 1 }})"></span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT: Product Info -->
        <div class="product-info-panel">
            <!-- Category & Badge -->
            <span class="product-category-tag">{{ $produit->category->nom ?? 'Chaussure' }}</span>
            
            <!-- Title -->
            <h1 class="product-show-title">{{ $produit->nom }}</h1>
            
            <!-- Tagline -->
            <p class="product-tagline">Chaussure pour homme</p>

            <!-- Price -->
            <div class="product-price-section">
                <span class="product-price">{{ number_format($produit->prix, 0, ' ', ' ') }} CDF</span>
            </div>

            <!-- Size Selection -->
            <div class="product-size-section">
                <div class="size-header-wrapper" style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.75rem;">
                    <label class="size-label" style="font-weight: 700; margin-bottom: 0;">Sélectionner la taille</label>
                    <a href="#" class="size-guide" style="font-size: 0.85rem; font-weight: 700; color: #111; text-decoration: none; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="fa-solid fa-ruler-horizontal"></i> Guide des tailles
                    </a>
                </div>
                <div class="size-grid">
                    @php 
                        $sizes = [
                            'EU 38.5' => false, 'EU 39' => false, 'EU 40' => true, 
                            'EU 40.5' => true, 'EU 41' => true, 'EU 42' => true, 
                            'EU 42.5' => true, 'EU 43' => true, 'EU 44' => true, 
                            'EU 44.5' => false, 'EU 45' => false, 'EU 45.5' => true, 
                            'EU 46' => false, 'EU 47' => true, 'EU 47.5' => false, 
                            'EU 48.5' => false, 'EU 49.5' => false
                        ]; 
                    @endphp
                    @foreach($sizes as $size => $available)
                        <button type="button" class="size-btn {{ $available ? '' : 'unavailable' }}" {{ $available ? 'onclick=selectSize(this)' : 'disabled' }}>{{ $size }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Color Selection -->
            <div class="product-color-section">
                <label class="color-label">Couleur</label>
                <div class="color-options">
                    <button class="color-btn active" style="background-color: #333;" title="Noir"></button>
                    <button class="color-btn" style="background-color: #4682B4;" title="Bleu"></button>
                    <button class="color-btn" style="background-color: #DC143C;" title="Rouge"></button>
                    <button class="color-btn" style="background-color: #FFD700;" title="Or"></button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="product-action-buttons">
                <form id="addToCartForm" action="{{ route('panier.ajouter', $produit->id) }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="taille" id="selectedSizeInput" value="">
                    <input type="hidden" name="couleur" id="selectedColorInput" value="">
                    <button type="submit" class="btn-add-to-cart pill-btn" id="addToCartBtn">
                        Ajouter au panier
                    </button>
                </form>
                <button type="button" class="btn-wishlist pill-btn">
                    Ajouter aux favoris <i class="fa-regular fa-heart" style="margin-left: 0.5rem; font-size: 1.1rem;"></i>
                </button>
            </div>

            <!-- Free Delivery Info -->
            <div class="product-info-box">
                <p><strong>Livraison gratuite</strong><br>Traceur en magasin</p>
            </div>

            <!-- Return Info -->
            <div class="product-info-box">
                <p><strong>Retour gratuit</strong><br>Traceur en magasin</p>
            </div>

            <!-- Description -->
            <div class="product-description-section">
                <p>{{ $produit->description }}</p>
            </div>

            <!-- Product Details -->
            <details class="product-details-accordion">
                <summary class="details-summary">Afficher les détails du produit</summary>
                <div class="details-content">
                    <ul>
                        <li><strong>Catégorie :</strong> {{ $produit->category->nom ?? 'N/A' }}</li>
                        <li><strong>Code produit :</strong> #{{ $produit->id }}</li>
                        <li><strong>Stock disponible :</strong> {{ $produit->stock }} unités</li>
                    </ul>
                </div>
            </details>
        </div>
    </div>

    <!-- Additional Content Below -->
    <div class="product-additional-section">
        <h2>À propos de ce produit</h2>
        <p>Découvrez une sélection premium de baskets et chaussures pour tous les styles. Confortable, durable et à la mode.</p>
    </div>
</div>

<!-- CART SUCCESS DRAWER (Nike Style) -->
<div class="cart-success-overlay" id="cartSuccessOverlay"></div>
<div class="cart-success-drawer" id="cartSuccessDrawer">
    <div class="drawer-header">
        <div class="drawer-title">
            <svg viewBox="0 0 24 24" fill="#10b981" width="20" height="20"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            Ajouté au panier
        </div>
        <button type="button" class="drawer-close" onclick="closeCartDrawer()">&times;</button>
    </div>

    <div class="drawer-product">
        <img src="" alt="Product" id="drawerProductImage">
        <div class="drawer-product-info">
            <h4 id="drawerProductCategory">Sneakers</h4>
            <h3 id="drawerProductName">Produit</h3>
            <p id="drawerProductVariant" style="color:#666;font-size:0.9rem;margin-top:0.2rem;"></p>
            <p id="drawerProductPrice" style="font-weight:800;margin-top:0.4rem;color:#111;"></p>
        </div>
    </div>

    <div class="drawer-actions">
        <a href="{{ route('panier.index') }}" class="drawer-btn drawer-btn-outline">
            Voir le panier (<span id="drawerCartCount">1</span>)
        </a>
        <a href="{{ route('panier.index') }}" class="drawer-btn drawer-btn-solid">Paiement</a>
    </div>
</div>

<style>
/* ── CART DRAWER ─────────────────────────────────────────── */
.cart-success-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 10000;
    opacity: 0; visibility: hidden;
    transition: all 0.3s ease;
}
.cart-success-overlay.active { opacity: 1; visibility: visible; }

.cart-success-drawer {
    position: fixed;
    top: 0; right: 0; bottom: 0;
    width: min(420px, 100vw);
    background: #fff;
    z-index: 10001;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex; flex-direction: column;
    padding: 1.5rem;
    gap: 1.25rem;
    box-shadow: -8px 0 40px rgba(0,0,0,0.18);
}
.cart-success-drawer.active { transform: translateX(0); }

.drawer-header {
    display: flex; align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 1rem;
}
.drawer-title {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 1rem; font-weight: 700; color: #111;
}
.drawer-close {
    background: none; border: none;
    font-size: 1.6rem; cursor: pointer;
    color: #6b7280; line-height: 1;
    width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    transition: background 150ms;
}
.drawer-close:hover { background: #f3f4f6; }

.drawer-product {
    display: flex; gap: 1rem; align-items: center;
}
.drawer-product img {
    width: 90px; height: 90px;
    object-fit: cover; border-radius: 8px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    flex-shrink: 0;
}
.drawer-product-info h4 {
    font-size: 0.72rem; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: #6b7280; margin: 0 0 0.2rem;
}
.drawer-product-info h3 {
    font-size: 1rem; font-weight: 800;
    color: #111; margin: 0;
    line-height: 1.3;
}

.drawer-actions {
    display: flex; flex-direction: column; gap: 0.75rem;
    margin-top: auto;
}
.drawer-btn {
    display: flex; align-items: center; justify-content: center;
    width: 100%; padding: 0.9rem 1rem;
    font-size: 0.9rem; font-weight: 800;
    letter-spacing: 0.03em; text-transform: uppercase;
    text-decoration: none; border-radius: 4px;
    cursor: pointer; transition: all 0.2s ease;
    border: 2px solid #111;
}
.drawer-btn-outline {
    background: #fff; color: #111;
}
.drawer-btn-outline:hover { background: #f3f4f6; }
.drawer-btn-solid {
    background: #111; color: #fff;
}
.drawer-btn-solid:hover { background: #000; }
</style>

<script>
const thumbnails = document.querySelectorAll('.product-thumbnail');
const slider = document.getElementById('imageSlider');
const dots = document.querySelectorAll('.gallery-dot');
let totalSlides = dots.length;

function goToSlide(index) {
    if(!slider) return;
    const slideWidth = slider.clientWidth;
    slider.scrollTo({ left: slideWidth * index, behavior: 'smooth' });
    updateActiveState(index);
}

function navigateGallery(direction) {
    if(!slider) return;
    const slideWidth = slider.clientWidth;
    let currentIndex = Math.round(slider.scrollLeft / slideWidth);
    let nextIndex = currentIndex + direction;
    if (nextIndex < 0) nextIndex = totalSlides - 1;
    if (nextIndex >= totalSlides) nextIndex = 0;
    goToSlide(nextIndex);
}

function updateActiveState(index) {
    dots.forEach((dot, i) => {
        if (i === index) dot.classList.add('active');
        else dot.classList.remove('active');
    });
    thumbnails.forEach((t, i) => {
        if (i === index) {
            t.classList.add('active');
            if (t.scrollIntoView) t.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
        } else {
            t.classList.remove('active');
        }
    });
}

if(slider) {
    slider.addEventListener('scroll', () => {
        const slideWidth = slider.clientWidth;
        const index = Math.round(slider.scrollLeft / slideWidth);
        updateActiveState(index);
    }, {passive: true});
}

function changeImage(element, index) {
    goToSlide(index);
}

document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft') navigateGallery(-1);
    else if (e.key === 'ArrowRight') navigateGallery(1);
});

function selectSize(btn) {
    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const sizeInput = document.getElementById('selectedSizeInput');
    if (sizeInput) sizeInput.value = btn.innerText;
}

// Cart drawer functions
function openCartDrawer() {
    document.getElementById('cartSuccessOverlay').classList.add('active');
    document.getElementById('cartSuccessDrawer').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCartDrawer() {
    document.getElementById('cartSuccessOverlay').classList.remove('active');
    document.getElementById('cartSuccessDrawer').classList.remove('active');
    document.body.style.overflow = '';
}

document.getElementById('cartSuccessOverlay').addEventListener('click', closeCartDrawer);

// AJAX form submit
const addToCartForm = document.getElementById('addToCartForm');
if (addToCartForm) {
    addToCartForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('addToCartBtn');
        btn.disabled = true;
        btn.textContent = 'Ajout en cours…';

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Fill drawer
                    document.getElementById('drawerProductName').textContent     = data.product.nom;
                    document.getElementById('drawerProductCategory').textContent = data.product.category;
                    document.getElementById('drawerProductPrice').textContent    = data.product.prix;
                    document.getElementById('drawerProductImage').src            = data.product.image;
                    document.getElementById('drawerCartCount').textContent       = data.cart_count;

                    const variantEl = document.getElementById('drawerProductVariant');
                    if (data.product.variant) {
                        variantEl.textContent = data.product.variant;
                        variantEl.style.display = 'block';
                    } else {
                        variantEl.style.display = 'none';
                    }

                    // Update navbar badge
                    const badge = document.getElementById('navCartBadge');
                    if (badge) {
                        badge.textContent = data.cart_count;
                        badge.classList.remove('cart-badge--hidden');
                    }

                    openCartDrawer();
                }
            } else {
                addToCartForm.submit();
            }
        } catch (err) {
            addToCartForm.submit();
        } finally {
            btn.disabled = false;
            btn.textContent = 'Ajouter au panier';
        }
    });
}
</script>
@endsection

