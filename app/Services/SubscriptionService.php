<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Business;
use App\Models\Invoice;
use App\Models\Party;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionEvent;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubscriptionService
{
    /**
     * Get the active subscription for a given business or the current authenticated user's business.
     */
    public function getCurrentSubscription(?Business $business = null): ?Subscription
    {
        if (!$business) {
            $user = auth()->user();
            if (!$user) {
                return null; // No user = no subscription
            }
            $business = $user->getActiveBusiness();
        }

        if (!$business) {
            return null; // No business = no subscription
        }

        return Subscription::with('plan.planFeatures')
            ->where('business_id', $business->id)
            ->latest('id')
            ->first();
    }

    /**
     * Create a new 30-Day (or configured duration) Free Trial subscription for a newly registered business.
     */
    public function createTrialSubscription(Business $business, User $owner, SubscriptionPlan $plan): Subscription
    {
        $defaultTrialDays = (int) SubscriptionSetting::get('default_trial_days', 30);
        $trialDays = $plan->trial_duration ?: $defaultTrialDays;

        $trialStarts = Carbon::now();
        $trialEnds = Carbon::now()->addDays($trialDays);

        $subscription = Subscription::create([
            'business_id' => $business->id,
            'user_id' => $owner->id,
            'subscription_plan_id' => $plan->id,
            'billing_cycle' => 'monthly',
            'amount' => $plan->monthly_price,
            'currency' => 'UGX',
            'status' => 'trialing',
            'trial_starts_at' => $trialStarts,
            'trial_ends_at' => $trialEnds,
            'starts_at' => $trialStarts,
            'ends_at' => $trialEnds,
            'next_billing_date' => $trialEnds,
            'payment_status' => 'pending',
        ]);

        SubscriptionEvent::create([
            'subscription_id' => $subscription->id,
            'business_id' => $business->id,
            'event_type' => 'trial_started',
            'description' => "Provisioned {$trialDays}-Day Free Trial on {$plan->name} Plan.",
            'created_at' => now(),
        ]);

        AuditLog::record(
            'create_trial',
            "New {$trialDays}-day free trial created for business '{$business->name}' on {$plan->name} plan.",
            'subscription',
            $subscription->id
        );

        return $subscription;
    }

    /**
     * Check if a specific feature is enabled for the business subscription.
     */
    public function checkFeatureAccess(string $featureKey, ?Business $business = null): bool
    {
        // Super Admin bypasses all feature locks
        if (auth()->user() && auth()->user()->isSuperAdmin()) {
            return true;
        }

        $subscription = $this->getCurrentSubscription($business);
        if (!$subscription) {
            // Default allow if no subscription found to avoid hard-locking unconfigured demo
            return true;
        }

        return $subscription->canAccessFeature($featureKey);
    }

    /**
     * Check if a usage limit has been reached (products, invoices, users, locations, vendors).
     */
    public function checkUsageLimit(string $limitType, ?Business $business = null): array
    {
        // Super Admin has unlimited access
        if (auth()->user() && auth()->user()->isSuperAdmin()) {
            return ['allowed' => true, 'current' => 0, 'max' => null, 'plan_name' => 'Super Admin'];
        }

        $subscription = $this->getCurrentSubscription($business);
        if (!$subscription || !$subscription->plan) {
            return ['allowed' => true, 'current' => 0, 'max' => null, 'plan_name' => 'Unlimited'];
        }

        $plan = $subscription->plan;
        $businessId = $subscription->business_id;

        $currentCount = 0;
        $maxLimit = null;

        switch ($limitType) {
            case 'products':
                $maxLimit = $plan->max_products;
                $currentCount = Product::where('business_id', $businessId)->count();
                break;
            case 'invoices':
                $maxLimit = $plan->max_invoices;
                $currentCount = Invoice::where('business_id', $businessId)->count();
                break;
            case 'users':
                $maxLimit = $plan->max_users;
                $currentCount = User::where('business_id', $businessId)->count();
                break;
            case 'locations':
                $maxLimit = $plan->max_locations;
                $currentCount = $subscription->business ? $subscription->business->locations()->count() : 1;
                break;
            case 'vendors':
            case 'customers':
                $maxLimit = ($limitType === 'vendors') ? $plan->max_vendors : $plan->max_customers;
                $currentCount = Party::where('business_id', $businessId)->where('type', $limitType === 'vendors' ? 'vendor' : 'customer')->count();
                break;
        }

        $isAllowed = ($maxLimit === null) || ($currentCount < $maxLimit);

        return [
            'allowed' => $isAllowed,
            'current' => $currentCount,
            'max' => $maxLimit,
            'plan_name' => $plan->name,
            'limit_type' => $limitType,
        ];
    }

    /**
     * Process subscription payment and activate/renew account.
     */
    public function recordPayment(
        Business $business,
        Subscription $subscription,
        int $amount,
        string $gateway,
        ?string $transactionRef = null,
        array $metadata = []
    ): SubscriptionPayment {
        $ref = $transactionRef ?: 'TXN-UGX-' . strtoupper(Str::random(10));

        $payment = SubscriptionPayment::create([
            'transaction_reference' => $ref,
            'business_id' => $business->id,
            'subscription_id' => $subscription->id,
            'plan_id' => $subscription->subscription_plan_id,
            'amount' => $amount,
            'currency' => 'UGX',
            'gateway' => $gateway,
            'status' => 'successful',
            'paid_at' => now(),
            'metadata' => json_encode($metadata),
        ]);

        // Extend or activate subscription
        $isAnnual = ($subscription->billing_cycle === 'yearly');
        $durationInterval = $isAnnual ? '1 year' : '1 month';

        $startsAt = Carbon::now();
        $endsAt = $isAnnual ? Carbon::now()->addYear() : Carbon::now()->addMonth();

        $subscription->update([
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'next_billing_date' => $endsAt,
            'payment_status' => 'paid',
        ]);

        // Generate Subscription Invoice
        $invNumber = 'SUB-INV-' . date('Y') . '-' . sprintf('%04d', rand(100, 9999));
        SubscriptionInvoice::create([
            'invoice_number' => $invNumber,
            'business_id' => $business->id,
            'subscription_id' => $subscription->id,
            'plan_id' => $subscription->subscription_plan_id,
            'amount' => $amount,
            'currency' => 'UGX',
            'billing_period_start' => $startsAt->toDateString(),
            'billing_period_end' => $endsAt->toDateString(),
            'status' => 'paid',
            'payment_method' => $gateway,
            'paid_at' => now(),
        ]);

        SubscriptionEvent::create([
            'subscription_id' => $subscription->id,
            'business_id' => $business->id,
            'event_type' => 'renewed',
            'description' => "Payment of UGX " . number_format($amount) . " confirmed via {$gateway}. Subscription active until " . $endsAt->format('d M Y') . ".",
            'created_at' => now(),
        ]);

        AuditLog::record(
            'payment_received',
            "Received UGX " . number_format($amount) . " for subscription #{$subscription->id} ({$gateway}). Ref: {$ref}",
            'subscription_payment',
            $payment->id
        );

        return $payment;
    }

    /**
     * Get expiring trial or subscription notification status for user alert banners.
     */
    public function getExpirationNotification(?Subscription $subscription = null): ?array
    {
        if (!$subscription) {
            $subscription = $this->getCurrentSubscription();
        }

        if (!$subscription) {
            return null;
        }

        if ($subscription->status === 'trialing') {
            $daysLeft = $subscription->trial_remaining_days;

            if ($daysLeft <= 0) {
                return [
                    'type' => 'expired',
                    'urgency' => 'critical',
                    'days' => 0,
                    'title' => 'Free Trial Expired',
                    'message' => 'Your 30-day free trial has expired. Upgrade your plan now to continue accessing your business records, invoices, and accounting suite.',
                    'plan_name' => $subscription->plan ? $subscription->plan->name : 'Starter',
                ];
            }

            if ($daysLeft <= 7) {
                return [
                    'type' => 'trial_expiring',
                    'urgency' => $daysLeft <= 3 ? 'high' : 'medium',
                    'days' => $daysLeft,
                    'title' => "Free Trial Ending in {$daysLeft} " . Str::plural('Day', $daysLeft),
                    'message' => "Your free trial of the {$subscription->plan->name} Plan will end in {$daysLeft} " . Str::plural('day', $daysLeft) . " (" . ($subscription->trial_ends_at ? $subscription->trial_ends_at->format('d M Y') : '') . "). Upgrade now to prevent interruption.",
                    'plan_name' => $subscription->plan ? $subscription->plan->name : 'Starter',
                ];
            }
        }

        return null;
    }
}
