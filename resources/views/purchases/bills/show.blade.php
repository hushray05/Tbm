@extends('layouts.app')

@section('title', 'Vendor Bill #' . $bill->bill_number . ' • TBM Purchases')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('purchases.bills.index') }}" class="text-xs font-bold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Bills</span>
        </a>
        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-4 py-2 bg-surface hover:bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-sm">print</span>
                <span>Print Bill</span>
            </button>
            @if($bill->balance_ugx > 0)
                <a href="{{ route('purchases.payments.create', ['bill_id' => $bill->id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-sm">payments</span>
                    <span>Record Payment</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Bill Statement Card -->
    <div class="bg-surface border border-border-quiet rounded-xl p-8 shadow-sm space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-b border-border-quiet pb-6">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-secondary text-white flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-lg">storefront</span>
                    </div>
                    <span class="font-headline font-bold text-xl text-text-rich">{{ $bill->vendor_name }}</span>
                </div>
                <p class="text-xs text-secondary leading-relaxed">
                    Supplier & Vendor Commercial Bill<br>
                    Settlement for: {{ $business->name ?? 'TBM Enterprise' }}<br>
                    @if($bill->purchaseOrder)Ref PO: {{ $bill->purchaseOrder->order_number }}@endif
                </p>
            </div>

            <div class="sm:text-right space-y-1">
                <span class="text-xs font-bold text-secondary uppercase tracking-widest block">Vendor Bill</span>
                <span class="font-headline text-2xl font-bold text-text-rich block font-numeric">#{{ $bill->bill_number }}</span>
                <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-bold uppercase border {{ $bill->status_badge }}">
                    Status: {{ $bill->status }}
                </span>
            </div>
        </div>

        <!-- Dates & Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-1">
                <span class="font-bold text-secondary uppercase tracking-wider block">Vendor Information:</span>
                <strong class="text-text-rich text-sm block">{{ $bill->vendor_name }}</strong>
                @if($bill->vendor)<span class="text-secondary block">Phone: {{ $bill->vendor->phone }} | TIN: {{ $bill->vendor->tin_number }}</span>@endif
            </div>
            <div class="sm:text-right space-y-1">
                <div class="flex sm:justify-end gap-2 text-secondary">
                    <span>Bill Date:</span>
                    <strong class="text-text-rich font-numeric">{{ $bill->bill_date ? $bill->bill_date->format('F d, Y') : '—' }}</strong>
                </div>
                <div class="flex sm:justify-end gap-2 text-secondary">
                    <span>Due Date:</span>
                    <strong class="text-text-rich font-numeric">{{ $bill->due_date ? $bill->due_date->format('F d, Y') : '—' }}</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-y border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Item & Description</th>
                        <th class="py-3 px-4 text-center">Qty Received</th>
                        <th class="py-3 px-4 text-right">Unit Cost (UGX)</th>
                        <th class="py-3 px-4 text-right">Total (UGX)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @foreach($bill->items as $bit)
                        <tr>
                            <td class="py-3.5 px-4 font-bold text-text-rich">{{ $bit->item_name }}</td>
                            <td class="py-3.5 px-4 text-center font-numeric text-secondary font-semibold">{{ $bit->quantity }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric text-secondary">{{ number_format($bit->unit_cost_ugx) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich">{{ number_format($bit->total_cost_ugx) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Financial Summary -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pt-6 border-t border-border-quiet">
            <div class="text-xs text-secondary space-y-1 max-w-sm">
                <span class="font-bold text-text-rich block">Procurement Remarks:</span>
                <p>{{ $bill->notes ?: 'All goods verified and taken into inventory on bill creation date.' }}</p>
            </div>

            <div class="sm:text-right space-y-2 text-xs min-w-[240px]">
                <div class="flex justify-between sm:justify-end gap-6 text-secondary">
                    <span>Subtotal:</span>
                    <span class="font-numeric font-semibold text-text-rich">UGX {{ number_format($bill->subtotal_ugx) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 text-secondary">
                    <span>VAT ({{ $bill->vat_rate_percent }}%):</span>
                    <span class="font-numeric font-semibold text-text-rich">UGX {{ number_format($bill->vat_amount_ugx) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 pt-2 border-t border-border-quiet text-sm font-bold text-text-rich">
                    <span>Grand Total:</span>
                    <span class="font-headline text-lg text-primary font-numeric">UGX {{ number_format($bill->total_amount_ugx) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 text-secondary">
                    <span>Paid to Date:</span>
                    <span class="font-numeric font-semibold text-emerald-600">UGX {{ number_format($bill->paid_amount_ugx) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 pt-2 border-t border-border-quiet font-bold text-text-rich">
                    <span>Balance Owed:</span>
                    <span class="font-numeric text-base {{ $bill->balance_ugx > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                        UGX {{ number_format($bill->balance_ugx) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Payments Logged Against Bill -->
        @if($bill->payments->count() > 0)
            <div class="border-t border-border-quiet pt-6 space-y-3">
                <h3 class="font-bold text-sm text-text-rich flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-emerald-600">check_circle</span>
                    <span>Payments Settled Against This Bill</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[10px]">
                            <tr>
                                <th class="py-2 px-3">Payment #</th>
                                <th class="py-2 px-3">Date</th>
                                <th class="py-2 px-3">Method</th>
                                <th class="py-2 px-3">Ref</th>
                                <th class="py-2 px-3 text-right">Amount Paid</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-quiet">
                            @foreach($bill->payments as $pm)
                                <tr>
                                    <td class="py-2.5 px-3 font-numeric font-bold text-primary">{{ $pm->payment_number }}</td>
                                    <td class="py-2.5 px-3 text-secondary font-numeric">{{ $pm->payment_date->format('d M Y') }}</td>
                                    <td class="py-2.5 px-3">{{ $pm->payment_method }}</td>
                                    <td class="py-2.5 px-3 text-secondary font-numeric">{{ $pm->reference_no ?: '—' }}</td>
                                    <td class="py-2.5 px-3 text-right font-numeric font-bold text-emerald-600">UGX {{ number_format($pm->amount_ugx) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
