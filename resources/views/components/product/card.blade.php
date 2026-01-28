@props(['product'])

<div class="group relative w-full bg-white shadow-md rounded-2xl overflow-hidden border border-[#f1e8df] transition-shadow duration-200 hover:shadow-lg flex flex-col cursor-pointer h-full"
     data-product-url="{{ $product->url }}">
    <a href="{{ $product->url }}" class="block pointer-events-none">
        <div class="h-56 overflow-hidden">
            <img src="{{ $product->image_url }}"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                 alt="{{ $product->name }}">
        </div>
    </a>

    <div class="p-4 flex-1 flex flex-col gap-2">
        <p class="text-xs flex items-center gap-1.5 {{ $product->stock_badge['text_class'] }}">
            <span class="{{ $product->stock_badge['dot_class'] }}" aria-hidden="true"></span>
            {{ $product->stock_badge['label'] }}
        </p>

        <a href="{{ $product->url }}" class="block mt-1 pointer-events-none">
            <h3 class="font-semibold text-[#3b241a] truncate">
                {{ $product->name }}
            </h3>
        </a>

        <p class="text-xs text-[#6f4c3b] mt-1">
            {{ $product->category_name }}
        </p>
    </div>

    <div class="px-4 pb-4 pt-3 mt-auto flex flex-col gap-3">
        <p class="text-sm text-[#3b241a]">
            Rp {{ $product->formatted_price }}
        </p>

        <button type="button"
                data-add-btn
                data-product-id="{{ $product->id }}"
                class="{{ $product->add_button_classes }}"
                @disabled(! $product->in_stock)>
            <x-heroicon-o-shopping-cart class="w-4 h-4" />
            Tambah
        </button>
    </div>
</div>
