<div class="bg-[#FFF9F4] min-h-screen">
    <div class="w-full bg-white border-b border-[rgb(241,230,220)] sticky top-0 z-30">
        <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 h-16 flex items-center gap-3">
            <a href="{{ route('cart.index') }}"
               class="inline-flex items-center justify-center gap-2 text-sm font-medium text-[#3b241a] h-10 px-3 rounded-full transition hover:bg-[#d3b58f] hover:text-[#3b241a]">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.6665 12.6667L5.33317 8.33333L9.6665 4" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="hidden sm:inline">Back to Cart</span>
                <span class="sr-only sm:not-sr-only sm:hidden">Back</span>
            </a>
            <p class="flex-1 text-center text-sm font-semibold text-[#3b241a] truncate">Checkout</p>
            <span class="w-10 sm:w-[120px] flex-shrink-0"></span>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-8 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-[1.15fr,0.85fr] gap-6 items-start">
            <div class="space-y-6">
                <div class="flex items-center gap-3 text-sm text-[#6f4c3b]">
                    <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-full flex items-center justify-center {{ $step >= 1 ? 'bg-[#7a4b24] text-white' : 'bg-[#f1e8df] text-[#6f4c3b]' }}">1</div>
                        <span class="{{ $step >= 1 ? 'text-[#3b241a] font-semibold' : '' }}">Customer</span>
                    </div>
                    <span class="h-[1px] w-10 bg-[#e4d6c6]"></span>
                    <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-full flex items-center justify-center {{ $step >= 2 ? 'bg-[#7a4b24] text-white' : 'bg-[#f1e8df] text-[#6f4c3b]' }}">2</div>
                        <span class="{{ $step >= 2 ? 'text-[#3b241a] font-semibold' : '' }}">Shipping</span>
                    </div>
                    <span class="h-[1px] w-10 bg-[#e4d6c6]"></span>
                    <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-full flex items-center justify-center {{ $step >= 3 ? 'bg-[#7a4b24] text-white' : 'bg-[#f1e8df] text-[#6f4c3b]' }}">3</div>
                        <span class="{{ $step >= 3 ? 'text-[#3b241a] font-semibold' : '' }}">Payment</span>
                    </div>
                    <span class="h-[1px] w-10 bg-[#e4d6c6]"></span>
                    <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-full flex items-center justify-center {{ $step >= 4 ? 'bg-[#7a4b24] text-white' : 'bg-[#f1e8df] text-[#6f4c3b]' }}">4</div>
                        <span class="{{ $step >= 4 ? 'text-[#3b241a] font-semibold' : '' }}">Confirm</span>
                    </div>
                </div>

                @if ($step === 1)
                    <form wire:submit.prevent="nextStep" class="space-y-6">
                        @include('livewire.checkout.steps.step-1')
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-[#7a4b24] text-white px-6 py-3 text-sm font-semibold hover:bg-[#693f1d] transition"
                                    wire:loading.attr="disabled">
                                Continue to Shipping
                            </button>
                        </div>
                    </form>
                @elseif ($step === 2)
                    <form wire:submit.prevent="nextStep" class="space-y-6">
                        @include('livewire.checkout.steps.step-2')
                        <div class="flex justify-between">
                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] px-5 py-3 text-sm font-semibold hover:bg-[#f5ece3] transition"
                                    wire:click="previousStep">
                                Back
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-[#7a4b24] text-white px-6 py-3 text-sm font-semibold hover:bg-[#693f1d] transition"
                                    wire:loading.attr="disabled">
                                Continue to Payment
                            </button>
                        </div>
                    </form>
                @elseif ($step === 3)
                    <form wire:submit.prevent="nextStep" class="space-y-6">
                        @include('livewire.checkout.steps.step-3')
                        <div class="flex justify-between">
                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] px-5 py-3 text-sm font-semibold hover:bg-[#f5ece3] transition"
                                    wire:click="previousStep">
                                Back
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-[#7a4b24] text-white px-6 py-3 text-sm font-semibold hover:bg-[#693f1d] transition"
                                    wire:loading.attr="disabled">
                                Confirm Payment
                            </button>
                        </div>
                    </form>
                @else
                    <form wire:submit.prevent="placeOrder" class="space-y-6">
                        @include('livewire.checkout.steps.step-4')
                        <div class="flex justify-between">
                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] px-5 py-3 text-sm font-semibold hover:bg-[#f5ece3] transition"
                                    wire:click="previousStep">
                                Back
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-full bg-[#7a4b24] text-white px-6 py-3 text-sm font-semibold hover:bg-[#693f1d] transition"
                                    wire:loading.attr="disabled">
                                <span>Place Order</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4 sticky top-24">
                <h2 class="text-sm font-semibold text-[#3b241a]">Order Summary</h2>

                <div class="space-y-4">
                    @foreach ($cartItems as $item)
                        <div class="flex items-start gap-3">
                            <div class="h-14 w-14 rounded-xl overflow-hidden bg-[#f9f2eb] border border-[#f1e8df]">
                                <img src="{{ $item['image_url'] ?? 'https://via.placeholder.com/80x80' }}"
                                     alt="{{ $item['name'] }}"
                                     class="h-full w-full object-cover">
                            </div>
                            <div class="flex-1 text-sm text-[#3b241a]">
                                <p class="font-semibold">{{ $item['name'] }}</p>
                                <p class="text-[#6f4c3b]">Qty: {{ $item['quantity'] }}</p>
                            </div>
                            <div class="text-sm font-semibold text-[#3b241a]">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2 text-sm text-[#6f4c3b]">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span class="text-[#3b241a]">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Shipping Fee</span>
                        <span class="text-[#3b241a]">{{ $wilayah_pengiriman_id ? 'Rp '. number_format($ongkir, 0, ',', '.') : 'Select zone' }}</span>
                    </div>
                </div>

                <div class="border-t border-[#f1e8df] pt-3">
                    <div class="flex items-center justify-between text-base font-semibold text-[#3b241a]">
                        <span>Total</span>
                        <span>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="text-xs text-[#6f4c3b] space-y-2">
                    @guest
                        <p>Checking out as guest. We will send order info to your email.</p>
                    @else
                        <p>Signed in as {{ auth()->user()->email ?? 'customer' }}.</p>
                    @endguest
                    <p>By placing your order, you agree to our Terms &amp; Conditions.</p>
                </div>
            </div>
        </div>
    </div>
</div>
