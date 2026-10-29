@extends('layouts.app')

@section('title', 'Create Sales Order • Sales • TBM Enterprise')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('sales.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Sales Orders</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Create New Sales Order</h1>
        <p class="text-xs text-secondary mt-0.5">Record a commercial sales order for fulfillment tracking and invoice conversion.</p>
    </div>

    <form action="{{ route('sales.orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- General Info Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">shopping_cart</span>
                <span>Customer & Order Details</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Order Number</label>
                    <input type="text" name="order_number" value="{{ $nextOrderNumber }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Existing Customer (Optional)</label>
                    <select id="partySelect" onchange="fillCustomerInfo(this)" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                        <option value="">-- Choose Existing or Type Below --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Customer / Organization Name *</label>
                    <input type="text" name="party_name" id="partyName" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich" placeholder="e.g. Uganda Breweries Ltd">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Order Date *</label>
                    <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                        <option value="draft">Draft</option>
                        <option value="confirmed" selected>Confirmed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Total Amount (UGX) *</label>
                    <input type="number" name="total_amount_ugx" id="totalAmountInput" value="0" min="1" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-3">
            <label class="block text-xs font-bold text-text-rich">Notes / Delivery Instructions</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:outline-none" placeholder="e.g. Deliver to Kampala warehouse, payment on delivery..."></textarea>
        </div>

        <!-- Image Upload -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-3">
            <label class="block text-xs font-bold text-text-rich">Attach Image (Optional)</label>
            <input type="file" name="image_file" accept="image/*" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            <p class="text-[10px] text-secondary">JPG, PNG, GIF, WebP. Max 2MB. Attach a photo of the order, delivery note, or related document.</p>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('sales.orders.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all">
                Save Sales Order
            </button>
        </div>
    </form>
</div>

<script>
    function fillCustomerInfo(select) {
        const opt = select.options[select.selectedIndex];
        if (opt.value) {
            document.getElementById('partyName').value = opt.value;
        }
    }
</script>
@endsection
