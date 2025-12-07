<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug, Request $request)
    {
        $product = Produk::active()
            ->where('slug', $slug)
            ->with([
                'kategori:id,nama',
                'resep.detail.bahan.satuan',
            ])
            ->firstOrFail();

        $availableUnits = $this->calculateAvailableUnits($product);

        return view('products.show', [
            'product' => $product,
            'availableUnits' => $availableUnits,
        ]);
    }

    protected function calculateAvailableUnits(Produk $product): ?int
    {
        $recipe = $product->resep;
        if (! $recipe || $recipe->detail->isEmpty()) {
            return null; // treat as unlimited/unknown
        }

        $limits = [];
        foreach ($recipe->detail as $detail) {
            $bahan = $detail->bahan;
            $needed = $detail->jumlah ?? 0;
            if (! $bahan || $needed <= 0) {
                continue;
            }
            $available = floor(($bahan->current_stok ?? 0) / $needed);
            $limits[] = (int) max($available, 0);
        }

        return empty($limits) ? null : min($limits);
    }
}
