<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\User;
use App\Observers\ModelObserver;
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
  public function boot()
{
    Product::observe(ModelObserver::class);
    // Category::observe(ModelObserver::class);
    // User::observe(ModelObserver::class);
    // Sale::observe(ModelObserver::class);
    // Agrega más modelos según necesites
}
}
