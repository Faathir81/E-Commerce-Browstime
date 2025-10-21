<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductImageController;

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

// Resources (tanpa middleware)
Route::resource('products', ProductController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy'])
    ->names('products');

Route::resource('categories', CategoryController::class)
    ->only(['index', 'show', 'store', 'update', 'destroy'])
    ->names('categories');

// Nested product images di bawah products
Route::prefix('products/{product}')->group(function () {
    Route::get('images', [ProductImageController::class, 'index'])->name('product-images.index');
    Route::post('images', [ProductImageController::class, 'store'])->name('product-images.store');
    Route::delete('images/{image}', [ProductImageController::class, 'destroy'])->name('product-images.destroy');

    // set-cover endpoint (optional, only supported if column exists)
    Route::post('images/{image}/set-cover', [ProductImageController::class, 'setCover'])
        ->name('product-images.set-cover');
});

require __DIR__.'/auth.php';
