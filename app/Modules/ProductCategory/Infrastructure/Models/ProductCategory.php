<?php

namespace App\Modules\ProductCategory\Infrastructure\Models;

use Database\Factories\ProductCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug'])]
class ProductCategory extends Model
{
    /** @use HasFactory<ProductCategoryFactory> */
    use HasFactory, SoftDeletes;

    protected static function newFactory(): ProductCategoryFactory
    {
        return ProductCategoryFactory::new();
    }
}
