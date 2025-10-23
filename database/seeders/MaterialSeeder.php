<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['name' => 'Tepung Terigu', 'unit' => 'gram'],
            ['name' => 'Gula Pasir',    'unit' => 'gram'],
            ['name' => 'Mentega',       'unit' => 'gram'],
            ['name' => 'Cokelat Chip',  'unit' => 'gram'],
            ['name' => 'Telur',         'unit' => 'pcs'],
            ['name' => 'Kemasan',       'unit' => 'pcs'],
        ];
        foreach ($materials as $m) {
            $id = DB::table('materials')->insertGetId([
                'name' => $m['name'],
                'unit' => $m['unit'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // stok masuk awal (adjust + in)
            DB::table('material_stocks')->insert([
                [
                    'material_id' => $id,
                    'type' => 'in',
                    'qty' => match ($m['unit']) {
                        'pcs'  => 200,
                        default => 10000, // gram
                    },
                    'note' => 'Stock awal',
                    'created_at' => now(),
                ],
            ]);
        }
    }
}
