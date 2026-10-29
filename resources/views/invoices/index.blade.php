@extends('layouts.app')

@section('title', 'Multi-Currency Invoices & Ledger • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Enterprise Ledger & Invoicing</h1>
            <p class="text-sm text-secondary mt-1">Multi-currency billing statements with automatic exchange rate conversion.</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-sm transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            <span>Create New Invoice</span>
        </a>
    </div>

    <!-- Summary Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block mb-2">Total Billed Ledger</span>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ $currencyService->format($totalBilledCents ?? 0) }}
            </div>
            <span class="text-xs text-secondary mt-1 block">Converted to active {{ $currentCurrency->code }}</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block mb-2">Collected / Paid</span>
            <div class="font-headline text-2xl font-bold text-success font-numeric">
                {{ $currencyService->format($paidBilledCents ?? 0) }}
            </div>
            <span class="text-xs text-success mt-1 block">Settled funds</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block mb-2">Pending Invoices</span>
            <div class="font-headline text-2xl font-bold text-warning font-numeric">
                {{ $currencyService->format($pendingBilledCents ?? 0) }}
            </div>
            <span class="text-xs text-secondary mt-1 block">Awaiting payment</span>
        </div>
    </div>

    <!-- Invoices List Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="p-4 sm:p-5 border-b border-border-quiet flex items-center justify-between bg-bg-subtle">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl fill">receipt_long</span>
                <span class="font-headline font-bold text-text-rich text-base">Invoices Ledger</span>
            </div>
            <span class="text-xs text-secondary">Exchange rates applied at time of issuance</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-bg-subtle/80 border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Invoice #</th>
                        <th class="px-4 py-3.5">Customer</th>
                        <th class="px-4 py-3.5">Issue Date</th>
                        <th class="px-4 py-3.5">Due Date</th>
                        <th class="px-4 py-3.5">Currency</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Amount</th>
                        <th class="px-4 py-3.5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-bg-subtle/70 transition-colors">
                            <td class="px-5 py-4 font-bold text-primary">
                                <a href="{{ route('invoices.show', $inv) }}" class="hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-semibold text-text-rich block">{{ $inv->customer_name }}</span>
                                <span class="text-xs text-secondary">{{ $inv->customer_email }}</span>
                            </td>
                            <td class="px-4 py-4 text-xs text-secondary font-numeric">
                                {{ $inv->invoice_date ? $inv->invoice_date->format('M d, Y') : ($inv->created_at ? $inv->created_at->format('M d, Y') : '—') }}
                            </td>
                            <td class="px-4 py-4 text-xs text-secondary font-numeric">
                                {{ $inv->due_date ? $inv->due_date->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-primary/10 text-primary">
                                    {{ $inv->currency_code }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $sColor = match($inv->status) {
                                        'paid' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'overdue' => 'bg-red-100 text-red-800',
                                        default => 'bg-slate-100 text-slate-700'
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full uppercase {{ $sColor }}">
                                    {{ $inv->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-numeric font-bold text-text-rich">
                                {{ $inv->formatted_total }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('invoices.show', $inv) }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline bg-bg-subtle px-2.5 py-1 rounded-md border border-border-quiet">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                    <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-secondary">
                                No invoices found. Click "Create New Invoice" to issue a billing statement.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
