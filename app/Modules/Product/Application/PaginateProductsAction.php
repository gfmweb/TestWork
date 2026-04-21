<?php

namespace App\Modules\Product\Application;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PaginateProductsAction
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    /**
     * @return LengthAwarePaginator<int, ProductRowDTO>
     */
    public function execute(ProductCriteriaDTO $criteria): LengthAwarePaginator
    {
        return $this->productRepository->paginate($criteria);
    }
}
