<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Business;
use App\Models\Subscription;
use App\Models\SubscriptionEvent;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use App\Models\User;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Super Admin Subscription Dashboard (ADMIN → SUBSCRIPTIONS → DASHBOARD).
     * Metrics: Total Businesses, Active Subs, Trial Businesses, Expired Trials, Monthly/Annual Revenue, ApexCharts.
     */
    public function dashboard(): View
    {
        $totalBusinesses = Business::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $trialBusinesses = Subscription::where('status', 'trialing')->count();
        $expiredTrials = Subscription::where('status', 'expired')
            ->orWhere(function ($q) {
                $q->where('status', 'trialing')->where('trial_ends_at', '<', now());
            })->count();
        $cancelledSubscriptions = Subscription::where('status', 'cancelled')->count();

        // Revenue in UGX from real database
        $monthlyRevenueUgx = (int) SubscriptionPayment::where('status', 'successful')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        $annualRevenueUgx = (int) SubscriptionPayment::where('status', 'successful')
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        // Subscribers by Plan
        $starterCount = Subscription::whereHas('plan', fn($q) => $q->where('slug', 'starter'))->count();
        $proCount = Subscription::whereHas('plan', fn($q) => $q->where('slug', 'professional'))->count();
        $premiumCount = Subscription::whereHas('plan', fn($q) => $q->where('slug', 'premium'))->count();

        // Trial conversion rate calculation
        $totalEverTrials = Subscription::count();
        $convertedPaid = Subscription::where('status', 'active')->count();
        $trialConversionRate = $totalEverTrials > 0 ? round(($convertedPaid / $totalEverTrials) * 100, 1) : 0;

        $recentSubscribers = Subscription::with(['business.owner', 'plan'])->latest()->take(6)->get();
        $recentPayments = SubscriptionPayment::with(['business', 'plan'])->latest()->take(5)->get();

        return view('admin.subscriptions.dashboard', compact(
            'totalBusinesses',
            'activeSubscriptions',
            'trialBusinesses',
            'expiredTrials',
            'cancelledSubscriptions',
            'monthlyRevenueUgx',
            'annualRevenueUgx',
            'starterCount',
            'proCount',
            'premiumCount',
            'trialConversionRate',
            'recentSubscribers',
            'recentPayments'
        ));
    }

    /**
     * Subscribers List Page (ADMIN → SUBSCRIBERS).
     * Filters: Plan, Status, Billing Cycle, Date. Search: Business, Owner, Email.
     */
    public function subscribers(Request $request): View
    {
        $query = Subscription::with(['business.owner', 'plan', 'payments']);

        if ($request->filled('plan')) {
            $query->where('subscription_plan_id', $request->plan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cycle')) {
            $query->where('billing_cycle', $request->cycle);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('business', function ($b) use ($s) {
                $b->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhereHas('owner', function ($u) use ($s) {
                      $u->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        $subscriptions = $query->latest('id')->paginate(15);
        $plans = SubscriptionPlan::all();

        return view('admin.subscriptions.subscribers.index', compact('subscriptions', 'plans'));
    }

    /**
     * Subscriber Details View (ADMIN → SUBSCRIBERS → DETAILS).
     * Shows Business Info, Owner Info, Plan, Status, Trial Dates, Payment History, Usage Counters, Admin Manual Controls.
     */
    public function subscriberShow(Subscription $subscription): View
    {
        $subscription->load(['business.owner', 'business.locations', 'plan.planFeatures', 'payments', 'invoices', 'events']);
        $plans = SubscriptionPlan::all();

        $business = $subscription->business;
        $userLimit = $this->subscriptionService->checkUsageLimit('users', $business);
        $productLimit = $this->subscriptionService->checkUsageLimit('products', $business);
        $invoiceLimit = $this->subscriptionService->checkUsageLimit('invoices', $business);
        $locationLimit = $this->subscriptionService->checkUsageLimit('locations', $business);

        return view('admin.subscriptions.subscribers.show', compact('subscription', 'plans', 'userLimit', 'productLimit', 'invoiceLimit', 'locationLimit'));
    }

    /**
     * Manual Super Admin Control: Extend Trial / Change Plan / Activate / Suspend / Cancel.
     */
    public function updateSubscriber(Request $request, Subscription $subscription): RedirectResponse
    {
        $action = $request->input('action');
        $business = $subscription->business;

        switch ($action) {
            case 'extend_trial':
                $days = (int) $request->input('additional_days', 7);
                $newTrialEnd = $subscription->trial_ends_at ? $subscription->trial_ends_at->addDays($days) : Carbon::now()->addDays($days);
                $subscription->update([
                    'status' => 'trialing',
                    'trial_ends_at' => $newTrialEnd,
                    'ends_at' => $newTrialEnd,
                    'next_billing_date' => $newTrialEnd,
                ]);

                SubscriptionEvent::create([
                    'subscription_id' => $subscription->id,
                    'business_id' => $business->id,
                    'event_type' => 'trial_extended',
                    'description' => "Trial extended by {$days} days by Super Administrator.",
                    'created_at' => now(),
                ]);

                AuditLog::record('extend_trial', "Extended free trial by {$days} days for {$business->name}", 'subscription', $subscription->id);
                return back()->with('success', "Trial successfully extended by {$days} days until " . $newTrialEnd->format('d M Y') . ".");

            case 'change_plan':
                $newPlanId = $request->input('plan_id');
                $newPlan = SubscriptionPlan::findOrFail($newPlanId);
                $subscription->update([
                    'subscription_plan_id' => $newPlan->id,
                    'amount' => ($subscription->billing_cycle === 'yearly') ? $newPlan->annual_price : $newPlan->monthly_price,
                ]);

                SubscriptionEvent::create([
                    'subscription_id' => $subscription->id,
                    'business_id' => $business->id,
                    'event_type' => 'plan_changed',
                    'description' => "Plan manually changed to {$newPlan->name} by Super Administrator.",
                    'created_at' => now(),
                ]);

                AuditLog::record('change_plan', "Changed plan to {$newPlan->name} for {$business->name}", 'subscription', $subscription->id);
                return back()->with('success', "Subscriber plan changed to {$newPlan->name}.");

            case 'activate_paid':
            case 'approve':
                $isAnnual = ($subscription->billing_cycle === 'yearly');
                $endsAt = $isAnnual ? Carbon::now()->addYear() : Carbon::now()->addMonth();

                $subscription->update([
                    'status' => 'active',
                    'payment_status' => 'paid',
                    'starts_at' => Carbon::now(),
                    'ends_at' => $endsAt,
                    'next_billing_date' => $endsAt,
                ]);

                // Update any pending payments for this subscription to successful
                SubscriptionPayment::where('subscription_id', $subscription->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'successful',
                        'paid_at' => Carbon::now(),
                    ]);

                // Generate Subscription Invoice
                $invNumber = 'SUB-INV-' . date('Y') . '-' . sprintf('%04d', rand(100, 9999));
                SubscriptionInvoice::create([
                    'invoice_number' => $invNumber,
                    'business_id' => $business->id,
                    'subscription_id' => $subscription->id,
                    'plan_id' => $subscription->subscription_plan_id,
                    'amount' => $subscription->amount,
                    'currency' => 'UGX',
                    'billing_period_start' => Carbon::now()->toDateString(),
                    'billing_period_end' => $endsAt->toDateString(),
                    'status' => 'paid',
                    'payment_method' => 'Pesapal Merchant Store',
                    'paid_at' => Carbon::now(),
                ]);

                SubscriptionEvent::create([
                    'subscription_id' => $subscription->id,
                    'business_id' => $business->id,
                    'event_type' => 'subscription_approved',
                    'description' => "Subscription officially approved & activated by Super Administrator.",
                    'created_at' => Carbon::now(),
                ]);

                AuditLog::record('activate_subscription', "Approved & activated paid subscription for {$business->name}", 'subscription', $subscription->id);
                return back()->with('success', "Subscription for {$business->name} successfully approved and activated (Paid until {$endsAt->format('d M Y')}).");

            case 'suspend':
                $subscription->update(['status' => 'suspended']);
                AuditLog::record('suspend_subscription', "Suspended subscription for {$business->name}", 'subscription', $subscription->id);
                return back()->with('warning', "Subscription suspended.");

            case 'cancel':
                $subscription->update(['status' => 'cancelled', 'cancelled_at' => now()]);
                AuditLog::record('cancel_subscription', "Cancelled subscription for {$business->name}", 'subscription', $subscription->id);
                return back()->with('info', "Subscription cancelled.");
        }

        return back()->with('info', 'No action performed.');
    }

    /**
     * Trial Accounts Management Table (ADMIN → TRIAL ACCOUNTS).
     */
    public function trials(): View
    {
        $trials = Subscription::with(['business.owner', 'plan'])
            ->where('status', 'trialing')
            ->orderBy('trial_ends_at', 'asc')
            ->get();

        return view('admin.subscriptions.trials.index', compact('trials'));
    }

    /**
     * Payments History List (ADMIN → PAYMENTS).
     */
    public function payments(): View
    {
        $payments = SubscriptionPayment::with(['business', 'plan'])->latest('id')->paginate(20);
        return view('admin.subscriptions.payments.index', compact('payments'));
    }

    /**
     * Invoices List (ADMIN → INVOICES).
     */
    public function invoices(): View
    {
        $invoices = SubscriptionInvoice::with(['business', 'plan'])->latest('id')->paginate(20);
        return view('admin.subscriptions.invoices.index', compact('invoices'));
    }

    /**
     * Subscription Settings Page (ADMIN → SETTINGS).
     * Edit Trial Days (7, 14, 30, 60, 90), Currency (UGX), Payment Gateway, Renewal Reminders without code changes!
     */
    public function settings(): View
    {
        $settings = SubscriptionSetting::all()->keyBy('key');
        return view('admin.subscriptions.settings.index', compact('settings'));
    }

    /**
     * Save Subscription Settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            SubscriptionSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            \Illuminate\Support\Facades\Cache::forget("sub_setting_{$key}");
        }

        AuditLog::record('update_subscription_settings', "Super Administrator updated subscription settings (Default trial days: {$request->input('default_trial_days')}, Currency: {$request->input('currency')}).");

        return back()->with('success', 'Subscription & Billing commercial settings saved successfully.');
    }
}
