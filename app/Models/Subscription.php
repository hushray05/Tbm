<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'subscription_plan_id',
        'billing_cycle',
        'amount',
        'currency',
        'status',
        'trial_starts_at',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'next_billing_date',
        'cancelled_at',
        'payment_status',
    ];

    protected $casts = [
        'amount' => 'integer',
        'trial_starts_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'next_billing_date' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class, 'subscription_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(SubscriptionInvoice::class, 'subscription_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(SubscriptionEvent::class, 'subscription_id');
    }

    /**
     * Check if currently in valid active or trialing status.
     */
    public function isValid(): bool
    {
        if ($this->status === 'active') {
            return $this->ends_at ? $this->ends_at->isFuture() : true;
        }

        if ($this->status === 'trialing') {
            // Check trial_ends_at first, fallback to ends_at
            $expiryDate = $this->trial_ends_at ?: $this->ends_at;
            return $expiryDate ? $expiryDate->isFuture() : true;
        }

        return false;
    }

    /**
     * Calculate remaining days in free trial.
     */
    public function getTrialRemainingDaysAttribute(): int
    {
        if ($this->status !== 'trialing' || !$this->trial_ends_at) {
            return 0;
        }

        $now = Carbon::now();
        if ($now->greaterThan($this->trial_ends_at)) {
            return 0;
        }

        return (int) $now->diffInDays($this->trial_ends_at, false) + 1;
    }

    /**
     * Determine if trial is expiring soon (<= 7 days).
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->status === 'trialing' && $this->trial_remaining_days <= 7 && $this->trial_remaining_days > 0;
    }

    /**
     * Check if a specific feature is accessible.
     */
    public function canAccessFeature(string $featureKey): bool
    {
        // Expired subscription cannot access any feature
        if (!$this->isValid()) {
            return false;
        }

        // Valid subscription: check if plan includes this feature
        return $this->plan ? $this->plan->hasFeature($featureKey) : true;
    }
}
