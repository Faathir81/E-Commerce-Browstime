<?php

namespace App\Livewire;

use Livewire\Component;

class Navbar extends Component
{
    public bool $hideSearchOnMobile = false;
    public bool $showBackButton = false;
    public string $backUrl;

    public function mount(): void
    {
        $this->backUrl = route('landing');
        $this->hydrateState();
    }

    public function render()
    {
        $this->hydrateState();

        return view('livewire.navbar');
    }

    protected function hydrateState(): void
    {
        $this->hideSearchOnMobile = $this->shouldHideSearchOnMobile();
        $this->showBackButton = $this->shouldShowBackButton();
    }

    protected function shouldHideSearchOnMobile(): bool
    {
        return request()->routeIs('landing')
            || request()->routeIs('search')
            || request()->routeIs('order.success')
            || request()->routeIs('product.show');
    }

    protected function shouldShowBackButton(): bool
    {
        return request()->routeIs('search') || request()->routeIs('product.show');
    }
}
