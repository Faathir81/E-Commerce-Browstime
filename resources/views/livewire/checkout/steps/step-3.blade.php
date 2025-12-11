<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
    <div>
        <h3 class="text-base font-semibold text-[#3b241a]">Payment Method</h3>
        <p class="text-xs text-[#6f4c3b]">Choose your preferred payment method.</p>
    </div>

    <div class="space-y-3">
        @foreach ($paymentMethods as $method)
            <label class="flex items-center gap-3 rounded-2xl border {{ $paymentMethod === $method['kode'] ? 'border-[#c79c68] bg-[#fff7ef]' : 'border-[#e4d6c6] bg-white' }} px-4 py-3 shadow-sm cursor-pointer transition">
                <input type="radio"
                       wire:model.live="paymentMethod"
                       value="{{ $method['kode'] }}"
                       class="text-[#7a4b24] focus:ring-[#7a4b24]">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-[#3b241a]">{{ $method['nama'] }}</p>
                    <p class="text-xs text-[#6f4c3b] capitalize">{{ $method['kode'] }}</p>
                </div>
            </label>
        @endforeach
        @error('paymentMethod') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    @if ($paymentMethod === 'transfer')
        <div class="space-y-2">
            <label class="text-xs text-[#6f4c3b]">Choose the bank account you want to transfer to *</label>
            <select wire:model="akun_bank_id"
                    class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]">
                <option value="">Select bank</option>
                @foreach ($banks as $bank)
                    <option value="{{ $bank['id'] }}">
                        {{ $bank['nama_bank'] }} — {{ $bank['nomor_rekening'] }} — {{ $bank['nama_pemilik'] ?? '' }}
                    </option>
                @endforeach
            </select>
            @error('akun_bank_id') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
        </div>
    @endif

    @if ($paymentMethod === 'qris')
        <div class="space-y-2">
            <label class="text-xs text-[#6f4c3b]">QRIS *</label>
            @if($selectedQris)
                <div class="rounded-2xl border border-[#e4d6c6] bg-white p-3 flex flex-col gap-2 items-center justify-center">
                    @if(!empty($selectedQris['gambar_qris']))
                        <div class="rounded-xl border border-[#f1e8df] bg-[#fffaf5] p-3 flex items-center justify-center">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($selectedQris['gambar_qris']) }}"
                                 alt="QRIS"
                                 class="max-h-48 object-contain">
                        </div>
                    @else
                        <p class="text-sm text-[#6f4c3b]">QRIS</p>
                    @endif
                    <p class="text-xs text-[#6f4c3b]">Scan the QR code above to pay.</p>
                </div>
                <input type="hidden" wire:model.live="qris_setting_id" value="{{ $selectedQris['id'] }}">
            @else
                <p class="text-sm text-[#b3261e]">QRIS is not available yet.</p>
            @endif
            @error('qris_setting_id') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
        </div>
    @endif

    @if (in_array($paymentMethod, ['transfer', 'qris']))
        <div class="space-y-2">
            <label class="text-xs text-[#6f4c3b]">Upload payment proof (JPG/PNG/PDF, max 5MB)</label>
            <input type="file"
                   wire:model="payment_proof"
                   accept=".jpg,.jpeg,.png,.pdf"
                   class="block w-full text-sm text-[#3b241a] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#7a4b24] file:text-white hover:file:bg-[#693f1d]">
            @error('payment_proof') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror

            @if(in_array($paymentMethod, ['transfer','qris']) && $payment_proof)
                <div class="mt-2 rounded-xl border border-[#e4d6c6] bg-[#fffaf5] p-3 space-y-2 flex flex-col items-center">
                    <p class="text-xs text-[#6f4c3b]">Payment proof preview:</p>
                    @if($isPaymentProofImage)
                        <img src="{{ $paymentProofPreviewUrl }}"
                             alt="Payment proof"
                             class="max-h-64 rounded-lg border border-[#f1e8df] object-contain">
                    @else
                        <p class="text-sm text-[#3b241a] text-center">{{ $paymentProofName }}</p>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
