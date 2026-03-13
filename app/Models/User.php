<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'contact_number',
        'profile_photo',
        'address',
        'is_admin',
        'user_status_id',
        'email_verification_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function userStatus(): BelongsTo
    {
        return $this->belongsTo(UserStatus::class, 'user_status_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasPurchasedProduct($productId): bool
    {
        return $this->orders()
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }

    public function hasReviewedProduct($productId): bool
    {
        return $this->reviews()
            ->where('product_id', $productId)
            ->exists();
    }

    public function getReviewForProduct($productId): ?Review
    {
        return $this->reviews()
            ->where('product_id', $productId)
            ->first();
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isActive(): bool
    {
        return $this->userStatus && $this->userStatus->name === 'active';
    }

    public function getFullNameAttribute(): string
    {
        $fullName = $this->first_name;
        if ($this->middle_name) {
            $fullName .= ' ' . $this->middle_name;
        }
        $fullName .= ' ' . $this->last_name;
        return trim($fullName);
    }

    public function getNameAttribute(): string
    {
        return $this->getFullNameAttribute();
    }
}
