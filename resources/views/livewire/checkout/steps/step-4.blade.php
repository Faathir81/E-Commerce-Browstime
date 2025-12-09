@php
    $selectedZone = collect($shippingZones)->firstWhere('id', $wilayah_pengiriman_id);
    $selectedMethod = collect($paymentMethods)->firstWhere('kode', $paymentMethod);
@endphp

<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-5">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-[#3b241a]">Review Your Order</h3>
            <p class="text-xs text-[#6f4c3b]">Pastikan detail pelanggan, pengiriman, dan pembayaran sudah benar.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-[#3b241a]">
        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Customer</p>
            <p class="text-sm font-semibold">{{ $nama_penerima }}</p>
            <p class="text-[#6f4c3b]">{{ $no_hp }}</p>
            @if ($email)
                <p class="text-[#6f4c3b]">{{ $email }}</p>
            @endif
        </div>

        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Shipping</p>
            <p class="text-sm font-semibold">{{ $selectedZone['nama'] ?? 'Not selected' }}</p>
            <p class="text-[#6f4c3b] leading-snug whitespace-pre-line">{{ $alamat_lengkap }}</p>
            <p class="text-[#6f4c3b]">ETA (courier): {{ $etd ?? '-' }}</p>
        </div>

        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Payment</p>
            <p class="text-sm font-semibold capitalize">{{ $selectedMethod['nama'] ?? $paymentMethod ?? '-' }}</p>
            @if ($paymentMethod === 'transfer' && $akun_bank_id)
                @php $bank = collect($banks)->firstWhere('id', $akun_bank_id); @endphp
                <p class="text-[#6f4c3b]">{{ $bank['nama_bank'] ?? '' }} - {{ $bank['nomor_rekening'] ?? '' }}</p>
            @elseif ($paymentMethod === 'qris' && $qris_setting_id)
                <p class="text-[#6f4c3b]">QRIS selected</p>
            @elseif ($paymentMethod === 'midtrans')
                <p class="text-[#6f4c3b]">Midtrans checkout will be prepared.</p>
            @endif
        </div>

        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Delivery Notes</p>
            <p class="text-[#3b241a]">{{ $catatan ?: 'No notes provided' }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-2 text-sm">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[#6f4c3b]">Subtotal</span>
                <p class="text-xs text-[#9b7a64]">Termasuk semua item di keranjang</p>
            </div>
            <span class="font-semibold text-[#3b241a]">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[#6f4c3b]">Shipping Fee</span>
                <p class="text-xs text-[#9b7a64]">Berbasis berat total: {{ number_format($total_berat ?? 0, 2, ',', '.') }} kg</p>
            </div>
            <span class="font-semibold text-[#3b241a]">Rp {{ number_format($ongkir ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between text-base font-semibold text-[#3b241a] pt-2 border-t border-[#f1e8df]">
            <span>Total</span>
            <span>Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
