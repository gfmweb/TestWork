<?php

namespace Tests\Unit\Services;

use App\Services\SlugService;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SlugServiceTest extends TestCase
{
    #[DataProvider('titlesProvider')]
    public function test_it_builds_latin_slug_from_russian_titles(string $title, string $expectedSlug): void
    {
        $service = app(SlugService::class);

        $this->assertSame($expectedSlug, $service->fromTitle($title));
    }

    public static function titlesProvider(): array
    {
        return [
            'simple words' => ['Привет мир', 'privet-mir'],
            'with punctuation' => ['Категория: Тест #1', 'kategoriya-test-1'],
            'with extra spaces' => ['  Новая   коллекция   ', 'novaya-kollektsiya'],
        ];
    }
}
