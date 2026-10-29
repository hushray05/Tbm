@extends('layouts.app')

@section('title', 'Sales Orders • Sales • TBM Enterprise')

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
                <span>Orders</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Sales Orders</h1>
            <p class="text-sm text-secondary mt-0.5">Track commercial order fulfillment and convert confirmed orders into invoices.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sales.orders.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add_shopping_cart</span>
                <span>New Sales Order</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl">shopping_cart</span>
                <span class="font-bold text-sm text-text-rich">Sales Orders (Total Volume: UGX {{ number_format($totalSalesOrderVolume) }})</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Order Date</th>
                        <th class="py-3 px-4 text-right">Amount (UGX)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Image</th>
                        <th class="py-3 px-4">Notes</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($orders as $o)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3.5 px-4 font-numeric font-bold text-primary">
                                {{ $o->order_number }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-text-rich">
                                {{ $o->party_name }}
                            </td>
                            <td class="py-3.5 px-4 text-secondary font-numeric">{{ $o->order_date ? $o->order_date->format('d M Y') : '—' }}</td>
                            <td class="py-3.5 px-4 text-right font-numeric font-bold text-text-rich">UGX {{ number_format($o->total_amount_ugx) }}</td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @php
                                    $statusBadges = [
                                        'draft' => 'bg-slate-100 text-slate-800 border-slate-200',
                                        'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'fulfilled' => 'bg-green-100 text-green-800 border-green-200',
                                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    ];
                                    $badge = $statusBadges[$o->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $badge }}">
                                    {{ $o->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($o->image_url)
                                    <a href="{{ asset($o->image_url) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset($o->image_url) }}" alt="Order {{ $o->order_number }}" class="w-10 h-10 rounded-lg object-cover border border-border-quiet hover:scale-110 transition-transform">
                                    </a>
                                @else
                                    <span class="text-secondary text-[10px]">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-secondary truncate max-w-xs">{{ $o->notes ?? '—' }}</td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($o->status === 'confirmed')
                                        <form action="{{ route('sales.orders.convert-invoice', $o) }}" method="POST" class="inline" onsubmit="return confirm('Convert this order into an invoice?');">
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
                            <td colspan="8" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">shopping_cart</span>
                                <p class="text-sm font-bold text-text-rich">No sales orders yet</p>
                                <p class="text-xs mt-1">Create your first sales order to start tracking fulfillment.</p>
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
