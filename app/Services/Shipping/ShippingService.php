<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ShippingService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.binderbyte.com/v1/';

    public function __construct()
    {
        $this->apiKey = (string) config('services.binderbyte.api_key');
    }

    /**
     * Ambil daftar provinsi (cached 1 hari).
     */
    public function getProvinces(): array
    {
        return Cache::remember('shipping_provinces', now()->addDay(), function () {
            $url = "{$this->baseUrl}provinsi?api_key={$this->apiKey}";
            $res = Http::get($url);

            if (! $res->successful()) {
                throw ValidationException::withMessages(['binderbyte' => 'Failed to fetch provinces']);
            }

            $data = $res->json('value');
            return collect($data)->map(fn($p) => [
                'id'   => (int) $p['id'],
                'name' => $p['name'],
            ])->all();
        });
    }

    /**
     * Ambil daftar kota berdasarkan provinsi_id (cached 1 hari per provinsi).
     */
    public function getCities(int $provinceId): array
    {
        return Cache::remember("shipping_cities_{$provinceId}", now()->addDay(), function () use ($provinceId) {
            $url = "{$this->baseUrl}kota?api_key={$this->apiKey}&provinsi_id={$provinceId}";
            $res = Http::get($url);

            if (! $res->successful()) {
                throw ValidationException::withMessages(['binderbyte' => 'Failed to fetch cities']);
            }

            $data = $res->json('value');
            return collect($data)->map(fn($c) => [
                'id'   => (int) $c['id'],
                'name' => $c['name'],
                'type' => $c['type'],
            ])->all();
        });
    }

    /**
     * Ambil daftar kecamatan (opsional, kalau API binderbyte menyediakan).
     * Kalau gak ada, return array kosong.
     */
    public function getDistricts(int $cityId): array
    {
        $url = "{$this->baseUrl}kecamatan?api_key={$this->apiKey}&kabupaten_id={$cityId}";
        $res = Http::get($url);

        if (! $res->successful()) {
            return [];
        }

        $data = $res->json('value');
        return collect($data)->map(fn($d) => [
            'id'   => (int) $d['id'],
            'name' => $d['name'],
        ])->all();
    }

    /**
     * Estimasi ongkir sederhana berbasis kota tujuan + berat.
     * Asumsi berat = gram, biaya per km berdasarkan dummy distance.
     * Bisa kamu ubah ke real API rajaongkir nanti kalau mau.
     */
    public function estimateCost(int $destinationCityId, int $weightGram = 1000): array
    {
        // Dummy distance simulation: ambil dari kode kota
        $distanceKm = ($destinationCityId % 100) + 10; // supaya bervariasi
        $baseRate   = 2500; // rupiah per km per kg

        $cost = round(($weightGram / 1000) * $baseRate * $distanceKm);

        return [
            'destination_city_id' => $destinationCityId,
            'distance_km'         => $distanceKm,
            'weight_gram'         => $weightGram,
            'cost'                => $cost,
            'formatted'           => number_format($cost, 0, ',', '.'),
        ];
    }
}
