<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-semibold text-[#3b241a]">Shipping Fee & ETA</h3>
            <p class="text-xs text-[#6f4c3b]">Calculate shipping after choosing your zone.</p>
        </div>
        <button type="button"
                class="inline-flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] px-4 py-2 text-xs font-semibold hover:bg-[#f5ece3] transition"
                wire:click="calculateShipping"
                wire:loading.attr="disabled">
            Refresh
        </button>
    </div>

    <div class="rounded-2xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-3 text-sm text-[#3b241a]">
        <div class="flex items-center justify-between">
            <span class="text-[#6f4c3b]">Shipping Zone</span>
            <span class="font-semibold">{{ $selectedZoneName }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[#6f4c3b]">Shipping Fee</span>
            <span class="font-semibold">{{ $formattedShippingFee }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[#6f4c3b]">Total Weight</span>
            <span class="font-semibold">{{ $formattedTotalWeight }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[#6f4c3b]">ETA</span>
            <span class="font-semibold">{{ $formattedEtd }}</span>
        </div>
    </div>

    @error('wilayah_pengiriman_id') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    @error('ongkir') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    @error('shipping_fee') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
</div>
