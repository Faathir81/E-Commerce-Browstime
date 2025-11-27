<?php

namespace App\Filament\Produksi\Resources\Produksis\Schemas;

use App\Models\Pesanan;

class ProduksiHelper
{
    public static function hitungKebutuhanBahan(Pesanan $pesanan): array
    {
        $pesanan->loadMissing('detailPesanans.produk.resep.detail.bahan.satuan');

        $kebutuhan = [];

        foreach ($pesanan->detailPesanans as $detail) {
            $produk = $detail->produk;
            $resep = $produk?->resep;

            if (!$resep) {
                continue;
            }

            foreach ($resep->detail as $detailResep) {
                $bahan = $detailResep->bahan;
                if (!$bahan) continue;

                $bahanId = $bahan->id;
                $qty = $detailResep->jumlah * $detail->qty;

                if (!isset($kebutuhan[$bahanId])) {
                    $kebutuhan[$bahanId] = [
                        'bahan'     => $bahan,
                        'kebutuhan' => 0,
                    ];
                }

                $kebutuhan[$bahanId]['kebutuhan'] += $qty;
            }
        }

        return $kebutuhan;
    }
}
