<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes ini dipakai untuk Livewire/Blade (satu domain). Aksi tulis (CRUD)
| dilindungi middleware 'auth'. Publik (katalog) hanya index & show.
| Jika nanti butuh API terpisah, mirror ke routes/api.php dengan sanctum.
|--------------------------------------------------------------------------
*/

// Halaman beranda (opsional)
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|----------------------------------------------------------------------
| Katalog Publik (BACA SAJA)
|----------------------------------------------------------------------
| - Produk: index, show
| - Kategori: index, show
| - Gambar produk: index (lihat daftar gambar per produk)
*/
Route::prefix('catalog')->name('catalog.')->group(function () {
    // Products
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])
        ->whereNumber('product')
        ->name('products.show');

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}', [CategoryController::class, 'show'])
        ->whereNumber('category')
        ->name('categories.show');

    // Product Images (read-only for public)
    Route::get('products/{product}/images', [ProductImageController::class, 'index'])
        ->whereNumber('product')
        ->name('products.images.index');
});

/*
|----------------------------------------------------------------------
| Admin / Backoffice (TULIS: butuh login)
|----------------------------------------------------------------------
| Aksi CRUD produk, kategori, dan manajemen gambar produk.
| Jika pakai Filament sebagai admin, guard default 'web' + 'auth' sudah cukup.
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Products - create/update/delete
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::match(['put', 'patch'], 'products/{product}', [ProductController::class, 'update'])
        ->whereNumber('product')
        ->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])
        ->whereNumber('product')
        ->name('products.destroy');

    // Categories - create/update/delete
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::match(['put', 'patch'], 'categories/{category}', [CategoryController::class, 'update'])
        ->whereNumber('category')
        ->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
        ->whereNumber('category')
        ->name('categories.destroy');

    // Product Images - upload / delete / set-cover (kalau fitur set-cover belum tersedia di schema,
    // controller akan merespons 400 "feature not supported")
    Route::post('products/{product}/images', [ProductImageController::class, 'store'])
        ->whereNumber('product')
        ->name('products.images.store');

    Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])
        ->whereNumber('product')
        ->whereNumber('image')
        ->name('products.images.destroy');

    Route::post('products/{product}/images/{image}/set-cover', [ProductImageController::class, 'setCover'])
        ->whereNumber('product')
        ->whereNumber('image')
        ->name('products.images.set-cover');
});

// Route auth (bawaan Breeze)
require __DIR__ . '/auth.php';
