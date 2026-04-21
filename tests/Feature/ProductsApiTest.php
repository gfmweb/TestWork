<?php

namespace Tests\Feature;

use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_endpoint_returns_paginated_items_with_default_per_page_15(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->count(20)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('per_page', 15)
            ->assertJsonPath('total', 20);
    }

    public function test_products_endpoint_filters_by_category_id(): void
    {
        $categoryA = ProductCategory::factory()->create();
        $categoryB = ProductCategory::factory()->create();

        Product::factory()->count(3)->create(['category_id' => $categoryA->id]);
        Product::factory()->count(2)->create(['category_id' => $categoryB->id]);

        $response = $this->getJson('/api/products?category_id='.$categoryA->id);

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('total', 3);

        foreach ($response->json('data') as $product) {
            $this->assertSame($categoryA->id, $product['category_id']);
        }
    }
}
