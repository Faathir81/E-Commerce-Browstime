document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-scroll-to]');
    if (!trigger) return;
    const targetSel = trigger.getAttribute('data-scroll-to');
    const target = document.querySelector(targetSel);
    if (!target) return;
    e.preventDefault();
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

const heroSlider = document.querySelector('[data-hero-slider]');
if (heroSlider) {
    const track = heroSlider.querySelector('[data-hero-track]');
    const slides = heroSlider.querySelectorAll('[data-hero-slide]');
    if (track && slides.length > 1) {
        let index = 0;
        setInterval(() => {
            index = (index + 1) % slides.length;
            track.style.transform = `translateX(-${index * 100}%)`;
        }, 3500);
    }
}
