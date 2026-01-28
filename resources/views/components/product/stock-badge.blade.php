@props(['inStock' => false])

@if($inStock)
    <span class="rounded-full bg-[#e8f7e5] text-[#2f7a3d] px-3 py-1">Stok Tersedia</span>
@else
    <span class="rounded-full bg-[#fdecea] text-[#b3261e] px-3 py-1">Stok Habis</span>
@endif
