<?php

use App\Modules\ProductCategory\Infrastructure\Http\Controllers\ProductCategoryIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/product-categories/ping', static fn (): array => [
    'module' => 'product-category',
    'status' => 'ok',
]);

Route::get('/product-categories', ProductCategoryIndexController::class);
