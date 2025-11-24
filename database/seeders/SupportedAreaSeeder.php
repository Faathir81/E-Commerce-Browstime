<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupportedAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $areas = [
            [
                'name' => 'Dramaga (IPB), Bogor',
                'province_id' => 9,
                'city_id' => 150, // Kota Bogor: 150, Kabupaten Bogor: 149
                'subdistrict_id' => 1463,
            ],
            [
                'name' => 'Ciomas, Bogor',
                'province_id' => 9,
                'city_id' => 149,
                'subdistrict_id' => 1458,
            ],
            [
                'name' => 'Cibungbulang, Bogor',
                'province_id' => 9,
                'city_id' => 149,
                'subdistrict_id' => 1445,
            ],
            [
                'name' => 'Bogor Barat, Kota Bogor',
                'province_id' => 9,
                'city_id' => 150,
                'subdistrict_id' => 1486,
            ],
            [
                'name' => 'Bogor Tengah, Kota Bogor',
                'province_id' => 9,
                'city_id' => 150,
                'subdistrict_id' => 1487,
            ],
        ];

        foreach ($areas as $area) {
            \App\Models\SupportedArea::create($area);
        }
    }
}
