<?php

namespace App\Console\Commands;

use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Services\RajaOngkirService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncRajaOngkirWilayah extends Command
{
    protected $signature = 'rajaongkir:sync-wilayah';

    protected $description = 'Sync provinsi, kota, dan kecamatan (Jawa Barat - Bogor area) dari API RajaOngkir ke database';

    public function __construct(protected RajaOngkirService $service)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Mulai sync wilayah JAWA BARAT (BOGOR AREA)...');

        // 1. Ambil semua provinsi
        $provinces = $this->service->provinces();

        if (empty($provinces)) {
            $this->error('Gagal ambil data provinsi dari API.');
            return self::FAILURE;
        }

        // Cari provinsi "Jawa Barat"
        $jawaBarat = collect($provinces)->first(function ($prov) {
            return strcasecmp($prov['name'] ?? '', 'Jawa Barat') === 0;
        });

        if (! $jawaBarat) {
            $this->error('Provinsi "Jawa Barat" tidak ditemukan di data API.');
            return self::FAILURE;
        }

        $this->info('→ Provinsi ditemukan: ' . $jawaBarat['name'] . ' (ID: ' . $jawaBarat['id'] . ')');

        // Simpan / update provinsi
        $provinsiModel = Provinsi::updateOrCreate(
            ['kode_rajaongkir' => $jawaBarat['id']],
            ['nama' => $jawaBarat['name']]
        );

        // 2. Ambil semua kota di Jawa Barat
        $this->info('Mengambil daftar kota/kabupaten di Jawa Barat...');
        $cities = $this->service->cities($jawaBarat['id']);

        if (empty($cities)) {
            $this->error('Data kota untuk Jawa Barat kosong.');
            return self::FAILURE;
        }

        // Filter hanya kota/kab yang mengandung "BOGOR"
        $targetCities = collect($cities)->filter(function ($city) {
            return Str::contains(Str::upper($city['name'] ?? ''), 'BOGOR');
        });

        if ($targetCities->isEmpty()) {
            $this->error('Tidak ditemukan kota/kab dengan nama mengandung "Bogor".');
            return self::FAILURE;
        }

        foreach ($targetCities as $city) {
            $this->info('→ Sync kota/kab: ' . $city['name'] . ' (ID: ' . $city['id'] . ')');

            // Simpan / update kota
            $kotaModel = Kota::updateOrCreate(
                ['kode_rajaongkir' => $city['id']],
                [
                    'nama'        => $city['name'],
                    'provinsi_id' => $provinsiModel->id,
                ]
            );

            // 3. Ambil semua kecamatan untuk kota ini
            $this->info('   Mengambil kecamatan untuk: ' . $city['name']);
            $districts = $this->service->districts($city['id']);

            if (empty($districts)) {
                $this->warn('   ⚠ Data kecamatan untuk kota ini kosong, lewati...');
                continue;
            }

            foreach ($districts as $dist) {
                $this->info('   → Sync kecamatan: ' . ($dist['name'] ?? 'N/A') . ' (ID: ' . ($dist['id'] ?? '-') . ')');

                Kecamatan::updateOrCreate(
                    ['kode_rajaongkir' => $dist['id']],
                    [
                        'nama'     => $dist['name'],
                        'kota_id'  => $kotaModel->id,
                        'kode_pos' => $dist['zip_code'] ?? null,
                    ]
                );
            }
        }

        $this->info('✅ Sync wilayah Jabar–Bogor selesai.');
        $this->info('   Silakan mapping kecamatan yang dipakai ke tabel `wilayah_pengiriman` (task #5).');

        return self::SUCCESS;
    }
}
