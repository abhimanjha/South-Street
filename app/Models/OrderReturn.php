<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReturn extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'order_id',
        'user_id',
        'return_number',
        'type',
        'status',
        'reason',
        'description',
        'images',
        'refund_amount',
        'bank_account',
        'ifsc_code',
        'account_holder_name',
        'requested_at',
        'approved_at',
        'picked_up_at',
        'received_at',
        'refund_processed_at',
        'completed_at',
        'admin_notes',
    ];

    protected $casts = [
        'images' => 'array',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'received_at' => 'datetime',
        'refund_processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'refund_amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canBeReturned(): bool
    {
        // Can only return if order is delivered and within 7 days
        if ($this->order->status !== 'delivered') {
            return false;
        }

        $deliveredDate = $this->order->updated_at; // Assuming updated_at is when it was delivered
        $daysSinceDelivery = now()->diffInDays($deliveredDate);

        return $daysSinceDelivery <= 7;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'requested' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'picked_up' => 'primary',
            'received' => 'success',
            'refund_processed' => 'success',
            'completed' => 'success',
            default => 'secondary',
        };
    }
}
