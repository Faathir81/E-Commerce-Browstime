<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (API style)
|--------------------------------------------------------------------------
| Semua endpoint API tetap di file web.php karena kita tidak pakai api.php.
| CSRF middleware dinonaktifkan untuk prefix "api/v1" agar bisa diakses via Postman.
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Catalog\CategoryController;
use App\Http\Controllers\Catalog\ProductController;
use App\Http\Controllers\Catalog\ProductImageController;
use App\Http\Controllers\Order\CartController;
use App\Http\Controllers\Order\CheckoutController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\MidtransWebhookController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Shipping\ShippingController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api/v1')
->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class])
->group(function () {
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

    require __DIR__.'/auth.php';

    /*
    |----------------------------------------------------
    | Catalog
    |----------------------------------------------------
    */
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{key}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);

        // Product Images
        Route::get('/{productId}/images', [ProductImageController::class, 'index']);
        Route::post('/{productId}/images', [ProductImageController::class, 'store']);
    });
    Route::delete('/product-images/{image}', [ProductImageController::class, 'destroy']);

    /*
    |----------------------------------------------------
    | Order & Cart
    |----------------------------------------------------
    */
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'add']);
        Route::post('/update', [CartController::class, 'update']);
        Route::delete('/remove/{productId}', [CartController::class, 'remove']);
        Route::delete('/clear', [CartController::class, 'clear']);
    });

    Route::prefix('checkout')->group(function () {
        Route::post('/', [CheckoutController::class, 'store']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{code}', [OrderController::class, 'show']);
        Route::post('/{order}/confirm', [OrderController::class, 'confirmReceived']);
    });

    /*
    |----------------------------------------------------
    | Payment
    |----------------------------------------------------
    */
    Route::prefix('payments')->group(function () {
        Route::post('/', [PaymentController::class, 'create']);
        Route::post('/{paymentId}/proof', [PaymentController::class, 'uploadProof']);
        Route::get('/{orderCode}/status', [PaymentController::class, 'status']);
    });

    // Midtrans webhook (jangan kena CSRF)
    Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle']);

    /*
    |----------------------------------------------------
    | Reports
    |----------------------------------------------------
    */
    Route::prefix('reports')->group(function () {
        Route::get('/sales-daily', [ReportController::class, 'salesDaily']);
        Route::get('/stock-current', [ReportController::class, 'stockCurrent']);
        Route::get('/dashboard-summary', [ReportController::class, 'dashboardSummary']);
    });

    /*
    |----------------------------------------------------
    | Shipping (Binderbyte)
    |----------------------------------------------------
    */
    Route::prefix('shipping')->group(function () {
        Route::get('/provinces', [ShippingController::class, 'provinces']);
        Route::get('/provinces/{provinceId}/cities', [ShippingController::class, 'cities']);
        Route::get('/cities/{cityId}/districts', [ShippingController::class, 'districts']);
        Route::post('/estimate', [ShippingController::class, 'estimate']);
    });
});

