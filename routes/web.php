<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test-ro', function () {
    $response = Http::withHeaders([
        'key' => config('services.rajaongkir.key'),
        'Accept' => 'application/json',
    ])->get(config('services.rajaongkir.base_url') . '/destination/domestic-destination', [
        'search' => 'jakarta',   // ← WAJIB ADA
    ]);

    return $response->json();
});

require __DIR__.'/auth.php';
