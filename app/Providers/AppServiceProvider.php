<?php

namespace App\Providers;

use App\Http\Responses\FilamentLoginResponse;
use App\Models\Pesanan;
use App\Observers\PesananObserver;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Listeners\AssignPelangganRole;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginResponse::class, FilamentLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Pesanan::observe(PesananObserver::class);
        Event::listen(
            Registered::class,
            AssignPelangganRole::class
        );
    }
}
