<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-5">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-[#3b241a]">Tinjau Pesanan</h3>
            <p class="text-xs text-[#6f4c3b]">Pastikan data pelanggan, pengiriman, dan pembayaran sudah benar.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-[#3b241a]">
        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Pelanggan</p>
            <p class="text-sm font-semibold">{{ $nama_penerima }}</p>
            <p class="text-[#6f4c3b]">{{ $no_hp }}</p>
            @if ($email)
                <p class="text-[#6f4c3b]">{{ $email }}</p>
            @endif
        </div>

        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Pengiriman</p>
            <p class="text-sm font-semibold">{{ $selectedCityName ?? $selectedZone['nama'] ?? 'Belum dipilih' }}</p>
            <p class="text-[#6f4c3b] leading-snug whitespace-pre-line">{{ $alamat_lengkap }}</p>
            @php
                $shippingLocations = collect([$selectedDistrictName, $selectedCityName, $selectedProvinceName])
                    ->filter()
                    ->implode(', ');
            @endphp
            @if ($shippingLocations)
                <p class="text-[#6f4c3b]">{{ $shippingLocations }}</p>
            @endif
            @if ($kode_pos)
                <p class="text-[#6f4c3b]">Kode Pos {{ $kode_pos }}</p>
            @endif
            <p class="text-[#6f4c3b]">Estimasi (kurir): {{ $formattedEtd }}</p>
        </div>

        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Metode Pembayaran</p>
            <p class="text-sm font-semibold capitalize">{{ $selectedMethod['nama'] ?? $paymentMethod ?? '-' }}</p>
            @if ($paymentMethod === 'transfer' && $akun_bank_id)
                <p class="text-[#6f4c3b]">{{ $selectedBank['nama_bank'] ?? '' }} - {{ $selectedBank['nomor_rekening'] ?? '' }}</p>
            @elseif ($paymentMethod === 'qris' && $qris_setting_id)
                <p class="text-[#6f4c3b]">QRIS dipilih</p>
            @elseif ($paymentMethod === 'midtrans')
                <p class="text-[#6f4c3b]">Checkout Midtrans akan disiapkan.</p>
            @endif
        </div>

        <div class="rounded-xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-1">
            <p class="text-xs uppercase tracking-wide text-[#9b7a64]">Catatan Pengiriman</p>
            <p class="text-[#3b241a]">{{ $catatan ?: 'Tidak ada catatan.' }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-[#f1e8df] bg-[#fffdfb] p-4 space-y-2 text-sm">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[#6f4c3b]">Subtotal</span>
                <p class="text-xs text-[#9b7a64]">Termasuk semua produk di keranjang</p>
            </div>
            <span class="font-semibold text-[#3b241a]">{{ $formattedSubtotalLabel }}</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[#6f4c3b]">Ongkos Kirim</span>
                <p class="text-xs text-[#9b7a64]">{{ $formattedTotalWeightNote }}</p>
            </div>
            <span class="font-semibold text-[#3b241a]">{{ $formattedOngkirLabel }}</span>
        </div>
        <div class="flex items-center justify-between text-base font-semibold text-[#3b241a] pt-2 border-t border-[#f1e8df]">
            <span>Total</span>
            <span>{{ $formattedTotalLabel }}</span>
        </div>
    </div>
</div>
