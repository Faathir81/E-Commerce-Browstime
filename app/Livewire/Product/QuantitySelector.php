<?php

namespace App\Livewire\Product;

use Livewire\Component;

class QuantitySelector extends Component
{
    public int $productId;
    public int $quantity = 1;
    public int $max;
    public bool $inStock;

    public function mount(int $productId, ?int $max = null, bool $inStock = true): void
    {
        $this->productId = $productId;
        $this->max = $max ?? 100;
        $this->inStock = $inStock;

        if (! $this->inStock && $this->quantity > $this->max) {
            $this->quantity = max(0, $this->max);
        }
    }

    public function increment(): void
    {
        if (! $this->inStock) {
            return;
        }

        if ($this->quantity < $this->max) {
            $this->quantity++;
        }
    }

    public function decrement(): void
    {
        if (! $this->inStock) {
            return;
        }

        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedQuantity($value): void
    {
        $val = (int) $value;
        if ($val < 1) {
            $val = 1;
        }
        if ($val > $this->max) {
            $val = $this->max;
        }

        $this->quantity = $val;
    }

    public function render()
    {
        return view('livewire.product.quantity-selector');
    }
}
