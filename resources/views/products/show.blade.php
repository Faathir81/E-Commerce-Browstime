@extends('layouts.app')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-14 py-8 lg:py-10 text-[#3b241a]">

    <div class="grid grid-cols-1 lg:grid-cols-[0.9fr,1.1fr] gap-8 lg:gap-12 items-start">
        {{-- IMAGE PANEL --}}
        <div class="relative bg-white rounded-3xl overflow-hidden shadow-lg max-w-[520px] lg:sticky lg:top-24 self-start">
            <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : 'https://via.placeholder.com/800x800' }}"
                 alt="{{ $product->nama }}"
                 class="w-full h-full max-h-[75vh] object-cover">
        </div>

        {{-- DETAILS PANEL --}}
        <div class="bg-white/70 rounded-3xl p-6 lg:p-8 shadow-sm border border-[#f1e8df]">
            <div class="flex flex-wrap items-center gap-2 mb-2 text-xs">
                @if(optional($product->kategori)->nama)
                    <span class="rounded-full bg-[#f1e8df] text-[#3b241a] px-3 py-1">{{ $product->kategori->nama }}</span>
                @endif
                @if($product->hasSufficientStock())
                    <span class="rounded-full bg-[#e8f7e5] text-[#2f7a3d] px-3 py-1">In Stock</span>
                @else
                    <span class="rounded-full bg-[#fdecea] text-[#b3261e] px-3 py-1">Out Stock</span>
                @endif
            </div>

            <h1 class="text-2xl lg:text-3xl font-semibold mb-2">{{ $product->nama }}</h1>
            <div class="text-3xl font-bold mb-4">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>

            <div class="space-y-3 mb-6">
                <h3 class="font-semibold text-base">Description</h3>
                <p class="text-sm leading-relaxed text-[#5a4135]">
                    {{ $product->deskripsi ?? 'Delicious handcrafted product made with premium ingredients.' }}
                </p>
            </div>

            <div class="rounded-2xl border border-[#f1e8df] bg-white p-4 lg:p-5 flex flex-col gap-4 mb-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#3b241a]">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 5V10L13.3333 11.6667" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 18.3334C14.6024 18.3334 18.3334 14.6025 18.3334 10.0001C18.3334 5.39771 14.6024 1.66675 10 1.66675C5.39765 1.66675 1.66669 5.39771 1.66669 10.0001C1.66669 14.6025 5.39765 18.3334 10 18.3334Z" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs text-[#6f4c3b]">Delivery Time</p>
                            <p class="font-semibold">{{ $product->waktu_produksi ?? '1-2 Days' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f4e9dc] text-[#3b241a]">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.16667 18.1084C9.42003 18.2547 9.70744 18.3317 10 18.3317C10.2926 18.3317 10.58 18.2547 10.8333 18.1084L16.6667 14.7751C16.9198 14.6289 17.13 14.4188 17.2763 14.1658C17.4225 13.9127 17.4997 13.6257 17.5 13.3334V6.66675C17.4997 6.37448 17.4225 6.08742 17.2763 5.83438C17.13 5.58134 16.9198 5.37122 16.6667 5.22508L10.8333 1.89175C10.58 1.74547 10.2926 1.66846 10 1.66846C9.70744 1.66846 9.42003 1.74547 9.16667 1.89175L3.33333 5.22508C3.08022 5.37122 2.86998 5.58134 2.72372 5.83438C2.57745 6.08742 2.5003 6.37448 2.5 6.66675V13.3334C2.5003 13.6257 2.57745 13.9127 2.72372 14.1658C2.86998 14.4188 3.08022 14.6289 3.33333 14.7751L9.16667 18.1084Z" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 18.3333V10" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2.7417 5.83325L10 9.99992L17.2584 5.83325" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.25 3.55835L13.75 7.85002" stroke="#8B4513" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs text-[#6f4c3b]">Available</p>
                            <p class="font-semibold">
                                {{ $availableUnits !== null ? $availableUnits . ' units' : 'Ready to order' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <h3 class="font-semibold text-base">Quantity</h3>
                <div class="flex items-center gap-3">
                    <button type="button"
                            data-qty-minus
                            class="w-10 h-10 flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] hover:bg-[#f5ece3] transition">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.33118 7.99463H12.6584" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <input type="number"
                           value="1"
                           min="1"
                           inputmode="numeric"
                           pattern="[0-9]*"
                           data-qty-input
                           data-max="{{ $availableUnits !== null ? $availableUnits : 100 }}"
                           class="no-spinner w-16 h-10 text-center border border-[#e4d6c6] rounded-lg focus:ring-[#bb936c] focus:border-[#bb936c]" />
                    <button type="button"
                            data-qty-plus
                            class="w-10 h-10 flex items-center justify-center rounded-full border border-[#e4d6c6] text-[#3b241a] hover:bg-[#f5ece3] transition">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.33118 7.99463H12.6584" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.99481 3.33105V12.6583" stroke="#3E2723" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <span class="text-xs text-[#6f4c3b]">Max: {{ $availableUnits !== null ? $availableUnits : '100' }} units</span>
                </div>
            </div>

            <button type="button"
                    class="w-full mt-2 inline-flex items-center justify-center gap-3 rounded-full bg-[#7a4b24] text-white py-3 text-sm font-semibold hover:bg-[#693f1d] transition">
                <x-heroicon-o-shopping-cart class="w-5 h-5" />
                Add to Cart
            </button>

            @if(optional($product->resep)->detail && $product->resep->detail->isNotEmpty())
                <div class="mt-8 rounded-2xl border border-[#f1e8df] bg-white p-5">
                    <h3 class="font-semibold text-base mb-1">Ingredients Used</h3>
                    <p class="text-xs text-[#6f4c3b] mb-4">Premium quality ingredients per batch</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($product->resep->detail as $detail)
                            <div class="flex flex-col gap-1 rounded-xl bg-[#f9f2eb] px-4 py-3 border border-[#f1e8df]">
                                <div class="flex items-start gap-2 text-sm font-semibold text-[#3b241a]">
                                    <span class="mt-1 h-2 w-2 rounded-full bg-[#7a4b24]"></span>
                                    <p class="truncate">{{ optional($detail->bahan)->nama ?? 'Bahan' }}</p>
                                </div>
                                <p class="text-sm text-[#6f4c3b] ml-4">
                                    {{ $detail->jumlah ?? '-' }} {{ optional($detail->satuan)->symbol ?? optional($detail->satuan)->nama }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.no-spinner::-webkit-outer-spin-button,
.no-spinner::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.no-spinner {
    -moz-appearance: textfield;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('[data-qty-input]');
    const minus = document.querySelector('[data-qty-minus]');
    const plus = document.querySelector('[data-qty-plus]');
    if (!input || !minus || !plus) return;

    const min = 1;
    const maxRaw = parseInt(input.dataset.max, 10);
    const max = Number.isNaN(maxRaw) ? Infinity : maxRaw;
    const clamp = (val) => {
        const n = Number.isNaN(val) ? min : val;
        return Math.min(max, Math.max(min, n));
    };

    const setValue = (val) => {
        const next = clamp(parseInt(val, 10));
        input.value = next;
    };

    minus.addEventListener('click', () => setValue(parseInt(input.value, 10) - 1));
    plus.addEventListener('click', () => setValue(parseInt(input.value, 10) + 1));
});
</script>
@endpush
@endsection
