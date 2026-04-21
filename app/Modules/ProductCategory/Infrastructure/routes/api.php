<?php

use App\Modules\ProductCategory\Presentation\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/product-categories/ping', static fn (): array => [
    'module' => 'product-category',
    'status' => 'ok',
]);

Route::get('/product-categories', ProductCategoryController::class);
