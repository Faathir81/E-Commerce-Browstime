@extends('layouts.app')

@section('content')
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
                <p class="text-sm text-[#6f4c3b]">Thank you for your order, {{ $customerName }}.</p>
                <p class="text-xs text-[#6f4c3b] mt-1">Order ID: <span class="font-semibold text-[#3b241a]">{{ $orderCode }}</span></p>
            </div>
            @if(session('status'))
                <div class="rounded-xl bg-[#e8f7e5] text-[#2f7a3d] px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
@endif
@if(session('error'))
    <div class="rounded-xl bg-[#fcecec] text-[#9f2c2c] px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1.2fr,0.8fr] gap-6 items-start">
            <div class="space-y-5">
                <div class="bg-white border border-[#f1e8df] rounded-3xl p-5 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2 text-sm font-semibold text-[#3b241a]">
                        <span class="h-2 w-2 rounded-full bg-[#c79c68]"></span>
                        <p>Payment Status</p>
                    </div>
                    <div class="rounded-2xl border border-[#f1e8df] bg-[#fff6ed] p-4 space-y-3">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#f2dfc8] text-[#7a4b24]">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6V12L15 15" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="1.6"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-[#9b7a64]">Payment Status</p>
                                    <div class="flex items-center gap-2 text-sm font-semibold text-[#3b241a]">
                                        <span class="inline-flex items-center rounded-full {{ $paymentInfo['badge']['bg'] }} {{ $paymentInfo['badge']['text'] }} px-3 py-1 text-xs font-semibold">{{ $paymentInfo['badge']['label'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-sm text-[#3b241a]">
                            <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#7a4b24] shadow-sm capitalize">{{ $paymentInfo['method'] }}</span>
                            @if($paymentInfo['is_transfer'] && ! empty($paymentInfo['account']))
                                @php
                                    $account = $paymentInfo['account'];
                                @endphp
                                <span class="text-[#c79c68]">•</span>
                                <span class="text-sm font-semibold">
                                    {{ $account['bank'] ?? 'Transfer Bank' }}
                                    @if(! empty($account['number']))
                                        <span class="text-[#6f4c3b] font-normal">— {{ $account['number'] }}</span>
                                    @endif
                                    @if(! empty($account['owner']))
                                        <span class="text-[#9b7a64] font-normal">a.n {{ $account['owner'] }}</span>
                                    @endif
                                </span>
                            @endif
                        </div>
                        @if($paymentInfo['method'] === 'qris' && $paymentInfo['qr_image_url'])
                            <div class="border-t border-[#f1e8df] pt-3 mt-2">
                                <p class="text-[11px] uppercase tracking-wide text-[#9b7a64] mb-2">Scan QRIS</p>
                                <div class="rounded-2xl border border-[#f1e8df] bg-white p-4 flex items-center justify-center">
                                    <img src="{{ $paymentInfo['qr_image_url'] }}" alt="QRIS" class="max-h-56 object-contain rounded-xl">
                                </div>
                            </div>
                        @endif
                        @if(! empty($paymentInfo['proof_image_url']))
                            <div class="border-t border-[#f1e8df] pt-3 mt-2">
                                <p class="text-[11px] uppercase tracking-wide text-[#9b7a64] mb-2">Payment Proof</p>
                                <div class="rounded-2xl border border-[#f1e8df] bg-white p-4 flex items-center justify-center">
                                    <img src="{{ $paymentInfo['proof_image_url'] }}" alt="Payment Proof" class="max-h-56 object-contain rounded-xl">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white border border-[#f1e8df] rounded-3xl p-5 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2 text-sm font-semibold text-[#3b241a]">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#f7ece0] text-[#c79c68]">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 16V8C20 6.89543 19.1046 6 18 6H6C4.89543 6 4 6.89543 4 8V16C4 17.1046 4.89543 18 6 18H18C19.1046 18 20 17.1046 20 16Z" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M4 10H20" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </span>
                        <p>Delivery Status</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-[#6f4c3b]">
                        <span class="inline-flex items-center rounded-full bg-[#f9e8c7] text-[#a36a0f] px-3 py-1 text-xs font-semibold whitespace-nowrap">
                            Processing Order
                        </span>
                        <span class="flex items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 8V12L14 14" stroke="#6f4c3b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#6f4c3b" stroke-width="1.5"/>
                            </svg>
                            <span class="whitespace-nowrap">Estimated Time of Arrival: {{ $deliveryInfo['eta_text'] }}</span>
                        </span>
                    </div>
                    <div class="space-y-3 text-sm text-[#3b241a]">
                        @foreach($deliverySteps as $step)
                            <div class="flex items-start gap-3 {{ $step['text_classes'] }}">
                                <span class="mt-1 h-6 w-6 rounded-full border-2 {{ $step['dot_classes'] }} flex items-center justify-center">
                                    @if($step['is_active'])
                                        <svg width="32" height="32" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_79_56)">
                                                <path d="M7.99485 14.6572C11.6743 14.6572 14.6572 11.6743 14.6572 7.99485C14.6572 4.31534 11.6743 1.33252 7.99485 1.33252C4.31534 1.33252 1.33252 4.31534 1.33252 7.99485C1.33252 11.6743 4.31534 14.6572 7.99485 14.6572Z" stroke="#FDF8F3" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M5.99609 7.99457L7.32856 9.32704L9.99349 6.66211" stroke="#FDF8F3" stroke-width="1.33247" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_79_56">
                                                    <rect width="15.9896" height="15.9896" fill="white"/>
                                                </clipPath>
                                            </defs>
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
                    @if(! empty($deliveryInfo['tracking_number']))
                        <div class="rounded-2xl border border-[#f1e8df] bg-[#fffaf5] p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white text-[#7a4b24] shadow-sm">
                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#794b24" stroke="#794b24">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <title>ionicons-v5-l</title>
                                            <polyline points="160 336 160 48 192 64 224 48 255.94 64 288.31 48 320 64 351.79 48 383.72 64 416 48 448.01 64 480 48 480 272" style="fill:none;stroke:#794b24;stroke-linejoin:round;stroke-width:32px"></polyline>
                                            <path d="M480,272V384a80,80,0,0,1-80,80h0a80,80,0,0,1-80-80V336H48a15.86,15.86,0,0,0-16,16c0,64,6.74,112,80,112H400" style="fill:none;stroke:#794b24;stroke-linejoin:round;stroke-width:32px"></path>
                                            <line x1="224" y1="144" x2="416" y2="144" style="fill:none;stroke:#794b24;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></line>
                                            <line x1="288" y1="224" x2="416" y2="224" style="fill:none;stroke:#794b24;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"></line>
                                        </g>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-[#3b241a]">Receipt Information</p>
                                    <p class="text-xs text-[#6f4c3b]">
                                        {{ $deliveryInfo['is_shipped'] ? 'Order is on the way.' : 'Tracking number is available.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-sm text-[#3b241a] space-y-1 sm:text-right">
                                <p class="font-semibold">Tracking: {{ $deliveryInfo['tracking_number'] }}</p>
                                @if(! empty($deliveryInfo['tracking_url']))
                                    <a href="{{ $deliveryInfo['tracking_url'] }}" target="_blank" class="inline-flex items-center gap-1 text-xs sm:text-sm text-[#7a4b24] font-semibold hover:text-[#5f3817]">
                                        Track here →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($canConfirmCompletion)
                        <form method="POST" action="{{ route('order.confirm', ['kode' => $orderCode]) }}" class="pt-2">
                            @csrf
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#2f7a3d] text-white px-5 py-3 text-sm font-semibold hover:bg-[#256531] transition">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Konfirmasi Terima Barang
                            </button>
                        </form>
                    @elseif($isCompleted)
                        <div class="rounded-2xl bg-[#e8f7e5] text-[#2f7a3d] px-4 py-3 text-sm font-semibold">
                            Pesanan sudah selesai dikonfirmasi.
                        </div>
                    @endif
                    <div class="pt-2 text-sm text-[#3b241a] space-y-1 border-t border-[#f1e8df] mt-2">
                        <p class="font-semibold">Delivery Address:</p>
                        <div class="text-[#6f4c3b] space-y-1">
                            <p class="font-semibold text-[#3b241a]">{{ $customerName }}</p>
                            @if($fullAddress)
                                <p class="text-xs text-[#9b7a64]">Complete: {{ $fullAddress }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#f1e8df] rounded-3xl p-5 space-y-4 shadow-sm w-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-[#3b241a]">Order Summary</p>
                        <p class="text-xs text-[#6f4c3b]">Item details and cost calculations</p>
                    </div>
                    <span class="rounded-full bg-[#fff3e6] text-[#7a4b24] px-3 py-1 text-xs font-semibold">Total {{ $orderSummary['items_count'] }} items</span>
                </div>

                <div class="rounded-2xl border border-[#f1e8df] bg-[#fffdfb] divide-y divide-[#f1e8df]">
                    @foreach($orderSummary['items'] as $detail)
                        <div class="grid grid-cols-[1fr,auto] gap-3 p-3">
                            <div>
                                <p class="text-sm font-semibold text-[#3b241a]">{{ $detail['product_name'] }}</p>
                                <p class="text-xs text-[#6f4c3b]">Price</p>
                            </div>
                            <div class="text-right text-sm text-[#3b241a]">
                                <p>x{{ $detail['qty'] }}</p>
                                <p class="text-xs text-[#6f4c3b]">Rp {{ $detail['subtotal_display'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-2xl border border-[#f1e8df] bg-[#fffaf5] p-4 space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-[#6f4c3b]">Subtotal</span>
                        <span class="font-semibold text-[#3b241a]">Rp {{ $orderSummary['subtotal_display'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#6f4c3b]">Shipping Fee</span>
                        <span class="font-semibold text-[#3b241a]">Rp {{ $orderSummary['shipping_display'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-base font-semibold text-[#3b241a] pt-2 border-t border-[#f1e8df]">
                        <span>Total</span>
                        <span>Rp {{ $orderSummary['total_display'] }}</span>
                    </div>
                </div>

                <div class="pt-1 space-y-2">
                    <a href="{{ route('landing') }}"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#7a4b24] text-white px-5 py-3 text-sm font-semibold hover:bg-[#693f1d] transition">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 9.99992L12 4L21 9.99992" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 10.9999V19.9999H19V10.9999" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 15.9999H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Back to Home
                    </a>

                    <div class="rounded-2xl bg-[#f8f1e7] px-3 py-2 text-xs text-[#6f4c3b] text-center">
                        Order confirmation has been sent to {{ $confirmationEmail }}
                    </div>
                </div>
            </div>
        </div>

        <x-review-section
            :pesanan="$pesanan"
            :order-code="$orderCode"
            :review-permissions="$reviewPermissions"
            :guest-email-for-review="$guestEmailForReview"
            :is-completed="$isCompleted"
        />
    </div>
</div>
@endsection
