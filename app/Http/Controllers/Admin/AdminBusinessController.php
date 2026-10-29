<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Currency;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBusinessController extends Controller
{
    /**
     * Display a master overview of all tenant businesses in the platform.
     */
    public function index(Request $request): View
    {
        $query = Business::with(['owner', 'subscriptions.plan', 'locations']);

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('tin_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $businesses = $query->latest()->paginate(15);

        // Platform metrics summary
        $totalBusinesses = Business::count();
        $activeBusinesses = Business::where('status', 'active')->count();
        $suspendedBusinesses = Business::where('status', 'suspended')->count();
        $totalLocations = BusinessLocation::count();

        return view('admin.businesses.index', compact(
            'businesses',
            'totalBusinesses',
            'activeBusinesses',
            'suspendedBusinesses',
            'totalLocations'
        ));
    }

    /**
     * Display a single business overview with its tenant stats, owner, branches, and subscription.
     */
    public function show(Business $business): View
    {
        $business->load(['owner', 'locations', 'subscriptions.plan', 'users']);
        
        $activeSubscription = $business->subscriptions()->latest()->first();
        $usersCount = $business->users()->count();
        $locationsCount = $business->locations()->count();

        return view('admin.businesses.show', compact('business', 'activeSubscription', 'usersCount', 'locationsCount'));
    }

    /**
     * Show form to amend/edit business organization details.
     */
    public function edit(Business $business): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get();
        $activeSubscription = $business->subscriptions()->latest()->first();

        return view('admin.businesses.edit', compact('business', 'plans', 'currencies', 'activeSubscription'));
    }

    /**
     * Update/amend business details by Super Admin.
     */
    public function update(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'city'            => 'nullable|string|max:100',
            'country'         => 'required|string|max:100',
            'currency_code'   => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'tin_number'      => 'nullable|string|max:50',
            'status'          => 'required|in:active,suspended,inactive',
            'plan_id'         => 'nullable|exists:subscription_plans,id',
        ]);

        $oldStatus = $business->status;
        $business->update([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'] ?? null,
            'city'            => $validated['city'] ?? null,
            'country'         => $validated['country'],
            'currency_code'   => $validated['currency_code'],
            'currency_symbol' => $validated['currency_symbol'],
            'tin_number'      => $validated['tin_number'] ?? null,
            'status'          => $validated['status'],
        ]);

        // If plan change was requested by admin
        if (!empty($validated['plan_id'])) {
            $subscription = $business->subscriptions()->latest()->first();
            if ($subscription && $subscription->plan_id != $validated['plan_id']) {
                $plan = SubscriptionPlan::find($validated['plan_id']);
                if ($plan) {
                    $subscription->update([
                        'plan_id' => $plan->id,
                        'amount'  => $plan->monthly_price,
                    ]);
                }
            }
        }

        // Record Audit Log
        AuditLog::log('business.amend', "Super Admin amended business profile for {$business->name} (Status: {$validated['status']})", [
            'business_id' => $business->id,
            'changes'     => $validated,
        ]);

        return redirect()->route('admin.businesses.index')->with('success', "Business {$business->name} details amended successfully.");
    }

    /**
     * Quick toggle active/suspended status for a business.
     */
    public function toggleStatus(Business $business): RedirectResponse
    {
        $newStatus = $business->status === 'active' ? 'suspended' : 'active';
        $business->update(['status' => $newStatus]);

        AuditLog::log('business.toggle_status', "Super Admin toggled {$business->name} status to {$newStatus}");

        return back()->with('success', "Business {$business->name} status set to " . ucfirst($newStatus) . '.');
    }
}
