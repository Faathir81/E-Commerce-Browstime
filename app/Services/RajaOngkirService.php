<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected function client()
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'key'    => config('services.rajaongkir.key'),
        ]);
    }

    protected function baseUrl(): string
    {
        return rtrim(config('services.rajaongkir.base_url'), '/');
    }

    /**
     * Ambil semua provinsi dari Komerce / RajaOngkir.
     * Endpoint: GET /destination/province
     */
    public function provinces(): array
    {
        $response = $this->client()
            ->get($this->baseUrl() . '/destination/province');

        return $response->json('data') ?? [];
    }

    /**
     * Ambil semua kota berdasarkan ID provinsi.
     * Endpoint: GET /destination/city/{provinceId}
     */
    public function cities(int $provinceId): array
    {
        $response = $this->client()
            ->get($this->baseUrl() . '/destination/city/' . $provinceId);

        return $response->json('data') ?? [];
    }

    /**
     * Ambil semua kecamatan berdasarkan ID kota.
     * Endpoint: GET /destination/district/{cityId}
     */
    public function districts(int $cityId): array
    {
        $response = $this->client()
            ->get($this->baseUrl() . '/destination/district/' . $cityId);

        return $response->json('data') ?? [];
    }

    /**
     * Search alamat (optional, buat fitur autocomplete kalau mau).
     * Endpoint: GET /destination/domestic-destination?search=...
     */
    public function searchDestination(string $keyword, int $limit = 20, int $offset = 0): array
    {
        $response = $this->client()
            ->get($this->baseUrl() . '/destination/domestic-destination', [
                'search' => $keyword,
                'limit'  => $limit,
                'offset' => $offset,
            ]);

        return $response->json('data') ?? [];
    }

    /**
     * Hitung ongkir (calculate/domestic-cost).
     * Ini nanti dipakai di task ongkir (checkout).
     */
    public function cost(int $originId, int $destinationId, int $weight, string $courier, bool $useLowestPrice = true): array
    {
        $response = $this->client()
            ->asForm()
            ->post($this->baseUrl() . '/calculate/domestic-cost', [
                'origin'      => $originId,
                'destination' => $destinationId,
                'weight'      => $weight,
                'courier'     => $courier,
                'price'       => $useLowestPrice ? 'lowest' : 'highest',
            ]);

        return $response->json('data') ?? [];
    }
}
