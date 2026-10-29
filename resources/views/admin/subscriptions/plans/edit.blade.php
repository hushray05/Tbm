@extends('layouts.admin')

@section('title', 'Edit Subscription Plan • Super Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Edit Plan: {{ $plan->name }}</h1>
            <p class="text-xs text-secondary mt-0.5">Adjust pricing, trial duration, limits, and feature toggles.</p>
        </div>
        <a href="{{ route('admin.subscriptions.plans.index') }}" class="text-xs font-semibold text-primary hover:underline">
            ← Back to Plans
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl p-6 md:p-8 tbm-shadow">
        <form action="{{ route('admin.subscriptions.plans.update', $plan) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. General Information -->
            <div class="space-y-4">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    1. General Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Plan Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $plan->name) }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Plan Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" required value="{{ old('slug', $plan->slug) }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-mono text-text-rich outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Highlight Badge</label>
                        <input type="text" name="badge" value="{{ old('badge', $plan->badge) }}" placeholder="e.g. MOST POPULAR" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Sort Order <span class="text-danger">*</span></label>
                        <input type="number" name="sort_order" required value="{{ old('sort_order', $plan->sort_order) }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Short Description</label>
                    <textarea name="description" rows="2" class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">{{ old('description', $plan->description) }}</textarea>
                </div>
            </div>

            <!-- 2. Pricing & Currency (UGX) -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    2. Pricing & Currency (UGX)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Monthly Price (UGX) <span class="text-danger">*</span></label>
                        <input type="number" name="monthly_price" required value="{{ old('monthly_price', $plan->monthly_price) }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric font-bold text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Annual Price (UGX) <span class="text-danger">*</span></label>
                        <input type="number" name="annual_price" required value="{{ old('annual_price', $plan->annual_price) }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric font-bold text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Currency Code</label>
                        <input type="text" name="currency" readonly value="{{ $plan->currency }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-slate-100 text-xs font-bold text-primary outline-none">
                        <input type="hidden" name="currency_symbol" value="UGX">
                    </div>
                </div>
            </div>

            <!-- 3. Trial Configuration -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    3. Free Trial Settings
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Trial Duration (Days) <span class="text-danger">*</span></label>
                        <select name="trial_duration" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                            <option value="7" {{ $plan->trial_duration == 7 ? 'selected' : '' }}>7 Days</option>
                            <option value="14" {{ $plan->trial_duration == 14 ? 'selected' : '' }}>14 Days</option>
                            <option value="30" {{ $plan->trial_duration == 30 ? 'selected' : '' }}>30 Days</option>
                            <option value="60" {{ $plan->trial_duration == 60 ? 'selected' : '' }}>60 Days</option>
                            <option value="90" {{ $plan->trial_duration == 90 ? 'selected' : '' }}>90 Days</option>
                        </select>
                        <input type="hidden" name="trial_unit" value="days">
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-2 text-xs font-bold text-text-rich cursor-pointer">
                            <input type="checkbox" name="trial_enabled" value="1" {{ $plan->trial_enabled ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-border-quiet">
                            <span>Enable Free Trial on this plan</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- 4. Usage Quotas & Limits -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    4. Usage Limits & Quotas (Leave blank for Unlimited)
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Users</label>
                        <input type="number" name="max_users" value="{{ old('max_users', $plan->max_users) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Locations</label>
                        <input type="number" name="max_locations" value="{{ old('max_locations', $plan->max_locations) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Products</label>
                        <input type="number" name="max_products" value="{{ old('max_products', $plan->max_products) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Invoices</label>
                        <input type="number" name="max_invoices" value="{{ old('max_invoices', $plan->max_invoices) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Receipts</label>
                        <input type="number" name="max_receipts" value="{{ old('max_receipts', $plan->max_receipts) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Purchases</label>
                        <input type="number" name="max_purchases" value="{{ old('max_purchases', $plan->max_purchases) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Vendors</label>
                        <input type="number" name="max_vendors" value="{{ old('max_vendors', $plan->max_vendors) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Max Customers</label>
                        <input type="number" name="max_customers" value="{{ old('max_customers', $plan->max_customers) }}" placeholder="Unlimited" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                    </div>
                </div>
            </div>

            <!-- 5. Feature Access Matrix -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    5. Feature Permissions Matrix
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($features as $feat)
                        @php $hasFeature = $plan->hasFeature($feat->key); @endphp
                        <label class="flex items-start gap-2.5 p-3 rounded-xl border border-border-quiet bg-bg-subtle hover:bg-white cursor-pointer transition-colors">
                            <input type="checkbox" name="features[]" value="{{ $feat->key }}" {{ $hasFeature ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-border-quiet mt-0.5">
                            <div>
                                <span class="text-xs font-bold text-text-rich block">{{ $feat->name }}</span>
                                <span class="text-[10px] text-secondary">{{ $feat->description }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-border-quiet flex items-center justify-end gap-3">
                <a href="{{ route('admin.subscriptions.plans.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-semibold text-secondary hover:bg-bg-subtle">
                    Cancel
                </a>
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-6 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">save</span>
                    <span>Update Plan Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
