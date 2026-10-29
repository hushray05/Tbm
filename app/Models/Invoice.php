<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'invoice_number',
        'customer_name',
        'customer_email',
        'billing_address',
        'invoice_date',
        'due_date',
        'currency_code',
        'exchange_rate_applied',
        'subtotal_cents',
        'tax_cents',
        'total_cents',
        'status',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'exchange_rate_applied' => 'decimal:6',
        'subtotal_cents' => 'integer',
        'tax_cents' => 'integer',
        'total_cents' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function getFormattedTotalAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->total_cents, $this->currency_code);
    }
}
