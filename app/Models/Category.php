<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache; // Make sure to import Cache
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Category extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = ['name', 'description'];

    /**
     * The "booted" method of the model.
     * Clears specific caches whenever a category is added, updated, or removed.
     */
    protected static function booted(): void
    {
        $clearCache = function () {
            Cache::forget('categories_all');          
            Cache::forget('homepage_categories');     
        };

        // Trigger cache clear on these events
        static::saved($clearCache);
        static::deleted($clearCache);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}