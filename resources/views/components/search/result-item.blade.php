@props(['item'])

<a wire:key="result-{{ $item['id'] }}"
   href="{{ $item['url'] }}"
   class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#f9f1ea] transition">

    <img src="{{ $item['image_url'] }}"
         class="w-12 h-12 rounded-lg object-cover"
         alt="{{ $item['name'] }}">

    <div class="flex-1 min-w-0">
        <p class="font-medium text-[#3b241a] truncate">
            {{ $item['name'] }}
        </p>
        <p class="text-sm text-[#6f4c3b]">
            Rp {{ $item['price'] }}
        </p>
    </div>

    <span class="text-xs bg-[#d7b28a] text-white px-3 py-1 rounded-full">
        {{ $item['category'] }}
    </span>
</a>
