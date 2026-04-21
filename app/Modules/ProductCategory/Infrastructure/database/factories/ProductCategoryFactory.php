<?php

namespace App\Modules\ProductCategory\Infrastructure\database\factories;

use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        $name = 'Категория '.$this->faker->unique()->numberBetween(1, 1000000);

        return [
            'name' => $name,
            'slug' => app(SlugService::class)->fromTitle($name),
        ];
    }
}
