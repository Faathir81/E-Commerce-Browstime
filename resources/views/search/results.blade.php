@extends('layouts.app')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-14 py-8">

    <div class="flex items-start gap-2 text-[#3b241a] mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-4.35-4.35m.7-5.65a6 6 0 11-12 0 6 6 0 0112 0z" />
        </svg>
        <div>
            @if ($keyword !== '')
                <p class="text-base">
                    Search results for
                </p>
                <p class="text-2xl font-semibold leading-tight">
                    {{ $keyword }}
                </p>
            @else
                <p class="text-base">
                    All Products
                </p>
                <p class="text-2xl font-semibold leading-tight">
                    Browse All Products
                </p>
            @endif
            <p class="text-base text-[#6f4c3b] mt-1">
                {{ $results->total() }}
                {{ $keyword === '' ? 'delicious treats available' : \Illuminate\Support\Str::plural('product', $results->total()) . ' found' }}
            </p>
        </div>
    </div>

    @if ($results->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
            No products found. Try another keyword.
        </div>
    @else
        <div class="grid gap-4 grid-cols-[repeat(auto-fit,minmax(220px,260px))]">
            @foreach ($results as $product)
                @php
                    $productUrl = route('product.show', $product->slug);
                    $inStock = $product->hasSufficientStock();
                @endphp
                <div class="group relative w-full max-w-[360px] bg-white shadow-md rounded-2xl overflow-hidden border border-[#f1e8df] transition-shadow duration-200 hover:shadow-lg flex flex-col cursor-pointer"
                     data-product-url="{{ $productUrl }}">
                    <a href="{{ $productUrl }}" class="block pointer-events-none">
                        <div class="h-56 overflow-hidden">
                            <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/400x300' }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                 alt="{{ $product->nama }}">
                        </div>
                    </a>

                    <div class="p-4 flex-1">
                        <p class="text-xs flex items-center gap-1 {{ $inStock ? 'text-[#7d6b5c]' : 'text-[#8b5a2b]' }}">
                            <span class="{{ $inStock ? 'text-green-600' : 'text-red-500' }}">●</span>
                            {{ $inStock ? 'In Stock' : 'Out Stock' }}
                        </p>

                        <a href="{{ $productUrl }}" class="block mt-1 pointer-events-none">
                            <h3 class="font-semibold text-[#3b241a] truncate">
                                {{ $product->nama }}
                            </h3>
                        </a>

                        <p class="text-sm text-[#3b241a] mt-1">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>

                        <p class="text-xs text-[#6f4c3b] mt-1">
                            {{ optional($product->kategori)->nama ?? 'Produk' }}
                        </p>
                    </div>

                    <div class="px-4 pb-4">
                        <button type="button"
                                data-add-btn
                                class="mt-1 w-full flex items-center justify-center gap-2 text-white text-sm py-2 rounded-xl transition {{ $inStock ? 'bg-[#3b241a] hover:bg-[#2c1c14]' : 'bg-gray-400 cursor-not-allowed' }}"
                                {{ $inStock ? '' : 'disabled' }}>
                            <x-heroicon-o-shopping-cart class="w-4 h-4" />
                            Add
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $results->links() }}
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('click', (e) => {
    const addBtn = e.target.closest('[data-add-btn]');
    if (addBtn) {
        e.stopPropagation();
        return;
    }
    const card = e.target.closest('[data-product-url]');
    if (!card) return;
    const url = card.getAttribute('data-product-url');
    if (url) window.location = url;
});
</script>
@endpush
