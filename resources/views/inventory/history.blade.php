@extends('layouts.app')

@section('title', 'Stock Movement History - ' . $product->name . ' • TBM Enterprise')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('inventory.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Back to Inventory & Stock</span>
            </a>
            <h1 class="text-2xl font-headline font-bold text-text-rich">Stock Movement Ledger</h1>
            <p class="text-xs text-secondary mt-0.5">Audit trail of all purchases, sales, adjustments, and returns for this product.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="document.getElementById('adjustStockModal').classList.remove('hidden')" class="px-3.5 py-2 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-base">tune</span>
                <span>Adjust Stock</span>
            </button>
        </div>
    </div>

    <!-- Product Summary Card -->
    <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl overflow-hidden bg-bg-subtle border border-border-quiet shrink-0 flex items-center justify-center">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <span class="material-symbols-outlined text-3xl text-secondary">inventory_2</span>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded">{{ $product->category }}</span>
                    <span class="text-xs text-secondary font-numeric">SKU: {{ $product->sku }}</span>
                </div>
                <h2 class="text-lg font-bold text-text-rich mt-1">{{ $product->name }}</h2>
                <div class="flex flex-wrap items-center gap-4 text-xs text-secondary mt-1">
                    <span>Selling Price: <strong class="text-text-rich font-numeric">{{ $product->formatted_price }}</strong></span>
                    <span>Cost / Purchase: <strong class="text-text-rich font-numeric">{{ $product->formatted_cost_price }}</strong></span>
                    <span>VAT Rate: <strong class="text-text-rich font-numeric">{{ $product->vat_rate }}%</strong></span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-6 border-t md:border-t-0 md:border-l border-border-quiet pt-3 md:pt-0 md:pl-6 w-full md:w-auto justify-between md:justify-end">
            <div class="text-center">
                <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block">Current Stock</span>
                <span class="text-2xl font-bold font-numeric block {{ $product->stock_quantity <= 10 ? 'text-amber-600' : 'text-emerald-600' }}">
                    {{ number_format($product->stock_quantity) }}
                </span>
                <span class="text-[10px] text-secondary">Units on hand</span>
            </div>
            <div class="text-center">
                <span class="text-[10px] font-bold text-secondary uppercase tracking-wider block">Stock Status</span>
                @if($product->stock_quantity <= 0)
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Out of Stock</span>
                @elseif($product->stock_quantity <= 10)
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Low Stock Alert</span>
                @else
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Healthy Stock</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Movement History Table -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-border-quiet flex items-center justify-between">
            <h3 class="text-sm font-bold text-text-rich flex items-center gap-2">
                <span class="material-symbols-outlined text-lg text-primary">history</span>
                <span>Transaction History Ledger ({{ $movements->total() }} events recorded)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary font-bold uppercase tracking-wider text-[11px] border-b border-border-quiet">
                    <tr>
                        <th class="py-3 px-4">Date & Time</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Reference</th>
                        <th class="py-3 px-4 text-center">Change (Qty)</th>
                        <th class="py-3 px-4 text-center">Balance After</th>
                        <th class="py-3 px-4">Logged By / Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet">
                    @forelse($movements as $m)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="py-3 px-4 text-secondary font-numeric whitespace-nowrap">
                                <span class="font-bold text-text-rich block">{{ $m->created_at->format('d M Y') }}</span>
                                <span class="text-[10px] text-secondary">{{ $m->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold border uppercase {{ $m->type_badge }}">
                                    {{ $m->type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-numeric font-semibold text-text-rich whitespace-nowrap">
                                {{ $m->reference ?: '—' }}
                            </td>
                            <td class="py-3 px-4 text-center font-numeric font-bold whitespace-nowrap">
                                @if($m->quantity_change > 0)
                                    <span class="text-emerald-600">+{{ number_format($m->quantity_change) }}</span>
                                @elseif($m->quantity_change < 0)
                                    <span class="text-red-600">{{ number_format($m->quantity_change) }}</span>
                                @else
                                    <span class="text-secondary">0</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-numeric font-bold text-text-rich whitespace-nowrap">
                                {{ number_format($m->balance_after) }}
                            </td>
                            <td class="py-3 px-4 text-secondary">
                                <span class="text-text-rich block">{{ $m->notes ?: 'Inventory adjustment' }}</span>
                                @if($m->creator)
                                    <span class="text-[10px] text-secondary">By {{ $m->creator->name }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline block mb-2">history_toggle_off</span>
                                <span class="font-bold block">No stock movements recorded yet</span>
                                <span class="text-xs">Adjust stock or record a purchase/sale to start building the product audit history.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
            <div class="p-4 border-t border-border-quiet">
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div id="adjustStockModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
    <div class="bg-surface border border-border-quiet rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-border-quiet mb-4">
            <h3 class="font-bold text-base text-text-rich flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">tune</span>
                <span>Adjust Stock for {{ $product->name }}</span>
            </h3>
            <button type="button" onclick="document.getElementById('adjustStockModal').classList.add('hidden')" class="p-1 rounded-lg text-secondary hover:text-danger hover:bg-red-50">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('inventory.adjust') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Adjustment Mode</label>
                <select name="adjustment_type" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                    <option value="add">Stock-In (+) Increase quantity</option>
                    <option value="subtract">Stock-Out (-) Decrease quantity</option>
                    <option value="set">Set Exact Count (=) Override total</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Quantity</label>
                    <input type="number" name="quantity" min="1" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="e.g. 50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Reason</label>
                    <select name="reason" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="purchase">Stock Received (Purchase)</option>
                        <option value="adjustment">Inventory Reconciliation</option>
                        <option value="opening">Opening Stock</option>
                        <option value="damage">Damaged / Expired</option>
                        <option value="return">Customer Return</option>
                        <option value="sale">Manual Sale</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Reference Code (Optional)</label>
                <input type="text" name="reference" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="e.g. PO-881, ADJ-04">
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Notes / Audit Remarks</label>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="Explain the reason for this inventory update..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border-quiet">
                <button type="button" onclick="document.getElementById('adjustStockModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm">
                    Confirm Adjustment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
