@props(['category'])

@if($category?->nama)
    <span class="rounded-full bg-[#f1e8df] text-[#3b241a] px-3 py-1">{{ $category->nama }}</span>
@endif
