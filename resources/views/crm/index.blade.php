@extends('layouts.app')

@section('title', 'CRM Pipeline & Lead Management • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">CRM & Pipeline Management</h1>
            <p class="text-sm text-secondary mt-0.5">Commercial opportunities across pan-African enterprise accounts.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-secondary">
                Pipeline Value in: <strong class="text-primary font-semibold">{{ $currentCurrency->code }}</strong>
            </span>
        </div>
    </div>

    <!-- Pipeline Metric Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block mb-2">Total Pipeline Value</span>
            <div class="font-headline text-2xl font-bold text-primary font-numeric">
                {{ $currencyService->format($totalPipelineValueCents) }}
            </div>
            <span class="text-xs text-secondary mt-1 block">Active opportunities</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block mb-2">Active In-Flight Deals</span>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ $activeDealsCount }} Enterprise Deals
            </div>
            <span class="text-xs text-secondary mt-1 block">Proposals & negotiations</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block mb-2">Closed / Won Accounts</span>
            <div class="font-headline text-2xl font-bold text-success font-numeric">
                {{ $wonLeadsCount }} Accounts
            </div>
            <span class="text-xs text-success mt-1 block">Converted to active contracts</span>
        </div>
    </div>

    <!-- Leads Table (Matching Stitch Lead Management 7819283d9cfc4b11a65036bab25b282b) -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="p-5 border-b border-border-quiet flex items-center justify-between bg-bg-subtle">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl fill">contacts</span>
                <span class="font-headline font-bold text-text-rich text-base">Key Account Opportunities</span>
            </div>
            <span class="text-xs text-secondary">Updated in real-time</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-bg-subtle/80 border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Lead Contact</th>
                        <th class="px-4 py-3.5">Company / Entity</th>
                        <th class="px-4 py-3.5">Phone</th>
                        <th class="px-4 py-3.5">Stage</th>
                        <th class="px-4 py-3.5">Account Owner</th>
                        <th class="px-5 py-3.5 text-right">Est. Value ({{ $currentCurrency->code }})</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-bg-subtle/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-900 border border-border-quiet shadow-sm shrink-0">
                                        <img src="{{ asset($lead->avatar) }}" alt="{{ $lead->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-bold text-text-rich block">{{ $lead->name }}</span>
                                        <span class="text-xs text-secondary">{{ $lead->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs font-semibold text-text-rich">
                                {{ $lead->company ?? 'Private Enterprise' }}
                            </td>
                            <td class="px-4 py-4 text-xs font-numeric text-secondary">
                                {{ $lead->phone ?? '—' }}
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $stColor = match($lead->status) {
                                        'won' => 'bg-green-100 text-green-800',
                                        'proposal' => 'bg-primary/10 text-primary',
                                        'qualified' => 'bg-purple-100 text-purple-800',
                                        default => 'bg-slate-100 text-slate-700'
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full uppercase {{ $stColor }}">
                                    {{ $lead->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-text-rich">
                                {{ $lead->assigned_to ?? 'General Pool' }}
                            </td>
                            <td class="px-5 py-4 text-right font-numeric font-bold text-primary">
                                {{ $lead->formatted_value }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-secondary">No leads recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
