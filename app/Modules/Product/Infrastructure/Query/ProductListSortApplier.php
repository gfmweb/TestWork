<?php

namespace App\Modules\Product\Infrastructure\Query;

use App\Modules\Product\Infrastructure\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final class ProductListSortApplier
{
    /**
     * @param  Builder<Product>  $query
     */
    public function apply(Builder $query, string $sort): void
    {
        $default = static function (Builder $q): void {
            $q->orderByDesc('created_at')->orderByDesc('id');
        };

        /** @var array<string, callable(Builder): void> $strategies */
        $strategies = [
            'price_asc' => static fn (Builder $q) => $q->orderBy('price'),
            'price_desc' => static fn (Builder $q) => $q->orderByDesc('price'),
            'rating_desc' => static fn (Builder $q) => $q->orderByDesc('rating'),
            'newest' => $default,
        ];

        ($strategies[$sort] ?? $default)($query);
    }
}
