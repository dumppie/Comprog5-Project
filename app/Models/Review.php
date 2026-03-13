<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Accessors
    public function getFormattedRatingAttribute()
    {
        return '★'.repeat($this->rating).'☆'.repeat(5 - $this->rating);
    }

    public function getStarRatingAttribute()
    {
        return $this->rating;
    }

    // Helper methods
    public function isAuthor(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public static function calculateAverageRating($productId): float
    {
        return self::forProduct($productId)->avg('rating') ?? 0.0;
    }

    public static function getRatingCount($productId, $rating = null): int
    {
        $query = self::forProduct($productId);
        
        if ($rating !== null) {
            $query->withRating($rating);
        }
        
        return $query->count();
    }
}
