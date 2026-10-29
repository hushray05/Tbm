@extends('layouts.app')

@section('title', 'Credit Notes (Sales Returns) • Sales • TBM Enterprise')

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
                <span>Credit Notes</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Credit Notes & Returns</h1>
            <p class="text-sm text-secondary mt-0.5">Manage customer credit adjustments, return processing, and automated inventory restocking.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sales.credit-notes.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add</span>
                <span>Issue Credit Note</span>
            </a>
        </div>
    </div>

    <!-- Credit Notes Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-600 text-xl">assignment_return</span>
                <span class="font-bold text-sm text-text-rich">Total Credit Issued: UGX {{ number_format($totalCredits) }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Credit Note #</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Reason / Notes</th>
                        <th class="py-3 px-4 text-center">Restocked</th>
                        <th class="py-3 px-4 text-right">Amount (UGX)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($creditNotes as $cn)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">{{ $cn->credit_note_number }}</td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $cn->credit_date ? $cn->credit_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 font-bold text-text-rich">{{ $cn->customer_name }}</td>
                            <td class="py-3.5 px-4 text-secondary">{{ $cn->reason }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if($cn->restock_inventory)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                        <span class="material-symbols-outlined text-xs">check</span> Yes (+Stock)
                                    </span>
                                @else
                                    <span class="text-secondary text-[11px]">No</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-purple-700 text-sm">
                                UGX {{ number_format($cn->amount_ugx) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-100 text-purple-800 border border-purple-200">
                                    {{ $cn->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">assignment_return</span>
                                <p class="text-sm font-bold text-text-rich">No credit notes issued yet</p>
                                <p class="text-xs mt-1">Issue credit notes for customer returns, damaged goods, or billing adjustments.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($creditNotes->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $creditNotes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
