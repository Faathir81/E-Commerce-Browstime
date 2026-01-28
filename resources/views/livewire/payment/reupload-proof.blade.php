<div class="space-y-3">
    <div class="rounded-2xl border border-[#f1e8df] bg-white p-4 flex flex-col items-center justify-center">
        @if($proofUrl)
            <img src="{{ $proofUrl }}" alt="Bukti Pembayaran" class="max-h-56 object-contain rounded-xl">
        @else
            <p class="text-sm text-[#6f4c3b]">Belum ada bukti pembayaran.</p>
        @endif
    </div>

    @if($canReupload)
        <form wire:submit.prevent="submit" class="space-y-3" enctype="multipart/form-data">
            <div class="space-y-1">
                <label class="text-xs text-[#6f4c3b]">Unggah bukti pembayaran baru (JPG/PNG/PDF, maks 5MB)</label>
                <input type="file"
                       wire:model="payment_proof"
                       accept=".jpg,.jpeg,.png,.pdf"
                       class="block w-full text-sm text-[#3b241a] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#7a4b24] file:text-white hover:file:bg-[#693f1d]">
                @error('payment_proof') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
                @if($payment_proof)
                    <div class="mt-2 rounded-xl border border-[#f1e8df] bg-white p-3 space-y-2 text-sm text-[#3b241a]">
                        <div class="flex items-center justify-between gap-2">
                            <span class="truncate">{{ $payment_proof->getClientOriginalName() }}</span>
                            <button type="button" class="text-[#b3261e] text-xs font-semibold" wire:click="$set('payment_proof', null)">Hapus</button>
                        </div>
                        @if($this->isTempImage)
                            <div class="rounded-xl border border-[#f1e8df] bg-[#fffaf5] p-3 flex items-center justify-center">
                                <img src="{{ $payment_proof->temporaryUrl() }}" alt="Pratinjau bukti bayar" class="max-h-48 object-contain rounded-lg">
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-full border border-[#e4d6c6] bg-[#fdf4e8] text-[#3b241a] px-5 py-3 text-sm font-semibold hover:bg-[#f5ece3] transition"
                        wire:loading.attr="disabled">
                    Unggah Ulang Bukti Pembayaran
                </button>
            </div>
        </form>
    @endif
</div>
