/**
 * Navbar Badges Management
 * Gère l'affichage et l'animation des compteurs bleu et rouge
 */

export class NavbarBadges {
    constructor() {
        this.cartBadge = document.getElementById("navCartBadge");
        this.accountBadge = document.getElementById("navAccountBadge");
        this.contactBadge = document.getElementById("navContactBadge");
    }

    /**
     * Initialiser les badges
     */
    init() {
        this.setupCartBadgeListener();
        this.setupAccountBadgeListener();
        this.setupContactBadgeListener();
        this.setupCartBadgeGuard();
        this.setupContactBadgeGuard();

        // Vérifier le panier initial
        this.checkInitialCart();
    }

    /**
     * Écouter les changements du contact (réponses admin) via des événements personnalisés
     */
    setupContactBadgeListener() {
        window.addEventListener("contact:updated", (event) => {
            const count = event.detail?.count || 0;
            this.updateContactBadge(count);
        });
    }

    /**
     * Guard pour le badge contact
     */
    setupContactBadgeGuard() {
        if (!this.contactBadge || typeof MutationObserver === 'undefined') return;

        const guard = (mutationsList) => {
            for (const m of mutationsList) {
                const text = (this.contactBadge.textContent || '').trim();
                const count = text.endsWith('+') ? parseInt(text, 10) || 9 : parseInt(text, 10) || 0;
                if (count > 0 && this.contactBadge.classList.contains('contact-badge--hidden')) {
                    this.contactBadge.classList.remove('contact-badge--hidden');
                }
                if (count <= 0 && !this.contactBadge.classList.contains('contact-badge--hidden')) {
                    this.contactBadge.classList.add('contact-badge--hidden');
                }
            }
        };

        this._contactBadgeObserver = new MutationObserver(guard);
        this._contactBadgeObserver.observe(this.contactBadge, {
            attributes: true,
            attributeFilter: ['class'],
            childList: true,
            characterData: true,
            subtree: true,
        });
    }

    /**
     * Empêcher d'autres scripts de masquer le badge alors que le compteur > 0
     * Utilise un MutationObserver pour rétablir la visibilité si nécessaire
     */
    setupCartBadgeGuard() {
        if (!this.cartBadge || typeof MutationObserver === 'undefined') return;

        const guard = (mutationsList) => {
            for (const m of mutationsList) {
                // Si contenu texte changé, ou classes changées, réappliquer la règle
                const text = (this.cartBadge.textContent || '').trim();
                const count = text.endsWith('+') ? parseInt(text, 10) || 9 : parseInt(text, 10) || 0;
                if (count > 0 && this.cartBadge.classList.contains('cart-badge--hidden')) {
                    this.cartBadge.classList.remove('cart-badge--hidden');
                }
                // If count is zero, ensure hidden
                if (count <= 0 && !this.cartBadge.classList.contains('cart-badge--hidden')) {
                    this.cartBadge.classList.add('cart-badge--hidden');
                }
            }
        };

        this._cartBadgeObserver = new MutationObserver(guard);
        this._cartBadgeObserver.observe(this.cartBadge, {
            attributes: true,
            attributeFilter: ['class'],
            childList: true,
            characterData: true,
            subtree: true,
        });
    }

    /**
     * Vérifier l'état initial du panier
     */
    checkInitialCart() {
        // Le badge du panier est déjà chargé via Blade PHP
        // Ajouter la classe d'animation à la première apparition
        if (
            this.cartBadge &&
            !this.cartBadge.classList.contains("cart-badge--hidden")
        ) {
            this.showCartBadgeWithAnimation();
        }
    }

    /**
     * Afficher le compteur du panier avec animation
     */
    showCartBadgeWithAnimation() {
        if (!this.cartBadge) return;

        // Assurez-vous que le badge est visible — ne pas le masquer automatiquement
        this.cartBadge.classList.remove("cart-badge--hidden");
    }

    /**
     * Mettre à jour le compteur du panier
     * @param {number} count - Nombre d'articles
     */
    updateCartBadge(count) {
        if (!this.cartBadge) return;

        if (count > 0) {
            this.cartBadge.textContent = count > 9 ? "9+" : count;
            this.showCartBadgeWithAnimation();
        } else {
            this.cartBadge.classList.add("cart-badge--hidden");
        }
    }

    /**
     * Mettre à jour le compteur du compte (réponses clients)
     * @param {number} count - Nombre de réponses
     */
    updateAccountBadge(count) {
        if (!this.accountBadge) return;

        if (count > 0) {
            this.accountBadge.textContent = count > 9 ? "9+" : count;
            this.accountBadge.classList.remove("account-badge--hidden");
        } else {
            this.accountBadge.classList.add("account-badge--hidden");
        }
    }

    /**
     * Mettre à jour le compteur contact (réponses admin)
     * @param {number} count
     */
    updateContactBadge(count) {
        if (!this.contactBadge) return;

        if (count > 0) {
            this.contactBadge.textContent = count > 9 ? '9+' : count;
            this.contactBadge.classList.remove('contact-badge--hidden');
        } else {
            this.contactBadge.classList.add('contact-badge--hidden');
        }
    }

    /**
     * Écouter les changements du panier via des événements personnalisés
     */
    setupCartBadgeListener() {
        window.addEventListener("cart:updated", (event) => {
            const count = event.detail?.count || 0;
            this.updateCartBadge(count);
        });
    }

    /**
     * Écouter les changements du compte via des événements personnalisés
     */
    setupAccountBadgeListener() {
        window.addEventListener("account:updated", (event) => {
            const count = event.detail?.count || 0;
            this.updateAccountBadge(count);
        });
    }

    /**
     * Afficher les deux compteurs si nécessaire
     */
    show() {
        if (this.cartBadge) {
            this.cartBadge.classList.remove("cart-badge--hidden");
        }
        if (this.accountBadge) {
            this.accountBadge.classList.remove("account-badge--hidden");
        }
    }

    /**
     * Masquer les deux compteurs
     */
    hide() {
        if (this.cartBadge) {
            this.cartBadge.classList.add("cart-badge--hidden");
        }
        if (this.accountBadge) {
            this.accountBadge.classList.add("account-badge--hidden");
        }
    }
}

// Initialiser au chargement
document.addEventListener("DOMContentLoaded", () => {
    const badges = new NavbarBadges();
    badges.init();

    // Exposer globalement pour utilisation
    window.NavbarBadges = badges;
});
