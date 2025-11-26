<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'subtotal', 'discount',
        'shipping_charge', 'tax', 'total', 'coupon_code', 'payment_method',
        'payment_status', 'payment_id', 'status', 'notes', 'admin_notes',
        'confirmed_at', 'shipped_at', 'delivered_at', 'tracking_number',
        'courier_service'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    public function canBeReturned(): bool
    {
        // Can only return if order is delivered and within 7 days
        if ($this->status !== 'delivered') {
            return false;
        }

        if (!$this->delivered_at) {
            return false;
        }

        $daysSinceDelivery = now()->diffInDays($this->delivered_at);
        return $daysSinceDelivery <= 7;
    }

    public function hasActiveReturn(): bool
    {
        return $this->returns()
            ->whereIn('status', ['requested', 'approved', 'picked_up', 'received'])
            ->exists();
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'primary',
            'processing' => 'info',
            'shipped' => 'info',
            'out_for_delivery' => 'warning',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'out_of_stock' => 'danger',
            'returned' => 'secondary',
            default => 'secondary'
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }
}