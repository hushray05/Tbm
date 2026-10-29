@extends('layouts.app')

@section('title', 'Create Purchase Order • Purchases • TBM Enterprise')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('purchases.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Purchase Orders</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Create Purchase Order</h1>
        <p class="text-xs text-secondary mt-0.5">Issue an official purchase order commitment to a vendor.</p>
    </div>

    <form action="{{ route('purchases.orders.store') }}" method="POST" class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">PO Number *</label>
                <input type="text" name="order_number" value="{{ $nextPONumber }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Order Date *</label>
                <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Select Existing Vendor</label>
                <select onchange="document.getElementById('vendorName').value = this.options[this.selectedIndex].text" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                    <option value="">-- Choose Vendor or Type Below --</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Vendor / Supplier Name *</label>
                <input type="text" name="party_name" id="vendorName" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich" placeholder="e.g. Kakira Sugar Works Ltd">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Total Estimated Amount (UGX) *</label>
                <input type="number" name="total_amount_ugx" min="1" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich" placeholder="e.g. 2500000">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Initial Status</label>
                <select name="status" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                    <option value="confirmed">Confirmed</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-text-rich mb-1">Order Specifications / Remarks</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich" placeholder="List requested items, delivery destination, packaging specifications..."></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
            <a href="{{ route('purchases.orders.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm">
                Issue Purchase Order
            </button>
        </div>
    </form>
</div>
@endsection
