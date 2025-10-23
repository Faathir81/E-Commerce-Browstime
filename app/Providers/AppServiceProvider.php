<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Events\OrderPaid;
use App\Events\PaymentVerified;
use App\Listeners\SendOrderStatusNotification;
use App\Listeners\ReduceStockOnOrderPaid;
use App\Models\ProductImage;
use App\Observers\ProductImageObserver;
use App\Services\Catalog\CategoryService;
use App\Services\Catalog\ProductService;
use App\Services\Catalog\ProductImageService;
use App\Services\Order\CartService;
use App\Services\Order\OrderService;
use App\Services\Payment\PaymentService;
use App\Services\Payment\MidtransService;
use App\Services\Stock\RecipeService;
use App\Services\Stock\StockService;
use App\Services\Report\ReportService;
use App\Services\Shipping\ShippingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CategoryService::class);
        $this->app->singleton(ProductService::class);
        $this->app->singleton(ProductImageService::class);
        $this->app->singleton(CartService::class);
        $this->app->singleton(OrderService::class);
        $this->app->singleton(PaymentService::class);
        $this->app->singleton(MidtransService::class);
        $this->app->singleton(RecipeService::class);
        $this->app->singleton(StockService::class);
        $this->app->singleton(ReportService::class);
        $this->app->singleton(ShippingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(OrderPaid::class, ReduceStockOnOrderPaid::class);
        Event::listen(PaymentVerified::class, SendOrderStatusNotification::class);
        ProductImage::observe(ProductImageObserver::class);
    }
}
