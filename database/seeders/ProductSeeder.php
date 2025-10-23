<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kategori
        $catId = DB::table('categories')->where('slug', 'cookies')->value('id') ?? DB::table('categories')->insertGetId([
            'name' => 'Cookies', 'slug' => 'cookies', 'created_at' => now(), 'updated_at' => now()
        ]);

        // Produk contoh
        $products = [
            ['name' => 'Choco Chip Cookies 200g', 'price' => 35000],
            ['name' => 'Classic Butter Cookies 200g', 'price' => 32000],
        ];

        foreach ($products as $p) {
            $pid = DB::table('products')->insertGetId([
                'category_id' => $catId,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'price' => $p['price'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('product_images')->insert([
                'product_id' => $pid,
                'url' => '/storage/products/sample.jpg',
                'is_cover' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
