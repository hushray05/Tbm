@extends('layouts.app')

@section('title', 'Quotes & Estimates • Sales • TBM Enterprise')

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
                <span>Quotes</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Sales Quotes & Estimates</h1>
            <p class="text-sm text-secondary mt-0.5">Send commercial price proposals to clients and convert accepted quotes into orders or invoices.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sales.quotes.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add</span>
                <span>New Quote</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl">request_quote</span>
                <span class="font-bold text-sm text-text-rich">Active Quotations (Total: UGX {{ number_format($totalQuotesVal) }})</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Quote #</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Quote Date</th>
                        <th class="py-3 px-4">Expiry Date</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                        <th class="py-3 px-4 text-right">VAT (18%)</th>
                        <th class="py-3 px-4 text-right">Grand Total</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($quotes as $q)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">
                                <a href="{{ route('sales.quotes.show', $q) }}" class="hover:underline">
                                    {{ $q->quote_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-text-rich">
                                <span class="font-bold block">{{ $q->customer_name }}</span>
                                @if($q->customer_email)<span class="text-[10px] text-secondary">{{ $q->customer_email }}</span>@endif
                            </td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $q->quote_date ? $q->quote_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $q->expiry_date ? $q->expiry_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric text-secondary">UGX {{ number_format($q->subtotal_ugx) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric text-secondary font-semibold">UGX {{ number_format($q->vat_amount_ugx) }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich">UGX {{ number_format($q->total_amount_ugx) }}</td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $q->status_badge }}">
                                    {{ $q->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('sales.quotes.show', $q) }}" class="p-1.5 rounded-lg border border-border-quiet text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="View Quote">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                    @if($q->status !== 'converted')
                                        <form action="{{ route('sales.quotes.convert-invoice', $q) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-2xs transition-all" title="Convert to Invoice">
                                                <span class="material-symbols-outlined text-base">receipt_long</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">request_quote</span>
                                <p class="text-sm font-bold text-text-rich">No quotations created yet</p>
                                <p class="text-xs mt-1">Create your first client quotation with automated 18% VAT calculation.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotes->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
