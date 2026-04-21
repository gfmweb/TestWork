<?php

namespace App\Modules\ProductCategory\Infrastructure\Providers;

use App\Modules\ProductCategory\Application\ProductCategoryRepositoryInterface;
use App\Modules\ProductCategory\Infrastructure\Repositories\EloquentProductCategoryRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProductCategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductCategoryRepositoryInterface::class, EloquentProductCategoryRepository::class);

        $this->mergeConfigFrom(
            __DIR__.'/../config/product-category.php',
            'product_category',
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'product-category');

        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__.'/../routes/api.php');
    }
}
