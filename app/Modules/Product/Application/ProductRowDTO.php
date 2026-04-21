<?php

namespace App\Modules\Product\Application;

use Illuminate\Contracts\Support\Arrayable;

final readonly class ProductRowDTO implements Arrayable
{
    public function __construct(
        public int $id,
        public string $name,
        public string $price,
        public int $categoryId,
        public bool $inStock,
        public float $rating,
        public ?string $createdAt,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'category_id' => $this->categoryId,
            'in_stock' => $this->inStock,
            'rating' => $this->rating,
            'created_at' => $this->createdAt,
        ];
    }
}
