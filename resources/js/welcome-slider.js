function scrollSlider(direction, sliderId = "categoriesSlider") {
    const slider = document.getElementById(sliderId);
    if (!slider) return;
    const scrollAmount = Math.min(slider.clientWidth * 0.75, 520);
    slider.scrollBy({
        left: direction * scrollAmount,
        behavior: "smooth",
    });
}

window.scrollSlider = scrollSlider;
