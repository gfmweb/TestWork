<?php

namespace App\Modules\ProductCategory\Infrastructure\Http\Controllers;

use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use Illuminate\Http\JsonResponse;

class ProductCategoryIndexController
{
    public function __invoke(): JsonResponse
    {
        $categories = ProductCategory::query()
            ->select(['id', 'name'])
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $categories,
        ]);
    }
}
