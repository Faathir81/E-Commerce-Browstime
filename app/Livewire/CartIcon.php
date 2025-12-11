<?php

namespace App\Livewire;

use Livewire\Component;

class CartIcon extends Component
{
    public int $totalQuantity = 0;
    public bool $showBadge = false;
    public string $cartUrl = '/cart';

    protected $listeners = [
        'cartUpdated' => 'updateCount',
    ];

    public function mount()
    {
        $this->cartUrl = url('/cart');
        $this->refreshState();
    }

    public function render()
    {
        $this->refreshState();
        return view('livewire.cart-icon');
    }

    public function updateCount(int $count): void
    {
        $this->totalQuantity = max(0, $count);
        $this->showBadge = $this->totalQuantity > 0;
    }

    protected function getSessionCount(): int
    {
        $cart = session('cart', []);
        return (int) array_sum($cart);
    }

    protected function refreshState(): void
    {
        $this->totalQuantity = $this->getSessionCount();
        $this->showBadge = $this->totalQuantity > 0;
    }
}
