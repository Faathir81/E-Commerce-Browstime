<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    private const CACHE_TTL_SECONDS = 30;
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
