<?php

namespace App\Modules\Product\Infrastructure\Repositories;

use App\Modules\Product\Application\ProductCriteriaDTO;
use App\Modules\Product\Application\ProductRowDTO;
use App\Modules\Product\Infrastructure\Interfaces\ProductRepositoryInterface;
use App\Modules\Product\Infrastructure\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

final class EloquentProductRepository implements ProductRepositoryInterface
{
    public function paginate(ProductCriteriaDTO $criteria): LengthAwarePaginator
    {
        $query = Product::query()
            ->select(['id', 'name', 'price', 'category_id', 'in_stock', 'rating', 'created_at'])
            ->when(
                $criteria->searchQuery !== null && $criteria->searchQuery !== '',
                static fn (Builder $q) => $q->whereRaw(
                    'LOWER(name) LIKE ?',
                    ['%'.mb_strtolower($criteria->searchQuery).'%']
                )
            )
            ->when($criteria->priceFrom !== null, static fn (Builder $q) => $q->where('price', '>=', $criteria->priceFrom))
            ->when($criteria->priceTo !== null, static fn (Builder $q) => $q->where('price', '<=', $criteria->priceTo))
            ->when($criteria->categoryId !== null, static fn (Builder $q) => $q->where('category_id', $criteria->categoryId))
            ->when($criteria->inStock !== null, static fn (Builder $q) => $q->where('in_stock', $criteria->inStock))
            ->when($criteria->ratingFrom !== null, static fn (Builder $q) => $q->where('rating', '>=', $criteria->ratingFrom));

        match ($criteria->sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating_desc' => $query->orderByDesc('rating'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        return $query
            ->paginate($criteria->perPage)
            ->withQueryString()
            ->through(function (Product $product): ProductRowDTO {
                $createdAt = $product->getAttribute('created_at');

                return new ProductRowDTO(
                    id: (int) $product->getKey(),
                    name: (string) $product->getAttribute('name'),
                    price: (string) $product->getAttribute('price'),
                    categoryId: (int) $product->getAttribute('category_id'),
                    inStock: (bool) $product->getAttribute('in_stock'),
                    rating: (float) $product->getAttribute('rating'),
                    createdAt: $createdAt instanceof Carbon ? $createdAt->toIso8601String() : null,
                );
            });
    }
}
