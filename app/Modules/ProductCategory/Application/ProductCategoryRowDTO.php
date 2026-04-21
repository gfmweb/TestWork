<?php

namespace App\Modules\ProductCategory\Application;

use Illuminate\Contracts\Support\Arrayable;

final readonly class ProductCategoryRowDTO implements Arrayable
{
    public function __construct(
        public int $id,
        public string $name,
        public int $productsCount,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'products_count' => $this->productsCount,
        ];
    }
}
