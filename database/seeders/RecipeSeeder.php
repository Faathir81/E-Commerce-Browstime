<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Map bahan by name
        $materials = DB::table('materials')->pluck('id','name');

        // Ambil semua produk
        $products = DB::table('products')->select('id','name')->get();

        foreach ($products as $p) {
            // Resep sederhana per 1 unit (200g cookies)
            $rows = [
                ['material' => 'Tepung Terigu', 'qty' => 120.0],
                ['material' => 'Gula Pasir',    'qty' => 50.0],
                ['material' => 'Mentega',       'qty' => 30.0],
                ['material' => 'Telur',         'qty' => 1.0],
                ['material' => 'Kemasan',       'qty' => 1.0],
            ];

            // Kalau nama produk mengandung 'Choco', tambahkan cokelat chip
            if (stripos($p->name, 'choco') !== false) {
                $rows[] = ['material' => 'Cokelat Chip', 'qty' => 40.0];
            }

            foreach ($rows as $r) {
                $mid = $materials[$r['material']] ?? null;
                if ($mid) {
                    DB::table('product_recipes')->insert([
                        'product_id' => $p->id,
                        'material_id' => $mid,
                        'qty_per_unit' => $r['qty'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
