@extends('layouts.app')

@section('title', 'Purchase Orders • Purchases • TBM Enterprise')

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
                <span>Purchase Orders</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Purchase Orders</h1>
            <p class="text-sm text-secondary mt-0.5">Issue purchase orders to suppliers and convert fulfilled orders into bills to receive stock.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('purchases.orders.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add</span>
                <span>New Purchase Order</span>
            </a>
        </div>
    </div>

    <!-- PO Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl">local_shipping</span>
                <span class="font-bold text-sm text-text-rich">Total PO Commitments: UGX {{ number_format($totalPOVolume) }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">PO #</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Supplier / Vendor</th>
                        <th class="py-3 px-4 text-right">Order Amount (UGX)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Remarks</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($orders as $po)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">{{ $po->order_number }}</td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $po->order_date ? $po->order_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 font-bold text-text-rich">{{ $po->party_name }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich">
                                UGX {{ number_format($po->total_amount_ugx) }}
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $po->status === 'fulfilled' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $po->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-secondary truncate max-w-xs">{{ $po->notes ?: '—' }}</td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($po->status !== 'fulfilled')
                                    <form action="{{ route('purchases.orders.convert-bill', $po) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-2xs transition-all flex items-center gap-1 mx-auto" title="Convert to Bill & Receive Stock">
                                            <span class="material-symbols-outlined text-xs">receipt_long</span>
                                            <span>Convert to Bill</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-emerald-700 font-bold">Fulfilled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">local_shipping</span>
                                <p class="text-sm font-bold text-text-rich">No purchase orders found</p>
                                <p class="text-xs mt-1">Issue purchase orders to suppliers for stock orders.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
