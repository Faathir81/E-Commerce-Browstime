<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class SearchBar extends Component
{
    #[Url(as: 'keyword')]
    public $search = '';
    public bool $dropdownOpen = false;
    public bool $hasSearch = false;
    public bool $showClear = false;
    public string $dropdownClasses = 'absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-lg border border-[#e0c8b0] max-h-80 overflow-y-auto overflow-x-hidden z-[60] p-2 max-w-[calc(100vw-2.5rem)] mx-auto';

    public function mount(): void
    {
        $this->search = trim((string) $this->search);
        $this->hasSearch = $this->search !== '';
        $this->showClear = $this->hasSearch;
        $this->dropdownOpen = false;
    }

    public function getResultsProperty()
    {
        $term = trim($this->search);
        $termLower = mb_strtolower($term);

        if ($term === '') {
            return collect();
        }

        return Produk::active()
            ->select('id', 'nama', 'slug', 'harga', 'gambar', 'kategori_id')
            ->with('kategori:id,nama')
            ->where(function ($q) use ($termLower) {
                $like = '%' . $termLower . '%';
                $q->whereRaw('LOWER(nama) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(slug) LIKE ?', [$like])
                  ->orWhereHas('kategori', function ($cat) use ($like) {
                      $cat->whereRaw('LOWER(nama) LIKE ?', [$like]);
                  });
            })
            ->orderBy('nama')
            ->limit(10)
            ->get()
            ->map(function (Produk $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->nama,
                    'url' => route('product.show', $product->slug),
                    'image_url' => $product->gambar
                        ? Storage::disk('public')->url($product->gambar)
                        : asset('img/placeholder.svg'),
                    'price' => formatCurrency($product->harga),
                    'category' => $product->kategori->nama ?? 'Produk',
                ];
            });
    }

    public function updatedSearch()
    {
        // Livewire 3 → pakai dispatch, PARAMETER HARUS DI-NAME
        $this->dispatch('search-updated', search: $this->search);
        $this->hasSearch = trim($this->search) !== '';
        $this->dropdownOpen = $this->hasSearch;
        $this->showClear = $this->hasSearch;
    }

    public function goToResults()
    {
        $term = trim($this->search);

        if ($term !== '') {
            return redirect()->route('search', ['keyword' => $term]);
        }

        return redirect()->route('search');
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->dropdownOpen = false;
        $this->hasSearch = false;
        $this->showClear = false;
        return redirect()->route('search');
    }

    public function openDropdown()
    {
        $this->dropdownOpen = $this->hasSearch;
    }

    public function closeDropdown()
    {
        $this->dropdownOpen = false;
    }

    public function render()
    {
        return view('livewire.search-bar', [
            'results' => $this->results,
        ]);
    }
}
