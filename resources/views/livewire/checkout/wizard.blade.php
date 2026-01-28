<div class="bg-[#FFF9F4] min-h-screen">
    <div class="w-full bg-white border-b border-[rgb(241,230,220)] sticky top-0 z-30">
        <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 h-16 flex items-center gap-3">
            <a href="{{ route('cart.index') }}"
               class="inline-flex items-center justify-center gap-2 text-sm font-medium text-[#3b241a] h-10 px-3 rounded-full transition hover:bg-[#d3b58f] hover:text-[#3b241a]">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.6665 12.6667L5.33317 8.33333L9.6665 4" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="hidden sm:inline">Kembali ke Keranjang</span>
                <span class="sr-only sm:not-sr-only sm:hidden">Kembali</span>
            </a>
            <p class="flex-1 text-center text-sm font-semibold text-[#3b241a] truncate">Checkout</p>
            <span class="w-10 sm:w-[120px] flex-shrink-0"></span>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-8 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-[1.05fr,0.95fr] gap-6 items-start">
            <div class="space-y-6">
                <div class="overflow-x-auto pb-2 -mx-1">
                <div class="flex items-center gap-3 min-w-max text-sm text-[#6f4c3b] px-1">
                    @foreach($steps as $s)
                        <div class="flex items-center gap-2">
                            <div @class([
                                'h-7 w-7 rounded-full flex items-center justify-center',
                                'bg-[#7a4b24] text-white' => $this->isStepActive($s['index']),
                                'bg-[#f1e8df] text-[#6f4c3b]' => ! $this->isStepActive($s['index']),
                            ])>{{ $s['index'] }}</div>
                            <span @class([
                                'text-[#3b241a] font-semibold' => $this->isStepActive($s['index']),
                            ])>{{ $s['label'] }}</span>
                        </div>
                        @if(!$loop->last)
                            <span class="h-[1px] w-10 bg-[#e4d6c6]"></span>
                        @endif
                    @endforeach
                </div>
                </div>

                <x-checkout.step
                    :step="$step"
                    :step-view="$stepView"
                    :config="$stepConfig"
                    :step-data="$stepData"
                />
            </div>

            <div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4 sticky top-24">
                <h2 class="text-sm font-semibold text-[#3b241a]">Ringkasan Pesanan</h2>

                <div class="space-y-4">
                    @foreach ($displayCartItems as $item)
                        <x-checkout.summary-item :item="$item" />
                    @endforeach
                </div>

                <x-checkout.totals
                    :subtotal="$formattedSubtotal"
                    :shipping="$formattedShippingFee"
                    :total="$formattedTotal"
                />

                <div class="text-xs text-[#6f4c3b] space-y-2">
                    @guest
                        <p>Checkout sebagai tamu. Info pesanan akan dikirim ke email kamu.</p>
                    @else
                        <p>Masuk sebagai {{ auth()->user()->email ?? 'pelanggan' }}.</p>
                    @endguest
                    <p>Dengan membuat pesanan, kamu menyetujui Syarat &amp; Ketentuan kami.</p>
                </div>
            </div>
        </div>
    </div>
</div>
