<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar_url',
        'company',
        'email',
        'phone',
        'status',
        'estimated_value_cents',
        'assigned_to',
    ];

    protected $casts = [
        'estimated_value_cents' => 'integer',
    ];

    public function getFormattedValueAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->estimated_value_cents);
    }

    public function getAvatarAttribute(): string
    {
        if ($this->avatar_url) {
            return $this->avatar_url;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=ffffff&bold=true&size=128';
    }
}
