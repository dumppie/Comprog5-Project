<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'stock_quantity',
        'thumbnail_photo',
        'status'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relationships
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(ProductPhoto::class)->where('is_thumbnail', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function ratingCount()
    {
        return $this->reviews()->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeSearch($query, $searchTerm)
    {
        if ($searchTerm) {
            return $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('category', 'LIKE', "%{$searchTerm}%");
            });
        }
        return $query;
    }

    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    // Scout Searchable methods
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'price' => $this->price,
            'status' => $this->status,
        ];
    }

    public function shouldBeSearchable()
    {
        return $this->status === 'active' && !$this->trashed();
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    public function getStockStatusAttribute()
    {
        return $this->stock_quantity > 0 ? 'In Stock' : 'Out of Stock';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    // Helper methods
    public function hasThumbnail()
    {
        return !is_null($this->thumbnail_photo);
    }

    public function getThumbnailUrl()
    {
        return $this->thumbnail_photo ? asset('storage/' . $this->thumbnail_photo) : null;
    }

    public function canBeDeleted()
    {
        return true; // Add business logic if needed
    }

    public function canBeRestored()
    {
        return $this->trashed();
    }

    // Inventory Management Methods
    public function isLowStock(): bool
    {
        $threshold = config('shop.low_stock_threshold', 5);
        return $this->stock_quantity > 0 && $this->stock_quantity <= $threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public function getStockStatusLevel(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }
        
        if ($this->isLowStock()) {
            return 'low_stock';
        }
        
        return 'in_stock';
    }

    public function checkAndNotifyLowStock(): void
    {
        if ($this->isOutOfStock() || $this->isLowStock()) {
            $this->notifyAdminAboutStockLevel();
        }
    }

    private function notifyAdminAboutStockLevel(): void
    {
        $adminEmail = config('shop.admin_email', 'admin@example.com');
        $stockStatus = $this->isOutOfStock() ? 'Out of Stock' : 'Low Stock';
        
        \Illuminate\Support\Facades\Mail::to($adminEmail)
            ->send(new \App\Mail\LowStockNotification(
                $this,
                $this->stock_quantity,
                $stockStatus
            ));
    }
}
