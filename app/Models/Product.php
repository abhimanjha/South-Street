<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'full_description',
        'category_id', 'price', 'discount_price', 'discount_percentage',
        'brand', 'stock_quantity', 'fabric', 'material', 'care_instructions',
        'is_featured', 'is_new', 'is_trending', 'is_active', 'views',
        'average_rating', 'total_reviews', 'meta_title', 'meta_description',
        'attributes'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_trending' => 'boolean',
        'is_active' => 'boolean',
        'attributes' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->price > 0) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new', true)->orderBy('created_at', 'desc');
    }
}
