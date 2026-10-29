@extends('layouts.app')

@section('title', 'Organization & System Settings • TBM Enterprise')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Organization & Settings</h1>
            <p class="text-xs text-secondary mt-0.5">Manage your business settings, currency preferences, and account information.</p>
        </div>
        <a href="{{ route('profile') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-all self-start sm:self-auto">
            <span class="material-symbols-outlined text-sm">account_circle</span>
            My Profile Settings
        </a>
    </div>

    <!-- Business Profile Card -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <h3 class="font-headline font-bold text-base text-text-rich mb-5 pb-3 border-b border-border-quiet flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">domain</span>
            Business Identity — {{ $business->name ?? 'Your Business' }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Business Logo & Name -->
            <div class="sm:col-span-2 lg:col-span-1 flex flex-col items-center gap-3 p-4 bg-bg-subtle rounded-xl border border-border-quiet">
                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-primary/10 border-2 border-primary/20 flex items-center justify-center">
                    @if($business && $business->logo_url)
                        <img src="{{ $business->logo_url }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-primary text-4xl fill">account_balance</span>
                    @endif
                </div>
                <div class="text-center">
                    <p class="font-headline font-bold text-text-rich text-sm">{{ $business->name ?? 'Business Name' }}</p>
                    <p class="text-[10px] text-secondary">{{ $business->city ?? 'Kampala' }}, {{ $business->country ?? 'Uganda' }}</p>
                    @if($business && $business->tin_number)
                        <p class="text-[10px] text-secondary mt-0.5">TIN: <span class="font-numeric font-bold">{{ $business->tin_number }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Business Details -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Business Email</span>
                    <span class="text-text-rich font-semibold">{{ $business->email ?? 'Not set' }}</span>
                </div>
                <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Phone Number</span>
                    <span class="text-text-rich font-semibold font-numeric">{{ $business->phone ?? 'Not set' }}</span>
                </div>
                <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Business Address</span>
                    <span class="text-text-rich font-semibold">{{ $business->address ?? 'Not set' }}</span>
                </div>
                <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Base Currency</span>
                    <span class="text-text-rich font-semibold">{{ $business->currency_code ?? 'UGX' }} ({{ $business->currency_symbol ?? 'UGX' }}) — Uganda Shilling</span>
                </div>
                <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Country & City</span>
                    <span class="text-text-rich font-semibold">{{ $business->city ?? 'Kampala' }}, {{ $business->country ?? 'Uganda' }}</span>
                </div>
                <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Business Status</span>
                    <span class="font-bold capitalize {{ $business && $business->status === 'active' ? 'text-success' : 'text-warning' }}">
                        {{ $business->status ?? 'Active' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Status Card -->
    @if($subscription)
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <h3 class="font-headline font-bold text-base text-text-rich mb-5 pb-3 border-b border-border-quiet flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">credit_card</span>
            Current Subscription Plan
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-primary/5 border border-primary/20 rounded-xl text-center">
                <p class="text-[10px] font-bold text-secondary uppercase mb-1">Plan</p>
                <p class="font-headline font-bold text-primary text-lg">{{ $subscription->plan->name ?? 'N/A' }}</p>
            </div>
            <div class="p-4 bg-bg-subtle border border-border-quiet rounded-xl text-center">
                <p class="text-[10px] font-bold text-secondary uppercase mb-1">Status</p>
                <p class="font-bold text-sm capitalize {{ $subscription->status === 'active' ? 'text-success' : 'text-warning' }}">{{ $subscription->status }}</p>
            </div>
            <div class="p-4 bg-bg-subtle border border-border-quiet rounded-xl text-center">
                <p class="text-[10px] font-bold text-secondary uppercase mb-1">Billing</p>
                <p class="font-bold text-sm text-text-rich capitalize">{{ $subscription->billing_cycle ?? 'Monthly' }}</p>
            </div>
            <div class="p-4 bg-bg-subtle border border-border-quiet rounded-xl text-center">
                <p class="text-[10px] font-bold text-secondary uppercase mb-1">Expires</p>
                <p class="font-bold text-sm text-text-rich font-numeric">{{ $subscription->ends_at ? \Carbon\Carbon::parse($subscription->ends_at)->format('d M Y') : 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-4 flex gap-3">
            <a href="{{ route('subscription.portal') }}" class="flex-1 sm:flex-none bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center justify-center gap-2 transition-all">
                <span class="material-symbols-outlined text-sm">manage_accounts</span>
                Manage Subscription
            </a>
            <a href="{{ route('subscription.pricing') }}" class="flex-1 sm:flex-none bg-surface border border-border-quiet hover:bg-bg-subtle text-text-rich px-4 py-2.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-2 transition-all">
                <span class="material-symbols-outlined text-sm">rocket_launch</span>
                View Plans & Pricing
            </a>
        </div>
    </div>
    @endif

    <!-- Multi-Currency Configuration -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <h3 class="font-headline font-bold text-base text-text-rich mb-5 pb-3 border-b border-border-quiet flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">currency_exchange</span>
            Active Currency & Localization
        </h3>

        <div class="p-4 bg-bg-subtle rounded-xl border border-border-quiet space-y-3 mb-4">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-bold text-secondary uppercase">Quick Switch Display Currency</label>
                <span class="text-[10px] text-secondary bg-primary/10 text-primary px-2 py-0.5 rounded font-bold">Default: UGX (Uganda Shilling)</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach($currencies->take(8) as $curr)
                    <form action="{{ route('currency.switch') }}" method="POST">
                        @csrf
                        <input type="hidden" name="currency" value="{{ $curr->code }}">
                        <button type="submit" class="w-full py-2.5 px-3 rounded-lg border text-xs font-bold flex items-center justify-between transition-all {{ $currentCurrency->code === $curr->code ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface border-border-quiet hover:bg-slate-100 text-text-rich' }}">
                            <span class="flex items-center gap-1.5">
                                <img src="{{ $curr->flag_image_url }}" srcset="{{ $curr->flag_image_url_2x }} 2x" alt="{{ $curr->country }}" class="w-5 h-3.5 object-cover rounded-sm shadow-sm shrink-0" loading="lazy">
                                <span>{{ $curr->code }}</span>
                            </span>
                            <span class="font-numeric">{{ $curr->symbol }}</span>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        <p class="text-xs text-secondary">
            <span class="material-symbols-outlined text-base align-middle text-primary">info</span>
            TBM Enterprise is based in Uganda. The primary system currency is <strong>UGX (Ugandan Shilling)</strong>. 
            All financial data is stored in UGX. Display currency conversion is for reference only.
        </p>
    </div>

    <!-- Current User Profile Quick Access -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <h3 class="font-headline font-bold text-base text-text-rich mb-5 pb-3 border-b border-border-quiet flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">account_circle</span>
            Your Account
        </h3>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-bg-subtle rounded-xl border border-border-quiet">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-900 border-2 border-primary/20 shrink-0">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=User' }}" alt="{{ $user->name ?? 'User' }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-bold text-base text-text-rich leading-tight">{{ $user->name ?? 'Team Member' }}</p>
                    <p class="text-xs text-secondary">{{ $user->email ?? 'user@tbm-enterprise.com' }}</p>
                    <p class="text-xs font-semibold text-primary mt-0.5">{{ $user->role ?? 'Member' }}{{ !empty($user->department) ? ' · ' . $user->department : '' }}</p>
                </div>
            </div>
            <a href="{{ route('profile') }}" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-all self-start sm:self-auto">
                <span class="material-symbols-outlined text-sm">manage_accounts</span>
                Edit My Profile
            </a>
        </div>

        <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet text-center">
                <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Phone</span>
                <span class="text-text-rich font-numeric font-semibold">{{ $user->phone ?? '—' }}</span>
            </div>
            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet text-center">
                <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Job Title</span>
                <span class="text-text-rich font-semibold">{{ $user->job_title ?? '—' }}</span>
            </div>
            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet text-center">
                <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Department</span>
                <span class="text-text-rich font-semibold">{{ $user->department ?? '—' }}</span>
            </div>
            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet text-center">
                <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Member Since</span>
                <span class="text-text-rich font-numeric font-semibold">{{ $user->created_at?->format('M Y') ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- System Info Card -->
    <div class="bg-surface-container-low border border-border-quiet rounded-2xl p-5">
        <h3 class="font-headline font-bold text-sm text-text-rich mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-outline text-base">info</span>
            System Information
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-[10px] font-bold text-secondary uppercase block">Platform</span>
                <span class="text-text-rich font-semibold">TBM Enterprise Suite</span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-secondary uppercase block">Base Region</span>
                <span class="text-text-rich font-semibold">Uganda, East Africa</span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-secondary uppercase block">Primary Currency</span>
                <span class="text-text-rich font-semibold">UGX (Ugandan Shilling)</span>
            </div>
            <div>
                <span class="text-[10px] font-bold text-secondary uppercase block">Payment Options</span>
                <span class="text-text-rich font-semibold">MTN MoMo, Airtel, Card</span>
            </div>
        </div>
    </div>
</div>
@endsection
