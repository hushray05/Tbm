@extends('layouts.app')

@section('title', 'Record Expense • Uganda UGX')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Record Expense Voucher</h1>
            <p class="text-xs text-secondary mt-0.5">Record business expenditures, utility bills, or fuel disbursements.</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="text-xs font-semibold text-primary hover:underline">
            ← Back to Expenses
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Expense Category <span class="text-danger">*</span></label>
                    <select name="category" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                        <option value="Rent">Rent & Warehousing</option>
                        <option value="Utilities">Utilities (Umeme Power, Water)</option>
                        <option value="Fuel & Transport">Fuel & Fleet Transport</option>
                        <option value="Supplies">Packaging & Office Supplies</option>
                        <option value="Marketing">Marketing & Trade Promotion</option>
                        <option value="Maintenance">Equipment Repairs & Maintenance</option>
                        <option value="Miscellaneous">Miscellaneous Operations</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Amount in UGX <span class="text-danger">*</span></label>
                    <input type="number" name="amount_ugx" required placeholder="e.g. 1500000" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Expense Date <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Vendor / Payee Name</label>
                    <input type="text" name="vendor_name" placeholder="e.g. TotalEnergies Uganda" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                        <option value="MTN Mobile Money">MTN Mobile Money</option>
                        <option value="Airtel Money">Airtel Money</option>
                        <option value="Bank Transfer">Bank Transfer (Stanbic/Centenary)</option>
                        <option value="Cash">Cash (Petty Cash)</option>
                        <option value="Card">Debit / Credit Card</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Reference / Receipt #</label>
                    <input type="text" name="reference_no" placeholder="e.g. UMEME-8921" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Description / Notes</label>
                <textarea name="description" rows="2" placeholder="Provide additional details regarding this expenditure..." class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-border-quiet flex items-center justify-end gap-3">
                <a href="{{ route('expenses.index') }}" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary hover:bg-bg-subtle">
                    Cancel
                </a>
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">save</span>
                    <span>Save Expense Voucher</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
