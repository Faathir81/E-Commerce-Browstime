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
        $this->info('Mulai sync wilayah JABODETABEK (Bogor, Depok, Bekasi, Tangerang, Jakarta)...');

        $provinces = collect($this->service->provinces());

        if ($provinces->isEmpty()) {
            $this->error('Gagal ambil data provinsi dari API.');
            return self::FAILURE;
        }

        $targets = [
            'JAWA BARAT'   => ['BOGOR', 'DEPOK', 'BEKASI'],
            'DKI JAKARTA'  => ['JAKARTA TIMUR', 'JAKARTA PUSAT', 'JAKARTA UTARA', 'JAKARTA BARAT', 'JAKARTA SELATAN'],
            'BANTEN'       => ['TANGERANG', 'TANGERANG SELATAN'],
        ];

        $syncedSomething = false;

        foreach ($targets as $provinceName => $cityNames) {
            $province = $this->findProvince($provinces, $provinceName);
            if (! $province) {
                $this->warn("⚠ Provinsi {$provinceName} tidak ditemukan di data API, lewati.");
                continue;
            }

            $this->info('→ Provinsi ditemukan: ' . $province['name'] . ' (ID: ' . $province['id'] . ')');

            $provinsiModel = Provinsi::updateOrCreate(
                ['kode_rajaongkir' => $province['id']],
                ['nama' => $province['name']]
            );

            $this->info('   Mengambil daftar kota/kabupaten di ' . $province['name'] . ' ...');
            $cities = collect($this->service->cities($province['id']));

            if ($cities->isEmpty()) {
                $this->warn("   ⚠ Data kota untuk {$province['name']} kosong, lewati.");
                continue;
            }

            $normalizedTargets = collect($cityNames)->map(fn ($n) => $this->normalizeCityName($n))->all();

            $filteredCities = $cities->filter(function ($city) use ($normalizedTargets) {
                $norm = $this->normalizeCityName($city['name'] ?? '');
                return in_array($norm, $normalizedTargets, true);
            });

            if ($filteredCities->isEmpty()) {
                $this->warn("   ⚠ Tidak ada kota/kab target di {$province['name']}, lewati.");
                continue;
            }

            foreach ($filteredCities as $city) {
                $this->info('   → Sync kota/kab: ' . $city['name'] . ' (ID: ' . $city['id'] . ')');

                $kotaModel = Kota::updateOrCreate(
                    ['kode_rajaongkir' => $city['id']],
                    [
                        'nama'        => $city['name'],
                        'provinsi_id' => $provinsiModel->id,
                    ]
                );

                $this->info('      Mengambil kecamatan untuk: ' . $city['name']);
                $districts = $this->service->districts($city['id']);

                if (empty($districts)) {
                    $this->warn('      ⚠ Data kecamatan untuk kota ini kosong, lewati...');
                    continue;
                }

                foreach ($districts as $dist) {
                    $this->info('      → Sync kecamatan: ' . ($dist['name'] ?? 'N/A') . ' (ID: ' . ($dist['id'] ?? '-') . ')');

                    Kecamatan::updateOrCreate(
                        ['kode_rajaongkir' => $dist['id']],
                        [
                            'nama'     => $dist['name'],
                            'kota_id'  => $kotaModel->id,
                            'kode_pos' => $dist['zip_code'] ?? null,
                        ]
                    );
                }

                $syncedSomething = true;
            }
        }

        if (! $syncedSomething) {
            $this->error('Tidak ada wilayah yang tersync. Cek konfigurasi target kota/provinsi atau data API.');
            return self::FAILURE;
        }

        $this->info('✅ Sync wilayah JABODETABEK selesai.');
        $this->info('   Silakan mapping kecamatan yang dipakai ke tabel `wilayah_pengiriman` (task #5).');

        return self::SUCCESS;
    }

    protected function findProvince($provinces, string $name): ?array
    {
        return $provinces->first(function ($prov) use ($name) {
            return Str::upper($prov['name'] ?? '') === Str::upper($name);
        });
    }

    protected function normalizeCityName(string $name): string
    {
        $upper = Str::upper($name);
        $upper = str_replace(['KOTA ', 'KABUPATEN '], '', $upper);
        return trim($upper);
    }
}