<?php

namespace App\Modules\Product\Infrastructure\Http\Controllers;

use App\Modules\Product\Infrastructure\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductIndexController
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min(100, $request->integer('per_page', 15)));
        $categoryId = $request->integer('category_id');

        $products = Product::query()
            ->select(['id', 'name', 'price', 'category_id'])
            ->when($categoryId !== 0, static fn ($query) => $query->where('category_id', $categoryId))
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return response()->json($products);
    }
}
