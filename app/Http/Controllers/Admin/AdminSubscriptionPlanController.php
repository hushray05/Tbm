<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Feature;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminSubscriptionPlanController extends Controller
{
    /**
     * Display Subscription Plans Table (ADMIN → SUBSCRIPTION PLANS).
     */
    public function index(): View
    {
        $plans = SubscriptionPlan::with(['planFeatures', 'subscriptions'])
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('admin.subscriptions.plans.index', compact('plans'));
    }

    /**
     * Show form to create new subscription plan.
     */
    public function create(): View
    {
        $features = Feature::where('is_active', true)->get();
        return view('admin.subscriptions.plans.create', compact('features'));
    }

    /**
     * Store new plan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'monthly_price' => 'required|numeric|min:0',
            'annual_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'trial_enabled' => 'nullable|boolean',
            'trial_duration' => 'required|integer|min:0',
            'trial_unit' => 'required|string|max:50',
            'max_users' => 'nullable|integer|min:1',
            'max_locations' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'max_invoices' => 'nullable|integer|min:1',
            'max_receipts' => 'nullable|integer|min:1',
            'max_purchases' => 'nullable|integer|min:1',
            'max_vendors' => 'nullable|integer|min:1',
            'max_customers' => 'nullable|integer|min:1',
            'max_storage_mb' => 'nullable|integer|min:1',
            'max_documents' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer',
            'features' => 'nullable|array',
        ]);

        $plan = SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'monthly_price' => (int) $validated['monthly_price'],
            'annual_price' => (int) $validated['annual_price'],
            'currency' => $validated['currency'],
            'currency_symbol' => $validated['currency_symbol'],
            'trial_enabled' => $request->boolean('trial_enabled', true),
            'trial_duration' => (int) $validated['trial_duration'],
            'trial_unit' => $validated['trial_unit'],
            'max_users' => $validated['max_users'] ?? null,
            'max_locations' => $validated['max_locations'] ?? null,
            'max_products' => $validated['max_products'] ?? null,
            'max_invoices' => $validated['max_invoices'] ?? null,
            'max_receipts' => $validated['max_receipts'] ?? null,
            'max_purchases' => $validated['max_purchases'] ?? null,
            'max_vendors' => $validated['max_vendors'] ?? null,
            'max_customers' => $validated['max_customers'] ?? null,
            'max_storage_mb' => $validated['max_storage_mb'] ?? null,
            'max_documents' => $validated['max_documents'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) $validated['sort_order'],
        ]);

        // Sync Features Matrix
        $allFeatures = Feature::all();
        $selectedFeatures = $request->input('features', []);

        foreach ($allFeatures as $feat) {
            SubscriptionPlanFeature::create([
                'subscription_plan_id' => $plan->id,
                'feature_key' => $feat->key,
                'is_enabled' => in_array($feat->key, $selectedFeatures),
            ]);
        }

        AuditLog::record('create_plan', "Created new subscription plan: {$plan->name} (UGX " . number_format($plan->monthly_price) . "/mo)", 'subscription_plan', $plan->id);

        return redirect()->route('admin.subscriptions.plans.index')->with('success', "Subscription Plan '{$plan->name}' created successfully.");
    }

    /**
     * Show form to edit an existing plan.
     */
    public function edit(SubscriptionPlan $plan): View
    {
        $plan->load('planFeatures');
        $features = Feature::all();

        return view('admin.subscriptions.plans.edit', compact('plan', 'features'));
    }

    /**
     * Update an existing plan.
     */
    public function update(Request $request, SubscriptionPlan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'monthly_price' => 'required|numeric|min:0',
            'annual_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'trial_enabled' => 'nullable|boolean',
            'trial_duration' => 'required|integer|min:0',
            'trial_unit' => 'required|string|max:50',
            'max_users' => 'nullable|integer|min:1',
            'max_locations' => 'nullable|integer|min:1',
            'max_products' => 'nullable|integer|min:1',
            'max_invoices' => 'nullable|integer|min:1',
            'max_receipts' => 'nullable|integer|min:1',
            'max_purchases' => 'nullable|integer|min:1',
            'max_vendors' => 'nullable|integer|min:1',
            'max_customers' => 'nullable|integer|min:1',
            'max_storage_mb' => 'nullable|integer|min:1',
            'max_documents' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer',
            'features' => 'nullable|array',
        ]);

        $plan->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'] ?? null,
            'badge' => $validated['badge'] ?? null,
            'monthly_price' => (int) $validated['monthly_price'],
            'annual_price' => (int) $validated['annual_price'],
            'currency' => $validated['currency'],
            'currency_symbol' => $validated['currency_symbol'],
            'trial_enabled' => $request->boolean('trial_enabled', true),
            'trial_duration' => (int) $validated['trial_duration'],
            'trial_unit' => $validated['trial_unit'],
            'max_users' => $validated['max_users'] ?? null,
            'max_locations' => $validated['max_locations'] ?? null,
            'max_products' => $validated['max_products'] ?? null,
            'max_invoices' => $validated['max_invoices'] ?? null,
            'max_receipts' => $validated['max_receipts'] ?? null,
            'max_purchases' => $validated['max_purchases'] ?? null,
            'max_vendors' => $validated['max_vendors'] ?? null,
            'max_customers' => $validated['max_customers'] ?? null,
            'max_storage_mb' => $validated['max_storage_mb'] ?? null,
            'max_documents' => $validated['max_documents'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (int) $validated['sort_order'],
        ]);

        // Sync Features Matrix
        $allFeatures = Feature::all();
        $selectedFeatures = $request->input('features', []);

        foreach ($allFeatures as $feat) {
            SubscriptionPlanFeature::updateOrCreate(
                ['subscription_plan_id' => $plan->id, 'feature_key' => $feat->key],
                ['is_enabled' => in_array($feat->key, $selectedFeatures)]
            );
        }

        AuditLog::record('update_plan', "Updated plan {$plan->name}: Monthly Price UGX " . number_format($plan->monthly_price) . ", Annual UGX " . number_format($plan->annual_price) . ", Trial: {$plan->trial_duration} days", 'subscription_plan', $plan->id);

        return redirect()->route('admin.subscriptions.plans.index')->with('success', "Plan '{$plan->name}' updated successfully.");
    }

    /**
     * Duplicate an existing plan.
     */
    public function duplicate(SubscriptionPlan $plan): RedirectResponse
    {
        $newPlan = $plan->replicate();
        $newPlan->name = $plan->name . ' (Copy)';
        $newPlan->slug = $plan->slug . '-copy-' . rand(10, 99);
        $newPlan->is_active = false;
        $newPlan->save();

        foreach ($plan->planFeatures as $pf) {
            SubscriptionPlanFeature::create([
                'subscription_plan_id' => $newPlan->id,
                'feature_key' => $pf->feature_key,
                'is_enabled' => $pf->is_enabled,
            ]);
        }

        AuditLog::record('duplicate_plan', "Duplicated plan {$plan->name} to {$newPlan->name}", 'subscription_plan', $newPlan->id);

        return back()->with('success', "Plan '{$newPlan->name}' cloned successfully.");
    }

    /**
     * Toggle plan active / inactive status.
     */
    public function toggle(SubscriptionPlan $plan): RedirectResponse
    {
        $plan->update(['is_active' => !$plan->is_active]);
        $status = $plan->is_active ? 'Activated' : 'Deactivated';

        AuditLog::record('toggle_plan', "{$status} plan {$plan->name}", 'subscription_plan', $plan->id);

        return back()->with('success', "Plan '{$plan->name}' has been {$status}.");
    }

    /**
     * Delete a plan.
     */
    public function destroy(SubscriptionPlan $plan): RedirectResponse
    {
        if ($plan->subscriptions()->count() > 0) {
            return back()->with('error', "Cannot delete plan '{$plan->name}' because it has active subscribers. Please deactivate it instead.");
        }

        $name = $plan->name;
        $plan->delete();

        AuditLog::record('delete_plan', "Deleted plan {$name}", 'subscription_plan', null);

        return redirect()->route('admin.subscriptions.plans.index')->with('success', "Plan '{$name}' deleted.");
    }
}
