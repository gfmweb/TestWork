<?php

namespace Tests\Feature;

use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoriesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_endpoint_returns_categories_with_products_count(): void
    {
        $categoryA = ProductCategory::factory()->create(['name' => 'Категория Б']);
        $categoryB = ProductCategory::factory()->create(['name' => 'Категория А']);

        Product::factory()->count(2)->create(['category_id' => $categoryA->id]);
        Product::factory()->count(1)->create(['category_id' => $categoryB->id]);

        $response = $this->getJson('/api/product-categories');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'products_count'],
                ],
            ]);

        $this->assertSame(
            [
                ['id' => $categoryB->id, 'name' => 'Категория А', 'products_count' => 1],
                ['id' => $categoryA->id, 'name' => 'Категория Б', 'products_count' => 2],
            ],
            $response->json('data')
        );
    }
}
