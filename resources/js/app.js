import './bootstrap';
import './components/CartDrawer';

// API Services — available globally via import or window for Blade templates
import { ProductService } from './services/ProductService.js';
import { CartService }    from './services/CartService.js';
import { AuthService }    from './services/AuthService.js';
import { OrderService }   from './services/OrderService.js';

// Expose to window for Blade-embedded scripts (progressive enhancement)
window.SteporaApi = { ProductService, CartService, AuthService, OrderService };

// Global: handle unauthenticated event fired by Axios interceptor
window.addEventListener('stepora:unauthenticated', () => {
    console.warn('[Stepora] Session expirée. Redirection vers /login...');
    // For Blade: redirect to login. For SPA: emit a store event.
    if (!window.location.pathname.startsWith('/login')) {
        window.location.href = '/login';
    }
});

document.addEventListener("DOMContentLoaded", () => {

    const toggle = document.querySelector(".menu-toggle");
    const menu = document.querySelector(".mobile-menu");
    const close = document.querySelector(".close-menu");

    if (toggle && menu) {
        toggle.addEventListener("click", () => {
            menu.classList.add("active");
        });
    }

    if (close && menu) {
        close.addEventListener("click", () => {
            menu.classList.remove("active");
        });
    }

});

window.toggleFilters = function () {
    const panel = document.getElementById('filtersPanel');
    if (panel) {
        panel.classList.toggle('hidden');
    }
};