<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    
    protected $fillable = ['user_id', 'book_id', 'rating', 'comment'];
        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function book()
    {
        return $this->belongsTo(Book::class);
    }
}