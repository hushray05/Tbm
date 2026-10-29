@extends('layouts.app')

@section('title', 'Creditors & Payables Directory • Purchases • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-secondary mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
                <span>/</span>
                <span class="text-primary">Purchases</span>
                <span>/</span>
                <span>Creditors</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Creditors & Payables</h1>
            <p class="text-sm text-secondary mt-0.5">Summary of all suppliers and vendors with outstanding payables and aging balances.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('purchases.bills.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add</span>
                <span>Record New Bill</span>
            </a>
        </div>
    </div>

    <!-- Debt Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-1">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Total Creditors Debt</span>
            <span class="font-headline text-2xl font-bold text-amber-600 font-numeric block">
                UGX {{ number_format($totalCreditorDebt) }}
            </span>
            <span class="text-[11px] text-secondary">Outstanding across {{ $openBills->count() }} unsettled bills</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-1">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Overdue Commitments</span>
            <span class="font-headline text-2xl font-bold text-red-600 font-numeric block">
                {{ $overdueCount }} <span class="text-xs font-normal text-secondary">bills past due date</span>
            </span>
            <span class="text-[11px] text-secondary">Requires priority settlement</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-1">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Registered Vendors</span>
            <span class="font-headline text-2xl font-bold text-text-rich font-numeric block">
                {{ $vendors->count() }}
            </span>
            <span class="text-[11px] text-secondary">Active supplier relationships</span>
        </div>
    </div>

    <!-- Unsettled Bills Table -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-600 text-xl">hourglass_top</span>
                <span class="font-bold text-sm text-text-rich">Unsettled Creditor Bills ({{ $openBills->count() }})</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Bill #</th>
                        <th class="py-3 px-4">Vendor / Supplier</th>
                        <th class="py-3 px-4">Bill Date</th>
                        <th class="py-3 px-4">Payment Due</th>
                        <th class="py-3 px-4 text-right">Total Amount</th>
                        <th class="py-3 px-4 text-right">Balance Owed</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Settle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($openBills as $ob)
                        @php
                            $isOverdue = $ob->due_date && $ob->due_date < now();
                        @endphp
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">
                                <a href="{{ route('purchases.bills.show', $ob) }}" class="hover:underline">
                                    {{ $ob->bill_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-text-rich">{{ $ob->vendor_name }}</td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $ob->bill_date ? $ob->bill_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 font-numeric font-semibold {{ $isOverdue ? 'text-red-600 font-bold' : 'text-secondary' }}">
                                {{ $ob->due_date ? $ob->due_date->format('d M Y') : '—' }}
                                @if($isOverdue)<span class="text-[10px] text-red-600 uppercase block">(Overdue)</span>@endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-numeric text-text-rich">UGX {{ number_format($ob->total_amount_ugx) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-amber-600 text-sm">UGX {{ number_format($ob->balance_ugx) }}</td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $ob->status_badge }}">
                                    {{ $ob->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('purchases.payments.create', ['bill_id' => $ob->id]) }}" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-bold shadow-2xs transition-all inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">payments</span>
                                    <span>Pay Now</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-emerald-500 mb-2">verified</span>
                                <p class="text-sm font-bold text-text-rich">Zero outstanding creditor payables!</p>
                                <p class="text-xs mt-1">All supplier bills have been fully settled.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
