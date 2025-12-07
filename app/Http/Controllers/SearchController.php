<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->get('keyword', ''));
        abort_if(strlen($keyword) > 100, 422, 'Keyword too long');

        $query = Produk::active()
            ->select('id', 'nama', 'slug', 'harga', 'gambar', 'kategori_id')
            ->with('kategori:id,nama')
            ->orderBy('nama');

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        $results = $query->paginate(24)->withQueryString();

        return view('search.results', [
            'keyword' => $keyword,
            'results' => $results,
        ]);
    }
}
