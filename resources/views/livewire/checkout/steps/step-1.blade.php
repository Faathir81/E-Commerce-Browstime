<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
    <h3 class="text-base font-semibold text-[#3b241a]">Customer Information</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1">
            <label class="text-xs text-[#6f4c3b]">Full Name *</label>
            <input type="text"
                   wire:model.defer="nama_penerima"
                   class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                   placeholder="e.g., John Doe">
            @error('nama_penerima') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-1">
            <label class="text-xs text-[#6f4c3b]">Phone Number *</label>
            <input type="text"
                   wire:model.defer="no_hp"
                   class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                   placeholder="08xxxxxxxxxx">
            @error('no_hp') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
        </div>
        @guest
            <div class="space-y-1 md:col-span-2">
                <label class="text-xs text-[#6f4c3b]">Email *</label>
                <input type="email"
                       wire:model.defer="email"
                       class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                       placeholder="your@email.com">
                @error('email') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
            </div>
        @endguest
    </div>
</div>

<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
    <h3 class="text-base font-semibold text-[#3b241a]">Shipping Information</h3>
    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Provinsi *</label>
        <select wire:model.live="provinsi_id"
                class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]">
            <option value="">Pilih provinsi</option>
            @foreach ($provinsis as $provinsi)
                <option value="{{ $provinsi['id'] }}">{{ $provinsi['nama'] }}</option>
            @endforeach
        </select>
        @error('provinsi_id') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Kota/Kabupaten *</label>
        <select wire:model.live="kota_id"
                class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                @disabled(! $provinsi_id)>
            <option value="">{{ empty($provinsi_id) ? 'Pilih provinsi dahulu' : 'Pilih kota/kabupaten' }}</option>
            @foreach ($kotas as $kota)
                <option value="{{ $kota['id'] }}">{{ $kota['nama'] }}</option>
            @endforeach
        </select>
        @error('kota_id') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Kecamatan *</label>
        <select wire:model.live="kecamatan_id"
                class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                @disabled(! $kota_id)>
            <option value="">{{ empty($kota_id) ? 'Pilih kota dahulu' : 'Pilih kecamatan' }}</option>
            @foreach ($kecamatans as $kecamatan)
                <option value="{{ $kecamatan['id'] }}">{{ $kecamatan['nama'] }}</option>
            @endforeach
        </select>
        @error('kecamatan_id') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Shipping Address *</label>
        <textarea rows="3"
                  wire:model.defer="alamat_lengkap"
                  class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                  placeholder="Street, RT/RW, Kelurahan, Kecamatan, Kota"></textarea>
        @error('alamat_lengkap') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Delivery Notes (Optional)</label>
        <textarea rows="2"
                  wire:model.defer="catatan"
                  class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                  placeholder="e.g., Call before delivery, leave at security post."></textarea>
        @error('catatan') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>
</div>
