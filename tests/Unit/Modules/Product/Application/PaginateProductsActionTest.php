<?php

namespace Tests\Unit\Modules\Product\Application;

use App\Modules\Product\Application\PaginateProductsAction;
use App\Modules\Product\Application\ProductCriteriaDTO;
use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginateProductsActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_execute_applies_default_per_page_and_returns_total(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->count(20)->create(['category_id' => $category->id]);

        $action = app(PaginateProductsAction::class);
        $result = $action->execute(new ProductCriteriaDTO);

        $this->assertSame(15, $result->perPage());
        $this->assertSame(20, $result->total());
    }

    public function test_execute_filters_by_category_id(): void
    {
        $categoryA = ProductCategory::factory()->create();
        $categoryB = ProductCategory::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $categoryA->id]);
        Product::factory()->count(2)->create(['category_id' => $categoryB->id]);

        $action = app(PaginateProductsAction::class);
        $result = $action->execute(new ProductCriteriaDTO(categoryId: $categoryA->id));

        $this->assertSame(3, $result->total());
        foreach ($result->items() as $product) {
            $this->assertSame($categoryA->id, $product->categoryId);
        }
    }
}
