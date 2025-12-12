<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    private const CACHE_TTL_SECONDS = 30;
    private const CACHE_TTL_REGION = 3600;
    private const MIN_WEIGHT_KG = 0.01;

    public function calculateDomesticCost(int $destinationSubdistrictId, int $weightGram, string $courier = 'jne'): array
    {
        $originId = (int) config('services.rajaongkir.origin_subdistrict_id', 763);
        $baseUrl = rtrim(config('services.rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $apiKey = (string) config('services.rajaongkir.key');
        $weightKg = $this->convertGramToKilogram($weightGram);
        $cacheKey = sprintf(
            'rajaongkir.domestic.%d.%d.%s.%s',
            $originId,
            $destinationSubdistrictId,
            $courier,
            number_format($weightKg, 2, '.', '')
        );

        return Cache::remember(
            $cacheKey,
            now()->addSeconds(self::CACHE_TTL_SECONDS),
            function () use ($originId, $destinationSubdistrictId, $courier, $weightKg, $apiKey, $baseUrl) {
                try {
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'key' => $apiKey,
                    ])
                        ->timeout(15)
                        ->asForm()
                        ->post($baseUrl . '/calculate/domestic-cost', [
                            'origin' => $originId,
                            'originType' => 'subdistrict',
                            'destination' => $destinationSubdistrictId,
                            'destinationType' => 'subdistrict',
                            'weight' => $weightKg,
                            'courier' => $courier,
                        ]);
                } catch (\Throwable $exception) {
                    throw new \RuntimeException('Failed to connect to RajaOngkir: ' . $exception->getMessage(), 0, $exception);
                }

                if ($response->failed()) {
                    try {
                        $response->throw();
                    } catch (RequestException $exception) {
                        $message = $exception->response?->json('message') ?? $exception->getMessage();
                        throw new \RuntimeException('RajaOngkir error: ' . $message, $exception->getCode(), $exception);
                    }
                }

                $option = $this->extractFirstOption($response->json());
                if (! $option) {
                    throw new \RuntimeException('RajaOngkir response malformed: cost option not found.');
                }

                return [
                    'service' => (string) ($option['service'] ?? $option['service_code'] ?? $option['code'] ?? ''),
                    'cost' => (int) round($option['cost'] ?? $option['value'] ?? $option['price'] ?? 0),
                    'etd' => (string) ($option['etd'] ?? $option['etd_time'] ?? $option['etd_text'] ?? ''),
                    'raw' => $option,
                ];
            }
        );
    }

    /**
     * Ambil daftar provinsi dari RajaOngkir dan normalisasi field id/nama.
     */
    public function provinces(): array
    {
        return Cache::remember(
            'rajaongkir.provinces',
            now()->addSeconds(self::CACHE_TTL_REGION),
            fn () => $this->fetchAndNormalizeRegion(
                endpoint: '/destination/province',  // ✅ PERBAIKAN
                normalizer: fn (array $row) => [
                    'id' => $row['id'] ?? $row['province_id'] ?? null,
                    'name' => $row['name'] ?? $row['province'] ?? null,
                ]
            )
        );
    }

    /**
     * Ambil daftar kota/kabupaten berdasarkan provinsi.
     */
    public function cities(int $provinceId): array
    {
        return Cache::remember(
            "rajaongkir.cities.{$provinceId}",
            now()->addSeconds(self::CACHE_TTL_REGION),
            fn () => $this->fetchAndNormalizeRegion(
                endpoint: "/destination/city/{$provinceId}",  // ✅ PERBAIKAN: pakai path param
                // HAPUS: query: ['province_id' => $provinceId],
                normalizer: function (array $row) use ($provinceId) {
                    return [
                        'id' => $row['id'] ?? $row['city_id'] ?? null,
                        'name' => $row['name'] ?? $row['city_name'] ?? null,
                        'province_id' => $row['province_id'] ?? $provinceId ?? null,
                        'postal_code' => $row['postal_code'] ?? null,
                    ];
                }
            )
        );
    }

    /**
     * Ambil daftar kecamatan berdasarkan kota/kabupaten.
     */
    public function districts(int $cityId): array
    {
        return Cache::remember(
            "rajaongkir.districts.{$cityId}",
            now()->addSeconds(self::CACHE_TTL_REGION),
            fn () => $this->fetchAndNormalizeRegion(
                endpoint: "/destination/district/{$cityId}",  // ✅ PERBAIKAN: path param + district bukan subdistricts
                // HAPUS: query: ['city_id' => $cityId],
                normalizer: function (array $row) use ($cityId) {
                    return [
                        'id' => $row['id'] ?? $row['subdistrict_id'] ?? null,
                        'name' => $row['name'] ?? $row['subdistrict_name'] ?? null,
                        'city_id' => $row['city_id'] ?? $cityId ?? null,
                        'zip_code' => $row['zip_code'] ?? $row['postal_code'] ?? null,
                    ];
                }
            )
        );
    }

    /**
     * Helper request data wilayah dan normalisasi hasil agar konsisten.
     */
    private function fetchAndNormalizeRegion(string $endpoint, array $query = [], callable $normalizer = null): array
    {
        $payload = $this->performGetRequest($endpoint, $query);
        $items = $this->extractList($payload);

        if (! $items) {
            return [];
        }

        if (! $normalizer) {
            return $items;
        }

        return collect($items)
            ->map(fn ($row) => $normalizer(is_array($row) ? $row : []))
            ->filter(fn ($row) => ! empty($row['id']) && ! empty($row['name']))
            ->values()
            ->all();
    }

    /**
     * Lakukan GET ke RajaOngkir dengan header API key.
     */
    private function performGetRequest(string $endpoint, array $query = []): array
    {
        $baseUrl = rtrim(config('services.rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $apiKey = (string) config('services.rajaongkir.key');

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'key' => $apiKey,
            ])
                ->timeout(15)
                ->get($baseUrl . $endpoint, $query);
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Failed to connect to RajaOngkir: ' . $exception->getMessage(), 0, $exception);
        }

        if ($response->failed()) {
            try {
                $response->throw();
            } catch (RequestException $exception) {
                $message = $exception->response?->json('message') ?? $exception->getMessage();
                throw new \RuntimeException('RajaOngkir error: ' . $message, $exception->getCode(), $exception);
            }
        }

        return $response->json() ?? [];
    }

    /**
     * Ekstrak list data dari payload RajaOngkir (mencoba beberapa bentuk umum).
     */
    private function extractList(array $payload): array
    {
        if (isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }

        $rajaOngkir = $payload['rajaongkir'] ?? null;
        if (is_array($rajaOngkir)) {
            $results = $rajaOngkir['results'] ?? $rajaOngkir['result'] ?? null;
            if (is_array($results)) {
                return $results;
            }
        }

        return [];
    }

    private function extractFirstOption(?array $payload): ?array
    {
        $data = $payload['data'] ?? null;
        if (! $data) {
            return null;
        }

        if (Arr::isAssoc($data)) {
            $costs = $data['costs'] ?? $data['results'] ?? null;
            if ($costs && is_array($costs)) {
                return collect($costs)->first();
            }
            return $data;
        }

        $first = collect($data)->first();
        if (! $first) {
            return null;
        }

        if (isset($first['costs']) && is_array($first['costs'])) {
            return collect($first['costs'])->first();
        }

        return $first;
    }

    private function convertGramToKilogram(int $gram): float
    {
        $kilogram = $gram / 1000;
        $rounded = round($kilogram, 2);

        return $rounded > 0 ? $rounded : self::MIN_WEIGHT_KG;
    }
}