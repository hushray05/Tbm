<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'business_id',
        'subscription_id',
        'plan_id',
        'amount',
        'currency',
        'billing_period_start',
        'billing_period_end',
        'status',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
        'paid_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'UGX ' . number_format($this->amount);
    }
}
