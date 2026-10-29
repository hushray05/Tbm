<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'badge',
        'monthly_price',
        'annual_price',
        'currency',
        'currency_symbol',
        'trial_enabled',
        'trial_duration',
        'trial_unit',
        'max_users',
        'max_locations',
        'max_products',
        'max_invoices',
        'max_receipts',
        'max_purchases',
        'max_vendors',
        'max_customers',
        'max_storage_mb',
        'max_documents',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'monthly_price' => 'integer',
        'annual_price' => 'integer',
        'trial_enabled' => 'boolean',
        'trial_duration' => 'integer',
        'max_users' => 'integer',
        'max_locations' => 'integer',
        'max_products' => 'integer',
        'max_invoices' => 'integer',
        'max_receipts' => 'integer',
        'max_purchases' => 'integer',
        'max_vendors' => 'integer',
        'max_customers' => 'integer',
        'max_storage_mb' => 'integer',
        'max_documents' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function planFeatures(): HasMany
    {
        return $this->hasMany(SubscriptionPlanFeature::class, 'subscription_plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'subscription_plan_id');
    }

    /**
     * Check if a specific feature key is enabled for this plan.
     */
    public function hasFeature(string $featureKey): bool
    {
        $feature = $this->planFeatures()->where('feature_key', $featureKey)->first();
        return $feature ? (bool) $feature->is_enabled : false;
    }

    /**
     * Get formatted monthly price in UGX.
     */
    public function getFormattedMonthlyPriceAttribute(): string
    {
        return 'UGX ' . number_format($this->monthly_price);
    }

    /**
     * Get formatted annual price in UGX.
     */
    public function getFormattedAnnualPriceAttribute(): string
    {
        return 'UGX ' . number_format($this->annual_price);
    }
}
