<?php

namespace App\View\Components\Checkout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SummaryItem extends Component
{
    public function __construct(public array $item)
    {
    }

    public function render(): View
    {
        return view('components.checkout.summary-item');
    }
}
