@extends('layouts.admin')

@section('title', 'Amend Business Details • ' . $business->name . ' • Super Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.businesses.index') }}" class="p-2 rounded-xl bg-surface border border-border-quiet text-secondary hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
        </a>
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Amend Organization: {{ $business->name }}</h1>
            <p class="text-xs text-secondary">Super Admin controls for tenant account configuration, tax info, and plan allocation.</p>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 md:p-8 tbm-shadow">
        <form action="{{ route('admin.businesses.update', $business) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: Identity -->
            <div class="space-y-4">
                <h3 class="font-headline font-bold text-base text-text-rich border-b border-border-quiet pb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">domain</span>
                    <span>Legal Identity & Tax Information</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-secondary uppercase mb-1.5">Business Name *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $business->name) }}" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                    </div>
                    <div>
                        <label for="tin_number" class="block text-xs font-bold text-secondary uppercase mb-1.5">URA Tax TIN Number</label>
                        <input type="text" name="tin_number" id="tin_number" value="{{ old('tin_number', $business->tin_number) }}" placeholder="1000293847" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary font-numeric">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-bold text-secondary uppercase mb-1.5">Official Contact Email *</label>
                        <input type="email" name="email" id="email" required value="{{ old('email', $business->email) }}" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-bold text-secondary uppercase mb-1.5">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $business->phone) }}" placeholder="+256 700 000000" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary font-numeric">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-xs font-bold text-secondary uppercase mb-1.5">Headquarters City</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $business->city) }}" placeholder="Kampala" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                    </div>
                    <div>
                        <label for="country" class="block text-xs font-bold text-secondary uppercase mb-1.5">Country *</label>
                        <input type="text" name="country" id="country" required value="{{ old('country', $business->country ?? 'Uganda') }}" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Currency & Governance -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-base text-text-rich border-b border-border-quiet pb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">currency_exchange</span>
                    <span>Operational Currency & Platform Status</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="currency_code" class="block text-xs font-bold text-secondary uppercase mb-1.5">Base Currency Code *</label>
                        <select name="currency_code" id="currency_code" class="w-full h-11 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary font-bold">
                            @foreach($currencies as $c)
                                <option value="{{ $c->code }}" {{ old('currency_code', $business->currency_code) === $c->code ? 'selected' : '' }}>
                                    {{ $c->code }} - {{ $c->name }} ({{ $c->symbol }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="currency_symbol" class="block text-xs font-bold text-secondary uppercase mb-1.5">Currency Symbol *</label>
                        <input type="text" name="currency_symbol" id="currency_symbol" required value="{{ old('currency_symbol', $business->currency_symbol ?? 'UGX') }}" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary font-numeric font-bold">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-secondary uppercase mb-1.5">Platform Status *</label>
                        <select name="status" id="status" class="w-full h-11 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary font-bold">
                            <option value="active" {{ old('status', $business->status) === 'active' ? 'selected' : '' }}>Active (Full Access)</option>
                            <option value="suspended" {{ old('status', $business->status) === 'suspended' ? 'selected' : '' }}>Suspended (Locked)</option>
                            <option value="inactive" {{ old('status', $business->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Plan Allocation -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-base text-text-rich border-b border-border-quiet pb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">layers</span>
                    <span>Assigned Subscription Plan Tier</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($plans as $plan)
                        @php
                            $isCurrentPlan = $activeSubscription && $activeSubscription->plan_id == $plan->id;
                        @endphp
                        <label class="border-2 rounded-xl p-4 flex flex-col justify-between cursor-pointer transition-all {{ $isCurrentPlan ? 'border-primary bg-primary/5' : 'border-border-quiet hover:bg-bg-subtle' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-headline font-bold text-sm text-text-rich">{{ $plan->name }}</span>
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ $isCurrentPlan ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            </div>
                            <span class="font-numeric font-bold text-primary text-sm">UGX {{ number_format($plan->monthly_price) }}</span>
                            <span class="text-[10px] text-secondary mt-1">Up to {{ $plan->max_users }} users • {{ $plan->max_locations }} branch</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border-quiet">
                <a href="{{ route('admin.businesses.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-semibold text-secondary hover:bg-bg-subtle transition-all">Cancel</a>
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-6 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    <span>Save & Amend Organization</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
