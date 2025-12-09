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
        $mg  = Satuan::create(['nama' => 'Milligram', 'symbol' => 'mg']);

        /* ============================
         * 8. BAHAN BAKU
         * ============================*/
        $matchaBubuk = BahanBaku::create([
            'nama'          => 'Matcha Bubuk',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 2000,
            'stok_minimum'  => 200,
        ]);

        $butterMix = BahanBaku::create([
            'nama'          => 'Butter Mix Margarine',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 5000,
            'stok_minimum'  => 500,
        ]);

        $telur = BahanBaku::create([
            'nama'          => 'Telur',
            'satuan_id'     => $pcs->id,
            'stok_awal'     => 100,
            'stok_minimum'  => 10,
        ]);

        $ekstrakVanili = BahanBaku::create([
            'nama'          => 'Ekstrak Vanili',
            'satuan_id'     => $mg->id,
            'stok_awal'     => 100000, // 100 gram
            'stok_minimum'  => 10000,
        ]);

        $patiSagu = BahanBaku::create([
            'nama'          => 'Pati Sagu',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 5000,
            'stok_minimum'  => 500,
        ]);

        $gulaKelapa = BahanBaku::create([
            'nama'          => 'Gula Kelapa',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 3000,
            'stok_minimum'  => 300,
        ]);

        $darkChocolate = BahanBaku::create([
            'nama'          => 'Dark Chocolate',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 3000,
            'stok_minimum'  => 300,
        ]);

        $cokelatChip = BahanBaku::create([
            'nama'          => 'Cokelat Chip',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 2000,
            'stok_minimum'  => 200,
        ]);

        $gulaAren = BahanBaku::create([
            'nama'          => 'Gula Aren',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 3000,
            'stok_minimum'  => 300,
        ]);

        $garamLaut = BahanBaku::create([
            'nama'          => 'Garam Laut',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 500,
            'stok_minimum'  => 50,
        ]);

        $kacangAlmond = BahanBaku::create([
            'nama'          => 'Kacang Almond',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 1000,
            'stok_minimum'  => 100,
        ]);

        $sodaKue = BahanBaku::create([
            'nama'          => 'Soda Kue',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 500,
            'stok_minimum'  => 50,
        ]);

        $kismis = BahanBaku::create([
            'nama'          => 'Kismis',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 2000,
            'stok_minimum'  => 200,
        ]);

        $jaheBubuk = BahanBaku::create([
            'nama'          => 'Jahe Bubuk',
            'satuan_id'     => $gr->id,
            'stok_awal'     => 1000,
            'stok_minimum'  => 100,
        ]);

        /* ============================
         * 9. PRODUK (COOKIES + BROWNIES)
         * ============================*/

        // 1. Classic Dark Chocolate – Pouch (90g)
        $classicPouch = Produk::create([
            'nama'          => 'Classic Dark Chocolate – Pouch',
            'slug'          => 'classic-dark-chocolate-pouch',
            'kategori_id'   => $katCookies->id,
            'harga'         => 15000,
            'berat'         => 90,
            'deskripsi'     => 'Enjoy the rich, authentic taste... (90 grams) – 10 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 2. Classic Dark Chocolate – Jar (180g)
        $classicJar = Produk::create([
            'nama'          => 'Classic Dark Chocolate – Jar',
            'slug'          => 'classic-dark-chocolate-jar',
            'kategori_id'   => $katCookies->id,
            'harga'         => 35000,
            'berat'         => 180,
            'deskripsi'     => 'Dive into the rich... (180 grams) – 18–20 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 3. Dark Chocolate Golden Raisin – Pouch (90g)
        $raisinPouch = Produk::create([
            'nama'          => 'Dark Chocolate Golden Raisin – Pouch',
            'slug'          => 'dark-chocolate-golden-raisin-pouch',
            'kategori_id'   => $katCookies->id,
            'harga'         => 15000,
            'berat'         => 90,
            'deskripsi'     => 'Experience the natural sweetness... (90 grams) – 10 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 4. Dark Chocolate Golden Raisin – Jar (180g)
        $raisinJar = Produk::create([
            'nama'          => 'Dark Chocolate Golden Raisin – Jar',
            'slug'          => 'dark-chocolate-golden-raisin-jar',
            'kategori_id'   => $katCookies->id,
            'harga'         => 35000,
            'berat'         => 180,
            'deskripsi'     => 'A delicious blend... (180 grams) – 18–20 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 5. Matcha Green Tea – Pouch (90g)
        $matchaPouch = Produk::create([
            'nama'          => 'Matcha Green Tea – Pouch',
            'slug'          => 'matcha-green-tea-pouch',
            'kategori_id'   => $katCookies->id,
            'harga'         => 15000,
            'berat'         => 90,
            'deskripsi'     => 'Experience the calming... (90 grams) – 10 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 6. Matcha Green Tea – Jar (180g)
        $matchaJar = Produk::create([
            'nama'          => 'Matcha Green Tea – Jar',
            'slug'          => 'matcha-green-tea-jar',
            'kategori_id'   => $katCookies->id,
            'harga'         => 35000,
            'berat'         => 180,
            'deskripsi'     => 'Savor the rich aroma... (180 grams) – 18–20 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 7. Warm Ginger Spice – Pouch (90g)
        $gingerPouch = Produk::create([
            'nama'          => 'Warm Ginger Spice – Pouch',
            'slug'          => 'warm-ginger-spice-pouch',
            'kategori_id'   => $katCookies->id,
            'harga'         => 15000,
            'berat'         => 90,
            'deskripsi'     => 'Enjoy the warm... (90 grams) – 10 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 8. Warm Ginger Spice – Jar (180g)
        $gingerJar = Produk::create([
            'nama'          => 'Warm Ginger Spice – Jar',
            'slug'          => 'warm-ginger-spice-jar',
            'kategori_id'   => $katCookies->id,
            'harga'         => 35000,
            'berat'         => 180,
            'deskripsi'     => 'Delight in the harmonious blend... (180 grams) – 18–20 pcs',
            'waktu_produksi'=> 120,
        ]);

        // 9. Brownies Crispy Chocolate – Ziplock (50g)
        $browniesChocoPouch = Produk::create([
            'nama'          => 'Brownies Crispy Chocolate – Ziplock',
            'slug'          => 'brownies-crispy-chocolate-ziplock',
            'kategori_id'   => $katBrownies->id,
            'harga'         => 15000,
            'berat'         => 50,
            'deskripsi'     => 'Enjoy the irresistible crunch... (50 grams)',
            'waktu_produksi'=> 120,
        ]);

        // 10. Brownies Crispy Matcha – Ziplock (50g)
        $browniesMatchaPouch = Produk::create([
            'nama'          => 'Brownies Crispy Matcha – Ziplock',
            'slug'          => 'brownies-crispy-matcha-ziplock',
            'kategori_id'   => $katBrownies->id,
            'harga'         => 15000,
            'berat'         => 50,
            'deskripsi'     => 'Delight in the fragrant... (50 grams)',
            'waktu_produksi'=> 120,
        ]);

        /* ============================
         * 10. BOM (RESEP PER 1 UNIT PRODUK)
         * ============================*/

        // Classic Dark Chocolate – Pouch
        $bomClassicPouch = ResepBom::create([
            'produk_id'  => $classicPouch->id,
            'deskripsi'  => 'Resep Classic Dark Chocolate – Pouch (90g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 30,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $cokelatChip->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 8,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $kacangAlmond->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 5,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 50,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 0.5,
            ],
            [
                'resep_id'  => $bomClassicPouch->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
        ]);

        // Classic Dark Chocolate – Jar (≈ 2x pouch)
        $bomClassicJar = ResepBom::create([
            'produk_id'  => $classicJar->id,
            'deskripsi'  => 'Resep Classic Dark Chocolate – Jar (180g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 60,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $cokelatChip->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 40,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 40,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 16,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $kacangAlmond->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 2,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 100,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomClassicJar->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 2,
            ],
        ]);

        // Dark Chocolate Golden Raisin – Pouch
        $bomRaisinPouch = ResepBom::create([
            'produk_id'  => $raisinPouch->id,
            'deskripsi'  => 'Resep Dark Chocolate Golden Raisin – Pouch (90g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 25,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $cokelatChip->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 5,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 18,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 6,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $gulaAren->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 6,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $kismis->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 50,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 0.5,
            ],
            [
                'resep_id'  => $bomRaisinPouch->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
        ]);

        // Dark Chocolate Golden Raisin – Jar
        $bomRaisinJar = ResepBom::create([
            'produk_id'  => $raisinJar->id,
            'deskripsi'  => 'Resep Dark Chocolate Golden Raisin – Jar (180g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 50,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $cokelatChip->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 36,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 40,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 12,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $gulaAren->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 12,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $kismis->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 2,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 100,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomRaisinJar->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 2,
            ],
        ]);

        // Matcha Green Tea – Pouch
        $bomMatchaPouch = ResepBom::create([
            'produk_id'  => $matchaPouch->id,
            'deskripsi'  => 'Resep Matcha Green Tea – Pouch (90g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $matchaBubuk->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 4,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 15,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $cokelatChip->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 5,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 26,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 50,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 0.5,
            ],
            [
                'resep_id'  => $bomMatchaPouch->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
        ]);

        // Matcha Green Tea – Jar
        $bomMatchaJar = ResepBom::create([
            'produk_id'  => $matchaJar->id,
            'deskripsi'  => 'Resep Matcha Green Tea – Jar (180g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $matchaBubuk->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 8,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 30,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $cokelatChip->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 40,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 52,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 2,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 100,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomMatchaJar->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 2,
            ],
        ]);

        // Warm Ginger Spice – Pouch
        $bomGingerPouch = ResepBom::create([
            'produk_id'  => $gingerPouch->id,
            'deskripsi'  => 'Resep Warm Ginger Spice – Pouch (90g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $jaheBubuk->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 3,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 20,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 28,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $gulaAren->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 12,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 5,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $kacangAlmond->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 5,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 50,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 0.5,
            ],
            [
                'resep_id'  => $bomGingerPouch->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
        ]);

        // Warm Ginger Spice – Jar
        $bomGingerJar = ResepBom::create([
            'produk_id'  => $gingerJar->id,
            'deskripsi'  => 'Resep Warm Ginger Spice – Jar (180g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $jaheBubuk->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 6,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 40,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 56,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $gulaAren->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 24,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $kacangAlmond->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 2,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 100,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomGingerJar->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 2,
            ],
        ]);

        // Brownies Crispy Chocolate – Pouch
        $bomBrowniesChoco = ResepBom::create([
            'produk_id'  => $browniesChocoPouch->id,
            'deskripsi'  => 'Resep Brownies Crispy Chocolate – Ziplock (50g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 18,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $cokelatChip->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 5,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 9,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 8,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $gulaAren->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 4,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 3,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $kacangAlmond->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 2,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 50,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 0.5,
            ],
            [
                'resep_id'  => $bomBrowniesChoco->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
        ]);

        // Brownies Crispy Matcha – Pouch
        $bomBrowniesMatcha = ResepBom::create([
            'produk_id'  => $browniesMatchaPouch->id,
            'deskripsi'  => 'Resep Brownies Crispy Matcha – Ziplock (50g)',
        ]);

        DetailResep::insert([
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $matchaBubuk->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 2,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $darkChocolate->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 10,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $butterMix->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 9,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $patiSagu->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 8,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $gulaKelapa->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 6,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $telur->id,
                'satuan_id' => $pcs->id,
                'jumlah'    => 1,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $ekstrakVanili->id,
                'satuan_id' => $mg->id,
                'jumlah'    => 50,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $garamLaut->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 0.5,
            ],
            [
                'resep_id'  => $bomBrowniesMatcha->id,
                'bahan_id'  => $sodaKue->id,
                'satuan_id' => $gr->id,
                'jumlah'    => 1,
            ],
        ]);
    }
}
