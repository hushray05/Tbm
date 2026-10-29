@extends('layouts.app')

@section('title', 'Sales & Purchase Orders • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">{{ $type === 'sales_order' ? 'Sales Orders' : 'Purchase Orders' }}</h1>
            <p class="text-xs text-secondary mt-0.5">Track commercial order fulfillment, supplier dispatch, and procurement workflows in UGX.</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center bg-bg-subtle p-1 rounded-xl border border-border-quiet text-xs font-bold">
                <a href="{{ route('orders.index', ['type' => 'sales_order']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ $type === 'sales_order' ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:text-text-rich' }}">
                    Sales Orders
                </a>
                <a href="{{ route('orders.index', ['type' => 'purchase_order']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ $type === 'purchase_order' ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:text-text-rich' }}">
                    Purchase Orders
                </a>
            </div>

            <button type="button" onclick="document.getElementById('orderModal').classList.remove('hidden')" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                <span>Create {{ $type === 'sales_order' ? 'Sales Order' : 'Purchase Order' }}</span>
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Order #</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5">{{ $type === 'sales_order' ? 'Customer' : 'Vendor / Supplier' }}</th>
                        <th class="px-5 py-3.5 text-right">Total Amount (UGX)</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($orders as $o)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5 font-bold font-numeric text-primary text-sm">{{ $o->order_number }}</td>
                            <td class="px-5 py-3.5 font-numeric text-secondary">{{ $o->order_date ? $o->order_date->format('d M Y') : 'N/A' }}</td>
                            <td class="px-5 py-3.5 font-semibold text-text-rich">{{ $o->party_name }}</td>
                            <td class="px-5 py-3.5 text-right font-numeric font-extrabold text-text-rich text-sm">
                                UGX {{ number_format($o->total_amount_ugx) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $o->status === 'fulfilled' ? 'bg-green-100 text-green-800' : ($o->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                                    {{ $o->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-secondary truncate max-w-xs">{{ $o->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-secondary">
                                No orders recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Order Modal -->
<div id="orderModal" class="fixed inset-0 bg-slate-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-surface rounded-2xl border border-border-quiet p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-headline font-bold text-base text-text-rich">Create {{ $type === 'sales_order' ? 'Sales Order' : 'Purchase Order' }}</h3>
            <button type="button" onclick="document.getElementById('orderModal').classList.add('hidden')" class="text-secondary hover:text-text-rich">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">{{ $type === 'sales_order' ? 'Customer Name' : 'Supplier Name' }} <span class="text-danger">*</span></label>
                <input type="text" name="party_name" required placeholder="e.g. Uganda Breweries Ltd" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Total Amount (UGX) <span class="text-danger">*</span></label>
                    <input type="number" name="total_amount_ugx" required placeholder="e.g. 7500000" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs font-numeric text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Order Date <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" required value="{{ date('Y-m-d') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Status <span class="text-danger">*</span></label>
                <select name="status" required class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                    <option value="confirmed" selected>Confirmed</option>
                    <option value="draft">Draft</option>
                    <option value="fulfilled">Fulfilled</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Notes / Instructions</label>
                <textarea name="notes" rows="2" placeholder="Delivery depot and dispatch terms..." class="w-full p-2.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('orderModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold shadow-sm hover:bg-primary-container">Save Order</button>
            </div>
        </form>
    </div>
</div>
@endsection
