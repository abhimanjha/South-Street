<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TailoringRequest extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'cloth_material',
        'color',
        'style_type',
        'size_details',
        'additional_notes',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
