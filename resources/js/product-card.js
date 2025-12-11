document.addEventListener('click', (event) => {
    const addBtn = event.target.closest('[data-add-btn]');
    if (addBtn) return;

    const card = event.target.closest('[data-product-url]');
    if (!card) return;

    const url = card.getAttribute('data-product-url');
    if (url) {
        window.location = url;
    }
});
