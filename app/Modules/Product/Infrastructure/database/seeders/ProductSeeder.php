<?php

namespace App\Modules\Product\Infrastructure\database\seeders;

use App\Modules\Product\Infrastructure\Models\Product;
use App\Modules\ProductCategory\Infrastructure\database\seeders\ProductCategorySeeder;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProductCategorySeeder::class);

        Product::factory()->count(250)->create();
    }
}
