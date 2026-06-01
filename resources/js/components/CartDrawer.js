/**
 * CART DRAWER - JavaScript Controller
 * Handles add to cart interactions and drawer display
 */

class CartDrawerManager {
    constructor() {
        this.drawer = document.getElementById("cartDrawer");
        this.overlay = document.getElementById("cartDrawerOverlay");
        this.closeBtn = document.getElementById("cartDrawerClose");
        this.isOpen = false;
        this.init();
    }

    /**
     * Initialize event listeners
     */
    init() {
        // Close button
        this.closeBtn?.addEventListener("click", () => this.close());

        // Overlay click to close
        this.overlay?.addEventListener("click", () => this.close());

        // Keyboard - ESC to close
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.isOpen) {
                this.close();
            }
        });

        // Intercept all "Add to Cart" buttons
        this.interceptAddToCartForms();
    }

    /**
     * Intercept form submission for "Add to Cart"
     */
    interceptAddToCartForms() {
        document.addEventListener("submit", (e) => {
            const form = e.target;

            // Check if form is part of add-to-cart
            if (
                form.action.includes("/panier/ajouter") ||
                form.classList.contains("cart-form")
            ) {
                e.preventDefault();
                this.handleAddToCart(form);
            }
        });

        // Also handle button clicks if they have data attributes
        document.addEventListener("click", (e) => {
            if (
                e.target.classList.contains("btn-add-to-cart") &&
                e.target.closest("form")
            ) {
                const form = e.target.closest("form");
                e.preventDefault();
                this.handleAddToCart(form);
            }
        });
    }

    /**
     * Handle form submission via AJAX
     */
    async handleAddToCart(form) {
        const formData = new FormData(form);
        const action = form.getAttribute("action");

        try {
            const response = await fetch(action, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success && data.product) {
                this.displayProduct(data.product, data.cart_count);
                this.open();

                // Auto-close after 8 seconds (optional)
                // this.autoClose(8000);
            } else {
                console.error("Error adding product:", data);
                // Fall back to traditional submission
                form.submit();
            }
        } catch (error) {
            console.error("AJAX Error:", error);
            // Fall back to traditional form submission
            form.submit();
        }
    }

    /**
     * Display product in drawer
     */
    displayProduct(product, cartCount = null) {
        // Set product image
        const imgEl = document.getElementById("cartDrawerImage");
        if (imgEl && product.image) {
            imgEl.src = product.image;
            imgEl.alt = product.nom;
        }

        // Set product name
        const nameEl = document.getElementById("cartDrawerProductName");
        if (nameEl) {
            nameEl.textContent = product.nom;
        }

        // Set category
        const categoryEl = document.getElementById("cartDrawerProductCategory");
        if (categoryEl && product.category) {
            categoryEl.textContent = product.category;
        }

        // Set quantity
        const quantityEl = document.getElementById("cartDrawerQuantity");
        if (quantityEl) {
            quantityEl.textContent = product.quantity || 1;
        }

        // Set variant (size/color)
        const variantEl = document.getElementById("cartDrawerVariant");
        if (variantEl) {
            variantEl.textContent = product.variant || "-";
        }

        // Set price
        const priceEl = document.getElementById("cartDrawerPrice");
        if (priceEl && product.prix) {
            priceEl.textContent = product.prix;
        }

        // Update cart count if needed (e.g., in header)
        if (cartCount !== null) {
            this.updateCartCount(cartCount);
        }
    }

    /**
     * Update cart count in header/navbar
     */
    updateCartCount(count) {
        const badge = document.getElementById("navCartBadge");
        if (badge) {
            badge.textContent = count > 9 ? "9+" : String(count);
            badge.classList.toggle("cart-badge--hidden", count <= 0);
        }

        document.querySelectorAll(".cart-count, [data-cart-count]").forEach((el) => {
            el.textContent = count;
        });
    }

    /**
     * Open the drawer
     */
    open() {
        if (!this.drawer) return;

        this.drawer.classList.add("active");
        this.isOpen = true;

        // Prevent body scroll
        document.body.style.overflow = "hidden";

        // Trigger animation
        this.drawer.offsetHeight; // Force reflow
    }

    /**
     * Close the drawer
     */
    close() {
        if (!this.drawer) return;

        this.drawer.classList.remove("active");
        this.isOpen = false;

        // Allow body scroll
        document.body.style.overflow = "";
    }

    /**
     * Auto-close after delay (in milliseconds)
     */
    autoClose(delay = 5000) {
        setTimeout(() => {
            this.close();
        }, delay);
    }

    /**
     * Toggle drawer open/close
     */
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }
}

// Initialize CartDrawer when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.cartDrawer = new CartDrawerManager();
    });
} else {
    window.cartDrawer = new CartDrawerManager();
}
