<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookObserver
{
    /**
     * Handle the Book "saved" event. (Triggered on both created and updated)
     */
    public function saved(Book $book): void
    {
        $this->invalidateCaches($book);
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        $this->invalidateCaches($book);
    }

    /**
     * Centralized tag invalidation logic
     */
    private function invalidateCaches(Book $book): void
    {
        // 1. Flush specific category cache
        if ($book->category_id) {
            Cache::tags(["category:{$book->category_id}"])->flush();
        }

        // 2. Flush the specific book cache
        Cache::tags(["book:{$book->id}"])->flush();

        // 3. Flush general catalog queries (like the homepage)
        Cache::tags(['catalog'])->flush();
        Cache::forget('featured_homepage_books');
        
        // Note: We deliberately DO NOT flush the 'categories' tag here, 
        // because adding/editing a book does not alter the Category names/slugs!
    }
}