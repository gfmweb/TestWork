<?php

namespace App\Modules\ProductCategory\Presentation\Http\Controllers;

use App\Modules\ProductCategory\Application\ListProductCategoriesAction;
use App\Modules\ProductCategory\Application\ProductCategoryRowDTO;
use App\Modules\ProductCategory\Presentation\Http\Requests\ProductCategoryRequest;
use Illuminate\Http\JsonResponse;

final class ProductCategoryController
{
    public function __construct(
        private readonly ListProductCategoriesAction $listProductCategoriesAction,
    ) {}

    public function __invoke(ProductCategoryRequest $request): JsonResponse
    {
        $rows = $this->listProductCategoriesAction->execute($request->toCriteria());

        return response()->json([
            'data' => array_map(
                static fn (ProductCategoryRowDTO $row): array => $row->toArray(),
                $rows
            ),
        ]);
    }
}
