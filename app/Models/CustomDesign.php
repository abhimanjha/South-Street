<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomDesign extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'design_type',
        'status',
        'images',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'submitted' => 'warning',
            'under_review' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'in_production' => 'primary',
            'completed' => 'success',
            default => 'secondary'
        };
    }
}
