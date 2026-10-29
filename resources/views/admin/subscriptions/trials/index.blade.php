@extends('layouts.admin')

@section('title', 'Trial Accounts Management • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Trial Accounts Management</h1>
            <p class="text-xs text-secondary mt-0.5">Monitor active free trial accounts, days remaining, and trigger manual trial extensions or plan conversions.</p>
        </div>
    </div>

    <!-- Trials Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Business & Owner</th>
                        <th class="px-5 py-3.5">Selected Plan</th>
                        <th class="px-5 py-3.5">Trial Start Date</th>
                        <th class="px-5 py-3.5">Trial Expiry Date</th>
                        <th class="px-5 py-3.5 text-center">Remaining Days</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($trials as $trial)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.subscriptions.subscribers.show', $trial) }}" class="font-headline font-bold text-text-rich text-xs hover:text-primary block">
                                    {{ $trial->business->name ?? 'Business' }}
                                </a>
                                <span class="text-[11px] text-secondary">Owner: {{ $trial->business->owner->name ?? 'N/A' }} ({{ $trial->business->email }})</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-primary">{{ $trial->plan->name ?? 'Starter' }}</span>
                                <span class="text-[10px] text-secondary block font-numeric">UGX {{ number_format($trial->amount) }} / mo</span>
                            </td>
                            <td class="px-5 py-3.5 font-numeric text-secondary">
                                {{ $trial->trial_starts_at ? $trial->trial_starts_at->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5 font-numeric font-bold text-amber-800">
                                {{ $trial->trial_ends_at ? $trial->trial_ends_at->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $trial->trial_remaining_days <= 3 ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $trial->trial_remaining_days }} Days Remaining
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.subscriptions.subscribers.show', $trial) }}" class="px-3 py-1 bg-primary hover:bg-primary-container text-white text-[11px] font-bold rounded-lg transition-colors">
                                        Extend / Convert
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-secondary">
                                No businesses currently in trialing status.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
