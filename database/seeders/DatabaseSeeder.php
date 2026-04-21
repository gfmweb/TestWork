<?php

namespace Database\Seeders;

use App\Modules\Product\Infrastructure\database\seeders\ProductSeeder;
use App\Modules\ProductCategory\Infrastructure\database\seeders\ProductCategorySeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
