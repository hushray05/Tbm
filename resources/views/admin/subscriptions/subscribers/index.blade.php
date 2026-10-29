@extends('layouts.admin')

@section('title', 'Subscribers Directory • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Subscribers Management</h1>
            <p class="text-xs text-secondary mt-0.5">Directory of businesses, owners, subscription states, and manual account overrides.</p>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-4 tbm-shadow">
        <form action="{{ route('admin.subscriptions.subscribers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search business, owner, email..." class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none">
            </div>

            <div>
                <select name="plan" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none">
                    <option value="">All Plans</option>
                    @foreach($plans as $p)
                        <option value="{{ $p->id }}" {{ request('plan') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none">
                    <option value="">All Statuses</option>
                    <option value="trialing" {{ request('status') === 'trialing' ? 'selected' : '' }}>Trialing</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Paid)</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <select name="cycle" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none">
                    <option value="">All Billing Cycles</option>
                    <option value="monthly" {{ request('cycle') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ request('cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 h-10 bg-primary hover:bg-primary-container text-white text-xs font-bold rounded-lg transition-colors flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm">search</span>
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-secondary hover:text-text-rich text-xs font-bold flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Subscribers Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Business & Owner</th>
                        <th class="px-5 py-3.5">Assigned Plan</th>
                        <th class="px-5 py-3.5 text-center">Cycle</th>
                        <th class="px-5 py-3.5">Trial / Renewal Date</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Amount (UGX)</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.subscriptions.subscribers.show', $sub) }}" class="font-headline font-bold text-text-rich text-xs hover:text-primary block">
                                    {{ $sub->business->name ?? 'Business' }}
                                </a>
                                <span class="text-[11px] text-secondary">Owner: {{ $sub->business->owner->name ?? 'N/A' }} ({{ $sub->business->email }})</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-primary block">{{ $sub->plan->name ?? 'Starter' }}</span>
                                <span class="text-[10px] text-secondary">Created: {{ $sub->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center capitalize font-medium text-text-rich">
                                {{ $sub->billing_cycle }}
                            </td>
                            <td class="px-5 py-3.5 font-numeric">
                                @if($sub->status === 'trialing')
                                    <span class="text-amber-700 font-bold block">{{ $sub->trial_ends_at ? $sub->trial_ends_at->format('d M Y') : 'N/A' }}</span>
                                    <span class="text-[10px] text-secondary">({{ $sub->trial_remaining_days }}d left)</span>
                                @else
                                    <span class="text-text-rich font-medium block">{{ $sub->ends_at ? $sub->ends_at->format('d M Y') : 'N/A' }}</span>
                                    <span class="text-[10px] text-success">Paid until date</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($sub->payment_status === 'pending_approval')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500 text-white animate-pulse block">
                                        Pending Approval
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : ($sub->status === 'trialing' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $sub->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right font-numeric font-extrabold text-text-rich">
                                UGX {{ number_format($sub->amount) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($sub->payment_status === 'pending_approval')
                                        <form action="{{ route('admin.subscriptions.subscribers.action', $sub) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition-all shadow-sm flex items-center gap-1" title="Approve & Activate Subscription">
                                                <span class="material-symbols-outlined text-xs">verified</span>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.subscriptions.subscribers.show', $sub) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary hover:bg-primary-container text-white text-[11px] font-bold transition-all shadow-sm">
                                        <span>Details</span>
                                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-secondary">
                                No subscribers found matching query.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="p-4 border-t border-border-quiet">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
