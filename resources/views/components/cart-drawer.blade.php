<!-- Cart Drawer Component -->
<div id="cartDrawer" class="cart-drawer">
    <!-- Overlay (backdrop) -->
    <div class="cart-drawer-overlay" id="cartDrawerOverlay"></div>

    <!-- Drawer Container -->
    <div class="cart-drawer-container">
        <!-- Header -->
        <div class="cart-drawer-header">
            <h3 class="cart-drawer-title">Produit ajouté au panier</h3>
            <button class="cart-drawer-close" id="cartDrawerClose" aria-label="Fermer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="cart-drawer-content">
            <!-- Product Image -->
            <div class="cart-drawer-product-image">
                <img id="cartDrawerImage" src="" alt="Produit ajouté">
            </div>

            <!-- Product Details -->
            <div class="cart-drawer-product-details">
                <h4 class="cart-drawer-product-name" id="cartDrawerProductName">Nom du produit</h4>
                <p class="cart-drawer-product-category" id="cartDrawerProductCategory">Catégorie</p>
                
                <div class="cart-drawer-product-meta">
                    <div class="cart-drawer-meta-item">
                        <span class="label">Quantité :</span>
                        <span class="value" id="cartDrawerQuantity">1</span>
                    </div>
                    <div class="cart-drawer-meta-item">
                        <span class="label">Variante :</span>
                        <span class="value" id="cartDrawerVariant">-</span>
                    </div>
                </div>

                <div class="cart-drawer-product-price">
                    <span id="cartDrawerPrice">0 CDF</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="cart-drawer-actions">
            <a href="{{ route('panier.index') }}" class="btn-drawer-action btn-drawer-secondary">
                <i class="fa-solid fa-shopping-bag"></i> Afficher le panier
            </a>
            <a href="{{ route('panier.index') }}#checkout" class="btn-drawer-action btn-drawer-primary">
                <i class="fa-solid fa-credit-card"></i> Procéder au paiement
            </a>
        </div>

        <!-- Success Message -->
        <div class="cart-drawer-success-message">
            <i class="fa-solid fa-check-circle"></i> Ajouté avec succès !
        </div>
    </div>
</div>
