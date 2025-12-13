<?php

namespace App\View\Components\Product;

use App\Models\Kategori;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CategoryBadge extends Component
{
    public function __construct(public ?Kategori $category)
    {
    }

    public function render(): View
    {
        return view('components.product.category-badge');
    }
}
