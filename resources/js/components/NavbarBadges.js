/**
 * Navbar Badges Management
 * Gère l'affichage et l'animation des compteurs bleu et rouge
 */

export class NavbarBadges {
    constructor() {
        this.cartBadge = document.getElementById('navCartBadge');
        this.accountBadge = document.getElementById('navAccountBadge');
        this.cartBadgeTimeout = null;
    }

    /**
     * Initialiser les badges
     */
    init() {
        this.setupCartBadgeListener();
        this.setupAccountBadgeListener();
        
        // Vérifier le panier initial
        this.checkInitialCart();
    }

    /**
     * Vérifier l'état initial du panier
     */
    checkInitialCart() {
        // Le badge du panier est déjà chargé via Blade PHP
        // Ajouter la classe d'animation à la première apparition
        if (this.cartBadge && !this.cartBadge.classList.contains('cart-badge--hidden')) {
            this.showCartBadgeWithAnimation();
        }
    }

    /**
     * Afficher le compteur du panier avec animation
     */
    showCartBadgeWithAnimation() {
        if (!this.cartBadge) return;

        // Réinitialiser le timeout existant
        if (this.cartBadgeTimeout) {
            clearTimeout(this.cartBadgeTimeout);
        }

        // Assurez-vous que le badge est visible
        this.cartBadge.classList.remove('cart-badge--hidden');

        // Masquer le badge après 5 secondes
        this.cartBadgeTimeout = setTimeout(() => {
            if (this.cartBadge) {
                this.cartBadge.classList.add('cart-badge--hidden');
            }
        }, 5000);
    }

    /**
     * Mettre à jour le compteur du panier
     * @param {number} count - Nombre d'articles
     */
    updateCartBadge(count) {
        if (!this.cartBadge) return;

        if (count > 0) {
            this.cartBadge.textContent = count > 9 ? '9+' : count;
            this.showCartBadgeWithAnimation();
        } else {
            this.cartBadge.classList.add('cart-badge--hidden');
        }
    }

    /**
     * Mettre à jour le compteur du compte (réponses clients)
     * @param {number} count - Nombre de réponses
     */
    updateAccountBadge(count) {
        if (!this.accountBadge) return;

        if (count > 0) {
            this.accountBadge.textContent = count > 9 ? '9+' : count;
            this.accountBadge.classList.remove('account-badge--hidden');
        } else {
            this.accountBadge.classList.add('account-badge--hidden');
        }
    }

    /**
     * Écouter les changements du panier via des événements personnalisés
     */
    setupCartBadgeListener() {
        window.addEventListener('cart:updated', (event) => {
            const count = event.detail?.count || 0;
            this.updateCartBadge(count);
        });
    }

    /**
     * Écouter les changements du compte via des événements personnalisés
     */
    setupAccountBadgeListener() {
        window.addEventListener('account:updated', (event) => {
            const count = event.detail?.count || 0;
            this.updateAccountBadge(count);
        });
    }

    /**
     * Afficher les deux compteurs si nécessaire
     */
    show() {
        if (this.cartBadge) {
            this.cartBadge.classList.remove('cart-badge--hidden');
        }
        if (this.accountBadge) {
            this.accountBadge.classList.remove('account-badge--hidden');
        }
    }

    /**
     * Masquer les deux compteurs
     */
    hide() {
        if (this.cartBadge) {
            this.cartBadge.classList.add('cart-badge--hidden');
        }
        if (this.accountBadge) {
            this.accountBadge.classList.add('account-badge--hidden');
        }
    }
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', () => {
    const badges = new NavbarBadges();
    badges.init();
    
    // Exposer globalement pour utilisation
    window.NavbarBadges = badges;
});
