<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Produk;

class SearchBar extends Component
{
    #[Url(as: 'keyword')]
    public $search = '';
    public bool $dropdownOpen = false;

    public function getResultsProperty()
    {
        $term = trim($this->search);

        if ($term === '') {
            return collect();
        }

        return Produk::active()
            ->select('id', 'nama', 'slug', 'harga', 'gambar', 'kategori_id')
            ->with('kategori:id,nama')
            ->where(function ($q) use ($term) {
                // Contains match untuk hasil luas, dibatasi limit untuk kontrol beban.
                $q->where('nama', 'like', "%{$term}%")
                  ->orWhere('deskripsi', 'like', "%{$term}%");
            })
            ->orderBy('nama')
            ->limit(10)
            ->get();
    }

    public function updatedSearch()
    {
        // Livewire 3 → pakai dispatch, PARAMETER HARUS DI-NAME
        $this->dispatch('search-updated', search: $this->search);
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
        return redirect()->route('search');
    }

    public function openDropdown()
    {
        $this->dropdownOpen = true;
    }

    public function closeDropdown()
    {
        $this->dropdownOpen = false;
    }

    public function render()
    {
        return view('livewire.search-bar');
    }
}
