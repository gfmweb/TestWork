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

    public function test_products_endpoint_filters_by_name_substring(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Laptop gaming']);
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Smartphone']);

        $response = $this->getJson('/api/products?q=laptop');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Laptop gaming');
    }

    public function test_products_endpoint_filters_by_price_range(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'price' => 100]);
        Product::factory()->create(['category_id' => $category->id, 'price' => 250]);
        Product::factory()->create(['category_id' => $category->id, 'price' => 400]);

        $response = $this->getJson('/api/products?price_from=150&price_to=300');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.price', '250.00');
    }

    public function test_products_endpoint_filters_by_in_stock(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'in_stock' => true]);
        Product::factory()->create(['category_id' => $category->id, 'in_stock' => false]);

        $response = $this->getJson('/api/products?in_stock=true');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.in_stock', true);
    }

    public function test_products_endpoint_filters_by_rating_from(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'rating' => 2.4]);
        Product::factory()->create(['category_id' => $category->id, 'rating' => 4.8]);

        $response = $this->getJson('/api/products?rating_from=4');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.rating', 4.8);
    }

    public function test_products_endpoint_sorts_by_price_ascending(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'price' => 300]);
        Product::factory()->create(['category_id' => $category->id, 'price' => 100]);
        Product::factory()->create(['category_id' => $category->id, 'price' => 200]);

        $response = $this->getJson('/api/products?sort=price_asc');

        $response->assertOk();
        $prices = array_map(
            static fn (array $item): float => (float) $item['price'],
            $response->json('data')
        );
        $this->assertSame([100.0, 200.0, 300.0], $prices);
    }

    public function test_products_endpoint_sorts_by_price_descending(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'price' => 100]);
        Product::factory()->create(['category_id' => $category->id, 'price' => 300]);
        Product::factory()->create(['category_id' => $category->id, 'price' => 200]);

        $response = $this->getJson('/api/products?sort=price_desc');

        $response->assertOk();
        $prices = array_map(
            static fn (array $item): float => (float) $item['price'],
            $response->json('data')
        );
        $this->assertSame([300.0, 200.0, 100.0], $prices);
    }

    public function test_products_endpoint_sorts_by_rating_descending(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'rating' => 1.2]);
        Product::factory()->create(['category_id' => $category->id, 'rating' => 4.9]);
        Product::factory()->create(['category_id' => $category->id, 'rating' => 3.4]);

        $response = $this->getJson('/api/products?sort=rating_desc');

        $response->assertOk();
        $ratings = array_map(
            static fn (array $item): float => (float) $item['rating'],
            $response->json('data')
        );
        $this->assertSame([4.9, 3.4, 1.2], $ratings);
    }

    public function test_products_endpoint_sorts_by_newest_created_at_desc(): void
    {
        $category = ProductCategory::factory()->create();

        $older = Product::factory()->create(['category_id' => $category->id, 'created_at' => now()->subDays(2)]);
        $newer = Product::factory()->create(['category_id' => $category->id, 'created_at' => now()->subDay()]);
        $newest = Product::factory()->create(['category_id' => $category->id, 'created_at' => now()]);

        $response = $this->getJson('/api/products?sort=newest');

        $response->assertOk();
        $ids = array_map(
            static fn (array $item): int => (int) $item['id'],
            $response->json('data')
        );
        $this->assertSame([$newest->id, $newer->id, $older->id], $ids);
    }

    public function test_products_endpoint_validation_uses_module_translations(): void
    {
        ProductCategory::factory()->create();

        $response = $this->getJson('/api/products?per_page=0');

        $response->assertUnprocessable()
            ->assertJsonPath(
                'errors.per_page.0',
                trans('product::validation.per_page.min', ['min' => 1])
            );
    }

    public function test_products_endpoint_rejects_soft_deleted_category_id(): void
    {
        app()->setLocale('ru');

        $category = ProductCategory::factory()->create();
        $category->delete();

        $response = $this->getJson('/api/products?category_id='.$category->id);

        $response->assertUnprocessable()
            ->assertJsonPath(
                'errors.category_id.0',
                trans('product::validation.category_id.exists')
            );
    }
}
