@extends('layouts.admin')

@section('title', 'Business Overview • ' . $business->name . ' • Super Admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.businesses.index') }}" class="p-2 rounded-xl bg-surface border border-border-quiet text-secondary hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline text-2xl font-bold text-text-rich">{{ $business->name }}</h1>
                <p class="text-xs text-secondary">Tenant ID: #BIZ-{{ str_pad($business->id, 4, '0', STR_PAD_LEFT) }} • Registered: {{ $business->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.businesses.edit', $business) }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">edit</span>
                <span>Amend Business Details</span>
            </a>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Business Details -->
        <div class="md:col-span-2 bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-5">
            <h3 class="font-headline font-bold text-base text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">domain</span>
                Organization Information
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3.5 bg-bg-subtle rounded-xl border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Legal Name</span>
                    <span class="font-bold text-text-rich text-sm">{{ $business->name }}</span>
                </div>
                <div class="p-3.5 bg-bg-subtle rounded-xl border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">TIN Number</span>
                    <span class="font-numeric font-bold text-text-rich text-sm">{{ $business->tin_number ?? 'Not provided' }}</span>
                </div>
                <div class="p-3.5 bg-bg-subtle rounded-xl border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Contact Email</span>
                    <span class="text-text-rich font-semibold">{{ $business->email }}</span>
                </div>
                <div class="p-3.5 bg-bg-subtle rounded-xl border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Phone Number</span>
                    <span class="font-numeric text-text-rich font-semibold">{{ $business->phone ?? '—' }}</span>
                </div>
                <div class="p-3.5 bg-bg-subtle rounded-xl border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">City & Country</span>
                    <span class="text-text-rich font-semibold">{{ $business->city ?? 'Kampala' }}, {{ $business->country ?? 'Uganda' }}</span>
                </div>
                <div class="p-3.5 bg-bg-subtle rounded-xl border border-border-quiet">
                    <span class="text-[10px] font-bold text-secondary uppercase block mb-1">Operational Currency</span>
                    <span class="font-bold text-primary font-numeric">{{ $business->currency_code }} ({{ $business->currency_symbol }})</span>
                </div>
            </div>

            <!-- Locations / Branches -->
            <div class="pt-3 border-t border-border-quiet space-y-3">
                <h4 class="font-headline font-bold text-sm text-text-rich">Registered Branches & Depots ({{ $business->locations->count() }})</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @forelse($business->locations as $loc)
                        <div class="p-3 rounded-xl bg-bg-subtle border border-border-quiet flex items-center justify-between">
                            <div>
                                <span class="font-bold text-text-rich block">{{ $loc->name }}</span>
                                <span class="text-[10px] text-secondary">{{ $loc->city ?? 'Kampala' }}</span>
                            </div>
                            @if($loc->is_primary)
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-[9px] font-bold uppercase">Primary HQ</span>
                            @endif
                        </div>
                    @empty
                        <div class="p-3 text-secondary text-xs">No branches registered.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar: Status & Subscription -->
        <div class="space-y-6">
            <!-- Account Status -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow space-y-4">
                <h3 class="font-headline font-bold text-sm text-text-rich">Account Standing</h3>
                <div class="flex items-center justify-between p-3 rounded-xl bg-bg-subtle border border-border-quiet">
                    <span class="text-xs text-secondary font-semibold">Tenant Status</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $business->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $business->status }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-bg-subtle border border-border-quiet">
                    <span class="text-xs text-secondary font-semibold">Staff Count</span>
                    <span class="font-numeric font-bold text-text-rich">{{ $usersCount }} users</span>
                </div>
            </div>

            <!-- Active Plan -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow space-y-3">
                <h3 class="font-headline font-bold text-sm text-text-rich">Subscription Plan</h3>
                @if($activeSubscription && $activeSubscription->plan)
                    <div class="p-3.5 rounded-xl bg-primary/5 border border-primary/20 space-y-1.5 text-xs">
                        <span class="text-[10px] text-secondary font-bold uppercase block">Current Tier</span>
                        <span class="font-headline font-bold text-primary text-base block">{{ $activeSubscription->plan->name }}</span>
                        <span class="font-numeric font-bold text-text-rich block">UGX {{ number_format($activeSubscription->amount) }} / month</span>
                        <span class="text-[10px] text-secondary capitalize block">Status: <strong>{{ $activeSubscription->status }}</strong></span>
                    </div>
                @else
                    <div class="p-3 rounded-xl bg-bg-subtle border border-border-quiet text-xs text-secondary">
                        No active subscription on file.
                    </div>
                @endif
                <a href="{{ route('admin.businesses.edit', $business) }}" class="w-full py-2 px-3 rounded-lg bg-surface hover:bg-bg-subtle text-primary border border-border-quiet text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-sm">tune</span>
                    <span>Amend Plan / Status</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
