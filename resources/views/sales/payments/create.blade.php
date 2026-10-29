@extends('layouts.app')

@section('title', 'Record Payment Received • Sales • TBM Enterprise')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('sales.payments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Payments</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Record Payment Received</h1>
        <p class="text-xs text-secondary mt-0.5">Settle customer accounts against pending invoices and issue official receipts.</p>
    </div>

    <form action="{{ route('sales.payments.store') }}" method="POST" class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Payment Number</label>
                <input type="text" name="payment_number" value="{{ $nextPaymentNum }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Payment Date *</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-text-rich mb-1">Apply to Pending Invoice (Optional)</label>
            <select name="invoice_id" id="invoiceSelect" onchange="autoFillInvoice(this)" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                <option value="">-- Direct Payment / Select Invoice --</option>
                @foreach($invoices as $inv)
                    <option value="{{ $inv->id }}" data-customer="{{ $inv->customer_name }}" data-total="{{ $inv->total_cents }}" {{ ($selectedInvoice && $selectedInvoice->id == $inv->id) ? 'selected' : '' }}>
                        {{ $inv->invoice_number }} — {{ $inv->customer_name }} (Due: {{ $inv->formatted_total }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Customer Name *</label>
                <input type="text" name="customer_name" id="customerName" value="{{ $selectedInvoice ? $selectedInvoice->customer_name : '' }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich" placeholder="e.g. Mukwano Distributors">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Amount Received (UGX) *</label>
                <input type="number" name="amount_ugx" id="amountInput" value="{{ $selectedInvoice ? $selectedInvoice->total_cents : '' }}" min="1" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich" placeholder="e.g. 500000">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Payment Method *</label>
                <select name="payment_method" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                    <option value="Cash">Cash</option>
                    <option value="MTN Mobile Money">MTN Mobile Money</option>
                    <option value="Airtel Money">Airtel Money</option>
                    <option value="Bank Transfer">Bank Transfer (EFT/RTGS)</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Card">Credit / Debit Card</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Transaction Ref / Cheque #</label>
                <input type="text" name="reference_no" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich" placeholder="e.g. MTN-891283 or CHQ-0012">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-text-rich mb-1">Notes / Remarks</label>
            <textarea name="notes" rows="2" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich" placeholder="Additional details regarding this settlement..."></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
            <a href="{{ route('sales.payments.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm">
                Record Payment & Generate Receipt
            </button>
        </div>
    </form>
</div>

<script>
    function autoFillInvoice(select) {
        const opt = select.options[select.selectedIndex];
        if (opt.value) {
            document.getElementById('customerName').value = opt.getAttribute('data-customer') || '';
            document.getElementById('amountInput').value = opt.getAttribute('data-total') || '';
        }
    }
</script>
@endsection
