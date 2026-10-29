@extends('layouts.app')

@section('title', 'Enterprise Ledger - New Invoice • TBM Enterprise')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Page Header (Matching Stitch Screen) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Create New Invoice</h1>
            <p class="text-xs md:text-sm text-secondary mt-0.5">Drafting invoice #{{ $nextInvoiceNum }} with multi-currency FX settlement</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('invoices.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-text-rich bg-surface hover:bg-bg-subtle transition-colors shadow-sm">
                Cancel
            </a>
            <button type="submit" form="invoiceForm" class="px-4 py-2 bg-primary text-white hover:bg-primary-container rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">send</span>
                <span>Generate Invoice</span>
            </button>
        </div>
    </div>

    <!-- Main Invoice Form -->
    <form id="invoiceForm" action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <input type="hidden" name="invoice_number" value="{{ $nextInvoiceNum }}">

        <!-- Bento Grid Layout (Matching Stitch Screen 86823a9fe5a4453ba65b12ee440c35f4) -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Left Column: Customer & Details (Col 8) -->
            <div class="col-span-12 lg:col-span-8 space-y-6">
                <!-- Customer Details Card -->
                <div class="bg-surface rounded-xl border border-border-quiet p-6 tbm-shadow">
                    <h3 class="font-headline font-bold text-base text-text-rich mb-4 border-b border-border-quiet pb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">business</span>
                        <span>Customer Details</span>
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Customer / Entity Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name', 'Acme Corp International') }}" placeholder="e.g. Globex Solutions Ltd" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-sm text-text-rich">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Billing Contact Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" required value="{{ old('customer_email', 'finance@acmecorp.co.uk') }}" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-sm text-text-rich">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Billing Address</label>
                                <input type="text" name="billing_address" value="{{ old('billing_address', '1200 Enterprise Way, Suite 400, London') }}" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-sm text-text-rich">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Line Items Card -->
                <div class="bg-surface rounded-xl border border-border-quiet p-6 tbm-shadow space-y-4">
                    <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                        <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">format_list_bulleted</span>
                            <span>Invoice Line Items</span>
                        </h3>
                        <button type="button" id="addItemBtn" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">add_circle</span>
                            <span>Add Item</span>
                        </button>
                    </div>

                    <div id="lineItemsContainer" class="space-y-3">
                        <!-- Default Line Item 1 -->
                        <div class="line-item grid grid-cols-12 gap-3 p-3 bg-bg-subtle rounded-lg border border-border-quiet items-center">
                            <div class="col-span-12 sm:col-span-6">
                                <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Description / Product</label>
                                <input type="text" name="items[0][description]" required value="TBM Enterprise Suite (Annual License)" class="w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm text-text-rich outline-none focus:border-primary">
                            </div>
                            <div class="col-span-4 sm:col-span-2">
                                <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Qty</label>
                                <input type="number" name="items[0][quantity]" required min="1" value="1" class="qty-input w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm font-numeric text-text-rich outline-none focus:border-primary">
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Unit Price ($ USD)</label>
                                <input type="number" step="0.01" name="items[0][unit_price]" required value="1299.00" class="price-input w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm font-numeric text-text-rich outline-none focus:border-primary">
                            </div>
                            <div class="col-span-2 sm:col-span-1 text-center pt-5">
                                <button type="button" class="remove-item-btn text-outline hover:text-danger p-1">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </div>

                        <!-- Default Line Item 2 -->
                        <div class="line-item grid grid-cols-12 gap-3 p-3 bg-bg-subtle rounded-lg border border-border-quiet items-center">
                            <div class="col-span-12 sm:col-span-6">
                                <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Description / Product</label>
                                <input type="text" name="items[1][description]" required value="Pan-African FX Clearing Gateway Integration" class="w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm text-text-rich outline-none focus:border-primary">
                            </div>
                            <div class="col-span-4 sm:col-span-2">
                                <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Qty</label>
                                <input type="number" name="items[1][quantity]" required min="1" value="1" class="qty-input w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm font-numeric text-text-rich outline-none focus:border-primary">
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Unit Price ($ USD)</label>
                                <input type="number" step="0.01" name="items[1][unit_price]" required value="850.00" class="price-input w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm font-numeric text-text-rich outline-none focus:border-primary">
                            </div>
                            <div class="col-span-2 sm:col-span-1 text-center pt-5">
                                <button type="button" class="remove-item-btn text-outline hover:text-danger p-1">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="pt-3 border-t border-border-quiet">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Invoice Settlement Notes</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Net 30 terms. Settlement accepted in USD or corresponding African local currency equivalence." class="w-full p-3 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none focus:border-primary">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Dates, Currency & Totals (Col 4) -->
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <!-- Meta Dates Card -->
                <div class="bg-surface rounded-xl border border-border-quiet p-6 tbm-shadow space-y-4">
                    <h3 class="font-headline font-bold text-sm text-text-rich uppercase tracking-wider border-b border-border-quiet pb-2">
                        Invoice Schedule
                    </h3>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Invoice Date</label>
                        <input type="date" name="invoice_date" required value="{{ date('Y-m-d') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-surface text-sm text-text-rich outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Due Date</label>
                        <input type="date" name="due_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-surface text-sm text-text-rich outline-none">
                    </div>
                </div>

                <!-- Currency Settings Card (Matching High Impact Section on Stitch Mockup) -->
                <div class="bg-surface rounded-xl border border-primary/40 p-6 tbm-shadow relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
                    <h3 class="font-headline font-bold text-base text-text-rich mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">currency_exchange</span>
                        <span>Currency Settings</span>
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase mb-1.5">Billing & Settlement Currency</label>
                            <div class="relative">
                                <select name="currency_code" id="invoiceCurrencySelect" class="w-full h-11 pl-4 pr-10 rounded-lg border border-primary bg-primary-fixed-dim/10 text-primary font-bold text-sm outline-none cursor-pointer">
                                    @foreach($currencies as $c)
                                        <option value="{{ $c->code }}" data-rate="{{ $c->exchange_rate }}" data-symbol="{{ $c->symbol }}" {{ $currentCurrency->code === $c->code ? 'selected' : '' }}>
                                            {{ $c->code }} - {{ $c->name }} ({{ $c->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Exchange Rate Alert Badge -->
                        <div class="bg-surface-container-low border border-primary/20 rounded-lg p-3 flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-primary text-lg mt-0.5">info</span>
                            <div class="text-xs">
                                <p class="font-bold text-text-rich" id="currencyExchangeHeadline">Multi-Currency Engine Active</p>
                                <p class="text-secondary mt-0.5" id="currencyExchangeRateText">1 USD = {{ number_format($currentCurrency->exchange_rate, 2) }} {{ $currentCurrency->code }}</p>
                                <p class="text-[10px] text-outline mt-0.5">Auto-converting with integer cent precision</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Totals Summary Card -->
                <div class="bg-surface rounded-xl border border-border-quiet p-6 tbm-shadow space-y-3">
                    <h3 class="font-headline font-bold text-sm text-text-rich uppercase tracking-wider border-b border-border-quiet pb-2">
                        Billing Calculation
                    </h3>
                    <div class="flex justify-between text-xs text-secondary">
                        <span>Subtotal (Base USD):</span>
                        <span class="font-numeric font-semibold text-text-rich" id="subtotalDisplay">$2,149.00</span>
                    </div>
                    <div class="flex justify-between text-xs text-secondary">
                        <span>Enterprise VAT (8%):</span>
                        <span class="font-numeric font-semibold text-text-rich" id="taxDisplay">$171.92</span>
                    </div>
                    <div class="pt-3 border-t border-border-quiet flex justify-between items-baseline">
                        <span class="text-sm font-bold text-text-rich">Total Converted:</span>
                        <div class="text-right">
                            <span class="font-headline text-xl font-bold text-primary font-numeric block" id="totalConvertedDisplay">
                                {{ $currencyService->format(232092) }}
                            </span>
                            <span class="text-[11px] text-secondary font-numeric" id="totalUsdDisplay">($2,320.92 USD)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 2;
    const container = document.getElementById('lineItemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    const currencySelect = document.getElementById('invoiceCurrencySelect');

    function calculateTotals() {
        let subtotalUsd = 0;
        document.querySelectorAll('.line-item').forEach(item => {
            const qty = parseFloat(item.querySelector('.qty-input').value) || 0;
            const price = parseFloat(item.querySelector('.price-input').value) || 0;
            subtotalUsd += (qty * price);
        });

        const taxUsd = subtotalUsd * 0.08;
        const totalUsd = subtotalUsd + taxUsd;

        const selectedOption = currencySelect.options[currencySelect.selectedIndex];
        const rate = parseFloat(selectedOption.getAttribute('data-rate')) || 1.0;
        const symbol = selectedOption.getAttribute('data-symbol') || '$';
        const code = currencySelect.value;

        const totalConverted = totalUsd * rate;

        document.getElementById('subtotalDisplay').innerText = '$' + subtotalUsd.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('taxDisplay').innerText = '$' + taxUsd.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('totalUsdDisplay').innerText = '($' + totalUsd.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' USD)';
        document.getElementById('totalConvertedDisplay').innerText = symbol + ' ' + totalConverted.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        document.getElementById('currencyExchangeRateText').innerText = '1 USD = ' + rate.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 4 }) + ' ' + code;
    }

    if (addItemBtn && container) {
        addItemBtn.addEventListener('click', function() {
            const div = document.createElement('div');
            div.className = 'line-item grid grid-cols-12 gap-3 p-3 bg-bg-subtle rounded-lg border border-border-quiet items-center';
            div.innerHTML = `
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Description / Product</label>
                    <input type="text" name="items[${itemIndex}][description]" required placeholder="Service or product deliverable" class="w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm text-text-rich outline-none focus:border-primary">
                </div>
                <div class="col-span-4 sm:col-span-2">
                    <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Qty</label>
                    <input type="number" name="items[${itemIndex}][quantity]" required min="1" value="1" class="qty-input w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm font-numeric text-text-rich outline-none focus:border-primary">
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Unit Price ($ USD)</label>
                    <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" required value="100.00" class="price-input w-full h-10 px-3 rounded-md border border-border-quiet bg-surface text-sm font-numeric text-text-rich outline-none focus:border-primary">
                </div>
                <div class="col-span-2 sm:col-span-1 text-center pt-5">
                    <button type="button" class="remove-item-btn text-outline hover:text-danger p-1">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </div>
            `;
            container.appendChild(div);
            itemIndex++;
            calculateTotals();
        });
    }

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            calculateTotals();
        }
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-btn')) {
            const lineItem = e.target.closest('.line-item');
            if (document.querySelectorAll('.line-item').length > 1) {
                lineItem.remove();
                calculateTotals();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Notice',
                    text: 'An invoice must contain at least one line item.'
                });
            }
        }
    });

    if (currencySelect) {
        currencySelect.addEventListener('change', calculateTotals);
    }

    calculateTotals();
});
</script>
@endpush
@endsection
