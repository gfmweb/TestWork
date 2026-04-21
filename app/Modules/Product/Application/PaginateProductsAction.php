<?php

namespace App\Modules\Product\Application;

use App\Modules\Product\Infrastructure\Interfaces\ProductRepositoryInterface;
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
