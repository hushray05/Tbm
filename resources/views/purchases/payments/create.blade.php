@extends('layouts.app')

@section('title', 'Record Payment to Supplier • Purchases • TBM Enterprise')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('purchases.payments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Payments Made</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Record Payment to Supplier</h1>
        <p class="text-xs text-secondary mt-0.5">Settle vendor bills and clear outstanding payables.</p>
    </div>

    <form action="{{ route('purchases.payments.store') }}" method="POST" class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Payment Reference #</label>
                <input type="text" name="payment_number" value="{{ $nextPaymentNum }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Payment Date *</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-text-rich mb-1">Select Open Bill to Pay</label>
            <select name="bill_id" id="billSelect" onchange="autoFillBill(this)" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                <option value="">-- Direct Supplier Advance / Choose Bill --</option>
                @foreach($bills as $bl)
                    <option value="{{ $bl->id }}" data-vendor="{{ $bl->vendor_name }}" data-balance="{{ $bl->balance_ugx }}" {{ ($selectedBill && $selectedBill->id == $bl->id) ? 'selected' : '' }}>
                        {{ $bl->bill_number }} — {{ $bl->vendor_name }} (Balance: UGX {{ number_format($bl->balance_ugx) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Vendor / Supplier Name *</label>
                <input type="text" name="vendor_name" id="vendorName" value="{{ $selectedBill ? $selectedBill->vendor_name : '' }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich" placeholder="e.g. Kakira Sugar Works Ltd">
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Amount Paid (UGX) *</label>
                <input type="number" name="amount_ugx" id="amountInput" value="{{ $selectedBill ? $selectedBill->balance_ugx : '' }}" min="1" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich" placeholder="e.g. 1000000">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Disbursement Method *</label>
                <select name="payment_method" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                    <option value="Bank Transfer">Bank Transfer (EFT/RTGS)</option>
                    <option value="MTN Mobile Money">MTN Mobile Money</option>
                    <option value="Airtel Money">Airtel Money</option>
                    <option value="Cash">Cash</option>
                    <option value="Cheque">Cheque</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Reference / Bank Slip #</label>
                <input type="text" name="reference_no" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich" placeholder="e.g. EFT-89218 or CHQ-4412">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-text-rich mb-1">Disbursement Notes</label>
            <textarea name="notes" rows="2" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich" placeholder="Payment approval details, ledger code..."></textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
            <a href="{{ route('purchases.payments.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm">
                Record Payment
            </button>
        </div>
    </form>
</div>

<script>
    function autoFillBill(select) {
        const opt = select.options[select.selectedIndex];
        if (opt.value) {
            document.getElementById('vendorName').value = opt.getAttribute('data-vendor') || '';
            document.getElementById('amountInput').value = opt.getAttribute('data-balance') || '';
        }
    }
</script>
@endsection
