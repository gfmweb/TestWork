<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    public function fromTitle(string $title, string $separator = '-'): string
    {
        return Str::slug($title, $separator, 'ru');
    }
}
