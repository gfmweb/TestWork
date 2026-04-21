<?php

namespace Tests\Feature;

use App\Modules\Product\Infrastructure\database\seeders\ProductSeeder;
use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Infrastructure\database\seeders\ProductCategorySeeder;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_seeder_creates_250_products_with_valid_fields_and_relations(): void
    {
        $this->seed([
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);

        $this->assertSame(250, Product::query()->count());
        $this->assertGreaterThanOrEqual(1, ProductCategory::query()->count());
        $this->assertSame(250, Product::query()->whereNotNull('category_id')->count());
        $this->assertSame(250, Product::query()->where('price', '>', 0)->count());
        $this->assertSame(250, Product::query()->where('name', '!=', '')->count());
        $this->assertSame(250, Product::query()->where('slug', '!=', '')->count());
        $this->assertSame(250, Product::query()->distinct('slug')->count('slug'));
        $this->assertSame(250, Product::query()->whereBetween('rating', [0, 5])->count());
    }
}
