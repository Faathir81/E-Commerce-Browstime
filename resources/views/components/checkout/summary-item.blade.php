@props(['item'])

<div class="flex items-start gap-3">
    <div class="h-14 w-14 rounded-xl overflow-hidden bg-[#f9f2eb] border border-[#f1e8df]">
        <img src="{{ $item['image_url'] }}"
             alt="{{ $item['name'] }}"
             class="h-full w-full object-cover">
    </div>
    <div class="flex-1 text-sm text-[#3b241a]">
        <p class="font-semibold">{{ $item['name'] }}</p>
        <p class="text-[#6f4c3b]">Jumlah: {{ $item['quantity'] }}</p>
    </div>
    <div class="text-sm font-semibold text-[#3b241a]">
        Rp {{ $item['subtotal_formatted'] }}
    </div>
</div>
