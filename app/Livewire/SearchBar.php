<?php

namespace App\Livewire;

use Livewire\Component;

class SearchBar extends Component
{
    public $search = '';

    protected $listeners = ['searchCategory' => 'setCategory'];

    public function setCategory($keyword)
    {
        $this->search = $keyword;
        $this->updatedSearch();
    }

    public function updatedSearch()
    {
        // logika search kamu di sini
    }

    public function render()
    {
        return view('livewire.search-bar');
    }
}
