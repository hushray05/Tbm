<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'business_id',
        'user_name',
        'user_email',
        'subject',
        'message',
        'category',
        'status', // pending, reviewed, resolved
        'admin_notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'reviewed' => 'bg-blue-100 text-blue-800 border-blue-200',
            default => 'bg-amber-100 text-amber-800 border-amber-200',
        };
    }
}
