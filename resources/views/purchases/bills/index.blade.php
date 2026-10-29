@extends('layouts.app')

@section('title', 'Vendor Bills & Invoices • Purchases • TBM Enterprise')

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
                <span>Bills</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Vendor Bills & Procurement</h1>
            <p class="text-sm text-secondary mt-0.5">Record received supplier invoices, track payments, and automatically increase inventory stock.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('purchases.bills.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add</span>
                <span>Record New Bill</span>
            </a>
        </div>
    </div>

    <!-- Bills KPI summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-1">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Total Billed Volume</span>
            <span class="font-headline text-2xl font-bold text-text-rich font-numeric block">
                UGX {{ number_format($totalBillVolume) }}
            </span>
            <span class="text-[11px] text-secondary">All historical supplier procurements</span>
        </div>
        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-1">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Outstanding Balance Owed</span>
            <span class="font-headline text-2xl font-bold text-amber-600 font-numeric block">
                UGX {{ number_format($outstandingBillBalance) }}
            </span>
            <span class="text-[11px] text-secondary">Payables pending settlement</span>
        </div>
        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-1">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Inventory Connection</span>
            <div class="flex items-center gap-2 pt-1 text-emerald-700 font-bold text-xs">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>Automatic Stock Increase Active</span>
            </div>
            <span class="text-[11px] text-secondary">Receiving a bill updates warehouse stock</span>
        </div>
    </div>

    <!-- Bills Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Bill #</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4">Bill Date</th>
                        <th class="py-3 px-4">Due Date</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                        <th class="py-3 px-4 text-right">VAT (18%)</th>
                        <th class="py-3 px-4 text-right">Total Amount</th>
                        <th class="py-3 px-4 text-right">Balance Due</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($bills as $b)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">
                                <a href="{{ route('purchases.bills.show', $b) }}" class="hover:underline">
                                    {{ $b->bill_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-text-rich">{{ $b->vendor_name }}</td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $b->bill_date ? $b->bill_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $b->due_date ? $b->due_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric text-secondary">UGX {{ number_format($b->subtotal_ugx) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric text-secondary font-semibold">UGX {{ number_format($b->vat_amount_ugx) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich">UGX {{ number_format($b->total_amount_ugx) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold {{ $b->balance_ugx > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                UGX {{ number_format($b->balance_ugx) }}
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $b->status_badge }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('purchases.bills.show', $b) }}" class="p-1.5 rounded-lg border border-border-quiet text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="View Bill">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                    @if($b->balance_ugx > 0)
                                        <a href="{{ route('purchases.payments.create', ['bill_id' => $b->id]) }}" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-bold shadow-2xs transition-all flex items-center gap-1" title="Pay Bill">
                                            <span class="material-symbols-outlined text-xs">payments</span>
                                            <span>Pay</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">receipt_long</span>
                                <p class="text-sm font-bold text-text-rich">No vendor bills recorded yet</p>
                                <p class="text-xs mt-1">Record supplier bills to track payables and automatically increase your stock inventory.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bills->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
