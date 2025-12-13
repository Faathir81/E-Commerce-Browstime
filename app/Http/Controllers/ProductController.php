<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\ViewModels\ProductDetailViewModel;
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
            'productDetail' => ProductDetailViewModel::fromProduct($product, $availableUnits),
        ]);
    }

    protected function calculateAvailableUnits(Produk $product): ?int
    {
        $recipe = $product->resep;
        if (! $recipe || $recipe->detail->isEmpty()) {
            return 0; // no recipe = tidak bisa diproduksi
        }

        $limits = [];
        foreach ($recipe->detail as $detail) {
            $bahan = $detail->bahan;
            $needed = $detail->jumlah ?? 0;
            if (! $bahan || $needed <= 0) {
                return 0; // bahan tidak ada / jumlah tidak valid -> out of stock
            }
            $available = floor(($bahan->current_stok ?? 0) / $needed);
            $limits[] = (int) max($available, 0);
        }

        return empty($limits) ? 0 : min($limits);
    }
}
