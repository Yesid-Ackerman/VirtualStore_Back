<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\User;
use App\Observers\ModelObserver;
use App\Observers\ProductObserver;
use App\Observers\SaleObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);;
        Sale::observe(SaleObserver::class);
    }
}
