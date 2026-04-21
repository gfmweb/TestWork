<?php

namespace App\Modules\Product\Presentation\Http\Controllers;

use App\Modules\Product\Application\PaginateProductsAction;
use App\Modules\Product\Presentation\Http\Requests\ProductRequest;
use Illuminate\Http\JsonResponse;

final class ProductController
{
    public function __construct(
        private readonly PaginateProductsAction $paginateProductsAction,
    ) {}

    public function __invoke(ProductRequest $request): JsonResponse
    {
        return response()->json(
            $this->paginateProductsAction->execute($request->toCriteria())
        );
    }
}
