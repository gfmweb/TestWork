<?php

namespace App\Modules\Product\Infrastructure\Repositories;

use App\Modules\Product\Application\ProductCriteriaDTO;
use App\Modules\Product\Application\ProductRepositoryInterface;
use App\Modules\Product\Application\ProductRowDTO;
use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\Product\Infrastructure\Query\ProductListSortApplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

final class EloquentProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly ProductListSortApplier $sortApplier,
    ) {}

    public function paginate(ProductCriteriaDTO $criteria): LengthAwarePaginator
    {
        $driver = Schema::getConnection()->getDriverName();

        $query = Product::query()
            ->select(['id', 'name', 'price', 'category_id', 'in_stock', 'rating', 'created_at'])
            ->when(
                $criteria->searchQuery !== null && $criteria->searchQuery !== '',
                function (Builder $q) use ($criteria, $driver): void {
                    if (in_array($driver, ['mysql', 'mariadb', 'pgsql'], true)) {
                        $q->whereFullText(['name'], $criteria->searchQuery);
                    } else {
                        $q->whereRaw(
                            'LOWER(name) LIKE ?',
                            ['%'.mb_strtolower((string) $criteria->searchQuery).'%']
                        );
                    }
                }
            )
            ->when($criteria->priceFrom !== null, static fn (Builder $q) => $q->where('price', '>=', $criteria->priceFrom))
            ->when($criteria->priceTo !== null, static fn (Builder $q) => $q->where('price', '<=', $criteria->priceTo))
            ->when($criteria->categoryId !== null, static fn (Builder $q) => $q->where('category_id', $criteria->categoryId))
            ->when($criteria->inStock !== null, static fn (Builder $q) => $q->where('in_stock', $criteria->inStock))
            ->when($criteria->ratingFrom !== null, static fn (Builder $q) => $q->where('rating', '>=', $criteria->ratingFrom));

        $this->sortApplier->apply($query, $criteria->sort);

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
