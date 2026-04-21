<?php

use App\Modules\Product\Infrastructure\Providers\ProductServiceProvider;
use App\Modules\ProductCategory\Infrastructure\Providers\ProductCategoryServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    ProductServiceProvider::class,
    ProductCategoryServiceProvider::class,
];
