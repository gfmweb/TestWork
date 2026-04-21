<?php

namespace App\Modules\ProductCategory\Application;

interface ProductCategoryRepositoryInterface
{
    /**
     * @return list<ProductCategoryRowDTO>
     */
    public function list(ProductCategoryCriteriaDTO $criteria): array;
}
