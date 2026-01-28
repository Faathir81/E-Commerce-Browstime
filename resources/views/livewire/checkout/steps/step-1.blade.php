<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
    <h3 class="text-base font-semibold text-[#3b241a]">Informasi Pelanggan</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1">
            <label class="text-xs text-[#6f4c3b]">Nama Lengkap *</label>
            <input type="text"
                   wire:model.defer="nama_penerima"
                   class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                   placeholder="contoh: Masbro">
            @error('nama_penerima') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
        </div>
        <div class="space-y-1">
            <label class="text-xs text-[#6f4c3b]">Nomor HP *</label>
            <input type="text"
                   wire:model.defer="no_hp"
                   class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                   placeholder="contoh: 08xxxxxxxxx">
            @error('no_hp') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
        </div>
        @guest
            <div class="space-y-1 md:col-span-2">
                <label class="text-xs text-[#6f4c3b]">Email *</label>
                <input type="email"
                       wire:model.defer="email"
                       class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                       placeholder="email@kamu.com">
                @error('email') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
            </div>
        @endguest
    </div>
</div>

<div class="bg-white border border-[#f1e8df] rounded-2xl shadow-sm p-5 lg:p-6 space-y-4">
    <h3 class="text-base font-semibold text-[#3b241a]">Informasi Pengiriman</h3>
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
        <label class="text-xs text-[#6f4c3b]">Kota / Kabupaten *</label>
        <select wire:model.live="kota_id"
                class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                @disabled(! $provinsi_id)>
            <option value="">{{ empty($provinsi_id) ? 'Pilih provinsi dulu' : 'Pilih kota/kabupaten' }}</option>
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
            <option value="">{{ empty($kota_id) ? 'Pilih kota dulu' : 'Pilih kecamatan' }}</option>
            @foreach ($kecamatans as $kecamatan)
                <option value="{{ $kecamatan['id'] }}">{{ $kecamatan['nama'] }}</option>
            @endforeach
        </select>
        @error('kecamatan_id') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Alamat Pengiriman *</label>
        <textarea rows="3"
                  wire:model.defer="alamat_lengkap"
                  class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                  placeholder="Jl., RT/RW, Kelurahan"></textarea>
        @error('alamat_lengkap') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Kode Pos *</label>
        <input type="text"
               wire:model.defer="kode_pos"
               class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
               placeholder="misal, 12345">
        @error('kode_pos') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label class="text-xs text-[#6f4c3b]">Catatan Pengiriman (Opsional)</label>
        <textarea rows="2"
                  wire:model.defer="catatan"
                  class="w-full rounded-xl border border-[#e4d6c6] bg-[#fffdfb] px-4 py-3 text-sm focus:border-[#bb936c] focus:ring-[#bb936c]"
                  placeholder="contoh, Telepon dulu sebelum kirim, titip di pos satpam."></textarea>
        @error('catatan') <p class="text-xs text-[#b3261e]">{{ $message }}</p> @enderror
    </div>
</div>
