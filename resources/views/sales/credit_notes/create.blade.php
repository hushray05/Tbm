@extends('layouts.app')

@section('title', 'Issue Credit Note • Sales • TBM Enterprise')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('sales.credit-notes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Credit Notes</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Issue Credit Note</h1>
        <p class="text-xs text-secondary mt-0.5">Credit a customer account for returned goods and optionally restock inventory.</p>
    </div>

    <form action="{{ route('sales.credit-notes.store') }}" method="POST" class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Credit Note #</label>
                <input type="text" name="credit_note_number" value="{{ $nextCreditNum }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Credit Date *</label>
                <input type="date" name="credit_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Customer / Client *</label>
                <input type="text" name="customer_name" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich" placeholder="e.g. Mukwano Distributors">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Original Invoice Ref (Optional)</label>
                <select name="invoice_id" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                    <option value="">-- Select Linked Invoice --</option>
                    @foreach($invoices as $iv)
                        <option value="{{ $iv->id }}">{{ $iv->invoice_number }} ({{ $iv->customer_name }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-bg-subtle p-4 rounded-xl border border-border-quiet space-y-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-base">inventory_2</span>
                <span class="text-xs font-bold text-text-rich">Inventory Restocking Options</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Returned Product (Optional)</label>
                    <select name="product_id" class="w-full px-3 py-2 bg-surface border border-border-quiet rounded-lg text-xs text-text-rich">
                        <option value="">-- Select Product if Returned --</option>
                        @foreach($products as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->name }} (Current: {{ $pr->stock_quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Units to Return / Restock</label>
                    <input type="number" name="restock_quantity" min="0" value="0" class="w-full px-3 py-2 bg-surface border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich" placeholder="0">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="restock_inventory" value="1" checked id="restockCheck" class="rounded text-primary focus:ring-primary h-4 w-4">
                <label for="restockCheck" class="text-xs font-semibold text-text-rich cursor-pointer">
                    Automatically increase product inventory stock count upon issuing this credit note
                </label>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-text-rich mb-1">Credit Amount (UGX) *</label>
            <input type="number" name="amount_ugx" min="1" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich" placeholder="e.g. 150000">
        </div>

        <div>
            <label class="block text-xs font-bold text-text-rich mb-1">Reason for Credit / Return</label>
            <textarea name="reason" rows="2" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich" placeholder="Damaged items received, billing discrepancy, overcharge..."></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
            <a href="{{ route('sales.credit-notes.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm">
                Issue Credit Note & Restock
            </button>
        </div>
    </form>
</div>
@endsection
