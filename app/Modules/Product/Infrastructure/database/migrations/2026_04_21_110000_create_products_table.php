<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->foreignId('category_id')
                ->constrained('product_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->boolean('in_stock')->default(true);
            $table->float('rating');
            $table->timestamps();
        });

        if (in_array($driver, ['mysql', 'mariadb', 'pgsql'], true)) {
            Schema::table('products', function (Blueprint $table): void {
                $table->fullText('name');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
