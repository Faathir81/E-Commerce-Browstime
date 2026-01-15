document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-review-verify]').forEach((wrapper) => {
        const input = wrapper.querySelector('[data-review-verify-input]');
        const button = wrapper.querySelector('[data-review-verify-button]');
        const successText = wrapper.querySelector('[data-review-verify-success]');
        const errorText = wrapper.querySelector('[data-review-verify-error]');
        const badge = wrapper.querySelector('[data-review-verify-badge]');
        const verifyUrl = wrapper.getAttribute('data-verify-url');

        if (!input || !button || !verifyUrl) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const setMessage = (type, message) => {
            if (successText) {
                successText.classList.toggle('hidden', type !== 'success');
                if (type === 'success') successText.textContent = message;
            }
            if (errorText) {
                errorText.classList.toggle('hidden', type !== 'error');
                if (type === 'error') errorText.textContent = message;
            }
        };

        const setVerified = (isVerified) => {
            if (badge) {
                badge.classList.toggle('hidden', !isVerified);
            }
            wrapper.dataset.verified = isVerified ? 'true' : 'false';
            button.textContent = isVerified ? 'Verifikasi ulang' : 'Verifikasi';
        };

        button.addEventListener('click', async () => {
            const email = input.value.trim();
            if (!email) {
                setMessage('error', 'Email wajib diisi.');
                setVerified(false);
                return;
            }

            button.disabled = true;
            button.classList.add('opacity-70', 'cursor-not-allowed');

            try {
                const response = await fetch(verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                    },
                    body: JSON.stringify({ guest_email: email }),
                });

                const data = await response.json();

                if (!response.ok || !data.valid) {
                    const errorMessage = data.message
                        || (data.errors && data.errors.guest_email ? data.errors.guest_email[0] : null)
                        || 'Email tidak valid.';
                    setMessage('error', errorMessage);
                    setVerified(false);
                    if (window.Livewire && typeof window.Livewire.emit === 'function') {
                        window.Livewire.emit('review-email-cleared');
                    }
                    return;
                }

                setMessage('success', data.message || 'Email terverifikasi. Anda dapat memberikan ulasan.');
                setVerified(true);

                if (window.Livewire && typeof window.Livewire.emit === 'function') {
                    window.Livewire.emit('review-email-verified', data.email || email);
                }
            } catch (error) {
                setMessage('error', 'Gagal memverifikasi email. Coba lagi.');
                setVerified(false);
            } finally {
                button.disabled = false;
                button.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    });
});
