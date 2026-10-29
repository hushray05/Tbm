<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'quote_number',
        'party_id',
        'customer_name',
        'customer_email',
        'quote_date',
        'expiry_date',
        'subtotal_ugx',
        'vat_rate_percent',
        'vat_amount_ugx',
        'discount_amount_ugx',
        'total_amount_ugx',
        'status', // draft, sent, accepted, rejected, converted
        'notes',
    ];

    protected $casts = [
        'quote_date' => 'date',
        'expiry_date' => 'date',
        'subtotal_ugx' => 'integer',
        'vat_rate_percent' => 'decimal:2',
        'vat_amount_ugx' => 'integer',
        'discount_amount_ugx' => 'integer',
        'total_amount_ugx' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->total_amount_ugx, 'UGX');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'accepted', 'converted' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'sent' => 'bg-blue-100 text-blue-800 border-blue-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
