@props(['inStock' => false])

@if($inStock)
    <span class="rounded-full bg-[#e8f7e5] text-[#2f7a3d] px-3 py-1">In Stock</span>
@else
    <span class="rounded-full bg-[#fdecea] text-[#b3261e] px-3 py-1">Out of Stock</span>
@endif
