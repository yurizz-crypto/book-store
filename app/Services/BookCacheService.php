<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class BookCacheService
{
    public function invalidateCatalog(): void
    {
        Cache::tags(['catalog'])->flush();
    }

    public function invalidateCategory(int $categoryId): void
    {
        Cache::tags(["category:{$categoryId}"])->flush();
    }

    public function invalidateBook(string $isbn): void
    {
        Cache::forget("book:isbn:{$isbn}");
    }
}