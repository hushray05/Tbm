@extends('layouts.app')

@section('title', 'Record Vendor Bill • Purchases • TBM Enterprise')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('purchases.bills.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-secondary hover:text-primary transition-colors mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Bills</span>
        </a>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Record Vendor Bill</h1>
        <p class="text-xs text-secondary mt-0.5">Enter supplier invoice items. Stock for selected products will be automatically increased upon recording.</p>
    </div>

    <form action="{{ route('purchases.bills.store') }}" method="POST" id="billForm" class="space-y-6">
        @csrf

        <!-- Vendor & Dates Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">storefront</span>
                <span>Vendor & Bill Information</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Bill Number *</label>
                    <input type="text" name="bill_number" value="{{ $nextBillNum }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Select Existing Vendor</label>
                    <select id="vendorSelect" onchange="fillVendor(this)" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                        <option value="">-- Choose Vendor or Type Below --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}" data-name="{{ $v->name }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Vendor / Supplier Name *</label>
                    <input type="text" name="vendor_name" id="vendorName" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich" placeholder="e.g. Kakira Sugar Works Ltd">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Linked Purchase Order (Optional)</label>
                    <select name="order_id" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich">
                        <option value="">-- None / Direct Bill --</option>
                        @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}">{{ $po->order_number }} ({{ $po->party_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Bill Date *</label>
                    <input type="date" name="bill_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Payment Due Date</label>
                    <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric text-text-rich">
                </div>
            </div>
        </div>

        <!-- Line Items Card (With Automatic Stock Increase Notice) -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">inventory</span>
                    <h2 class="text-sm font-bold text-text-rich">Items Received & Cost Breakdown</h2>
                </div>
                <button type="button" onclick="addBillItemRow()" class="text-xs text-primary font-bold hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    <span>Add Item</span>
                </button>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-xs text-emerald-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">info</span>
                <span>Connecting products here will <strong>automatically increase your inventory stock balance</strong> and create an audit ledger entry.</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="billItemsTable">
                    <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[10px]">
                        <tr>
                            <th class="py-2.5 px-3">Product Catalog Item</th>
                            <th class="py-2.5 px-3">Item Description</th>
                            <th class="py-2.5 px-3 w-24 text-center">Qty</th>
                            <th class="py-2.5 px-3 w-36 text-right">Unit Cost (UGX)</th>
                            <th class="py-2.5 px-3 w-36 text-right">Total Cost (UGX)</th>
                            <th class="py-2.5 px-3 w-10 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="billItemsBody" class="divide-y divide-border-quiet">
                        <tr class="bill-row">
                            <td class="py-3 px-3">
                                <select name="items[0][product_id]" onchange="onProductSelect(this)" class="product-select w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-semibold text-text-rich">
                                    <option value="">-- Custom / Non-stock item --</option>
                                    @foreach($products as $pr)
                                        <option value="{{ $pr->id }}" data-name="{{ $pr->name }}" data-cost="{{ $pr->cost_price_cents ?: $pr->price_cents }}">
                                            {{ $pr->name }} (Current: {{ $pr->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-3 px-3">
                                <input type="text" name="items[0][item_name]" required class="item-name-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs text-text-rich" placeholder="Description...">
                            </td>
                            <td class="py-3 px-3">
                                <input type="number" name="items[0][quantity]" value="1" min="1" required oninput="calculateBillTotals()" class="qty-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-center">
                            </td>
                            <td class="py-3 px-3">
                                <input type="number" name="items[0][unit_cost]" value="0" min="0" required oninput="calculateBillTotals()" class="cost-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-right">
                            </td>
                            <td class="py-3 px-3 text-right font-numeric font-bold text-text-rich line-cost">
                                UGX 0
                            </td>
                            <td class="py-3 px-3 text-center">
                                <button type="button" onclick="removeBillItemRow(this)" class="text-secondary hover:text-danger p-1">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Calculation & Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-3">
                <label class="block text-xs font-bold text-text-rich">Procurement Remarks / Delivery Notes</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich" placeholder="Delivered to Jinja Rd depot, Waybill #9981..."></textarea>
            </div>

            <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-3 text-xs">
                <div class="flex justify-between text-secondary">
                    <span>Subtotal:</span>
                    <span class="font-numeric font-bold text-text-rich" id="billSubtotalDisplay">UGX 0</span>
                </div>
                <div class="flex justify-between text-secondary">
                    <span>Input VAT ({{ $vatRate }}%):</span>
                    <span class="font-numeric font-bold text-text-rich" id="billVatDisplay">UGX 0</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-border-quiet text-sm font-bold text-text-rich">
                    <span>Total Payable:</span>
                    <span class="font-headline text-lg text-primary font-numeric" id="billTotalDisplay">UGX 0</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('purchases.bills.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm">
                Save Bill & Increase Inventory
            </button>
        </div>
    </form>
</div>

<script>
    let bRowIdx = 1;
    const bVatRate = {{ $vatRate }};

    function fillVendor(select) {
        const opt = select.options[select.selectedIndex];
        if (opt.value) {
            document.getElementById('vendorName').value = opt.getAttribute('data-name') || '';
        }
    }

    function onProductSelect(select) {
        const opt = select.options[select.selectedIndex];
        const row = select.closest('tr');
        if (opt.value) {
            row.querySelector('.item-name-input').value = opt.getAttribute('data-name') || '';
            row.querySelector('.cost-input').value = opt.getAttribute('data-cost') || 0;
            calculateBillTotals();
        }
    }

    function addBillItemRow() {
        const body = document.getElementById('billItemsBody');
        const tr = document.createElement('tr');
        tr.className = 'bill-row';
        tr.innerHTML = `
            <td class="py-3 px-3">
                <select name="items[${bRowIdx}][product_id]" onchange="onProductSelect(this)" class="product-select w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-semibold text-text-rich">
                    <option value="">-- Custom / Non-stock item --</option>
                    @foreach($products as $pr)
                        <option value="{{ $pr->id }}" data-name="{{ $pr->name }}" data-cost="{{ $pr->cost_price_cents ?: $pr->price_cents }}">
                            {{ $pr->name }} (Current: {{ $pr->stock_quantity }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td class="py-3 px-3">
                <input type="text" name="items[${bRowIdx}][item_name]" required class="item-name-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs text-text-rich" placeholder="Description...">
            </td>
            <td class="py-3 px-3">
                <input type="number" name="items[${bRowIdx}][quantity]" value="1" min="1" required oninput="calculateBillTotals()" class="qty-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-center">
            </td>
            <td class="py-3 px-3">
                <input type="number" name="items[${bRowIdx}][unit_cost]" value="0" min="0" required oninput="calculateBillTotals()" class="cost-input w-full px-2.5 py-1.5 bg-bg-subtle border border-border-quiet rounded-md text-xs font-numeric font-bold text-right">
            </td>
            <td class="py-3 px-3 text-right font-numeric font-bold text-text-rich line-cost">
                UGX 0
            </td>
            <td class="py-3 px-3 text-center">
                <button type="button" onclick="removeBillItemRow(this)" class="text-secondary hover:text-danger p-1">
                    <span class="material-symbols-outlined text-base">delete</span>
                </button>
            </td>
        `;
        body.appendChild(tr);
        bRowIdx++;
        calculateBillTotals();
    }

    function removeBillItemRow(btn) {
        const rows = document.querySelectorAll('.bill-row');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            calculateBillTotals();
        } else {
            alert('A bill must have at least one item line.');
        }
    }

    function calculateBillTotals() {
        let subtotal = 0;
        document.querySelectorAll('.bill-row').forEach(tr => {
            const qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(tr.querySelector('.cost-input').value) || 0;
            const lineCost = qty * cost;
            subtotal += lineCost;
            tr.querySelector('.line-cost').textContent = 'UGX ' + Math.round(lineCost).toLocaleString();
        });

        const vat = Math.round(subtotal * (bVatRate / 100));
        const total = subtotal + vat;

        document.getElementById('billSubtotalDisplay').textContent = 'UGX ' + subtotal.toLocaleString();
        document.getElementById('billVatDisplay').textContent = 'UGX ' + vat.toLocaleString();
        document.getElementById('billTotalDisplay').textContent = 'UGX ' + total.toLocaleString();
    }
</script>
@endsection
