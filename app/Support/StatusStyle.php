<?php

namespace App\Support;

class StatusStyle
{
    /**
     * Mapping untuk status pesanan (order).
     */
    public static function pesanan(?string $state): array
    {
        $map = self::pesananMap();

        return $map[$state] ?? self::fallback($state);
    }

    public static function pesananOptions(): array
    {
        return self::toOptions(self::pesananMap());
    }

    public static function pesananIcons(): array
    {
        return self::extractFromMap(self::pesananMap(), 'icon');
    }

    public static function pesananColors(): array
    {
        return self::extractFromMap(self::pesananMap(), 'color');
    }

    /**
     * Mapping label untuk jenis mutasi stok.
     */
    public static function mutasiStok(?string $state): array
    {
        $map = self::mutasiStokMap();

        return $map[$state] ?? self::fallback($state);
    }

    public static function mutasiStokLabel(?string $state): string
    {
        return self::mutasiStok($state)['label'];
    }

    public static function mutasiStokOptions(): array
    {
        return self::toOptions(self::mutasiStokMap());
    }

    /**
     * Mapping untuk status pembayaran.
     */
    public static function pembayaran(?string $state): array
    {
        $map = self::pembayaranMap();

        return $map[$state] ?? self::fallback($state);
    }

    public static function pembayaranOptions(): array
    {
        return self::toOptions(self::pembayaranMap());
    }

    private static function pesananMap(): array
    {
        return [
            'pending'  => [
                'label' => 'Menunggu Pembayaran',
                'color' => 'gray',
                'icon'  => 'heroicon-m-arrow-path',
            ],
            'paid'     => [
                'label' => 'Terbayar',
                'color' => 'info',
                'icon'  => 'heroicon-m-banknotes',
            ],
            'perlu_perbaikan' => [
                'label' => 'Perlu Perbaikan',
                'color' => 'warning',
                'icon'  => 'heroicon-m-exclamation-triangle',
            ],
            'produksi' => [
                'label' => 'Diproduksi',
                'color' => 'info', // tampilan dibuat netral supaya terlihat non-aktif
                'icon'  => 'heroicon-m-sparkles',
            ],
            'dikirim'  => [
                'label' => 'Dikirim',
                'color' => 'success', // tampilan dibuat netral supaya terlihat non-aktif
                'icon'  => 'heroicon-m-truck',
            ],
            'selesai'  => [
                'label' => 'Selesai',
                'color' => 'success',
                'icon'  => 'heroicon-m-check-circle',
            ],
            'batal'    => [
                'label' => 'Batal',
                'color' => 'danger',
                'icon'  => 'heroicon-m-x-circle',
            ],
        ];
    }

    private static function mutasiStokMap(): array
    {
        return [
            'pemakaian_produksi' => [
                'label' => 'Pemakaian Produksi',
                'color' => 'warning',
            ],
            'stok_masuk' => [
                'label' => 'Stok Masuk',
                'color' => 'success',
            ],
            'stok_rusak' => [
                'label' => 'Stok Rusak',
                'color' => 'danger',
            ],
            'stok_expired' => [
                'label' => 'Stok Expired',
                'color' => 'danger',
            ],
            'penyesuaian' => [
                'label' => 'Penyesuaian Stok',
                'color' => 'info',
            ],
        ];
    }

    private static function pembayaranMap(): array
    {
        return [
            'pending'             => [
                'label' => 'Belum Bayar',
                'color' => 'gray',
                'icon'  => 'heroicon-m-clock',
            ],
            'menunggu_verifikasi' => [
                'label' => 'Menunggu Verifikasi',
                'color' => 'warning',
                'icon'  => 'heroicon-m-shield-check',
            ],
            'valid'               => [
                'label' => 'Valid',
                'color' => 'success',
                'icon'  => 'heroicon-m-check-badge',
            ],
            'invalid'             => [
                'label' => 'Invalid',
                'color' => 'danger',
                'icon'  => 'heroicon-m-x-circle',
            ],
        ];
    }

    private static function fallback(?string $state): array
    {
        $label = $state
            ? ucwords(str_replace('_', ' ', $state))
            : '-';

        return [
            'label' => $label,
            'color' => 'gray',
            'icon'  => 'heroicon-m-ellipsis-horizontal',
        ];
    }

    private static function toOptions(array $map): array
    {
        $options = [];

        foreach ($map as $value => $config) {
            $options[$value] = $config['label'];
        }

        return $options;
    }

    private static function extractFromMap(array $map, string $key): array
    {
        $result = [];

        foreach ($map as $value => $config) {
            if (! isset($config[$key])) {
                continue;
            }

            $result[$value] = $config[$key];
        }

        return $result;
    }
}
