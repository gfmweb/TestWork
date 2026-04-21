<?php

namespace App\Modules\Product\Infrastructure\Interfaces;

use App\Modules\Product\Application\ProductCriteriaDTO;
use App\Modules\Product\Application\ProductRowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, ProductRowDTO>
     */
    public function paginate(ProductCriteriaDTO $criteria): LengthAwarePaginator;
}
