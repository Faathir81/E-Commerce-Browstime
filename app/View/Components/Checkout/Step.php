<?php

namespace App\View\Components\Checkout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Step extends Component
{
    public function __construct(
        public int $step,
        public string $stepView,
        public array $config,
        public array $stepData = [],
    ) {
    }

    public function render(): View
    {
        return view('components.checkout.step');
    }
}
