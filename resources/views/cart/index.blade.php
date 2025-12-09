@extends('layouts.app')

@section('content')
<div class="bg-[#FFF9F4] min-h-screen">
    <div class="w-full bg-white border-b border-[rgb(241,230,220)] sticky top-0 z-30">
        <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 h-16 flex items-center gap-3">
            <a href="{{ route('product.all') }}"
               class="inline-flex items-center justify-center gap-2 text-sm font-medium text-[#3b241a] h-10 px-3 rounded-full transition hover:bg-[#d3b58f] hover:text-[#3b241a]">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.6665 12.6667L5.33317 8.33333L9.6665 4" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="hidden sm:inline">Continue Shopping</span>
                <span class="sr-only sm:not-sr-only sm:hidden">Back</span>
            </a>
            <p class="flex-1 text-center text-sm font-semibold text-[#3b241a] truncate">Shopping Cart</p>
            <span class="w-10 sm:w-[120px] flex-shrink-0"></span>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-[1.1fr,0.9fr] gap-6 items-start">
            {{-- LEFT: CART ITEMS --}}
            <div class="space-y-4" data-cart-items>
                @foreach ($cartItems as $item)
                    <div class="bg-white border border-[#f1e8df] rounded-xl shadow-sm px-4 py-3 flex gap-4 items-center" data-cart-item>
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
                                class="h-9 w-9 flex items-center justify-center rounded-full text-[#b3261e] hover:text-white hover:bg-[#d4183d] transition"
                                data-remove-btn
                                data-id="{{ $item->id }}"
                                data-remove-url="{{ url('/cart/remove/' . $item->id) }}">
                            <span class="sr-only">Remove</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.6665 7.33337V11.3334" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.3335 7.33337V11.3334" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12.6668 4V13.3333C12.6668 13.687 12.5264 14.0261 12.2763 14.2761C12.0263 14.5262 11.6871 14.6667 11.3335 14.6667H4.66683C4.31321 14.6667 3.97407 14.5262 3.72402 14.2761C3.47397 14.0261 3.3335 13.687 3.3335 13.3333V4" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 4H14" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.3335 4.00004V2.66671C5.3335 2.31309 5.47397 1.97395 5.72402 1.7239C5.97407 1.47385 6.31321 1.33337 6.66683 1.33337H9.3335C9.68712 1.33337 10.0263 1.47385 10.2763 1.7239C10.5264 1.97395 10.6668 2.31309 10.6668 2.66671V4.00004" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <div class="bg-white border border-[#f1e8df] rounded-xl shadow-sm px-4 py-6 text-center text-sm text-[#6f4c3b] {{ count($cartItems) > 0 ? 'hidden' : '' }}" data-empty-state>
                Your cart is empty.
            </div>

            {{-- RIGHT: ORDER SUMMARY --}}
            <div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4 sticky top-20">
                <h2 class="text-sm font-semibold text-[#3b241a]">Order Summary</h2>
                <div class="space-y-2 text-sm text-[#6f4c3b]">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="text-[#3b241a]" data-cart-subtotal>Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Delivery Fee</span>
                        <span class="text-[#3b241a]" data-cart-delivery>Rp {{ number_format($deliveryFee ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="border-t border-[#f1e8df] pt-3">
                    <div class="flex items-center justify-between text-base font-semibold text-[#3b241a]">
                        <span>Total</span>
                        <span data-cart-total>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    @php $totalQty = array_sum(session('cart', [])); @endphp
                    <a href="{{ url('/checkout') }}"
                       data-proceed-btn
                       class="block w-full text-center rounded-full bg-[#7a4b24] text-white py-3 text-sm font-semibold hover:bg-[#693f1d] transition {{ $totalQty > 0 ? '' : 'pointer-events-none opacity-60' }}">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('product.all') }}" class="block w-full text-center rounded-full border border-[#e4d6c6] text-[#3b241a] py-3 text-sm font-medium hover:bg-[#f5ece3] transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div data-cart-meta data-total-quantity="{{ array_sum(session('cart', [])) }}"></div>
</div>
@endsection
