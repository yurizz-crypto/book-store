<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Book extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected static function booted(): void
    {
        $clearCache = function () {
            Cache::forget('featured_homepage_books');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'isbn',
        'price',
        'stock_quantity',
        'description',
        'cover_image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    /**
     * Scope a query to search books by title or author.
     */
    public function scopeSearch(Builder $query, ?string $searchTerm): Builder
    {
        if (!$searchTerm) {
            return $query;
        }

        $term = strtolower(trim($searchTerm));

        return $query->where(function($q) use ($term) {
            $q->where(DB::raw('LOWER(title)'), 'like', "%{$term}%")
              ->orWhere(DB::raw('LOWER(author)'), 'like', "%{$term}%");
        });
    }
}