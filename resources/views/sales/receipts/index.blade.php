@extends('layouts.app')

@section('title', 'Sales Receipts • Sales • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-secondary mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
                <span>/</span>
                <span class="text-primary">Sales</span>
                <span>/</span>
                <span>Sales Receipts</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Sales Receipts</h1>
            <p class="text-sm text-secondary mt-0.5">Official payment receipts generated from customer invoices and POS register checkouts.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('pos') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">point_of_sale</span>
                <span>Open POS Counter</span>
            </a>
        </div>
    </div>

    <!-- Receipts Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600 text-xl">receipt</span>
                <span class="font-bold text-sm text-text-rich">Total Receipt Volume: UGX {{ number_format($totalReceiptVolume) }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Receipt #</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Invoice Linked</th>
                        <th class="py-3 px-4">Payment Mode</th>
                        <th class="py-3 px-4 text-right">Total (UGX)</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($receipts as $r)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">
                                <a href="{{ route('sales.receipts.show', $r) }}" class="hover:underline">
                                    {{ $r->receipt_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $r->date ? $r->date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 font-bold text-text-rich">{{ $r->customer_name }}</td>
                            <td class="py-3.5 px-4 font-numeric text-secondary">
                                @if($r->invoice)
                                    <a href="{{ route('invoices.show', $r->invoice) }}" class="text-primary hover:underline font-bold">
                                        {{ $r->invoice->invoice_number }}
                                    </a>
                                @else
                                    <span class="text-[11px] text-secondary font-sans">POS Direct Sale</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-bg-subtle border border-border-quiet text-text-rich">
                                    {{ $r->payment_mode }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich text-sm">
                                UGX {{ number_format($r->total) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('sales.receipts.show', $r) }}" class="px-3 py-1 bg-surface hover:bg-bg-subtle border border-border-quiet text-text-rich rounded-md text-xs font-bold shadow-2xs inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">print</span>
                                    <span>Print</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">receipt</span>
                                <p class="text-sm font-bold text-text-rich">No sales receipts issued yet</p>
                                <p class="text-xs mt-1">Receipts are automatically created whenever you record an invoice payment or complete a POS checkout.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($receipts->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
