<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Spatie
use Spatie\Permission\Models\Role;

// Models
use App\Models\User;
use App\Models\MetodePembayaran;
use App\Models\AkunBank;
use App\Models\QrisSetting;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\ResepBom;
use App\Models\DetailResep;
use App\Models\Pelanggan;
use App\Models\AlamatPengiriman;
use App\Models\WilayahPengiriman;

class BrowstimeSeeder extends Seeder
{
    public function run(): void
    {
        /* ============================
         * 1. ROLE
         * ============================*/
        $roles = ['admin', 'produksi', 'keuangan', 'pelanggan'];

        foreach ($roles as $r) {
            Role::firstOrCreate([
                'name' => $r,
                'guard_name' => 'web',
            ]);
        }

        /* ============================
         * 2. USERS
         * ============================*/
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
        ]);

        $stafProduksi = User::create([
            'name' => 'Staf Produksi',
            'email' => 'produksi@demo.com',
            'password' => Hash::make('password'),
        ]);

        $keuangan = User::create([
            'name' => 'Bagian Keuangan',
            'email' => 'keuangan@demo.com',
            'password' => Hash::make('password'),
        ]);

        $pelangganUser = User::create([
            'name' => 'Pelanggan Login',
            'email' => 'pelanggan1@demo.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');
        $stafProduksi->assignRole('produksi');
        $keuangan->assignRole('keuangan');
        $pelangganUser->assignRole('pelanggan');

        /* ============================
         * 3. METODE PEMBAYARAN
         * ============================*/
        MetodePembayaran::insert([
            ['nama' => 'Transfer Bank', 'kode' => 'transfer', 'aktif' => 1],
            ['nama' => 'QRIS Static',   'kode' => 'qris',     'aktif' => 1],
            ['nama' => 'Midtrans',      'kode' => 'midtrans', 'aktif' => 1],
        ]);

        /* ============================
         * 4. AKUN BANK
         * ============================*/
        AkunBank::create([
            'nama_bank' => 'BCA',
            'nama_pemilik' => 'Browstime',
            'nomor_rekening' => '1234567890',
            'aktif' => 1,
            'urutan' => 1,
        ]);

        /* ============================
         * 5. QRIS STATIC
         * ============================*/
        QrisSetting::create([
            'gambar_qris' => 'qris/default.png'
        ]);

        /* ============================
         * 6. KATEGORI
         * ============================*/
        $katCookies = Kategori::create(['nama' => 'Cookies', 'slug' => 'cookies']);
        $katBrownies = Kategori::create(['nama' => 'Brownies', 'slug' => 'brownies']);

        /* ============================
         * 7. SATUAN
         * ============================*/
        $gr = Satuan::create(['nama' => 'Gram', 'symbol' => 'gr']);
        $pcs = Satuan::create(['nama' => 'Pcs', 'symbol' => 'pcs']);

        /* ============================
         * 8. BAHAN BAKU
         * ============================*/
        $tepung = BahanBaku::create([
            'nama' => 'Tepung Terigu',
            'satuan_id' => $gr->id,
            'stok_awal' => 5000,
            'stok_minimum' => 500,
        ]);

        $gula = BahanBaku::create([
            'nama' => 'Gula Pasir',
            'satuan_id' => $gr->id,
            'stok_awal' => 3000,
            'stok_minimum' => 300,
        ]);

        $butter = BahanBaku::create([
            'nama' => 'Butter',
            'satuan_id' => $gr->id,
            'stok_awal' => 2000,
            'stok_minimum' => 200,
        ]);

        /* ============================
         * 9. PRODUK
         * ============================*/
        $produk1 = Produk::create([
            'nama' => 'Choco Cookies',
            'slug' => 'choco-cookies',
            'kategori_id' => $katCookies->id,
            'harga' => 35000,
            'deskripsi' => 'Cookies coklat premium',
            'waktu_produksi' => 1,
        ]);

        $produk2 = Produk::create([
            'nama' => 'Classic Brownies',
            'slug' => 'classic-brownies',
            'kategori_id' => $katBrownies->id,
            'harga' => 45000,
            'deskripsi' => 'Brownies klasik lembut',
            'waktu_produksi' => 1,
        ]);

        /* ============================
         * 10. BOM
         * ============================*/
        $bom1 = ResepBom::create([
            'produk_id' => $produk1->id,
            'deskripsi' => 'Resep cookies'
        ]);

        DetailResep::insert([
            ['resep_id' => $bom1->id, 'bahan_id' => $tepung->id, 'satuan_id' => $gr->id, 'jumlah' => 100],
            ['resep_id' => $bom1->id, 'bahan_id' => $gula->id,   'satuan_id' => $gr->id, 'jumlah' => 50],
        ]);

        $bom2 = ResepBom::create([
            'produk_id' => $produk2->id,
            'deskripsi' => 'Resep brownies'
        ]);

        DetailResep::insert([
            ['resep_id' => $bom2->id, 'bahan_id' => $tepung->id, 'satuan_id' => $gr->id, 'jumlah' => 150],
            ['resep_id' => $bom2->id, 'bahan_id' => $butter->id, 'satuan_id' => $gr->id, 'jumlah' => 100],
        ]);

        /* ============================
         * 11. PELANGGAN (GUEST + LOGIN)
         * ============================*/
        $guest = Pelanggan::create([
            'nama' => 'Pelanggan Guest',
            'email' => 'guest@demo.com',
            'no_hp' => '08123456789',
        ]);

        $pelanggan1 = Pelanggan::create([
            'user_id' => $pelangganUser->id,
            'nama' => 'Pelanggan Login',
            'email' => 'pelanggan1@demo.com',
            'no_hp' => '08123456788',
        ]);

        /* ============================
         * 12. WILAYAH PENGIRIMAN
         * ============================*/
        $wilayah = WilayahPengiriman::create([
            'nama' => 'Depok - Beji',
            'provinsi_id' => 1,
            'kota_id' => 1,
            'kecamatan_id' => 1,
            'aktif' => 1,
        ]);

        /* ============================
         * 13. ALAMAT PENGIRIMAN
         * ============================*/
        AlamatPengiriman::create([
            'pelanggan_id' => $guest->id,
            'nama_penerima' => $guest->nama,
            'no_hp' => $guest->no_hp,
            'alamat_lengkap' => 'Jl. Mawar No. 12',
            'kode_pos' => '16425',
            'wilayah_pengiriman_id' => $wilayah->id,
        ]);

        AlamatPengiriman::create([
            'pelanggan_id' => $pelanggan1->id,
            'nama_penerima' => 'Pelanggan Login',
            'no_hp' => '08123456788',
            'alamat_lengkap' => 'Jl. Punklorde No. 1',
            'kode_pos' => '16425',
            'wilayah_pengiriman_id' => $wilayah->id,
        ]);
    }
}
