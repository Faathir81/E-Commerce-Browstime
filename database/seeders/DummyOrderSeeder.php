<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyOrderSeeder extends Seeder
{
    public function run()
    {
        // ============================
        // 0. TRUNCATE SECURE
        // ============================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ulasans')->truncate();
        DB::table('pembayarans')->truncate();
        DB::table('detail_pesanans')->truncate();
        DB::table('pesanans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ============================
        // Base data
        // ============================
        $produkList = DB::table('produks')->pluck('harga', 'id')->toArray();
        $pelanggans = DB::table('pelanggans')->get();
        $alamatList = DB::table('alamat_pengirimans')->get();

        // ============================
        // Generate 10 orders
        // ============================
        for ($i = 1; $i <= 10; $i++) {

            $tanggal = Carbon::now()
                ->subDays(rand(0, 6))
                ->setTime(rand(8, 21), rand(0, 59));

            // Guest or Login
            $isGuest = rand(0, 1);

            if ($isGuest) {
                $userId = null;
                $guestEmail = "guest{$i}@mail.com";
                $alamat = $alamatList->first();
            } else {
                $pel = $pelanggans->whereNotNull('user_id')->random();
                $userId = $pel->user_id;
                $guestEmail = null;

                $alamat = $alamatList
                    ->where('pelanggan_id', $pel->id)
                    ->first();
            }

            // Detail Order
            $selectedProduk = array_rand($produkList, rand(1, 2));
            if (!is_array($selectedProduk)) {
                $selectedProduk = [$selectedProduk];
            }

            $detailInsert = [];
            $subtotal = 0;

            foreach ($selectedProduk as $pid) {
                $qty = rand(1, 3);
                $harga = $produkList[$pid];
                $subtotal += $qty * $harga;

                $detailInsert[] = [
                    'produk_id' => $pid,
                    'qty' => $qty,
                    'harga' => $harga,
                    'subtotal' => $qty * $harga,
                ];
            }

            $ongkir = 10000;
            $total = $subtotal + $ongkir;

            // Pesanan
            $pesananId = DB::table('pesanans')->insertGetId([
                'kode' => strtoupper('ORD-' . uniqid()),
                'user_id' => $userId,
                'guest_email' => $guestEmail,
                'wilayah_pengiriman_id' => $alamat->wilayah_pengiriman_id,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total' => $total,
                'status' => ['pending', 'paid'][rand(0, 1)],
                'eta' => $tanggal->copy()->addDays(2),
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            // Detail
            foreach ($detailInsert as $d) {
                DB::table('detail_pesanans')->insert([
                    'pesanan_id' => $pesananId,
                    'produk_id' => $d['produk_id'],
                    'qty' => $d['qty'],
                    'harga' => $d['harga'],
                    'subtotal' => $d['subtotal'],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
            }

            // Pembayaran
            $method = ['transfer', 'qris', 'midtrans'][rand(0, 2)];

            DB::table('pembayarans')->insert([
                'pesanan_id' => $pesananId,
                'metode' => $method,
                'akun_bank_id' => $method === 'transfer' ? 1 : null,
                'qris_setting_id' => $method === 'qris' ? 1 : null,
                'jumlah' => $total,
                'status' => ['pending', 'menunggu_verifikasi', 'valid'][rand(0, 2)],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);
        }
    }
}
