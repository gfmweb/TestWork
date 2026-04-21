<?php

use App\Modules\Product\Infrastructure\Http\Controllers\ProductIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/products/ping', static fn (): array => [
    'module' => 'product',
    'status' => 'ok',
]);

Route::get('/products', ProductIndexController::class);
