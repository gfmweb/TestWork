<?php

namespace App\Modules\ProductCategory\Infrastructure\Interfaces;

use App\Modules\ProductCategory\Application\ProductCategoryCriteriaDTO;
use App\Modules\ProductCategory\Application\ProductCategoryRowDTO;

interface ProductCategoryRepositoryInterface
{
    /**
     * @return list<ProductCategoryRowDTO>
     */
    public function list(ProductCategoryCriteriaDTO $criteria): array;
}
