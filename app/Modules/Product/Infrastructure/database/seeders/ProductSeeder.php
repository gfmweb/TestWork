<?php

namespace App\Modules\Product\Infrastructure\database\seeders;

use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Infrastructure\database\seeders\ProductCategorySeeder;
use Illuminate\Database\Seeder;

/**
 * Seeds demo products. Expects at least one product category to exist
 * (run {@see ProductCategorySeeder} first).
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->count(250)->create();
    }
}
