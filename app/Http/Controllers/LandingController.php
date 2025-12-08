<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class LandingController extends Controller
{
    public function index()
    {
        $bestSellers = Produk::active()
            ->with(['resep.detail.bahan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('landing.index', compact('bestSellers'));
    }
}
