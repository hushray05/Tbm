@extends('layouts.app')

@section('title', 'Enterprise Inventory & Stock Management • TBM Operations')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Inventory & Stock Management</h1>
            <p class="text-sm text-secondary mt-1">Real-time stock control, inventory valuation, stock-in/stock-out adjustments, and movement history.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" onclick="openStockModal()" class="bg-primary hover:bg-primary-container text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-base">tune</span>
                <span>Adjust Stock</span>
            </button>
            <a href="{{ route('products.index') }}" class="bg-surface hover:bg-bg-subtle border border-border-quiet text-text-rich px-3.5 py-2 rounded-lg text-xs font-bold shadow-2xs transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-base">add_box</span>
                <span>Products Catalog</span>
            </a>
        </div>
    </div>

    <!-- Inventory KPIs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-secondary">
                <span class="text-xs font-bold uppercase tracking-wider">Total Units in Stock</span>
                <span class="material-symbols-outlined text-primary text-xl">inventory_2</span>
            </div>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">
                {{ number_format($totalUnits) }} <span class="text-xs font-normal text-secondary">units</span>
            </div>
            <span class="text-[11px] text-secondary block">Across {{ $totalItems }} distinct items</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-secondary">
                <span class="text-xs font-bold uppercase tracking-wider">Selling Asset Valuation</span>
                <span class="material-symbols-outlined text-emerald-600 text-xl">account_balance_wallet</span>
            </div>
            <div class="font-headline text-2xl font-bold text-emerald-600 font-numeric">
                {{ $currencyService->format($totalValuationCents) }}
            </div>
            <span class="text-[11px] text-secondary block">Cost Value: {{ $currencyService->format($totalCostValuationCents) }}</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-secondary">
                <span class="text-xs font-bold uppercase tracking-wider">Low Stock Items</span>
                <span class="material-symbols-outlined text-amber-500 text-xl">warning</span>
            </div>
            <div class="font-headline text-2xl font-bold text-amber-600 font-numeric">
                {{ $lowStockCount }} <span class="text-xs font-normal text-secondary">items &le; {{ $threshold }} units</span>
            </div>
            <span class="text-[11px] text-secondary block">Reorder alert triggered</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-secondary">
                <span class="text-xs font-bold uppercase tracking-wider">Out of Stock</span>
                <span class="material-symbols-outlined text-red-500 text-xl">remove_shopping_cart</span>
            </div>
            <div class="font-headline text-2xl font-bold text-red-600 font-numeric">
                {{ $outOfStockCount }} <span class="text-xs font-normal text-secondary">items depleted</span>
            </div>
            <span class="text-[11px] text-secondary block">Immediate replenishment needed</span>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-surface border border-border-quiet rounded-xl p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Stock Level Status Filter -->
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <a href="{{ route('inventory.index', ['category' => $category, 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ empty($stockFilter) ? 'bg-primary text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                All ({{ $totalItems }})
            </a>
            <a href="{{ route('inventory.index', ['stock_status' => 'healthy', 'category' => $category, 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ $stockFilter === 'healthy' ? 'bg-emerald-600 text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                Healthy (>{{ $threshold }})
            </a>
            <a href="{{ route('inventory.index', ['stock_status' => 'low', 'category' => $category, 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ $stockFilter === 'low' ? 'bg-amber-500 text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                Low Stock (&le;{{ $threshold }})
            </a>
            <a href="{{ route('inventory.index', ['stock_status' => 'out', 'category' => $category, 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ $stockFilter === 'out' ? 'bg-red-600 text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                Out of Stock (0)
            </a>
        </div>

        <!-- Search Box & Category Select -->
        <form action="{{ route('inventory.index') }}" method="GET" class="flex items-center gap-2 max-w-md w-full">
            @if($stockFilter)<input type="hidden" name="stock_status" value="{{ $stockFilter }}">@endif
            <select name="category" onchange="this.form.submit()" class="h-9 px-2.5 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none font-semibold">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-outline text-base">search</span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search SKU, Product name..." class="w-full h-9 pl-8 pr-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary text-xs text-text-rich outline-none">
            </div>
            <button type="submit" class="h-9 px-3 bg-primary text-white text-xs font-bold rounded-lg shadow-2xs">Filter</button>
        </form>
    </div>

    <!-- Inventory Data Table -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Product / Item</th>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-4 py-3.5 text-center">On-Hand Stock</th>
                        <th class="px-4 py-3.5 text-center">Stock Status</th>
                        <th class="px-4 py-3.5 text-right">Selling Price</th>
                        <th class="px-4 py-3.5 text-right">Total Valuation</th>
                        <th class="px-4 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($products as $p)
                        @php
                            $totalVal = $p->stock_quantity * $p->price_cents;
                            $statusBadge = match(true) {
                                $p->stock_quantity <= 0 => ['bg' => 'bg-red-100 text-red-800 border-red-200', 'label' => 'Out of Stock', 'icon' => 'error'],
                                $p->stock_quantity <= $threshold => ['bg' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Low Stock', 'icon' => 'warning'],
                                default => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'In Stock', 'icon' => 'check_circle'],
                            };
                        @endphp
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-bg-subtle border border-border-quiet shrink-0 flex items-center justify-center">
                                        @if($p->image_url)
                                            <img src="{{ asset($p->image_url) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-lg text-secondary">inventory_2</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('products.show', $p) }}" class="font-bold text-text-rich hover:text-primary transition-colors block text-xs">
                                            {{ $p->name }}
                                        </a>
                                        <span class="text-[10px] text-primary font-numeric font-semibold">SKU: {{ $p->sku }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-secondary font-medium">{{ $p->category }}</td>
                            <td class="px-4 py-3.5 text-center font-numeric font-bold text-sm {{ $p->stock_quantity <= $threshold ? 'text-amber-600' : 'text-text-rich' }}">
                                {{ number_format($p->stock_quantity) }}
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase border {{ $statusBadge['bg'] }}">
                                    <span class="material-symbols-outlined text-xs">{{ $statusBadge['icon'] }}</span>
                                    <span>{{ $statusBadge['label'] }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-numeric text-text-rich font-medium">
                                {{ $p->formatted_price }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-numeric font-bold text-primary">
                                {{ $currencyService->format($totalVal) }}
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" onclick="quickAdjust({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->stock_quantity }})" class="p-1.5 rounded-lg border border-border-quiet text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Adjust Stock">
                                        <span class="material-symbols-outlined text-base">tune</span>
                                    </button>
                                    <a href="{{ route('inventory.history', $p) }}" class="p-1.5 rounded-lg border border-border-quiet text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Stock Movement History">
                                        <span class="material-symbols-outlined text-base">history</span>
                                    </a>
                                    <form action="{{ route('cart.add') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="product">
                                        <input type="hidden" name="id" value="{{ $p->id }}">
                                        <button type="submit" class="p-1.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs shadow-2xs transition-all" title="Add to POS">
                                            <span class="material-symbols-outlined text-base">point_of_sale</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">inventory_2</span>
                                <p class="text-sm font-medium text-text-rich">No inventory items match your search</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Recent Stock Movements Audit Ledger -->
    @if(isset($recentMovements) && $recentMovements->count() > 0)
        <div class="bg-surface border border-border-quiet rounded-xl shadow-xs p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">sync_alt</span>
                    <span class="font-headline font-bold text-sm text-text-rich">Recent Stock Audit Trail</span>
                </div>
                <span class="text-xs text-secondary">Last 10 inventory transactions across all products</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-bg-subtle text-secondary font-bold uppercase text-[10px]">
                        <tr>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Product</th>
                            <th class="py-2.5 px-3">Type</th>
                            <th class="py-2.5 px-3">Reference</th>
                            <th class="py-2.5 px-3 text-center">Change</th>
                            <th class="py-2.5 px-3 text-center">Balance After</th>
                            <th class="py-2.5 px-3">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-quiet">
                        @foreach($recentMovements as $mv)
                            <tr class="hover:bg-bg-subtle/50">
                                <td class="py-2.5 px-3 text-secondary font-numeric whitespace-nowrap">{{ $mv->created_at->format('d M H:i') }}</td>
                                <td class="py-2.5 px-3 font-bold text-text-rich">{{ $mv->product->name ?? 'Deleted Item' }}</td>
                                <td class="py-2.5 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border uppercase {{ $mv->type_badge }}">
                                        {{ $mv->type }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 font-numeric text-secondary">{{ $mv->reference ?: '—' }}</td>
                                <td class="py-2.5 px-3 text-center font-numeric font-bold whitespace-nowrap">
                                    @if($mv->quantity_change > 0)
                                        <span class="text-emerald-600">+{{ number_format($mv->quantity_change) }}</span>
                                    @elseif($mv->quantity_change < 0)
                                        <span class="text-red-600">{{ number_format($mv->quantity_change) }}</span>
                                    @else
                                        <span class="text-secondary">0</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-center font-numeric font-bold text-text-rich whitespace-nowrap">{{ number_format($mv->balance_after) }}</td>
                                <td class="py-2.5 px-3 text-secondary truncate max-w-xs">{{ $mv->notes ?: 'Stock adjustment' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Stock Adjustment Modal -->
<div id="stockAdjustModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
    <div class="bg-surface border border-border-quiet rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-border-quiet mb-4">
            <h3 class="font-bold text-base text-text-rich flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">tune</span>
                <span id="stockModalTitle">Stock Adjustment</span>
            </h3>
            <button type="button" onclick="closeStockModal()" class="p-1 rounded-lg text-secondary hover:text-danger hover:bg-red-50">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('inventory.adjust') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Select Product / Item</label>
                <select name="product_id" id="modalProductId" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                    @foreach($allProducts as $ap)
                        <option value="{{ $ap->id }}">{{ $ap->name }} (Stock: {{ $ap->stock_quantity }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Adjustment Action</label>
                <select name="adjustment_type" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                    <option value="add">Stock-In (+) Add stock to inventory</option>
                    <option value="subtract">Stock-Out (-) Deduct stock</option>
                    <option value="set">Physical Count (=) Set exact verified count</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Quantity</label>
                    <input type="number" name="quantity" min="1" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-numeric font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="e.g. 25">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Reason</label>
                    <select name="reason" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="purchase">Purchase Received</option>
                        <option value="adjustment">Stock Reconciliation</option>
                        <option value="opening">Opening Stock</option>
                        <option value="damage">Damaged / Expired</option>
                        <option value="return">Customer Return</option>
                        <option value="sale">Manual Sale</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Reference Code (Optional)</label>
                <input type="text" name="reference" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="e.g. PO-2026-001 or ADJ-12">
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Audit Notes</label>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="Add remarks for this stock movement..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border-quiet">
                <button type="button" onclick="closeStockModal()" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm">
                    Apply Adjustment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStockModal() {
        document.getElementById('stockModalTitle').textContent = 'Stock Adjustment';
        document.getElementById('stockAdjustModal').classList.remove('hidden');
    }
    function closeStockModal() {
        document.getElementById('stockAdjustModal').classList.add('hidden');
    }
    function quickAdjust(productId, productName, currentStock) {
        const select = document.getElementById('modalProductId');
        select.value = productId;
        document.getElementById('stockModalTitle').textContent = 'Adjust Stock: ' + productName + ' (' + currentStock + ' units)';
        document.getElementById('stockAdjustModal').classList.remove('hidden');
    }
</script>
@endsection
