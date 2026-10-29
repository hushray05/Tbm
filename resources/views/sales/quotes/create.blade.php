@extends('layouts.app')

@section('title', 'Create Commercial Quote • Sales • TBM Enterprise')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('sales.quotes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Quotes</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Create New Quotation</h1>
        <p class="text-xs text-secondary mt-0.5">Generate a formal proposal with items, quantities, and automated 18% VAT.</p>
    </div>

    <form action="{{ route('sales.quotes.store') }}" method="POST" id="quoteForm" class="space-y-6">
        @csrf

        <!-- General Info Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                <span>Customer & Quote Details</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Quote Number</label>
                    <input type="text" name="quote_number" value="{{ $nextQuoteNum }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Existing Customer (Optional)</label>
                    <select id="partySelect" onchange="fillCustomerInfo(this)" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                        <option value="">-- Choose Existing or Type Below --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-email="{{ $c->email }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Customer / Organization Name *</label>
                    <input type="text" name="customer_name" id="customerName" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich" placeholder="e.g. Skyline Media Ltd">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Customer Email</label>
                    <input type="email" name="customer_email" id="customerEmail" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich" placeholder="procurement@client.ug">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Quote Date *</label>
                    <input type="date" name="quote_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
                </div>
            </div>
        </div>

        <!-- Line Items Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                <h2 class="text-sm font-bold text-text-rich flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">inventory_2</span>
                    <span>Quotation Line Items</span>
                </h2>
                <button type="button" onclick="addItemRow()" class="text-xs text-primary font-bold hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    <span>Add Item</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="itemsTable">
                    <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[10px]">
                        <tr>
                            <th class="py-2.5 px-3">Item / Product Name</th>
                            <th class="py-2.5 px-3 w-28 text-center">Qty</th>
                            <th class="py-2.5 px-3 w-40 text-right">Unit Price (UGX)</th>
                            <th class="py-2.5 px-3 w-40 text-right">Total (UGX)</th>
                            <th class="py-2.5 px-3 w-12 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody" class="divide-y divide-border-quiet">
                        <tr class="item-row">
                            <td class="py-3 px-3">
                                <input type="text" name="items[0][item_name]" required class="w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-semibold text-text-rich" placeholder="Item description...">
                            </td>
                            <td class="py-3 px-3">
                                <input type="number" name="items[0][quantity]" value="1" min="1" required oninput="calculateTotals()" class="qty-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-center">
                            </td>
                            <td class="py-3 px-3">
                                <input type="number" name="items[0][unit_price]" value="0" min="0" required oninput="calculateTotals()" class="price-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-right">
                            </td>
                            <td class="py-3 px-3 text-right font-numeric font-bold text-text-rich line-total">
                                UGX 0
                            </td>
                            <td class="py-3 px-3 text-center">
                                <button type="button" onclick="removeItemRow(this)" class="text-secondary hover:text-danger p-1">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Predefined Product Quick-Add -->
            <div class="pt-3 border-t border-border-quiet flex items-center gap-3">
                <span class="text-xs text-secondary font-bold">Quick add product from catalog:</span>
                <select id="quickProductSelect" onchange="quickAddProduct(this)" class="px-3 py-1.5 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich max-w-xs">
                    <option value="">-- Choose Product to Add --</option>
                    @foreach($products as $pr)
                        <option value="{{ $pr->id }}" data-name="{{ $pr->name }}" data-price="{{ $pr->price_cents }}">
                            {{ $pr->name }} (UGX {{ number_format($pr->price_cents) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Calculation & Notes Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-3">
                <label class="block text-xs font-bold text-text-rich">Terms & Commercial Notes</label>
                <textarea name="notes" rows="4" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:outline-none" placeholder="Payment terms: 50% advance, delivery within 5 days..."></textarea>
            </div>

            <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-3 text-xs">
                <div class="flex justify-between text-secondary">
                    <span>Subtotal:</span>
                    <span class="font-numeric font-bold text-text-rich" id="subtotalDisplay">UGX 0</span>
                </div>
                <div class="flex justify-between text-secondary">
                    <span>VAT Rate:</span>
                    <span class="font-numeric font-semibold text-text-rich">{{ $vatRate }}% (Uganda Standard)</span>
                </div>
                <div class="flex justify-between text-secondary">
                    <span>VAT Amount:</span>
                    <span class="font-numeric font-bold text-text-rich" id="vatDisplay">UGX 0</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-border-quiet text-sm font-bold text-text-rich">
                    <span>Grand Total:</span>
                    <span class="font-headline text-lg text-primary font-numeric" id="grandTotalDisplay">UGX 0</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('sales.quotes.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all">
                Save & Issue Quotation
            </button>
        </div>
    </form>
</div>

<script>
    let rowIdx = 1;
    const vatRate = {{ $vatRate }};

    function fillCustomerInfo(select) {
        const opt = select.options[select.selectedIndex];
        if (opt.value) {
            document.getElementById('customerName').value = opt.getAttribute('data-name') || '';
            document.getElementById('customerEmail').value = opt.getAttribute('data-email') || '';
        }
    }

    function addItemRow(name = '', price = 0, productId = '') {
        const body = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="py-3 px-3">
                <input type="hidden" name="items[${rowIdx}][product_id]" value="${productId}">
                <input type="text" name="items[${rowIdx}][item_name]" value="${name}" required class="w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-semibold text-text-rich" placeholder="Item description...">
            </td>
            <td class="py-3 px-3">
                <input type="number" name="items[${rowIdx}][quantity]" value="1" min="1" required oninput="calculateTotals()" class="qty-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-center">
            </td>
            <td class="py-3 px-3">
                <input type="number" name="items[${rowIdx}][unit_price]" value="${price}" min="0" required oninput="calculateTotals()" class="price-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-right">
            </td>
            <td class="py-3 px-3 text-right font-numeric font-bold text-text-rich line-total">
                UGX ${Number(price).toLocaleString()}
            </td>
            <td class="py-3 px-3 text-center">
                <button type="button" onclick="removeItemRow(this)" class="text-secondary hover:text-danger p-1">
                    <span class="material-symbols-outlined text-base">delete</span>
                </button>
            </td>
        `;
        body.appendChild(tr);
        rowIdx++;
        calculateTotals();
    }

    function removeItemRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            calculateTotals();
        } else {
            alert('A quote must contain at least one line item.');
        }
    }

    function quickAddProduct(select) {
        const opt = select.options[select.selectedIndex];
        if (opt.value) {
            const name = opt.getAttribute('data-name');
            const price = opt.getAttribute('data-price');
            addItemRow(name, price, opt.value);
            select.value = '';
        }
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(tr => {
            const qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
            const price = parseFloat(tr.querySelector('.price-input').value) || 0;
            const lineTotal = qty * price;
            subtotal += lineTotal;
            tr.querySelector('.line-total').textContent = 'UGX ' + Math.round(lineTotal).toLocaleString();
        });

        const vat = Math.round(subtotal * (vatRate / 100));
        const grandTotal = subtotal + vat;

        document.getElementById('subtotalDisplay').textContent = 'UGX ' + subtotal.toLocaleString();
        document.getElementById('vatDisplay').textContent = 'UGX ' + vat.toLocaleString();
        document.getElementById('grandTotalDisplay').textContent = 'UGX ' + grandTotal.toLocaleString();
    }
</script>
@endsection
