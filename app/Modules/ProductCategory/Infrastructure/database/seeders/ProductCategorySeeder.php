<?php

namespace App\Modules\ProductCategory\Infrastructure\database\seeders;

use App\Modules\ProductCategory\Infrastructure\Models\ProductCategory;
use App\Services\SlugService;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $slugService = app(SlugService::class);

        $names = [
            'Электроника',
            'Бытовая техника',
            'Смартфоны',
            'Ноутбуки',
            'Планшеты',
            'Телевизоры',
            'Аудиотехника',
            'Игровые приставки',
            'Фото и видео',
            'Комплектующие для ПК',
            'Офисная техника',
            'Дом и интерьер',
            'Мебель',
            'Освещение',
            'Текстиль',
            'Посуда',
            'Товары для кухни',
            'Сад и огород',
            'Автотовары',
            'Детские товары',
            'Одежда и обувь',
            'Красота и уход',
            'Здоровье',
            'Спорт и отдых',
            'Книги и канцелярия',
        ];

        foreach ($names as $name) {
            ProductCategory::query()->updateOrCreate(
                ['name' => $name],
                ['slug' => $slugService->fromTitle($name)],
            );
        }
    }
}
