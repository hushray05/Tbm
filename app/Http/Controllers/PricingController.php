<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Company;
use App\Models\Feature;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Public dynamic pricing page (/pricing).
     * Loads plans, prices, features, limits, and trial duration completely from the database.
     */
    public function index(): View
    {
        $plans = SubscriptionPlan::with('planFeatures')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $features = Feature::where('is_active', true)->get();
        $defaultTrialDays = (int) SubscriptionSetting::get('default_trial_days', 30);
        
        $companies = Company::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pricing.index', compact('plans', 'features', 'defaultTrialDays', 'companies'));
    }

    /**
     * Client Subscription Portal (/subscription).
     * Displays active subscription, trial countdown, usage limits, and upgrade options.
     */
    public function portal(): View
    {
        $subscription = $this->subscriptionService->getCurrentSubscription();
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        // Calculate usage stats
        $userLimit = $this->subscriptionService->checkUsageLimit('users');
        $productLimit = $this->subscriptionService->checkUsageLimit('products');
        $invoiceLimit = $this->subscriptionService->checkUsageLimit('invoices');
        $locationLimit = $this->subscriptionService->checkUsageLimit('locations');

        // Get all features for plan breakdown display
        $features = Feature::orderBy('name')->get();
        $defaultTrialDays = (int) SubscriptionSetting::get('default_trial_days', 30);

        return view('subscription.portal', compact('subscription', 'plans', 'userLimit', 'productLimit', 'invoiceLimit', 'locationLimit', 'features', 'defaultTrialDays'));
    }

    /**
     * Checkout page for subscription upgrade or renewal.
     */
    public function checkout(Request $request): View
    {
        $planSlug = $request->query('plan', 'premium');
        $billingCycle = $request->query('cycle', 'monthly'); // monthly or yearly

        $plan = SubscriptionPlan::where('slug', $planSlug)->where('is_active', true)->firstOrFail();
        $amount = ($billingCycle === 'yearly') ? $plan->annual_price : $plan->monthly_price;
        $subscription = $this->subscriptionService->getCurrentSubscription();

        return view('subscription.checkout', compact('plan', 'billingCycle', 'amount', 'subscription'));
    }

    /**
     * Process subscription payment submission (Pesapal gateway with Admin Approval).
     */
    public function processPayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'gateway' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'payment_reference' => 'required|string|max:255',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);
        $amount = ($validated['billing_cycle'] === 'yearly') ? $plan->annual_price : $plan->monthly_price;

        $user = auth()->user();
        $business = $user ? $user->getActiveBusiness() : Business::first();
        $subscription = $this->subscriptionService->getCurrentSubscription($business);

        if (!$subscription) {
            $subscription = Subscription::create([
                'business_id' => $business->id,
                'user_id' => $user ? $user->id : 1,
                'subscription_plan_id' => $plan->id,
                'billing_cycle' => $validated['billing_cycle'],
                'amount' => $amount,
                'currency' => 'UGX',
                'status' => 'trialing',
                'payment_status' => 'pending_approval',
            ]);
        } else {
            $subscription->update([
                'subscription_plan_id' => $plan->id,
                'billing_cycle' => $validated['billing_cycle'],
                'amount' => $amount,
                'payment_status' => 'pending_approval',
            ]);
        }

        // Record pending payment record for Super Administrator verification
        \App\Models\SubscriptionPayment::create([
            'transaction_reference' => $validated['payment_reference'],
            'business_id' => $business->id,
            'subscription_id' => $subscription->id,
            'plan_id' => $plan->id,
            'amount' => $amount,
            'currency' => 'UGX',
            'gateway' => 'Pesapal Merchant Store',
            'status' => 'pending',
            'paid_at' => null,
            'metadata' => json_encode([
                'phone' => $validated['phone_number'] ?? null,
                'cycle' => $validated['billing_cycle'],
                'reference' => $validated['payment_reference'],
                'submitted_by' => $user->name ?? 'User',
            ]),
        ]);

        return redirect()->route('subscription.portal')->with('success', "Payment reference ({$validated['payment_reference']}) submitted successfully! Your {$plan->name} subscription is awaiting Administrator review & approval.");
    }
}
