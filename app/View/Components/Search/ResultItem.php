<?php

namespace App\View\Components\Search;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ResultItem extends Component
{
    public function __construct(public array $item)
    {
    }

    public function render(): View
    {
        return view('components.search.result-item');
    }
}
