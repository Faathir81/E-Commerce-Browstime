@extends('layouts.app')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-14 py-8 lg:py-10 text-[#3b241a]">

    <div class="grid grid-cols-1 lg:grid-cols-[0.9fr,1.1fr] gap-8 lg:gap-12 items-start">
        {{-- IMAGE PANEL --}}
        <div class="relative bg-white rounded-3xl overflow-hidden shadow-lg max-w-[520px] lg:sticky lg:top-24 self-start">
            <img src="{{ $productDetail->image_url }}"
                 alt="{{ $productDetail->name }}"
                 class="w-full h-full max-h-[75vh] object-cover">
        </div>

        {{-- DETAILS PANEL --}}
        <div class="bg-white/70 rounded-3xl p-6 lg:p-8 shadow-sm border border-[#f1e8df]">
            <div class="flex flex-wrap items-center gap-2 mb-2 text-xs">
                <x-product.category-badge :category="$product->kategori" />
                <x-product.stock-badge :in-stock="$productDetail->in_stock" />
            </div>

            <h1 class="text-2xl lg:text-3xl font-semibold mb-2">{{ $productDetail->name }}</h1>
            <div class="text-3xl font-bold mb-4">Rp {{ $productDetail->price_formatted }}</div>

            <div class="space-y-3 mb-6">
                <h3 class="font-semibold text-base">Description</h3>
                <p class="text-sm leading-relaxed text-[#5a4135]">
                    {{ $productDetail->description }}
                </p>
            </div>

            <div class="rounded-2xl border border-[#f1e8df] bg-white p-4 lg:p-5 flex flex-col gap-4 mb-6">
                <div class="flex flex-wrap gap-4 sm:gap-6 text-sm">
                    <div class="flex items-center gap-3 flex-1 min-w-[180px]">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#3b241a]">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 5V10L13.3333 11.6667" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 18.3334C14.6024 18.3334 18.3334 14.6025 18.3334 10.0001C18.3334 5.39771 14.6024 1.66675 10 1.66675C5.39765 1.66675 1.66669 5.39771 1.66669 10.0001C1.66669 14.6025 5.39765 18.3334 10 18.3334Z" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div class="leading-tight">
                            <p class="text-xs text-[#6f4c3b]">Production Time</p>
                            <p class="font-semibold">{{ $productDetail->delivery_time }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-1 min-w-[180px]">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#3b241a]">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.16667 18.1084C9.42003 18.2547 9.70744 18.3317 10 18.3317C10.2926 18.3317 10.58 18.2547 10.8333 18.1084L16.6667 14.7751C16.9198 14.6289 17.13 14.4188 17.2763 14.1658C17.4225 13.9127 17.4997 13.6257 17.5 13.3334V6.66675C17.4997 6.37448 17.4225 6.08742 17.2763 5.83438C17.13 5.58134 16.9198 5.37122 16.6667 5.22508L10.8333 1.89175C10.58 1.74547 10.2926 1.66846 10 1.66846C9.70744 1.66846 9.42003 1.74547 9.16667 1.89175L3.33333 5.22508C3.08022 5.37122 2.86998 5.58134 2.72372 5.83438C2.57745 6.08742 2.5003 6.37448 2.5 6.66675V13.3334C2.5003 13.6257 2.57745 13.9127 2.72372 14.1658C2.86998 14.4188 3.08022 14.6289 3.33333 14.7751L9.16667 18.1084Z" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 18.3333V10" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2.7417 5.83325L10 9.99992L17.2584 5.83325" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.25 3.55835L13.75 7.85002" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div class="leading-tight">
                            <p class="text-xs text-[#6f4c3b]">Available</p>
                            <p class="font-semibold">
                                {{ $productDetail->available_units_text }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <livewire:product.quantity-selector
                    :product-id="$productDetail->id"
                    :max="$productDetail->available_units ?? 100"
                    :in-stock="$productDetail->in_stock"
                />
            </div>

            <button type="button"
                    data-add-cart
                    data-product-id="{{ $productDetail->id }}"
                    class="{{ $productDetail->add_button_classes }}"
                    @disabled(! $productDetail->in_stock)>
                <x-heroicon-o-shopping-cart class="w-5 h-5" />
                Add to Cart
            </button>

        </div>
    </div>

    <div class="mt-10 lg:mt-14">
        <x-product.review-section :summary="$productReviews" />
    </div>
</div>
@endsection
