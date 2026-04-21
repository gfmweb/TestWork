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

    public function test_categories_endpoint_excludes_soft_deleted_by_default(): void
    {
        $active = ProductCategory::factory()->create(['name' => 'Видимая']);
        $deleted = ProductCategory::factory()->create(['name' => 'Скрытая']);
        $deleted->delete();

        $response = $this->getJson('/api/product-categories');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame($active->id, $response->json('data.0.id'));
    }

    public function test_categories_endpoint_includes_soft_deleted_when_with_trashed_true(): void
    {
        $active = ProductCategory::factory()->create(['name' => 'Видимая']);
        $deleted = ProductCategory::factory()->create(['name' => 'Скрытая']);
        $deleted->delete();

        $response = $this->getJson('/api/product-categories?with_trashed=true');

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    public function test_categories_endpoint_validation_rejects_invalid_with_trashed(): void
    {
        app()->setLocale('ru');

        $response = $this->getJson('/api/product-categories?with_trashed=yes');

        $response->assertUnprocessable()
            ->assertJsonPath(
                'errors.with_trashed.0',
                trans('product-category::validation.with_trashed.in')
            );
    }
}
