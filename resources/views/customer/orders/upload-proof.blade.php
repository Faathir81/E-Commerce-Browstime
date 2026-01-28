<div class="bg-[#FFF9F4] min-h-screen">
    <div class="mx-auto max-w-screen-sm px-6 sm:px-8 lg:px-10 py-10">
        <div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-6 space-y-6">
            <div class="space-y-1">
                <h1 class="text-lg font-semibold text-[#3b241a]">Unggah Bukti Pembayaran</h1>
                <p class="text-sm text-[#6f4c3b]">Kode Pesanan: <span class="font-semibold text-[#3b241a]">{{ $pesanan?->kode ?? '-' }}</span></p>
                @if($pembayaran)
                    <p class="text-sm text-[#6f4c3b]">Metode: <span class="font-semibold text-[#3b241a]">{{ strtoupper($pembayaran->metode) }}</span></p>
                @endif
            </div>

            @if(session('status'))
                <div class="rounded-xl bg-[#e8f7e5] text-[#2f7a3d] px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if($pembayaran && $pembayaran->bukti_bayar)
                <div class="space-y-2">
                    <p class="text-xs text-[#6f4c3b]">Bukti pembayaran saat ini:</p>
                    @if(\Illuminate\Support\Str::endsWith(strtolower($pembayaran->bukti_bayar), ['.jpg','.jpeg','.png']))
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pembayaran->bukti_bayar) }}" alt="Bukti pembayaran" class="rounded-xl border border-[#f1e8df] max-h-72 object-contain">
                    @else
                        <a class="text-sm text-[#7a4b24] underline" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pembayaran->bukti_bayar) }}" target="_blank" rel="noopener">
                            Lihat bukti bayar (PDF)
                        </a>
                    @endif
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
                <div class="space-y-1">
                    <label class="text-xs text-[#6f4c3b]">Unggah bukti pembayaran (JPG/PNG/PDF, maks 5MB)</label>
                    <input type="file"
                           wire:model="payment_proof"
                           accept=".jpg,.jpeg,.png,.pdf"
                           class="block w-full text-sm text-[#3b241a] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#7a4b24] file:text-white hover:file:bg-[#693f1d]">
                    @error('payment_proof') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-full bg-[#7a4b24] text-white px-5 py-3 text-sm font-semibold hover:bg-[#693f1d] transition"
                            wire:loading.attr="disabled">
                        Unggah
                    </button>
                    <a href="{{ route('order.success', ['kode' => $pesanan?->kode ?? '']) }}"
                       class="inline-flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] px-5 py-3 text-sm font-semibold hover:bg-[#f5ece3] transition">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
