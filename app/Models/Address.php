<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street_address', // Ensure this is here
        'city',
        'state',
        'postal_code',
        'country',
        'is_default',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
