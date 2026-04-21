<?php

use Illuminate\Support\Facades\Route;

Route::get('/product-categories/ping', static fn (): array => [
    'module' => 'product-category',
    'status' => 'ok',
]);
