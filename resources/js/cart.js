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
    // Use emit for compatibility; dispatch caused payload shape issues on some setups.
    if (window.Livewire?.emit) {
        window.Livewire.emit('cartUpdated', total);
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

// CART PAGE QTY & REMOVE
const formatRp = (num) =>
    'Rp ' +
    Number(num || 0)
        .toLocaleString('id-ID');

const updateCartSummary = (subtotal) => {
    const subEl = document.querySelector('[data-cart-subtotal]');
    const delEl = document.querySelector('[data-cart-delivery]');
    const totEl = document.querySelector('[data-cart-total]');
    if (subEl) subEl.textContent = formatRp(subtotal);
    if (delEl) delEl.textContent = 'Calculated at checkout';
    if (totEl) totEl.textContent = formatRp(subtotal);
};

const setCartState = (totalQuantity) => {
    const emptyState = document.querySelector('[data-empty-state]');
    const cartItemsContainer = document.querySelector('[data-cart-items]');
    const proceedBtn = document.querySelector('[data-proceed-btn]');

    const isEmpty = Number(totalQuantity || 0) <= 0;

    if (emptyState) emptyState.classList.toggle('hidden', !isEmpty);
    if (cartItemsContainer) cartItemsContainer.classList.toggle('hidden', isEmpty);

    if (proceedBtn) {
        if (isEmpty) {
            proceedBtn.classList.add('pointer-events-none', 'opacity-60');
            proceedBtn.setAttribute('aria-disabled', 'true');
        } else {
            proceedBtn.classList.remove('pointer-events-none', 'opacity-60');
            proceedBtn.setAttribute('aria-disabled', 'false');
        }
    }
};

const initCartPage = () => {
    const qtyButtons = document.querySelectorAll('[data-qty-btn]');
    const removeButtons = document.querySelectorAll('[data-remove-btn]');
    const meta = document.querySelector('[data-cart-meta]');
    if (meta?.dataset.totalQuantity !== undefined) {
        setCartState(parseInt(meta.dataset.totalQuantity, 10));
    }

    qtyButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const action = btn.dataset.action;
            const updateUrl = btn.dataset.updateUrl;
            const id = btn.dataset.id;
            if (!action || !updateUrl || !id) return;

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ action }),
            })
                .then((res) => (res.ok ? res.json() : res.json().then((err) => Promise.reject(err))))
                .then((data) => {
                    const display = document.querySelector(`[data-qty-display][data-id="${id}"]`);
                    const row = btn.closest('[data-cart-item]');
                    if (data.itemQuantity > 0) {
                        if (display) display.textContent = data.itemQuantity;
                    } else if (row) {
                        row.remove();
                    }
                    const total = data?.totalQuantity ?? 0;
                    notifyLivewire(total);
                    updateCartBadge(total);
                    updateCartSummary(data?.subtotal);
                    setCartState(total);
                })
                .catch((err) => console.error('Update cart failed', err));
        });
    });

    removeButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const removeUrl = btn.dataset.removeUrl;
            const id = btn.dataset.id;
            if (!removeUrl || !id) return;
            fetch(removeUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            })
                .then((res) => (res.ok ? res.json() : res.json().then((err) => Promise.reject(err))))
                .then((data) => {
                    const row = btn.closest('[data-cart-item]');
                    if (row) row.remove();
                    const total = data?.totalQuantity ?? 0;
                    notifyLivewire(total);
                    updateCartBadge(total);
                    updateCartSummary(data?.subtotal);
                    setCartState(total);
                })
                .catch((err) => console.error('Remove cart item failed', err));
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initCartPage();
});
