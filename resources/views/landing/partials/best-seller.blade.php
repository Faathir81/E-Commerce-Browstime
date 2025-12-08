<section class="w-full mt-16">
    {{-- WRAPPER KONSISTEN (SAMA KAYA CATEGORY SECTION) --}}
    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-[#3b241a]">Our Best Sellers</h2>
                <p class="text-sm text-[#7c6a5a]">Customer favorites you'll absolutely love</p>
            </div>

            <a 
                href="{{ route('search') }}" 
                class="inline-flex items-center rounded-full border border-[#d3b58f] px-5 py-2 text-sm font-medium text-[#3b241a] transition-colors hover:bg-[#c79c68] hover:text-[#2b1a14]"
            >
                View All Products
            </a>
        </div>

        {{-- GRID PRODUK --}}
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            @foreach ($bestSellers as $product)
                @php
                    $inStock = $product->hasSufficientStock();
                    $productUrl = route('product.show', $product->slug);
                @endphp

                <div class="group relative w-full bg-white shadow-md rounded-2xl overflow-hidden border border-[#f1e8df] transition-shadow duration-200 hover:shadow-lg cursor-pointer flex flex-col h-full"
                     data-product-url="{{ $productUrl }}">

                    {{-- GAMBAR PRODUK --}}
                    <div class="h-56 overflow-hidden pointer-events-none">
                        <img 
                            src="{{ asset('storage/' . ($product->gambar ?? 'placeholder.jpg')) }}" 
                            alt="{{ $product->nama }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                        >
                    </div>

                    {{-- BODY CARD --}}
                    <div class="p-4 flex flex-col flex-1">
                        
                        <div class="flex flex-col gap-2">

                        {{-- Status --}}
                        <p class="text-xs flex items-center gap-1.5 {{ $inStock ? 'text-[#7d6b5c]' : 'text-[#8b5a2b]' }}">
                            <span class="inline-block w-2.5 h-2.5 rounded-full {{ $inStock ? 'bg-green-600' : 'bg-red-500' }}"></span>
                            {{ $inStock ? 'In Stock' : 'Out Stock' }}
                        </p>

                        {{-- Judul --}}
                        <h3 class="font-semibold text-[#3b241a] mt-1">
                            {{ $product->nama }}
                        </h3>
                        </div>

                        <div class="mt-auto flex flex-col gap-3">
                            {{-- Harga --}}
                            <p class="text-sm text-[#3b241a]">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </p>

                            {{-- BUTTON --}}
                            <button 
                                type="button"
                                data-add-btn
                                data-product-id="{{ $product->id }}"
                                class="w-full flex items-center justify-center gap-2 text-white text-sm py-2 rounded-xl transition {{ $inStock ? 'bg-[#3b241a] hover:bg-[#2c1c14]' : 'bg-gray-400 cursor-not-allowed' }}"
                                {{ $inStock ? '' : 'disabled' }}
                            >
                                <x-heroicon-o-shopping-cart class="w-4 h-4" />
                                Add
                            </button>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

    </div>
</section>

@push('scripts')
@endpush

