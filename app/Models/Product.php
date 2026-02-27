<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, SoftDeletes;

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
}
