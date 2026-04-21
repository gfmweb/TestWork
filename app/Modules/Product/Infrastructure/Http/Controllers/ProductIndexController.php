<?php

namespace App\Modules\Product\Infrastructure\Http\Controllers;

use App\Modules\Product\Infrastructure\Http\Requests\ProductIndexRequest;
use App\Modules\Product\Infrastructure\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class ProductIndexController
{
    public function __invoke(ProductIndexRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $perPage = (int) ($validated['per_page'] ?? 15);
        $sort = (string) ($validated['sort'] ?? 'newest');
        $q = isset($validated['q']) ? trim((string) $validated['q']) : null;
        $priceFrom = isset($validated['price_from']) ? (float) $validated['price_from'] : null;
        $priceTo = isset($validated['price_to']) ? (float) $validated['price_to'] : null;
        $categoryId = isset($validated['category_id']) ? (int) $validated['category_id'] : null;
        $inStock = array_key_exists('in_stock', $validated)
            ? filter_var($validated['in_stock'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE)
            : null;
        $ratingFrom = isset($validated['rating_from']) ? (float) $validated['rating_from'] : null;

        $products = Product::query()
            ->select(['id', 'name', 'price', 'category_id', 'in_stock', 'rating', 'created_at'])
            ->when(
                $q !== null && $q !== '',
                static fn (Builder $query) => $query->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($q).'%'])
            )
            ->when($priceFrom !== null, static fn (Builder $query) => $query->where('price', '>=', $priceFrom))
            ->when($priceTo !== null, static fn (Builder $query) => $query->where('price', '<=', $priceTo))
            ->when($categoryId !== null, static fn (Builder $query) => $query->where('category_id', $categoryId))
            ->when($inStock !== null, static fn (Builder $query) => $query->where('in_stock', $inStock))
            ->when($ratingFrom !== null, static fn (Builder $query) => $query->where('rating', '>=', $ratingFrom));

        match ($sort) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'rating_desc' => $products->orderByDesc('rating'),
            default => $products->orderByDesc('created_at')->orderByDesc('id'),
        };

        $paginatedProducts = $products
            ->paginate($perPage)
            ->withQueryString();

        return response()->json($paginatedProducts);
    }
}
