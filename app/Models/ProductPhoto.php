<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'photo_path',
        'is_thumbnail',
        'caption'
    ];

    protected $casts = [
        'is_thumbnail' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    public function getFullPathAttribute()
    {
        return $this->photo_path ? storage_path('app/public/' . $this->photo_path) : null;
    }

    // Helper methods
    public function isThumbnail()
    {
        return $this->is_thumbnail;
    }

    public function getExtension()
    {
        return pathinfo($this->photo_path, PATHINFO_EXTENSION);
    }

    public function isImage()
    {
        $extension = strtolower($this->getExtension());
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
}
