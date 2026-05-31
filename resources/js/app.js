import './bootstrap';
import './components/CartDrawer';

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