<?php

use Illuminate\Support\Facades\Route;

Route::get('/products/ping', static fn (): array => [
    'module' => 'product',
    'status' => 'ok',
]);
