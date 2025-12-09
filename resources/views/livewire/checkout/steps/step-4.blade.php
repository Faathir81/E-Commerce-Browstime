@php
    $selectedZone = collect($shippingZones)->firstWhere('id', $wilayah_pengiriman_id);
    $selectedMethod = collect($paymentMethods)->firstWhere('kode', $paymentMethod);
@endphp

<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
    <h3 class="text-base font-semibold text-[#3b241a]">Review Your Order</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-[#3b241a]">
        <div class="space-y-2">
            <p class="text-xs text-[#6f4c3b]">Customer</p>
            <p class="font-semibold">{{ $nama_penerima }}</p>
            <p class="text-[#6f4c3b]">{{ $no_hp }}</p>
            @if ($email)
                <p class="text-[#6f4c3b]">{{ $email }}</p>
            @endif
        </div>
        <div class="space-y-2">
            <p class="text-xs text-[#6f4c3b]">Shipping</p>
            <p class="font-semibold">{{ $selectedZone['nama'] ?? 'Not selected' }}</p>
            <p class="text-[#6f4c3b] leading-snug whitespace-pre-line">{{ $alamat_lengkap }}</p>
            <p class="text-[#6f4c3b]">ETA: {{ $eta ? \Carbon\Carbon::parse($eta)->format('d M Y, H:i') : '-' }}</p>
        </div>
        <div class="space-y-2">
            <p class="text-xs text-[#6f4c3b]">Payment</p>
            <p class="font-semibold capitalize">{{ $selectedMethod['nama'] ?? $paymentMethod ?? '-' }}</p>
            @if ($paymentMethod === 'transfer' && $akun_bank_id)
                @php $bank = collect($banks)->firstWhere('id', $akun_bank_id); @endphp
                <p class="text-[#6f4c3b]">{{ $bank['nama_bank'] ?? '' }} - {{ $bank['nomor_rekening'] ?? '' }}</p>
            @elseif ($paymentMethod === 'qris' && $qris_setting_id)
                <p class="text-[#6f4c3b]">QRIS selected</p>
            @elseif ($paymentMethod === 'midtrans')
                <p class="text-[#6f4c3b]">Midtrans checkout will be prepared.</p>
            @endif
        </div>
        <div class="space-y-2">
            <p class="text-xs text-[#6f4c3b]">Delivery Notes</p>
            <p class="text-[#6f4c3b]">{{ $catatan ?: 'No notes provided' }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-2 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-[#6f4c3b]">Subtotal</span>
            <span class="font-semibold text-[#3b241a]">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-[#6f4c3b]">Shipping Fee</span>
            <span class="font-semibold text-[#3b241a]">Rp {{ number_format($ongkir ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between text-base font-semibold text-[#3b241a] pt-2 border-t border-[#f1e8df]">
            <span>Total</span>
            <span>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
