<?php

namespace App\Modules\ProductCategory\Application;

final readonly class ProductCategoryCriteriaDTO
{
    public function __construct(
        public bool $withTrashed = false,
    ) {}
}
