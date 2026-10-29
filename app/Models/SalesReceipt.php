<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesReceipt extends Model
{
    use HasFactory;

    protected $table = 'sales_receipts';

    protected $fillable = [
        'business_id',
        'receipt_number',
        'customer_id',
        'customer_name',
        'invoice_id',
        'date',
        'payment_mode',
        'subtotal_ugx',
        'vat_amount_ugx',
        'total',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'subtotal_ugx' => 'integer',
        'vat_amount_ugx' => 'integer',
        'total' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'customer_id');
    }

    public function getFormattedTotalAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->total, 'UGX');
    }
}
