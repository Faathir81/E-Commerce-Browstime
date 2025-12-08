<?php

namespace App\Livewire;

use Livewire\Component;

class CartIcon extends Component
{
    public int $totalQuantity = 0;

    protected $listeners = [
        'cartUpdated' => 'updateCount',
    ];

    public function mount()
    {
        $this->totalQuantity = $this->getSessionCount();
    }

    public function render()
    {
        $this->totalQuantity = $this->getSessionCount();
        return view('livewire.cart-icon');
    }

    public function updateCount(int $count): void
    {
        $this->totalQuantity = max(0, $count);
    }

    protected function getSessionCount(): int
    {
        $cart = session('cart', []);
        return (int) array_sum($cart);
    }
}
