<?php

namespace Tests\Feature;

use Tests\TestCase;

class ModulesServiceProvidersTest extends TestCase
{
    public function test_module_api_routes_are_loaded_with_api_prefix(): void
    {
        $this->getJson('/api/products/ping')
            ->assertOk()
            ->assertJson([
                'module' => 'product',
                'status' => 'ok',
            ]);

        $this->getJson('/api/product-categories/ping')
            ->assertOk()
            ->assertJson([
                'module' => 'product-category',
                'status' => 'ok',
            ]);
    }

    public function test_module_configs_and_translations_are_loaded(): void
    {
        $this->assertSame('products', config('product.route_prefix'));
        $this->assertSame('product-categories', config('product_category.route_prefix'));

        app()->setLocale('ru');

        $this->assertSame('Модуль Product активен.', trans('product::messages.ping'));
        $this->assertSame('Модуль ProductCategory активен.', trans('product-category::messages.ping'));
    }
}
