@extends('layouts.app')

@section('title', 'Feature Upgrade Required • TBM Enterprise')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 text-center">
    <div class="bg-surface border border-border-quiet rounded-2xl p-8 md:p-12 tbm-shadow space-y-6">
        <div class="w-20 h-20 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center mx-auto shadow-sm">
            <span class="material-symbols-outlined text-4xl">lock</span>
        </div>

        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                Premium Plan Feature
            </span>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Upgrade Required for {{ ucwords(str_replace('_', ' ', $featureKey)) }}</h1>
            <p class="text-secondary text-sm max-w-lg mx-auto">
                The <strong>{{ ucwords(str_replace('_', ' ', $featureKey)) }}</strong> module is not enabled on your current <strong>{{ $subscription->plan->name ?? 'Starter' }}</strong> subscription plan.
            </p>
        </div>

        <div class="bg-bg-subtle border border-border-quiet rounded-xl p-5 text-left max-w-md mx-auto space-y-3">
            <span class="text-xs font-bold text-primary uppercase tracking-wider block">What you unlock with Professional & Premium:</span>
            <div class="space-y-2 text-xs text-text-rich">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-success text-base">check_circle</span>
                    <span>Automated Uganda PAYE & NSSF payroll processing & payslips</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-success text-base">check_circle</span>
                    <span>Uganda URA VAT 18% & Local Excise Duty compliance tracking</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-success text-base">check_circle</span>
                    <span>Real-time Profit & Loss statement and Balance Sheet</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-success text-base">check_circle</span>
                    <span>Multi-location branch inventory & commercial sales orders</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('subscription.portal') }}" class="w-full sm:w-auto bg-primary hover:bg-primary-container text-white px-6 py-3 rounded-lg text-sm font-bold shadow-md transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">rocket_launch</span>
                <span>Manage & Upgrade Plan</span>
            </a>
            <a href="https://store.pesapal.com/taskbusinessmanagerpos" target="_blank" class="w-full sm:w-auto bg-amber-400 hover:bg-amber-300 text-slate-900 px-6 py-3 rounded-lg text-sm font-extrabold shadow-md transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-base">open_in_new</span>
                <span>Pay via Pesapal Store</span>
            </a>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto bg-surface hover:bg-bg-subtle text-secondary border border-border-quiet px-5 py-3 rounded-lg text-sm font-medium transition-all">
                <span>Return to Dashboard</span>
            </a>
        </div>
    </div>
</div>
@endsection
