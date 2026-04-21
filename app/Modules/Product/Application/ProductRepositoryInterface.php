<?php

namespace App\Modules\Product\Application;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, ProductRowDTO>
     */
    public function paginate(ProductCriteriaDTO $criteria): LengthAwarePaginator;
}
