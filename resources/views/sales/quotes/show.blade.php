@extends('layouts.app')

@section('title', 'Quote #' . $quote->quote_number . ' • TBM Sales')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('sales.quotes.index') }}" class="text-xs font-bold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Quotes</span>
        </a>
        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-4 py-2 bg-surface hover:bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-sm">print</span>
                <span>Print Quote</span>
            </button>
            @if($quote->status !== 'converted')
                <form action="{{ route('sales.quotes.convert-order', $quote) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 bg-secondary hover:bg-slate-700 text-white rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-sm">local_shipping</span>
                        <span>Convert to Sales Order</span>
                    </button>
                </form>
                <form action="{{ route('sales.quotes.convert-invoice', $quote) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 bg-primary hover:bg-primary-container text-white rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-sm">receipt_long</span>
                        <span>Convert to Invoice</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Printable Quotation Sheet -->
    <div class="bg-surface border border-border-quiet rounded-xl p-8 shadow-sm space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-b border-border-quiet pb-6">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-lg fill">account_balance</span>
                    </div>
                    <span class="font-headline font-bold text-xl text-primary">{{ $quote->business->name ?? 'TBM Enterprise' }}</span>
                </div>
                <p class="text-xs text-secondary leading-relaxed">
                    {{ $quote->business->address ?? 'Plot 42 Jinja Road, Industrial Area' }}<br>
                    {{ $quote->business->city ?? 'Kampala' }}, {{ $quote->business->country ?? 'Uganda' }}<br>
                    TIN: {{ $quote->business->tin_number ?? '1002345678' }} | Phone: {{ $quote->business->phone ?? '+256 772 123456' }}
                </p>
            </div>

            <div class="sm:text-right space-y-1">
                <span class="text-xs font-bold text-secondary uppercase tracking-widest block">Commercial Proposal</span>
                <span class="font-headline text-2xl font-bold text-text-rich block font-numeric">#{{ $quote->quote_number }}</span>
                <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-bold uppercase border {{ $quote->status_badge }}">
                    Status: {{ $quote->status }}
                </span>
            </div>
        </div>

        <!-- Customer & Date Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-1">
                <span class="font-bold text-secondary uppercase tracking-wider block">Quotation For:</span>
                <strong class="text-text-rich text-sm block">{{ $quote->customer_name }}</strong>
                @if($quote->customer_email)<span class="text-secondary block">{{ $quote->customer_email }}</span>@endif
            </div>
            <div class="sm:text-right space-y-1">
                <div class="flex sm:justify-end gap-2 text-secondary">
                    <span>Quote Date:</span>
                    <strong class="text-text-rich font-numeric">{{ $quote->quote_date ? $quote->quote_date->format('F d, Y') : '—' }}</strong>
                </div>
                <div class="flex sm:justify-end gap-2 text-secondary">
                    <span>Valid Until:</span>
                    <strong class="text-text-rich font-numeric">{{ $quote->expiry_date ? $quote->expiry_date->format('F d, Y') : '—' }}</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-y border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Item & Description</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Unit Price (UGX)</th>
                        <th class="py-3 px-4 text-right">Line Total (UGX)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @foreach($quote->items as $it)
                        <tr>
                            <td class="py-3 px-4 font-bold text-text-rich">{{ $it->item_name }}</td>
                            <td class="py-3 px-4 text-center font-numeric text-secondary font-semibold">{{ $it->quantity }}</td>
                            <td class="py-3 px-4 text-right font-numeric text-secondary">{{ number_format($it->unit_price_ugx) }}</td>
                            <td class="py-3 px-4 text-right font-numeric font-bold text-text-rich">{{ number_format($it->total_price_ugx) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Notes -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pt-6 border-t border-border-quiet">
            <div class="text-xs text-secondary space-y-1 max-w-sm">
                <span class="font-bold text-text-rich block">Terms & Remarks:</span>
                <p>{{ $quote->notes ?: 'Prices inclusive of standard 18% Uganda VAT. Quotation valid until expiry date.' }}</p>
            </div>

            <div class="sm:text-right space-y-2 text-xs min-w-[220px]">
                <div class="flex justify-between sm:justify-end gap-6 text-secondary">
                    <span>Subtotal:</span>
                    <span class="font-numeric font-semibold text-text-rich">UGX {{ number_format($quote->subtotal_ugx) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 text-secondary">
                    <span>VAT ({{ $quote->vat_rate_percent }}%):</span>
                    <span class="font-numeric font-semibold text-text-rich">UGX {{ number_format($quote->vat_amount_ugx) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 pt-3 border-t border-border-quiet text-sm font-bold text-text-rich">
                    <span>Grand Total:</span>
                    <span class="font-headline text-xl text-primary font-numeric">UGX {{ number_format($quote->total_amount_ugx) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
