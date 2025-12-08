const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const cartAddUrl = document.querySelector('meta[name="cart-add-url"]')?.getAttribute('content') || '/cart/add';

const updateCartBadge = (total) => {
    const badge = document.querySelector('[data-cart-count]');
    if (!badge) return;
    const count = Number.isNaN(total) ? 0 : Math.max(0, total);
    badge.textContent = count;
    badge.classList.toggle('hidden', count === 0);
};

const notifyLivewire = (total) => {
    if (window.Livewire?.emit) {
        window.Livewire.emit('cartUpdated', total);
    }
    if (window.Livewire?.dispatch) {
        window.Livewire.dispatch('cartUpdated', total);
    }
};

const addToCart = (productId, quantity = 1) => {
    if (!productId) return;
    return fetch(cartAddUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity }),
    })
        .then((res) => (res.ok ? res.json() : res.json().then((err) => Promise.reject(err))))
        .then((data) => {
            const total = data?.totalQuantity ?? 0;
            notifyLivewire(total);
            updateCartBadge(total);
            return data;
        })
        .catch((err) => {
            console.error('Add to cart failed', err);
        });
};

const initProductQuantity = () => {
    const input = document.querySelector('[data-qty-input]');
    const minus = document.querySelector('[data-qty-minus]');
    const plus = document.querySelector('[data-qty-plus]');
    const addBtn = document.querySelector('[data-add-cart]');
    if (!input || !minus || !plus) return;

    const min = 1;
    const maxRaw = parseInt(input.dataset.max, 10);
    const max = Number.isNaN(maxRaw) ? Infinity : maxRaw;
    const clamp = (val) => {
        const n = Number.isNaN(val) ? min : val;
        return Math.min(max, Math.max(min, n));
    };
    const setValue = (val) => {
        const next = clamp(parseInt(val, 10));
        input.value = next;
    };

    minus.addEventListener('click', () => setValue(parseInt(input.value, 10) - 1));
    plus.addEventListener('click', () => setValue(parseInt(input.value, 10) + 1));

    addBtn?.addEventListener('click', () => {
        if (addBtn.disabled) return;
        const productId = addBtn.dataset.productId;
        const qty = clamp(parseInt(input.value, 10));
        addToCart(productId, qty);
    });
};

const initLandingCards = () => {
    document.addEventListener('click', (e) => {
        const addBtn = e.target.closest('[data-add-btn]');
        if (addBtn) {
            e.preventDefault();
            e.stopPropagation();
            const productId = addBtn.dataset.productId;
            if (!productId || addBtn.disabled) return;
            addToCart(productId, 1);
            return;
        }

        const card = e.target.closest('[data-product-url]');
        if (!card) return;
        const url = card.getAttribute('data-product-url');
        if (url) window.location = url;
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initProductQuantity();
    initLandingCards();
});
