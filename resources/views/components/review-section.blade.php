@props([
    'pesanan',
    'orderCode',
    'reviewPermissions' => [],
    'guestEmailForReview' => null,
    'isCompleted' => false,
])

@if($isCompleted)
    <details class="review-accordion bg-white border border-[#f1e8df] rounded-3xl p-5 shadow-sm group" {{ old('guest_email') || old('rating') || session('error') ? 'open' : '' }}>
        <summary class="flex items-center justify-between cursor-pointer text-sm font-semibold text-[#3b241a] gap-3 rounded-2xl px-3 py-2 hover:bg-[#fdf7f0] transition">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#f7ece0] text-[#c79c68]">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 7V12L15 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="1.6"/>
                    </svg>
                </span>
                <div class="flex flex-col">
                    <p>Ulasan Produk</p>
                    <span class="text-xs text-[#9b7a64] group-open:hidden">Klik untuk membuka</span>
                    <span class="text-xs text-[#9b7a64] hidden group-open:inline">Klik untuk tutup</span>
                </div>
            </div>
            <svg class="chevron h-5 w-5 text-[#c79c68]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 9l-7 7-7-7" />
            </svg>
        </summary>

        <div class="accordion-content mt-4 space-y-4">
            @if(!Auth::check() && empty($pesanan?->user_id))
                <div class="rounded-2xl border border-[#f1e8df] bg-[#fffaf5] p-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                     data-review-verify
                     data-verify-url="{{ url('/order-success/' . $orderCode . '/verify-email') }}"
                     data-verified="{{ $guestEmailForReview ? 'true' : 'false' }}">
                    <div class="flex-1">
                        <p class="text-xs text-[#6f4c3b] mb-1">
                            {{ $guestEmailForReview ? 'Email pemesan (bisa diganti jika salah)' : 'Masukkan email yang dipakai saat memesan untuk membuka form ulasan.' }}
                        </p>
                        <input type="email"
                               name="guest_email"
                               value="{{ $guestEmailForReview }}"
                               class="w-full rounded-xl border border-[#e8dccf] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c79c68]"
                               placeholder="you@mail.com"
                               data-review-verify-input
                               required>
                        <p class="text-[11px] text-[#2f7a3d] font-semibold mt-1 {{ $guestEmailForReview ? '' : 'hidden' }}"
                           data-review-verify-success>
                            Email terverifikasi. Anda dapat memberikan ulasan.
                        </p>
                        <p class="text-[11px] text-[#9f2c2c] font-semibold mt-1 hidden" data-review-verify-error></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-[#e8f7e5] text-[#2f7a3d] px-3 py-1 text-xs font-semibold {{ $guestEmailForReview ? '' : 'hidden' }}"
                              data-review-verify-badge>
                            Terverifikasi
                        </span>
                        <button type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#7a4b24] text-white px-4 py-2 text-sm font-semibold hover:bg-[#693f1d] transition"
                                data-review-verify-button>
                            {{ $guestEmailForReview ? 'Verifikasi ulang' : 'Verifikasi' }}
                        </button>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                @foreach($pesanan->detailPesanans as $detail)
                    <livewire:review.review-item
                        :key="'review-'.$detail->id"
                        :detail="$detail"
                        :can-review="($reviewPermissions[$detail->id]['can_review'] ?? false)"
                        :has-review="($reviewPermissions[$detail->id]['has_review'] ?? false)"
                        :guest-email-for-review="$guestEmailForReview"
                    />
                @endforeach
            </div>
        </div>
    </details>
@endif
