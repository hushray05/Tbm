@extends('layouts.admin')

@section('title', 'Inventory & Stock Control • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Inventory & Stock Audit Control</h1>
            <p class="text-xs text-secondary mt-0.5">Replenish stock levels, audit physical warehouse counts, and log asset inventory adjustments.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="bg-primary hover:bg-primary-container text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">add_box</span>
                <span>New Item SKU</span>
            </a>
            <a href="{{ route('inventory.index') }}" class="px-3.5 py-2 rounded-lg border border-border-quiet text-secondary hover:text-primary hover:bg-bg-subtle text-xs font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">visibility</span>
                <span>Public View</span>
            </a>
        </div>
    </div>

    <!-- Admin Stock Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-1">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Total Warehouse Count</span>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ number_format($totalUnits) }} <span class="text-xs text-secondary font-normal">units</span>
            </div>
            <span class="text-[11px] text-secondary block">Across all active catalog lines</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-1">
            <span class="text-xs font-bold text-warning uppercase tracking-wider block">Low Stock Items</span>
            <div class="font-headline text-2xl font-bold text-warning font-numeric">
                {{ $lowStockCount }} Items
            </div>
            <span class="text-[11px] text-secondary block">Stock level &le; 100 units</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-1">
            <span class="text-xs font-bold text-primary uppercase tracking-wider block">Total Inventory Valuation</span>
            <div class="font-headline text-2xl font-bold text-primary font-numeric">
                {{ $currencyService->format($totalValuationCents) }}
            </div>
            <span class="text-[11px] text-secondary block">Calculated at current FX exchange</span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-surface border border-border-quiet rounded-xl p-4 tbm-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.inventory.index') }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition-colors {{ empty($status) ? 'bg-primary text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                All Products
            </a>
            <a href="{{ route('admin.inventory.index', ['status' => 'low']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition-colors {{ $status === 'low' ? 'bg-warning text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                Low Stock (&le;100)
            </a>
            <a href="{{ route('admin.inventory.index', ['status' => 'out']) }}" class="px-3.5 py-1.5 rounded-lg font-semibold transition-colors {{ $status === 'out' ? 'bg-danger text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                Out of Stock (0)
            </a>
        </div>

        <form action="{{ route('admin.inventory.index') }}" method="GET" class="relative max-w-xs w-full">
            @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search SKU, Product name..." class="w-full h-9 pl-9 pr-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary text-xs text-text-rich outline-none">
        </form>
    </div>

    <!-- Inventory Admin Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Item & SKU</th>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-4 py-3.5 text-center">Current Stock</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right">Unit Price (USD)</th>
                        <th class="px-4 py-3.5 text-right">Valuation ({{ $currentCurrency->code }})</th>
                        <th class="px-4 py-3.5 text-center">Stock Adjustment Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($products as $p)
                        @php
                            $totalVal = $p->stock_quantity * $p->price_cents;
                            $statusBadge = match(true) {
                                $p->stock_quantity <= 0 => ['bg' => 'bg-red-100 text-red-700', 'label' => 'Out of Stock'],
                                $p->stock_quantity <= 100 => ['bg' => 'bg-amber-100 text-amber-800', 'label' => 'Low Stock'],
                                default => ['bg' => 'bg-green-100 text-green-700', 'label' => 'In Stock'],
                            };
                        @endphp
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    @if($p->image_url)
                                        <img src="{{ asset($p->image_url) }}" alt="{{ $p->name }}" class="w-10 h-10 rounded-lg object-cover border border-border-quiet shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-bg-subtle border border-border-quiet flex items-center justify-center shrink-0 text-secondary">
                                            <span class="material-symbols-outlined text-lg">inventory_2</span>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-bold text-text-rich block text-xs">{{ $p->name }}</span>
                                        <span class="text-[10px] text-primary font-numeric font-semibold">{{ $p->sku }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-secondary">{{ $p->category }}</td>
                            <td class="px-4 py-3.5 text-center font-numeric font-bold text-sm text-text-rich">
                                {{ number_format($p->stock_quantity) }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $statusBadge['bg'] }}">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-numeric text-text-rich">
                                ${{ number_format($p->price_cents / 100, 2) }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-numeric font-bold text-primary">
                                {{ $currencyService->format($totalVal) }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <!-- Quick Restock / Adjust Form Inline -->
                                <form action="{{ route('admin.inventory.adjust', $p) }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <select name="adjustment_type" class="h-8 px-2 rounded-lg border border-border-quiet bg-surface text-[11px] font-semibold text-text-rich outline-none">
                                        <option value="add">+ Add</option>
                                        <option value="subtract">- Deduct</option>
                                        <option value="set">= Set To</option>
                                    </select>
                                    <input type="number" name="quantity" min="1" value="25" class="w-16 h-8 px-2 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none">
                                    <button type="submit" class="bg-primary hover:bg-primary-container text-white px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1" title="Apply Stock Adjustment">
                                        <span class="material-symbols-outlined text-sm">sync_alt</span>
                                        <span>Update</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">inventory_2</span>
                                <p class="text-sm font-medium text-text-rich">No products matching inventory search criteria</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
