<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasFactory, Notifiable, AuditableTrait;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email', 'role', 
        'password', 'two_factor_enabled', 'two_factor_code', 'two_factor_expires_at',
    ];

    protected $auditExclude = ['password', 'remember_token', 'two_factor_code'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_expires_at' => 'datetime',
        ];
    }

    public function routeNotificationForBackup()
    {
        return $this->email;
    }

    public function orders() { return $this->hasMany(Order::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function addresses() { return $this->hasMany(Address::class); }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->first_name} {$this->middle_name} {$this->last_name}")
        );
    }

    public function hasAddress(): bool
    {
        return $this->addresses()->exists();
    }


    public function hasPurchasedBook(Book $book): bool
    {
        return $this->orders()
            ->where('status', 'completed')
            ->whereHas('orderItems', fn($q) => $q->where('book_id', $book->id))
            ->exists();
    }

    public function resetTwoFactorCode(): void
    {
        $this->timestamps = false;
        $this->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null
        ]);
    }
}