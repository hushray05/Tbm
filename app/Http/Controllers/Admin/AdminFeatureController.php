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

class AdminFeatureController extends Controller
{
    /**
     * Display Subscription Features Manager (ADMIN → SUBSCRIPTION FEATURES).
     */
    public function index(): View
    {
        $features = Feature::with('planMappings')->get();
        $plans = SubscriptionPlan::all();

        return view('admin.subscriptions.features.index', compact('features', 'plans'));
    }

    /**
     * Store new feature.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:100|unique:features,key',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
        ]);

        $key = Str::snake($validated['key']);

        $feature = Feature::create([
            'name' => $validated['name'],
            'key' => $key,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'is_active' => true,
        ]);

        // Assign default mapping across all existing plans
        $plans = SubscriptionPlan::all();
        $assignedPlans = $request->input('plans', []);

        foreach ($plans as $p) {
            SubscriptionPlanFeature::create([
                'subscription_plan_id' => $p->id,
                'feature_key' => $key,
                'is_enabled' => in_array($p->id, $assignedPlans),
            ]);
        }

        AuditLog::record('create_feature', "Created new feature: {$feature->name} (key: {$key})", 'feature', $feature->id);

        return back()->with('success', "Feature '{$feature->name}' created and assigned to plans.");
    }

    /**
     * Update an existing feature or toggle its plan mapping.
     */
    public function update(Request $request, Feature $feature): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $feature->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Update plan matrix
        $plans = SubscriptionPlan::all();
        $assignedPlans = $request->input('plans', []);

        foreach ($plans as $p) {
            SubscriptionPlanFeature::updateOrCreate(
                ['subscription_plan_id' => $p->id, 'feature_key' => $feature->key],
                ['is_enabled' => in_array($p->id, $assignedPlans)]
            );
        }

        AuditLog::record('update_feature', "Updated feature: {$feature->name}", 'feature', $feature->id);

        return back()->with('success', "Feature '{$feature->name}' updated successfully.");
    }

    /**
     * Delete a feature.
     */
    public function destroy(Feature $feature): RedirectResponse
    {
        $name = $feature->name;
        SubscriptionPlanFeature::where('feature_key', $feature->key)->delete();
        $feature->delete();

        AuditLog::record('delete_feature', "Deleted feature: {$name}", 'feature', null);

        return back()->with('success', "Feature '{$name}' deleted.");
    }
}
