<?php

use App\Modules\Product\Presentation\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products/ping', static fn (): array => [
    'module' => 'product',
    'status' => 'ok',
]);

Route::get('/products', ProductController::class);
