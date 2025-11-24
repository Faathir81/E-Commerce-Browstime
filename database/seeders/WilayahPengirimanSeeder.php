<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WilayahPengiriman;

class WilayahPengirimanSeeder extends Seeder
{
    public function run()
    {
        $areas = [
            [
                'nama' => 'Dramaga (IPB), Bogor',
                'provinsi_id' => 9,
                'kota_id' => 149, // Kab. Bogor
                'kecamatan_id' => 1463,
            ],
            [
                'nama' => 'Ciomas, Bogor',
                'provinsi_id' => 9,
                'kota_id' => 149,
                'kecamatan_id' => 1458,
            ],
            [
                'nama' => 'Cibungbulang, Bogor',
                'provinsi_id' => 9,
                'kota_id' => 149,
                'kecamatan_id' => 1445,
            ],
            [
                'nama' => 'Tamansari, Bogor',
                'provinsi_id' => 9,
                'kota_id' => 149,
                'kecamatan_id' => 1638,
            ],
            [
                'nama' => 'Bogor Barat (Kota Bogor)',
                'provinsi_id' => 9,
                'kota_id' => 150, // Kota Bogor
                'kecamatan_id' => 1486,
            ],
        ];

        foreach ($areas as $area) {
            WilayahPengiriman::create($area);
        }
    }
}
