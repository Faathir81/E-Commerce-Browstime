<div class="rounded-2xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-3">
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-[#3b241a]">{{ $productName }}</p>
            <p class="text-xs text-[#6f4c3b]">Qty: {{ $qty }}</p>
        </div>
        @if($hasReview)
            <span class="inline-flex items-center rounded-full bg-[#e8f7e5] text-[#2f7a3d] px-3 py-1 text-xs font-semibold">Sudah diulas</span>
        @endif
    </div>

    @if($hasReview)
        <div class="space-y-2 text-xs text-[#6f4c3b]">
            <p class="font-semibold text-[#3b241a]">Ulasan Anda</p>
            <div class="rounded-xl border border-[#e8dccf] bg-white px-3 py-2">
                <p class="text-sm font-semibold text-[#3b241a]">Rating: {{ $reviewRating ?? '-' }} / 5</p>
                @if($reviewKomentar)
                    <p class="mt-1 leading-relaxed">{{ $reviewKomentar }}</p>
                @else
                    <p class="mt-1 italic text-[#9b7a64]">Tidak ada komentar.</p>
                @endif
            </div>
        </div>
    @elseif(! $canReview)
        <div class="text-xs text-[#9b7a64]">
            Form ulasan muncul setelah verifikasi kepemilikan dan belum ada ulasan.
        </div>
    @else
        <form wire:submit.prevent="submit" class="space-y-3">
            @if($successMessage)
                <div class="rounded-xl bg-[#e8f7e5] text-[#2f7a3d] px-3 py-2 text-xs font-semibold">
                    {{ $successMessage }}
                </div>
            @endif

            @error('general')
                <div class="rounded-xl bg-[#fcecec] text-[#9f2c2c] px-3 py-2 text-xs">
                    {{ $message }}
                </div>
            @enderror

            @guest
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-[#3b241a]" for="guest_email_{{ $detailId }}">Email pemesan</label>
                    <input id="guest_email_{{ $detailId }}"
                           type="email"
                           wire:model.defer="guestEmailForReview"
                           @class([
                               'w-full rounded-xl border px-3 py-2 text-sm focus:outline-none',
                               'border-[#e8dccf] focus:ring-2 focus:ring-[#c79c68]' => ! $guestEmailForReview,
                               'border-[#e8dccf] bg-[#f6f0e8] text-[#9b7a64] cursor-not-allowed' => $guestEmailForReview,
                           ])
                           @if($guestEmailForReview) disabled @endif
                           required>
                    @if($guestEmailForReview)
                        <p class="text-[11px] text-[#2f7a3d] font-semibold mt-1">Email terverifikasi. Anda dapat memberikan ulasan.</p>
                    @endif
                    @error('guestEmailForReview')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endguest

            <div class="space-y-1">
                <label class="text-xs font-semibold text-[#3b241a]" for="rating_{{ $detailId }}">Rating (1-5)</label>
                <select id="rating_{{ $detailId }}"
                        wire:model.defer="rating"
                        class="w-full rounded-xl border border-[#e8dccf] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c79c68]"
                        required>
                    <option value="">Pilih rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                @error('rating')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-[#3b241a]" for="komentar_{{ $detailId }}">Komentar</label>
                <textarea id="komentar_{{ $detailId }}"
                          wire:model.defer="komentar"
                          rows="3"
                          class="w-full rounded-xl border border-[#e8dccf] px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c79c68]"
                          placeholder="Tulis pengalaman Anda (opsional)"></textarea>
                @error('komentar')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#2f7a3d] text-white px-4 py-2 text-sm font-semibold hover:bg-[#256531] transition">
                Kirim Ulasan
            </button>
        </form>
    @endif
</div>
