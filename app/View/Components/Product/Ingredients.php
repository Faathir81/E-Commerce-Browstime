<?php

namespace App\View\Components\Product;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Ingredients extends Component
{
    public function __construct(public array $items)
    {
    }

    public function render(): View
    {
        return view('components.product.ingredients');
    }
}
