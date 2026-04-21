<?php

namespace App\Modules\ProductCategory\Application;

use App\Modules\ProductCategory\Infrastructure\Interfaces\ProductCategoryRepositoryInterface;

final class ListProductCategoriesAction
{
    public function __construct(
        private readonly ProductCategoryRepositoryInterface $productCategoryRepository,
    ) {}

    /**
     * @return list<ProductCategoryRowDTO>
     */
    public function execute(ProductCategoryCriteriaDTO $criteria): array
    {
        return $this->productCategoryRepository->list($criteria);
    }
}
