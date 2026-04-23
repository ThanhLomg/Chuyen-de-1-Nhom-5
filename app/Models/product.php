<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'short_description',
        'price', 'sale_price', 'stock', 'image', 'gallery', 'material', 'dimensions',
        'color', 'brand', 'views', 'is_featured', 'is_active'
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'integer',
        'sale_price' => 'integer',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/product-placeholder.jpg');
    }

    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery ?? [])->map(fn($p) => asset('storage/' . $p))->toArray();
    }

    public function getDisplayPriceAttribute(): int
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentAttribute(): ?int
{
    if (!$this->sale_price || $this->sale_price >= $this->price) return null;
    return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
}

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }

    public function getFormattedDisplayPriceAttribute(): string
    {
        return number_format($this->display_price, 0, ',', '.') . 'đ';
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock === 0) return 'out_of_stock';
        if ($this->stock <= 5) return 'low_stock';
        return 'in_stock';
    }

    public function isAvailableForQuantity(int $qty): bool
    {
        return $this->stock >= $qty;
    }
}