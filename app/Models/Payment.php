<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_id',
        'gateway',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'paid_at'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsCompleted(string $paymentId = null, array $response = null): void
    {
        $this->update([
            'status' => 'completed',
            'payment_id' => $paymentId,
            'gateway_response' => $response,
            'paid_at' => now()
        ]);
    }

    public function markAsFailed(array $response = null): void
    {
        $this->update([
            'status' => 'failed',
            'gateway_response' => $response
        ]);
    }
}
