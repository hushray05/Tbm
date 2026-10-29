@extends('layouts.app')

@section('title', 'Payments Made • Purchases • TBM Enterprise')

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
                <span>Payments Made</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Payments Made to Suppliers</h1>
            <p class="text-sm text-secondary mt-0.5">Track outgoing settlements paid to vendors against bills and manage accounts payable.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('purchases.payments.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add</span>
                <span>Record Payment Made</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl">payments</span>
                <span class="font-bold text-sm text-text-rich">Total Settlements Disbursed: UGX {{ number_format($totalPaymentsMade) }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Payment #</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4">Bill Ref</th>
                        <th class="py-3 px-4">Method</th>
                        <th class="py-3 px-4">Transaction Ref</th>
                        <th class="py-3 px-4 text-right">Amount (UGX)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($payments as $p)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">{{ $p->payment_number }}</td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $p->payment_date ? $p->payment_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 font-bold text-text-rich">{{ $p->vendor_name }}</td>
                            <td class="py-3.5 px-4 font-numeric text-secondary">
                                @if($p->bill)
                                    <a href="{{ route('purchases.bills.show', $p->bill) }}" class="text-primary hover:underline font-bold">
                                        {{ $p->bill->bill_number }}
                                    </a>
                                @else
                                    <span>Direct Advance</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-bg-subtle border border-border-quiet text-text-rich">
                                    {{ $p->payment_method }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-numeric text-secondary">{{ $p->reference_no ?: '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich text-sm">
                                UGX {{ number_format($p->amount_ugx) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">payments</span>
                                <p class="text-sm font-bold text-text-rich">No payments recorded yet</p>
                                <p class="text-xs mt-1">Record payments made to suppliers to track your cash outflows and clear vendor balances.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
