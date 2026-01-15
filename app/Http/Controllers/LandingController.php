<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\ViewModels\ProductCardViewModel;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    public function index()
    {
        $heroSlides = Produk::active()
            ->where('harga', 15000)
            ->with('kategori')
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (Produk $product) {
                return ProductCardViewModel::fromProduct($product);
            });

        $shopDisplay = Produk::active()
            ->with(['resep.detail.bahan'])
            ->latest()
            ->limit(5)
            ->get();

        $shopDisplay = $shopDisplay->map(function (Produk $product) {
            return ProductCardViewModel::fromProduct($product);
        });

        return view('landing.index', [
            'heroSlides' => $heroSlides,
            'shopDisplay' => $shopDisplay,
        ]);
    }
}
