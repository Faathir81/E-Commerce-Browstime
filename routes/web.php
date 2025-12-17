<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MidtransPaymentController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\OrderSuccessController;
use App\Http\Controllers\OrderCompletionController;
use App\Livewire\Payment\UploadProof;
use App\Livewire\Checkout\CheckoutWizard;
use App\Http\Controllers\UlasanController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/checkout', CheckoutWizard::class)->name('checkout');
Route::get('/order-success/{kode}', OrderSuccessController::class)->name('order.success');
Route::get('/orders/{order}/upload-proof', UploadProof::class)->name('order.upload-proof');
Route::post('/ulasans', [UlasanController::class, 'store'])->name('ulasans.store');

Route::get('/products', function () {
    return 'all products here'; // nanti diganti view asli
})->name('product.all');

Route::redirect('/products', '/search')->name('product.redirect');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/payments/midtrans/{kode}', [MidtransPaymentController::class, 'store'])->name('payments.midtrans');
Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handle'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('webhook.midtrans');
// Route::post('/payments/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
//     ->withoutMiddleware([VerifyCsrfToken::class])
//     ->name('payments.midtrans.webhook');
Route::get('/payments/midtrans/finish/{kode}', [MidtransPaymentController::class, 'finish'])
    ->name('payments.midtrans.finish');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/order-success/{kode}/confirm', OrderCompletionController::class)
    ->name('order.confirm');

Route::post('/__ping', fn () => response('pong'))
    ->withoutMiddleware([VerifyCsrfToken::class]);

require __DIR__.'/auth.php';
