<?php

namespace App\View\Components\Product;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StockBadge extends Component
{
    public function __construct(public bool $inStock)
    {
    }

    public function render(): View
    {
        return view('components.product.stock-badge');
    }
}
