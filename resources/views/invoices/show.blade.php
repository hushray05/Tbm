@extends('layouts.app')

@section('title', 'Invoice #' . $invoice->invoice_number . ' • TBM Ledger')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Action Bar (Hidden when printing) -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('invoices.index') }}" class="text-xs font-bold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Invoices</span>
        </a>
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-primary hover:bg-primary-container text-white rounded-lg text-xs font-bold shadow-md flex items-center gap-2 transition-all cursor-pointer">
                <span class="material-symbols-outlined text-base">print</span>
                <span>Print Invoice</span>
            </button>
            <a href="mailto:{{ $invoice->customer_email }}" class="px-4 py-2.5 bg-surface hover:bg-bg-subtle border border-border-quiet text-text-rich rounded-lg text-xs font-bold shadow-2xs flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-base">mail</span>
                <span>Email Client</span>
            </a>
        </div>
    </div>

    <!-- Printable Invoice Card -->
    <div class="bg-surface border border-border-quiet rounded-xl p-8 tbm-shadow space-y-8 printable-invoice-sheet">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-b border-border-quiet pb-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-lg fill">account_balance</span>
                    </div>
                    <span class="font-headline font-bold text-xl text-primary">{{ $payee ? $payee->name : (auth()->user() && auth()->user()->getActiveBusiness() ? auth()->user()->getActiveBusiness()->name : 'TBM Enterprise') }}</span>
                </div>
                <p class="text-xs text-secondary leading-relaxed">
                    Uganda Business Operating System<br>
                    {{ $payee && $payee->address ? $payee->address : 'Uganda' }}<br>
                    @if($payee && $payee->tin_number)TIN: {{ $payee->tin_number }} | @endif
                    @if($payee && $payee->phone)Tel: {{ $payee->phone }}@endif
                    @if($payee && $payee->email)<br>{{ $payee->email }}@endif
                </p>
            </div>

            <div class="sm:text-right space-y-1">
                <span class="text-xs font-bold text-secondary uppercase tracking-widest block">Commercial Tax Invoice</span>
                <span class="font-headline text-2xl font-bold text-text-rich block font-numeric">#{{ $invoice->invoice_number }}</span>
                <div class="pt-1">
                    @php
                        $sColor = match($invoice->status) {
                            'paid' => 'bg-green-100 text-green-800 border-green-200',
                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'overdue' => 'bg-red-100 text-red-800 border-red-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                        };
                    @endphp
                    <span class="text-xs font-bold px-3 py-1 rounded-full uppercase border {{ $sColor }}">
                        Status: {{ $invoice->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Billing Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-1">
                <span class="font-bold text-secondary uppercase tracking-wider block">Billed To:</span>
                <strong class="text-text-rich text-sm block">{{ $invoice->customer_name }}</strong>
                @if($invoice->customer_email)<span class="text-secondary block">{{ $invoice->customer_email }}</span>@endif
                <span class="text-secondary block whitespace-pre-line">{{ $invoice->billing_address ?? 'Uganda Operations' }}</span>
            </div>

            <div class="sm:text-right space-y-1">
                <div class="flex sm:justify-end gap-2">
                    <span class="text-secondary">Issue Date:</span>
                    <strong class="text-text-rich font-numeric">{{ $invoice->invoice_date ? $invoice->invoice_date->format('F d, Y') : ($invoice->created_at ? $invoice->created_at->format('F d, Y') : '—') }}</strong>
                </div>
                <div class="flex sm:justify-end gap-2">
                    <span class="text-secondary">Payment Due:</span>
                    <strong class="text-text-rich font-numeric">{{ $invoice->due_date ? $invoice->due_date->format('F d, Y') : '—' }}</strong>
                </div>
                <div class="flex sm:justify-end gap-2">
                    <span class="text-secondary">Billing Currency:</span>
                    <strong class="text-primary font-bold">{{ $invoice->currency_code }}</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-y border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Item & Description</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Unit Price ({{ $invoice->currency_code }})</th>
                        <th class="py-3 px-4 text-right">Total ({{ $invoice->currency_code }})</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($invoice->items as $item)
                        <tr>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-text-rich block">{{ $item->description }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-numeric text-secondary font-semibold">{{ $item->quantity }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric text-secondary">{{ $currencyService->format($item->unit_price_cents, $invoice->currency_code) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich">{{ $currencyService->format($item->total_price_cents, $invoice->currency_code) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-secondary">No item details recorded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totals & Payment Certification Box -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pt-6 border-t border-border-quiet">
            <!-- Left: Payment details & QR Stamp -->
            <div class="space-y-4 max-w-sm">
                <div class="text-xs text-secondary space-y-1">
                    <span class="font-bold text-text-rich block">Payment Terms & Remarks:</span>
                    <p>{{ $invoice->notes ?? 'Payment due within 30 days of issuance. Bank: Stanbic Bank Uganda, A/C: 9030012345678, Branch: Forest Mall.' }}</p>
                </div>

                <!-- Digital Certificate / Stamp -->
                <div class="flex items-center gap-3 p-3 rounded-lg bg-bg-subtle/80 border border-border-quiet">
                    <div class="w-10 h-10 rounded bg-surface border border-border-quiet flex items-center justify-center shrink-0 text-text-rich">
                        <span class="material-symbols-outlined text-2xl">verified</span>
                    </div>
                    <div class="text-[11px] leading-tight">
                        <span class="font-bold text-text-rich block">Official Uganda Tax Compliance</span>
                        <span class="text-secondary block font-numeric">Auth Ref: {{ substr(md5($invoice->id . $invoice->invoice_number), 0, 16) }}</span>
                        <span class="text-success font-semibold flex items-center gap-1 mt-0.5">
                            <span class="material-symbols-outlined text-xs">check_circle</span> 18% Uganda VAT Registered
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Subtotals and Grand Total with 18% VAT -->
            <div class="sm:text-right space-y-2 text-xs min-w-[220px]">
                <div class="flex justify-between sm:justify-end gap-6 text-secondary">
                    <span>Subtotal:</span>
                    <span class="font-numeric font-semibold text-text-rich">{{ $currencyService->format($invoice->subtotal_cents, $invoice->currency_code) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 text-secondary">
                    <span>VAT (18%):</span>
                    <span class="font-numeric font-semibold text-text-rich">{{ $currencyService->format($invoice->tax_cents, $invoice->currency_code) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 pt-3 border-t border-border-quiet text-sm font-bold text-text-rich">
                    <span>Grand Total:</span>
                    <span class="font-headline text-xl text-primary font-numeric">{{ $invoice->formatted_total }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide top nav, sidebar, footer, action buttons */
    header, aside, #sidebar, .no-print, nav, button, a[href*="mailto"] {
        display: none !important;
    }
    body {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .printable-invoice-sheet {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    .md\:ml-sidebar-width {
        margin-left: 0 !important;
    }
}
</style>
@endsection
