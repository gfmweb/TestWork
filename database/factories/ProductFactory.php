<?php

namespace Database\Factories;

use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = 'Товар '.$this->faker->unique()->numberBetween(1, 1000000);

        return [
            'name' => $name,
            'slug' => app(SlugService::class)->fromTitle($name),
            'price' => $this->faker->randomFloat(2, 10, 150000),
            'category_id' => ProductCategory::query()->inRandomOrder()->value('id') ?? ProductCategory::factory()->create()->id,
            'in_stock' => $this->faker->boolean(75),
            'rating' => $this->faker->randomFloat(1, 0, 5),
        ];
    }
}
