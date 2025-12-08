@extends('layouts.app')

@section('content')
<div class="bg-[#FFF9F4] min-h-screen">
    <div class="w-full bg-white border-b border-[#e9dfd4]">
        <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-4 flex items-center justify-between">
            <a href="{{ url()->previous() ?: route('landing') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-[#3b241a] hover:text-[#7a4b24] transition">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.6665 12.6667L5.33317 8.33333L9.6665 4" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Continue Shopping
            </a>
            <p class="text-sm font-medium text-[#3b241a]">Shopping Cart</p>
            <span class="w-[120px]"></span>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr,0.9fr] gap-6 items-start">
            {{-- LEFT: CART ITEMS --}}
            <div class="space-y-4">
                @forelse ($cartItems as $item)
                    <div class="bg-white border border-[#f1e8df] rounded-xl shadow-sm px-4 py-3 flex gap-4 items-center">
                        <div class="h-20 w-20 rounded-lg overflow-hidden bg-[#f9f2eb] border border-[#f1e8df]">
                            <img src="{{ $item->image_url ?? 'https://via.placeholder.com/120x120' }}"
                                 alt="{{ $item->name }}"
                                 class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-[#3b241a]">{{ $item->name }}</p>
                            <p class="text-sm text-[#6f4c3b]">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button"
                                    class="h-9 w-9 flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] hover:bg-[#f5ece3] transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    data-qty-btn
                                    data-id="{{ $item->id }}"
                                    data-action="decrease"
                                    data-update-url="{{ url('/cart/update/' . $item->id) }}">
                                <span class="sr-only">Decrease</span>
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.3335 8H12.6668" stroke="#3B241A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <span class="w-8 text-center text-sm font-medium text-[#3b241a]" data-qty-display data-id="{{ $item->id }}">{{ $item->quantity }}</span>
                            <button type="button"
                                    class="h-9 w-9 flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] hover:bg-[#f5ece3] transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    data-qty-btn
                                    data-id="{{ $item->id }}"
                                    data-action="increase"
                                    data-update-url="{{ url('/cart/update/' . $item->id) }}">
                                <span class="sr-only">Increase</span>
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.3335 8H12.6668" stroke="#3B241A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 3.33334V12.6667" stroke="#3B241A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <button type="button"
                                class="text-[#b3261e] hover:text-[#8f1f18] transition"
                                data-remove-btn
                                data-id="{{ $item->id }}"
                                data-remove-url="{{ url('/cart/remove/' . $item->id) }}">
                            <span class="sr-only">Remove</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.8335 4.66669H12.1668" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.50016 4.66667V3.66667C5.50016 3.29848 5.79697 3.00167 6.16516 3.00167H9.8335C10.2017 3.00167 10.4985 3.29848 10.4985 3.66667V4.66667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.3335 7.33333V11.3333" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.6665 7.33333V11.3333" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4.6665 4.66669H11.3332V12C11.3332 12.7364 10.7364 13.3334 9.99984 13.3334H5.99984C5.26345 13.3334 4.6665 12.7364 4.6665 12V4.66669Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="bg-white border border-[#f1e8df] rounded-xl shadow-sm px-4 py-6 text-center text-sm text-[#6f4c3b]">
                        Your cart is empty.
                    </div>
                @endforelse
            </div>

            {{-- RIGHT: ORDER SUMMARY --}}
            <div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
                <h2 class="text-sm font-semibold text-[#3b241a]">Order Summary</h2>
                <div class="space-y-2 text-sm text-[#6f4c3b]">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="text-[#3b241a]">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Delivery Fee</span>
                        <span class="text-[#3b241a]">Rp {{ number_format($deliveryFee ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="border-t border-[#f1e8df] pt-3">
                    <div class="flex items-center justify-between text-base font-semibold text-[#3b241a]">
                        <span>Total</span>
                        <span>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <a href="{{ url('/checkout') }}" class="block w-full text-center rounded-full bg-[#7a4b24] text-white py-3 text-sm font-semibold hover:bg-[#693f1d] transition">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('landing') }}" class="block w-full text-center rounded-full border border-[#e4d6c6] text-[#3b241a] py-3 text-sm font-medium hover:bg-[#f5ece3] transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
