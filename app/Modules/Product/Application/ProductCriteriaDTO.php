<?php

namespace App\Modules\Product\Application;

final readonly class ProductCriteriaDTO
{
    public function __construct(
        public int $perPage = 15,
        public string $sort = 'newest',
        public ?string $searchQuery = null,
        public ?float $priceFrom = null,
        public ?float $priceTo = null,
        public ?int $categoryId = null,
        public ?bool $inStock = null,
        public ?float $ratingFrom = null,
    ) {}
}
