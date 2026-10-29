@extends('layouts.app')

@section('title', 'Subscription & Billing • TBM Enterprise')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Subscription & Billing Management</h1>
            <p class="text-xs text-secondary mt-0.5">Manage your workspace plan, usage quotas, payment receipts, and upgrade options in UGX.</p>
        </div>
        <div>
            <a href="{{ route('subscription.pricing') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">compare_arrows</span>
                <span>Compare All Plans</span>
            </a>
        </div>
    </div>

    <!-- Active Subscription Status Banner Card -->
    <div class="bg-surface border border-primary/30 rounded-2xl p-6 tbm-shadow relative overflow-hidden bg-gradient-to-r from-primary-fixed-dim/20 to-surface">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider {{ $subscription && $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $subscription ? strtoupper($subscription->status) : 'TRIALING' }}
                    </span>
                    <span class="text-xs font-bold text-secondary">
                        Plan: <span class="text-primary font-extrabold">{{ $subscription->plan->name ?? 'Starter' }}</span>
                    </span>
                    <span class="text-xs text-secondary">• Billing: <span class="capitalize font-semibold text-text-rich">{{ $subscription->billing_cycle ?? 'Monthly' }}</span></span>
                </div>

                <div>
                    <h2 class="font-headline text-2xl font-bold text-text-rich">
                        {{ $subscription->plan->name ?? 'Starter' }} Plan
                        <span class="text-base font-normal text-secondary font-numeric">({{ $subscription->plan->formatted_monthly_price ?? 'UGX 15,000' }} / mo)</span>
                    </h2>
                    <p class="text-xs text-secondary mt-0.5">{{ $subscription->plan->description ?? '' }}</p>
                </div>

                @if($subscription && $subscription->payment_status === 'pending_approval')
                    <div class="flex items-center gap-2 text-xs font-bold text-blue-900 bg-blue-50 border border-blue-200 px-3.5 py-2 rounded-xl inline-flex">
                        <span class="material-symbols-outlined text-base text-primary animate-spin">sync</span>
                        <span>Pesapal Payment Submitted: Awaiting Super Administrator Review & Approval</span>
                    </div>
                @elseif($subscription && $subscription->status === 'trialing')
                    <div class="flex items-center gap-2 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-3.5 py-1.5 rounded-lg inline-flex">
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        <span>{{ $subscription->trial_remaining_days }} Days Remaining in Free Trial (Ends {{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('d M Y') : '' }})</span>
                    </div>
                @elseif($subscription && $subscription->status === 'active')
                    <div class="flex items-center gap-2 text-xs font-semibold text-success bg-green-50 border border-green-200 px-3.5 py-1.5 rounded-lg inline-flex">
                        <span class="material-symbols-outlined text-sm">verified</span>
                        <span>Active subscription paid until {{ $subscription->ends_at ? $subscription->ends_at->format('d M Y') : 'Next Billing Date' }}</span>
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('subscription.checkout', ['plan' => 'premium', 'cycle' => 'yearly']) }}" class="bg-primary hover:bg-primary-container text-white px-5 py-3 rounded-xl text-xs font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">rocket_launch</span>
                    <span>Upgrade to Premium (UGX 400,000/yr)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Usage Quotas & Limits Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <!-- Users Limit -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-secondary uppercase">
                <span>Team Users</span>
                <span class="material-symbols-outlined text-primary text-base">group</span>
            </div>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ $userLimit['current'] }} <span class="text-sm font-normal text-secondary">/ {{ $userLimit['max'] ?? '∞' }}</span>
            </div>
            <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width: {{ $userLimit['max'] ? min(100, ($userLimit['current'] / $userLimit['max']) * 100) : 20 }}%"></div>
            </div>
        </div>

        <!-- Products Limit -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-secondary uppercase">
                <span>Products Catalog</span>
                <span class="material-symbols-outlined text-primary text-base">inventory_2</span>
            </div>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ $productLimit['current'] }} <span class="text-sm font-normal text-secondary">/ {{ $productLimit['max'] ?? '∞' }}</span>
            </div>
            <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width: {{ $productLimit['max'] ? min(100, ($productLimit['current'] / $productLimit['max']) * 100) : 10 }}%"></div>
            </div>
        </div>

        <!-- Invoices Limit -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-secondary uppercase">
                <span>Invoices Generated</span>
                <span class="material-symbols-outlined text-primary text-base">receipt_long</span>
            </div>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ $invoiceLimit['current'] }} <span class="text-sm font-normal text-secondary">/ {{ $invoiceLimit['max'] ?? '∞' }}</span>
            </div>
            <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width: {{ $invoiceLimit['max'] ? min(100, ($invoiceLimit['current'] / $invoiceLimit['max']) * 100) : 5 }}%"></div>
            </div>
        </div>

        <!-- Locations Limit -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-3">
            <div class="flex items-center justify-between text-xs font-bold text-secondary uppercase">
                <span>Business Locations</span>
                <span class="material-symbols-outlined text-primary text-base">warehouse</span>
            </div>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ $locationLimit['current'] }} <span class="text-sm font-normal text-secondary">/ {{ $locationLimit['max'] ?? '∞' }}</span>
            </div>
            <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width: {{ $locationLimit['max'] ? min(100, ($locationLimit['current'] / $locationLimit['max']) * 100) : 25 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Your Current Plan: Full Feature Breakdown -->
    @if($subscription && $subscription->plan)
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-headline font-bold text-text-rich text-lg">
                Your <span class="text-primary">{{ $subscription->plan->name }}</span> Plan — Full Feature Breakdown
            </h3>
            <a href="{{ route('subscription.pricing') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">compare_arrows</span>
                <span>Compare All Plans</span>
            </a>
        </div>
        <p class="text-xs text-secondary">Everything marked <span class="font-bold text-success">✓</span> is active on your current plan. Items marked <span class="font-bold text-outline">✗</span> require an upgrade.</p>

        <!-- Limits Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 text-center">
                <span class="material-symbols-outlined text-primary text-xl block mb-1">group</span>
                <div class="font-bold text-lg text-text-rich font-numeric">{{ $subscription->plan->max_users ?? '∞' }}</div>
                <div class="text-[10px] text-secondary font-bold uppercase tracking-wider">Team Users</div>
            </div>
            <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 text-center">
                <span class="material-symbols-outlined text-primary text-xl block mb-1">inventory_2</span>
                <div class="font-bold text-lg text-text-rich font-numeric">{{ $subscription->plan->max_products ? number_format($subscription->plan->max_products) : '∞' }}</div>
                <div class="text-[10px] text-secondary font-bold uppercase tracking-wider">Products</div>
            </div>
            <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 text-center">
                <span class="material-symbols-outlined text-primary text-xl block mb-1">receipt_long</span>
                <div class="font-bold text-lg text-text-rich font-numeric">{{ $subscription->plan->max_invoices ? number_format($subscription->plan->max_invoices) : '∞' }}</div>
                <div class="text-[10px] text-secondary font-bold uppercase tracking-wider">Invoices/mo</div>
            </div>
            <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 text-center">
                <span class="material-symbols-outlined text-primary text-xl block mb-1">warehouse</span>
                <div class="font-bold text-lg text-text-rich font-numeric">{{ $subscription->plan->max_locations ?? '∞' }}</div>
                <div class="text-[10px] text-secondary font-bold uppercase tracking-wider">Locations</div>
            </div>
        </div>

        <!-- Feature Access List -->
        @if($features->isNotEmpty())
        <div class="border-t border-border-quiet pt-4">
            <h4 class="text-xs font-bold text-secondary uppercase tracking-wider mb-3">Feature Access</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($features as $feature)
                    @php $hasAccess = $subscription->plan->hasFeature($feature->key); @endphp
                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ $hasAccess ? 'bg-emerald-50 border border-emerald-100' : 'bg-slate-50 border border-slate-100' }}">
                        <span class="material-symbols-outlined text-base {{ $hasAccess ? 'text-success' : 'text-outline/50' }}">
                            {{ $hasAccess ? 'check_circle' : 'cancel' }}
                        </span>
                        <span class="text-xs font-medium {{ $hasAccess ? 'text-text-rich' : 'text-outline/70 line-through' }}">{{ $feature->name }}</span>
                        @if(!$hasAccess)
                            <span class="ml-auto text-[9px] font-bold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded">Upgrade</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Quick Switch / Upgrade Grid -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-6">
        <div>
            <h3 class="font-headline font-bold text-text-rich text-lg">Change Subscription Plan</h3>
            <p class="text-xs text-secondary mt-1">Every plan includes a <strong>{{ $defaultTrialDays }}-Day Free Trial</strong> with zero upfront payment required. Cancel or switch anytime.</p>
        </div>

        <!-- Monthly / Annual Billing Toggle Switch -->
        <div class="flex items-center justify-center gap-3 pt-2">
            <span class="text-xs font-bold cursor-pointer" id="labelMonthly" onclick="setBillingCycle('monthly')">Monthly Billing</span>
            <button type="button" id="billingToggleBtn" class="w-14 h-7 rounded-full bg-primary relative p-1 transition-colors focus:outline-none" onclick="toggleBillingCycle()">
                <div id="toggleBall" class="w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-200"></div>
            </button>
            <span class="text-xs font-bold text-secondary flex items-center gap-1.5 cursor-pointer" id="labelAnnual" onclick="setBillingCycle('annual')">
                <span>Annual Billing</span>
                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Save ~17%</span>
            </span>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
                <div class="bg-surface rounded-2xl border {{ $plan->badge === 'MOST POPULAR' ? 'border-primary shadow-xl ring-2 ring-primary/20 relative' : 'border-border-quiet tbm-shadow' }} {{ $subscription && $subscription->subscription_plan_id == $plan->id ? 'ring-2 ring-success/30 border-success relative' : '' }} p-7 flex flex-col justify-between transition-all hover:translate-y-[-4px]">
                    
                    @if($plan->badge)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-primary text-white text-[11px] font-bold px-3.5 py-0.5 rounded-full uppercase tracking-wider shadow-sm">
                            {{ $plan->badge }}
                        </div>
                    @endif

                    <div class="space-y-5">
                        <div>
                            <h3 class="font-headline font-bold text-xl text-text-rich">{{ $plan->name }}</h3>
                            <p class="text-xs text-secondary mt-1 min-h-[32px]">{{ $plan->description }}</p>
                        </div>

                        <!-- Dynamic Price Display (UGX) -->
                        <div class="py-3 border-y border-border-quiet">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs font-bold text-secondary">UGX</span>
                                <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric plan-price-monthly">
                                    {{ number_format($plan->monthly_price) }}
                                </span>
                                <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric plan-price-annual hidden">
                                    {{ number_format($plan->annual_price) }}
                                </span>
                                <span class="text-xs text-secondary font-medium plan-period-monthly">/ month</span>
                                <span class="text-xs text-secondary font-medium plan-period-annual hidden">/ year</span>
                            </div>
                            <div class="mt-1 flex items-center gap-1.5 text-xs text-success font-semibold">
                                <span class="material-symbols-outlined text-sm">verified</span>
                                <span>{{ $plan->trial_duration }}-Day Free Trial Included</span>
                            </div>
                        </div>

                        <!-- Usage Limits -->
                        <div class="space-y-2 text-xs">
                            <span class="text-[11px] font-bold text-outline uppercase tracking-wider block">Usage Limits</span>
                            <div class="flex items-center justify-between text-text-rich">
                                <span class="text-secondary">Users:</span>
                                <span class="font-bold">{{ $plan->max_users ? $plan->max_users . ' User' . ($plan->max_users > 1 ? 's' : '') : 'Unlimited' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-text-rich">
                                <span class="text-secondary">Locations:</span>
                                <span class="font-bold">{{ $plan->max_locations ? $plan->max_locations . ' Location' . ($plan->max_locations > 1 ? 's' : '') : 'Multi-Location' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-text-rich">
                                <span class="text-secondary">Products Catalog:</span>
                                <span class="font-bold">{{ $plan->max_products ? number_format($plan->max_products) . ' items' : 'Unlimited' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-text-rich">
                                <span class="text-secondary">Monthly Invoices:</span>
                                <span class="font-bold">{{ $plan->max_invoices ? number_format($plan->max_invoices) : 'Unlimited' }}</span>
                            </div>
                        </div>

                        <!-- Feature Matrix -->
                        <div class="space-y-2.5 pt-2 border-t border-border-quiet">
                            <span class="text-[11px] font-bold text-outline uppercase tracking-wider block">Included Features</span>
                            <div class="space-y-2 text-xs">
                                @foreach($features as $f)
                                    @php $isEnabled = $plan->hasFeature($f->key); @endphp
                                    <div class="flex items-center gap-2.5 {{ $isEnabled ? 'text-text-rich' : 'text-outline/70' }}">
                                        @if($isEnabled)
                                            <span class="material-symbols-outlined text-success text-base shrink-0">check_circle</span>
                                            <span>{{ $f->name }}</span>
                                        @else
                                            <span class="material-symbols-outlined text-outline/50 text-base shrink-0">cancel</span>
                                            <span class="line-through">{{ $f->name }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Button -->
                    <div class="pt-6 mt-6 border-t border-border-quiet">
                        @if($subscription && $subscription->subscription_plan_id == $plan->id)
                            <button type="button" class="w-full py-3 px-4 rounded-xl text-center font-bold text-sm shadow-sm flex items-center justify-center gap-2 bg-success/10 text-success border border-success/30 cursor-default">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                <span>Current Plan</span>
                            </button>
                        @else
                            <a href="{{ route('subscription.checkout', ['plan' => $plan->slug, 'cycle' => 'monthly']) }}" class="w-full py-3 px-4 rounded-xl text-center font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 {{ $plan->badge === 'MOST POPULAR' ? 'bg-primary hover:bg-primary-container text-white' : 'bg-bg-subtle hover:bg-primary hover:text-white text-primary border border-primary/20' }}">
                                <span>Choose {{ $plan->name }}</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        @endif
                        <span class="block text-center text-[11px] text-secondary mt-2">No payment method required to start</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Payment Gateways Supported Banner -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div>
                <h4 class="font-headline font-bold text-text-rich text-base">Ugandan Payment Gateways Supported</h4>
                <p class="text-xs text-secondary mt-0.5">Pay seamlessly with MTN Mobile Money, Airtel Money Uganda, Visa, Mastercard, or Bank Transfer.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold">MTN Mobile Money</span>
                <span class="px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-red-900 text-xs font-bold">Airtel Money</span>
                <span class="px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold">Visa / Mastercard</span>
            </div>
        </div>
    </div>
</div>

<script>
    let currentCycle = 'monthly';

    function setBillingCycle(cycle) {
        currentCycle = cycle;
        const ball = document.getElementById('toggleBall');
        const labelMonthly = document.getElementById('labelMonthly');
        const labelAnnual = document.getElementById('labelAnnual');

        const monthlyPrices = document.querySelectorAll('.plan-price-monthly');
        const annualPrices = document.querySelectorAll('.plan-price-annual');
        const monthlyPeriods = document.querySelectorAll('.plan-period-monthly');
        const annualPeriods = document.querySelectorAll('.plan-period-annual');

        if (cycle === 'annual') {
            ball.style.transform = 'translateX(28px)';
            labelMonthly.classList.remove('text-primary');
            labelMonthly.classList.add('text-secondary');
            labelAnnual.classList.remove('text-secondary');
            labelAnnual.classList.add('text-primary');

            monthlyPrices.forEach(el => el.classList.add('hidden'));
            annualPrices.forEach(el => el.classList.remove('hidden'));
            monthlyPeriods.forEach(el => el.classList.add('hidden'));
            annualPeriods.forEach(el => el.classList.remove('hidden'));
        } else {
            ball.style.transform = 'translateX(0px)';
            labelAnnual.classList.remove('text-primary');
            labelAnnual.classList.add('text-secondary');
            labelMonthly.classList.remove('text-secondary');
            labelMonthly.classList.add('text-primary');

            monthlyPrices.forEach(el => el.classList.remove('hidden'));
            annualPrices.forEach(el => el.classList.add('hidden'));
            monthlyPeriods.forEach(el => el.classList.remove('hidden'));
            annualPeriods.forEach(el => el.classList.add('hidden'));
        }
    }

    function toggleBillingCycle() {
        setBillingCycle(currentCycle === 'monthly' ? 'annual' : 'monthly');
    }
</script>
@endsection
