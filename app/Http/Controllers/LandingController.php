<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\ViewModels\ProductCardViewModel;
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
            return ProductCardViewModel::fromProduct($product);
        });

        return view('landing.index', [
            'shopDisplay' => $shopDisplay,
        ]);
    }
}
