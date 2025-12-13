<?php

namespace App\View\Components\Checkout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Totals extends Component
{
    public function __construct(
        public string $subtotal,
        public string $shipping,
        public string $total,
    ) {
    }

    public function render(): View
    {
        return view('components.checkout.totals');
    }
}
