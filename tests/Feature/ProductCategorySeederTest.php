<?php

namespace Tests\Feature;

use App\Modules\ProductCategory\Infrastructure\database\seeders\ProductCategorySeeder;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategorySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_category_seeder_creates_25_unique_russian_categories(): void
    {
        $this->seed(ProductCategorySeeder::class);

        $this->assertSame(25, ProductCategory::query()->count());
        $this->assertSame(25, ProductCategory::query()->distinct('name')->count('name'));
        $this->assertSame(25, ProductCategory::query()->distinct('slug')->count('slug'));
    }
}
