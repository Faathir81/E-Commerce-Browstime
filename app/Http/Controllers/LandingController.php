<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    public function index()
    {
        $shopDisplay = Produk::active()
            ->with(['resep.detail.bahan'])
            ->latest()
            ->limit(5)
            ->get();

        $shopDisplay = $shopDisplay->map(function (Produk $product) {
            $imagePath = $product->gambar ?? 'placeholder.jpg';
            $imageUrl = $product->gambar
                ? asset('storage/' . $imagePath)
                : asset('storage/placeholder.jpg');

            return [
                'id' => $product->id,
                'name' => $product->nama,
                'product_url' => route('product.show', $product->slug),
                'in_stock' => $product->hasSufficientStock(),
                'formatted_price' => number_format($product->harga ?? 0, 0, ',', '.'),
                'image_url' => $imageUrl,
            ];
        });

        return view('landing.index', [
            'shopDisplay' => $shopDisplay,
        ]);
    }
}
