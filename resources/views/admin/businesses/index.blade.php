@extends('layouts.admin')

@section('title', 'Tenant Businesses Directory • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Tenant Businesses & Organizations</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-900 border border-amber-300">
                    Platform Master
                </span>
            </div>
            <p class="text-xs text-secondary mt-0.5">Global directory of all business accounts on TBM Enterprise. Super Admin can view overviews and amend organization details.</p>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Total Businesses</span>
                <span class="material-symbols-outlined text-primary text-xl">domain</span>
            </div>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">{{ number_format($totalBusinesses) }}</div>
            <span class="text-xs text-secondary mt-1 block">Registered enterprise tenants</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Active Operations</span>
                <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
            </div>
            <div class="font-headline text-2xl font-bold text-emerald-600 font-numeric">{{ number_format($activeBusinesses) }}</div>
            <span class="text-xs text-secondary mt-1 block">In good standing</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Suspended / Inactive</span>
                <span class="material-symbols-outlined text-danger text-xl">block</span>
            </div>
            <div class="font-headline text-2xl font-bold text-danger font-numeric">{{ number_format($suspendedBusinesses) }}</div>
            <span class="text-xs text-secondary mt-1 block">Locked or overdue accounts</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Total Branches</span>
                <span class="material-symbols-outlined text-amber-500 text-xl">store</span>
            </div>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">{{ number_format($totalLocations) }}</div>
            <span class="text-xs text-secondary mt-1 block">Storefronts & depots</span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-4 tbm-shadow">
        <form action="{{ route('admin.businesses.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex-1 w-full relative">
                <span class="material-symbols-outlined text-secondary absolute left-3 top-2.5 text-lg">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by business name, email, city, TIN..." class="w-full h-10 pl-9 pr-4 rounded-xl border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
            </div>

            <div class="w-full sm:w-48">
                <select name="status" class="w-full h-10 px-3 rounded-xl border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary-container text-white px-5 h-10 rounded-xl text-xs font-bold shadow-sm transition-all shrink-0">
                Filter Directory
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.businesses.index') }}" class="text-xs text-secondary hover:text-danger font-semibold shrink-0">Clear</a>
            @endif
        </form>
    </div>

    <!-- Tenant Businesses Directory Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl tbm-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-border-quiet bg-bg-subtle text-secondary font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Business / Organization</th>
                        <th class="py-3 px-4">Owner / Contact</th>
                        <th class="py-3 px-4">Location & Currency</th>
                        <th class="py-3 px-4">TIN Number</th>
                        <th class="py-3 px-4">Active Plan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Super Admin Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet">
                    @forelse($businesses as $b)
                        @php
                            $sub = $b->subscriptions->sortByDesc('created_at')->first();
                        @endphp
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <!-- Business Identity -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold shrink-0">
                                        {{ strtoupper(substr($b->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.businesses.show', $b) }}" class="font-bold text-text-rich hover:text-primary block">
                                            {{ $b->name }}
                                        </a>
                                        <span class="text-[10px] text-secondary">{{ $b->slug }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Owner & Email -->
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-text-rich block">{{ $b->owner->name ?? 'Unassigned' }}</span>
                                <span class="text-[11px] text-secondary">{{ $b->email ?? $b->owner->email ?? '—' }}</span>
                            </td>

                            <!-- City & Currency -->
                            <td class="py-3.5 px-4">
                                <span class="text-text-rich font-medium block">{{ $b->city ?? 'Kampala' }}, {{ $b->country ?? 'Uganda' }}</span>
                                <span class="text-[10px] text-secondary font-numeric font-bold bg-primary/5 text-primary px-1.5 py-0.2 rounded inline-block mt-0.5">
                                    {{ $b->currency_code ?? 'UGX' }} ({{ $b->currency_symbol ?? 'UGX' }})
                                </span>
                            </td>

                            <!-- TIN -->
                            <td class="py-3.5 px-4 font-numeric font-semibold text-secondary">
                                {{ $b->tin_number ?? '—' }}
                            </td>

                            <!-- Active Plan -->
                            <td class="py-3.5 px-4">
                                @if($sub && $sub->plan)
                                    <span class="font-bold text-primary block">{{ $sub->plan->name }}</span>
                                    <span class="text-[10px] text-secondary capitalize">{{ $sub->status }} ({{ $sub->billing_cycle ?? 'Monthly' }})</span>
                                @else
                                    <span class="text-secondary text-[11px]">Starter (Default)</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $b->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $b->status }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.businesses.show', $b) }}" class="p-1.5 rounded-lg text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="View Business Overview">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.businesses.edit', $b) }}" class="p-1.5 rounded-lg text-primary hover:bg-primary/10 font-bold transition-colors flex items-center gap-1" title="Amend Business Info">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                        <span>Amend</span>
                                    </a>
                                    <form action="{{ route('admin.businesses.toggle', $b) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg {{ $b->status === 'active' ? 'text-secondary hover:text-danger hover:bg-red-50' : 'text-success hover:bg-green-50' }} transition-colors" title="{{ $b->status === 'active' ? 'Suspend Account' : 'Activate Account' }}">
                                            <span class="material-symbols-outlined text-base">{{ $b->status === 'active' ? 'pause_circle' : 'play_circle' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-secondary text-xs">
                                No tenant businesses found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($businesses->hasPages())
            <div class="p-4 border-t border-border-quiet">
                {{ $businesses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
