<div class="space-y-3">
    <h3 class="font-semibold text-base">Quantity</h3>
    <div class="flex items-center gap-3">
        <button type="button"
                wire:click="decrement"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] transition {{ $inStock ? 'hover:bg-[#f5ece3]' : 'opacity-50 cursor-not-allowed' }}"
                @disabled(! $inStock)>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.33118 7.99463H12.6584" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <input type="number"
               wire:model.live="quantity"
               min="1"
               inputmode="numeric"
               pattern="[0-9]*"
               class="no-spinner w-16 h-10 text-center border border-[#e4d6c6] rounded-lg focus:ring-[#bb936c] focus:border-[#bb936c]"
               @disabled(! $inStock) />
        <button type="button"
                wire:click="increment"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] transition {{ $inStock ? 'hover:bg-[#f5ece3]' : 'opacity-50 cursor-not-allowed' }}"
                @disabled(! $inStock)>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.33118 7.99463H12.6584" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M7.99481 3.33105V12.6583" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <span class="text-xs text-[#6f4c3b]">Max: {{ $max }} units</span>
    </div>
</div>
