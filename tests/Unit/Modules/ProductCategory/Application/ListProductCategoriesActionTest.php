<?php

namespace Tests\Unit\Modules\ProductCategory\Application;

use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Application\ListProductCategoriesAction;
use App\Modules\ProductCategory\Application\ProductCategoryCriteriaDTO;
use App\Modules\ProductCategory\Application\ProductCategoryRowDTO;
use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductCategoriesActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_execute_returns_categories_ordered_by_name_with_product_counts(): void
    {
        $categoryA = ProductCategory::factory()->create(['name' => 'Категория Б']);
        $categoryB = ProductCategory::factory()->create(['name' => 'Категория А']);

        Product::factory()->count(2)->create(['category_id' => $categoryA->id]);
        Product::factory()->count(1)->create(['category_id' => $categoryB->id]);

        $action = app(ListProductCategoriesAction::class);
        $rows = $action->execute(new ProductCategoryCriteriaDTO);

        $this->assertCount(2, $rows);
        $this->assertInstanceOf(ProductCategoryRowDTO::class, $rows[0]);
        $this->assertInstanceOf(ProductCategoryRowDTO::class, $rows[1]);

        $this->assertSame(
            [
                ['id' => $categoryB->id, 'name' => 'Категория А', 'products_count' => 1],
                ['id' => $categoryA->id, 'name' => 'Категория Б', 'products_count' => 2],
            ],
            array_map(static fn (ProductCategoryRowDTO $row): array => $row->toArray(), $rows)
        );
    }

    public function test_execute_excludes_soft_deleted_categories_by_default(): void
    {
        $active = ProductCategory::factory()->create(['name' => 'Активная']);
        $deleted = ProductCategory::factory()->create(['name' => 'Удалённая']);
        $deleted->delete();

        $action = app(ListProductCategoriesAction::class);
        $rows = $action->execute(new ProductCategoryCriteriaDTO);

        $this->assertCount(1, $rows);
        $this->assertSame($active->id, $rows[0]->id);
    }

    public function test_execute_includes_soft_deleted_when_with_trashed_is_true(): void
    {
        $active = ProductCategory::factory()->create(['name' => 'Активная']);
        $deleted = ProductCategory::factory()->create(['name' => 'Удалённая']);
        $deleted->delete();

        $action = app(ListProductCategoriesAction::class);
        $rows = $action->execute(new ProductCategoryCriteriaDTO(withTrashed: true));

        $this->assertCount(2, $rows);
        $ids = array_map(static fn (ProductCategoryRowDTO $row): int => $row->id, $rows);
        sort($ids);
        $expected = [$active->id, $deleted->id];
        sort($expected);
        $this->assertSame($expected, $ids);
    }
}
