@extends('layouts.admin')

@section('title', 'Subscription Plans Management • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Subscription Plans Management</h1>
            <p class="text-xs text-secondary mt-0.5">Configure commercial tiers, pricing in UGX, trial durations, usage quotas, and feature entitlements without code changes.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.subscriptions.plans.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                <span>Create New Plan</span>
            </a>
            <a href="{{ route('admin.subscriptions.features.index') }}" class="bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">toggle_on</span>
                <span>Features Matrix</span>
            </a>
        </div>
    </div>

    <!-- Plans Management Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-4 py-3.5">Plan Name</th>
                        <th class="px-4 py-3.5">Monthly Price</th>
                        <th class="px-4 py-3.5">Annual Price</th>
                        <th class="px-4 py-3.5 text-center">Trial</th>
                        <th class="px-4 py-3.5 text-center">Users</th>
                        <th class="px-4 py-3.5 text-center">Locations</th>
                        <th class="px-4 py-3.5 text-center">Products</th>
                        <th class="px-4 py-3.5 text-center">Invoices</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Subscribers</th>
                        <th class="px-4 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @foreach($plans as $plan)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-headline font-bold text-text-rich text-sm">{{ $plan->name }}</span>
                                    @if($plan->badge)
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-primary/10 text-primary border border-primary/20">
                                            {{ $plan->badge }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-secondary font-mono">slug: {{ $plan->slug }}</span>
                            </td>
                            <td class="px-4 py-3.5 font-numeric font-extrabold text-primary">
                                UGX {{ number_format($plan->monthly_price) }}
                            </td>
                            <td class="px-4 py-3.5 font-numeric font-bold text-text-rich">
                                UGX {{ number_format($plan->annual_price) }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-semibold text-success">
                                {{ $plan->trial_duration }} Days
                            </td>
                            <td class="px-4 py-3.5 text-center font-numeric font-bold">
                                {{ $plan->max_users ?? '∞' }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-numeric font-bold">
                                {{ $plan->max_locations ?? '∞' }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-numeric font-bold">
                                {{ $plan->max_products ? number_format($plan->max_products) : '∞' }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-numeric font-bold">
                                {{ $plan->max_invoices ? number_format($plan->max_invoices) : '∞' }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-numeric font-extrabold text-text-rich">
                                {{ $plan->subscriptions->count() }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit -->
                                    <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Edit Plan">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>

                                    <!-- Duplicate -->
                                    <form action="{{ route('admin.subscriptions.plans.duplicate', $plan) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Duplicate Plan">
                                            <span class="material-symbols-outlined text-base">content_copy</span>
                                        </button>
                                    </form>

                                    <!-- Toggle Active/Inactive -->
                                    <form action="{{ route('admin.subscriptions.plans.toggle', $plan) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-amber-600 hover:bg-bg-subtle transition-colors" title="{{ $plan->is_active ? 'Deactivate' : 'Activate' }}">
                                            <span class="material-symbols-outlined text-base">{{ $plan->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.subscriptions.plans.destroy', $plan) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-danger hover:bg-red-50 transition-colors" title="Delete Plan">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
