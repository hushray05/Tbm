<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'credit_note_number',
        'party_id',
        'invoice_id',
        'customer_name',
        'credit_date',
        'amount_ugx',
        'reason',
        'status', // draft, issued, applied
        'restock_inventory',
    ];

    protected $casts = [
        'credit_date' => 'date',
        'amount_ugx' => 'integer',
        'restock_inventory' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->amount_ugx, 'UGX');
    }
}
