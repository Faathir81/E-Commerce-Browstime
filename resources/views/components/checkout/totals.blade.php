@props(['subtotal', 'shipping', 'total'])

<div class="space-y-2 text-sm text-[#6f4c3b]">
    <div class="flex items-center justify-between">
        <span>Subtotal</span>
        <span class="text-[#3b241a]">Rp {{ $subtotal }}</span>
    </div>
    <div class="flex items-center justify-between">
        <span>Shipping Fee</span>
        <span class="text-[#3b241a]">{{ $shipping }}</span>
    </div>
</div>

<div class="border-t border-[#f1e8df] pt-3">
    <div class="flex items-center justify-between text-base font-semibold text-[#3b241a]">
        <span>Total</span>
        <span>Rp {{ $total }}</span>
    </div>
</div>
