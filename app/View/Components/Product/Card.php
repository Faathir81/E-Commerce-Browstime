<?php

namespace App\View\Components\Product;

use App\ViewModels\ProductCardViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(public ProductCardViewModel $product)
    {
    }

    public function render(): View
    {
        return view('components.product.card');
    }
}
