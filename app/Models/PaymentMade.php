<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMade extends Model
{
    use HasFactory;

    protected $table = 'payments_made';

    protected $fillable = [
        'business_id',
        'payment_number',
        'bill_id',
        'party_id',
        'vendor_name',
        'amount_ugx',
        'payment_date',
        'payment_method',
        'reference_no',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_ugx' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedAmountAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->amount_ugx, 'UGX');
    }
}
