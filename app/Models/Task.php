<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'project_name',
        'priority',
        'status',
        'price_cents',
        'due_date',
        'assigned_user',
        'client_name',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'due_date' => 'date',
    ];

    public function getFormattedPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->price_cents);
    }
}
