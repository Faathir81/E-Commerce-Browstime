@extends('layouts.app')

@section('content')
@php
    $firstItem = $pesanan?->detailPesanans->first();
    $productName = $firstItem?->produk?->nama ?? ($firstItem?->produk_id ? 'Produk #' . $firstItem->produk_id : '-');
    $qty = $firstItem?->qty ?? 0;
    $paymentMethod = $pembayaran?->metode ?? '-';
    $qrImage = $pembayaran?->qrisSetting?->gambar_qris ?? null;
    $statusMap = [
        'paid' => 'confirmed',
        'produksi' => 'baking',
        'dikirim' => 'delivery',
        'selesai' => 'delivered',
    ];
    $statusOrder = ['confirmed', 'baking', 'delivery', 'delivered'];
    $currentStep = $statusMap[$pesanan?->status ?? ''] ?? null;
    $currentIndex = $currentStep ? array_search($currentStep, $statusOrder) : false;
@endphp

<div class="bg-[#FFF9F4] min-h-screen">
    <div class="mx-auto max-w-screen-2xl px-6 sm:px-8 lg:px-14 py-10 space-y-6">

        <div class="bg-white/80 border border-[#f1e8df] rounded-3xl shadow-sm p-6 text-center space-y-3">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#e8f7e5] text-[#2f7a3d] mx-auto">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-[#3b241a]">Order Confirmed!</h1>
                <p class="text-sm text-[#6f4c3b]">Thank you for your order, {{ auth()->user()->name ?? 'customer' }}.</p>
                <p class="text-xs text-[#6f4c3b] mt-1">Order ID: <span class="font-semibold text-[#3b241a]">{{ $pesanan?->kode ?? $kode ?? '-' }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1.2fr,0.8fr] gap-6">
            <div class="space-y-5">
                <div class="bg-white border border-[#f1e8df] rounded-3xl p-5 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2 text-sm font-semibold text-[#3b241a]">
                        <span class="h-2 w-2 rounded-full bg-[#c79c68]"></span>
                        <p>Payment Status</p>
                    </div>
                    @php
                        $paymentStatus = $pembayaran?->status ?? 'pending';
                        $statusBadge = [
                            'pending' => ['label' => 'Waiting Payment', 'bg' => 'bg-[#fff3d4]', 'text' => 'text-[#a36a0f]'],
                            'menunggu_verifikasi' => ['label' => 'Waiting Verification', 'bg' => 'bg-[#fff3d4]', 'text' => 'text-[#a36a0f]'],
                            'valid' => ['label' => 'Confirmed', 'bg' => 'bg-[#e8f7e5]', 'text' => 'text-[#2f7a3d]'],
                            'invalid' => ['label' => 'Declined', 'bg' => 'bg-[#fdecea]', 'text' => 'text-[#b3261e]'],
                        ][$paymentStatus] ?? ['label' => 'Waiting Payment', 'bg' => 'bg-[#fff3d4]', 'text' => 'text-[#a36a0f]'];
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} px-3 py-1 text-xs font-semibold">{{ $statusBadge['label'] }}</span>
                        <p class="text-xs text-[#6f4c3b] capitalize">{{ $paymentMethod }}</p>
                    </div>
                    @if($paymentMethod === 'qris' && $qrImage)
                        <div class="rounded-2xl border border-[#f1e8df] bg-[#fffaf5] p-4 flex items-center justify-center">
                            <img src="{{ Storage::disk('public')->url($qrImage) }}" alt="QRIS" class="max-h-56 object-contain rounded-xl">
                        </div>
                    @endif
                </div>

                <div class="bg-white border border-[#f1e8df] rounded-3xl p-5 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2 text-sm font-semibold text-[#3b241a]">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#f7ece0] text-[#c79c68]">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 16V8C20 6.89543 19.1046 6 18 6H6C4.89543 6 4 6.89543 4 8V16C4 17.1046 4.89543 18 6 18H18C19.1046 18 20 17.1046 20 16Z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M4 10H20" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </span>
                        <p>Delivery Status</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-[#6f4c3b]">
                        <span class="inline-flex items-center rounded-full bg-[#f9e8c7] text-[#a36a0f] px-3 py-1 text-xs font-semibold">Processing Order</span>
                        <span class="flex items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 8V12L14 14" stroke="#6f4c3b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#6f4c3b" stroke-width="1.5"/>
                            </svg>
                            Estimated delivery: {{ $pesanan?->eta ? \Carbon\Carbon::parse($pesanan->eta)->format('d M Y, H:i') : '1-2 days' }}
                        </span>
                    </div>
                    <div class="space-y-3 text-sm text-[#3b241a]">
                        @php
                            $steps = [
                                ['key' => 'confirmed', 'title' => 'Order Confirmed', 'desc' => 'Your order has been received'],
                                ['key' => 'baking', 'title' => 'Baking in Progress', 'desc' => "We're preparing your order"],
                                ['key' => 'delivery', 'title' => 'Out for Delivery', 'desc' => 'Your order is on the way'],
                                ['key' => 'delivered', 'title' => 'Delivered', 'desc' => 'Package arrived'],
                            ];
                        @endphp
                        @foreach($steps as $idx => $step)
                            @php
                                $active = $currentIndex !== false && $idx <= $currentIndex;
                                $dotClasses = $active
                                    ? 'border-[#7a4b24] bg-[#7a4b24]'
                                    : 'border-[#d9c7b7] bg-[#f6eee4]';
                            @endphp
                            <div class="flex items-start gap-3 {{ $active ? 'text-[#3b241a]' : 'text-[#6f4c3b]' }}">
                                <span class="mt-1 h-4 w-4 rounded-full border-2 {{ $dotClasses }} flex items-center justify-center">
                                    @if($active)
                                        <svg width="10" height="10" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.00016 8.66667L7.3335 10L10.0002 6.66667" stroke="#fff" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </span>
                                <div>
                                    <p class="font-semibold">{{ $step['title'] }}</p>
                                    <p class="text-xs text-[#6f4c3b]">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pt-2 text-sm text-[#3b241a] space-y-1 border-t border-[#f1e8df] mt-2">
                        <p class="font-semibold">Delivery Address:</p>
                        <p class="text-[#6f4c3b]">{{ $pesanan?->alamat_lengkap ?? 'Your shipping address' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#f1e8df] rounded-3xl p-5 space-y-4 shadow-sm">
                <div class="text-sm font-semibold text-[#3b241a]">Order Summary</div>

                <div class="space-y-1 text-sm text-[#3b241a]">
                    @foreach($pesanan?->detailPesanans ?? [] as $detail)
                        <div class="flex items-center justify-between">
                            <span class="text-[#3b241a]">{{ $detail->produk->nama ?? 'Produk' }}</span>
                            <span class="text-[#3b241a]">x{{ $detail->qty }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-[#6f4c3b]">
                            <span>Harga</span>
                            <span>Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <hr class="border-[#f1e8df]">

                <div class="flex items-center justify-between text-base font-semibold text-[#3b241a]">
                    <span>Total</span>
                    <span>Rp {{ number_format($pesanan?->total ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="pt-1">
                    <a href="{{ route('landing') }}"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#7a4b24] text-white px-5 py-3 text-sm font-semibold hover:bg-[#693f1d] transition">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9.99992L12 4L21 9.99992" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 10.9999V19.9999H19V10.9999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 15.9999H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back to Home
                    </a>
                </div>

                <div class="rounded-2xl bg-[#f8f1e7] px-3 py-2 text-xs text-[#6f4c3b]">
                    Order confirmation has been sent to {{ auth()->user()->email ?? ($pesanan?->guest_email ?? '-') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
