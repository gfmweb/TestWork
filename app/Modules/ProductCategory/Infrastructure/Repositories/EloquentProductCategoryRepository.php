<?php

namespace App\Modules\ProductCategory\Infrastructure\Repositories;

use App\Modules\ProductCategory\Application\ProductCategoryCriteriaDTO;
use App\Modules\ProductCategory\Application\ProductCategoryRepositoryInterface;
use App\Modules\ProductCategory\Application\ProductCategoryRowDTO;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;

final class EloquentProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function list(ProductCategoryCriteriaDTO $criteria): array
    {
        $query = ProductCategory::query()
            ->select(['id', 'name'])
            ->withCount('products');

        if ($criteria->withTrashed) {
            $query->withTrashed();
        }

        return $query
            ->orderBy('name')
            ->get()
            ->map(static function (ProductCategory $category): ProductCategoryRowDTO {
                return new ProductCategoryRowDTO(
                    id: (int) $category->getKey(),
                    name: (string) $category->getAttribute('name'),
                    productsCount: (int) $category->getAttribute('products_count'),
                );
            })
            ->all();
    }
}
