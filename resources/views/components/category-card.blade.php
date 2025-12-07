@props(['label', 'keyword'])

<div 
    wire:click="$dispatch('searchCategory', '{{ $keyword }}')"
    class="cursor-pointer rounded-2xl border border-[#e7dcd2] bg-white px-8 py-6 text-center shadow-sm transition hover:shadow-md"
>
    <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-[#f4ece6]">
        {{ $slot }}
    </div>

    <p class="text-[#3b241a] font-medium">{{ $label }}</p>
</div>
