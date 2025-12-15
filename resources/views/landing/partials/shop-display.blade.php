<section class="w-full mt-16">
    {{-- WRAPPER KONSISTEN (SAMA KAYA CATEGORY SECTION) --}}
    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="space-y-1">
                <h2 class="text-xl font-semibold text-[#3b241a]">Our Products</h2>
                <p class="text-sm text-[#7c6a5a]">Customer favorites you'll absolutely love</p>
            </div>

            <a 
                href="{{ route('search') }}" 
                class="inline-flex items-center justify-center rounded-full border border-[#d3b58f] px-5 py-2 text-sm font-medium text-[#3b241a] transition-colors hover:bg-[#c79c68] hover:text-[#2b1a14] w-full sm:w-auto"
            >
                View All Products
            </a>
        </div>

        {{-- GRID PRODUK --}}
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            @foreach ($shopDisplay as $product)
                <x-product.card :product="$product" />
            @endforeach
        </div>

    </div>
</section>

@push('scripts')
@endpush
