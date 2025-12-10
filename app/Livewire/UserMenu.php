<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserMenu extends Component
{
    public bool $isAuthenticated = false;
    public ?string $profileUrl = null;
    public ?string $loginUrl = null;

    public function mount(): void
    {
        $this->isAuthenticated = Auth::check();
        $this->profileUrl = $this->isAuthenticated ? route('profile.edit') : null;
        $this->loginUrl = $this->isAuthenticated ? null : route('login');
    }

    public function render()
    {
        return view('livewire.user-menu');
    }
}
