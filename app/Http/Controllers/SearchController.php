<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\ViewModels\ProductCardViewModel;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->get('keyword', ''));
        $keywordLower = mb_strtolower($keyword);
        abort_if(strlen($keyword) > 100, 422, 'Keyword too long');

        $query = Produk::active()
            ->select('id', 'nama', 'slug', 'harga', 'gambar', 'kategori_id')
            ->with([
                'kategori:id,nama',
                'resep.detail.bahan',
            ])
            ->orderBy('nama');

        if ($keyword !== '') {
            $query->where(function ($q) use ($keywordLower) {
                $like = '%' . $keywordLower . '%';
                $q->whereRaw('LOWER(nama) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(slug) LIKE ?', [$like])
                  ->orWhereHas('kategori', function ($cat) use ($like) {
                      $cat->whereRaw('LOWER(nama) LIKE ?', [$like]);
                  });
            });
        }

        $results = $query
            ->paginate(24)
            ->through(fn (Produk $product) => ProductCardViewModel::fromProduct($product))
            ->withQueryString();

        return view('search.results', [
            'keyword' => $keyword,
            'results' => $results,
        ]);
    }
}
