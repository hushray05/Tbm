<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'bill_number',
        'party_id',
        'order_id',
        'vendor_name',
        'bill_date',
        'due_date',
        'subtotal_ugx',
        'vat_rate_percent',
        'vat_amount_ugx',
        'total_amount_ugx',
        'paid_amount_ugx',
        'balance_ugx',
        'status', // pending, partially_paid, paid, overdue
        'notes',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'subtotal_ugx' => 'integer',
        'vat_rate_percent' => 'decimal:2',
        'vat_amount_ugx' => 'integer',
        'total_amount_ugx' => 'integer',
        'paid_amount_ugx' => 'integer',
        'balance_ugx' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PaymentMade::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->total_amount_ugx, 'UGX');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'partially_paid' => 'bg-blue-100 text-blue-800 border-blue-200',
            'overdue' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-amber-100 text-amber-800 border-amber-200',
        };
    }
}
