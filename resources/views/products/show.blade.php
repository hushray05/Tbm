@extends('layouts.app')

@section('title', $product->name . ' • TBM Item Details')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="text-xs font-bold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Items Catalog</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.edit', $product) }}" class="px-3.5 py-1.5 bg-surface hover:bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich shadow-2xs flex items-center gap-1.5 transition-colors">
                <span class="material-symbols-outlined text-sm">edit</span>
                <span>Edit Item</span>
            </a>
            <a href="{{ route('inventory.history', $product) }}" class="px-3.5 py-1.5 bg-primary hover:bg-primary-container text-white rounded-lg text-xs font-bold shadow-2xs flex items-center gap-1.5 transition-colors">
                <span class="material-symbols-outlined text-sm">history</span>
                <span>Stock History</span>
            </a>
        </div>
    </div>

    <!-- Main Item Canvas -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left 7 Cols: Product Details & Specs -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-surface border border-border-quiet rounded-xl overflow-hidden shadow-xs space-y-6">
                <!-- Hero Product Image -->
                <div class="w-full h-72 md:h-80 bg-slate-950 overflow-hidden relative flex items-center justify-center">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-bg-subtle text-secondary">
                            <span class="material-symbols-outlined text-7xl">inventory_2</span>
                        </div>
                    @endif
                    <span class="absolute top-3 right-3 text-xs font-numeric font-bold px-3 py-1 rounded-md bg-black/70 backdrop-blur text-white shadow-sm">
                        SKU: {{ $product->sku }}
                    </span>
                </div>

                <div class="p-6 md:p-8 pt-2 md:pt-4 space-y-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-primary/10 text-primary uppercase">
                                {{ $product->category }}
                            </span>
                            @if($product->stock_quantity <= 0)
                                <span class="text-xs text-red-600 font-bold flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                    Out of Stock (0 units)
                                </span>
                            @elseif($product->stock_quantity <= 10)
                                <span class="text-xs text-amber-600 font-bold flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    Low Stock Alert ({{ $product->stock_quantity }} units)
                                </span>
                            @else
                                <span class="text-xs text-emerald-600 font-bold flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    Healthy Stock ({{ $product->stock_quantity }} units)
                                </span>
                            @endif
                        </div>
                        <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">{{ $product->name }}</h1>
                    </div>

                    <div class="border-t border-border-quiet pt-4 space-y-2">
                        <h3 class="font-headline font-bold text-xs text-secondary uppercase tracking-wider">Description</h3>
                        <p class="text-sm text-text-rich leading-relaxed whitespace-pre-line">
                            {{ $product->description ?: 'No additional description entered for this item.' }}
                        </p>
                    </div>

                    <!-- Commercial Details -->
                    <div class="border-t border-border-quiet pt-4 space-y-3">
                        <h3 class="font-headline font-bold text-xs text-secondary uppercase tracking-wider">Financial & Tax Details</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                                <span class="font-semibold text-secondary block">Selling Price</span>
                                <strong class="text-sm text-text-rich font-numeric">{{ $product->formatted_price }}</strong>
                            </div>
                            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                                <span class="font-semibold text-secondary block">Buying / Cost Price</span>
                                <strong class="text-sm text-text-rich font-numeric">{{ $product->formatted_cost_price }}</strong>
                            </div>
                            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                                <span class="font-semibold text-secondary block">Uganda Standard VAT</span>
                                <strong class="text-sm text-text-rich font-numeric">{{ $product->vat_rate }}%</strong>
                            </div>
                            <div class="p-3 bg-bg-subtle rounded-lg border border-border-quiet">
                                <span class="font-semibold text-secondary block">Total Inventory Valuation</span>
                                <strong class="text-sm text-primary font-numeric">UGX {{ number_format($product->stock_quantity * $product->price_cents) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 5 Cols: Actions & Operations Card -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-6 sticky top-20">
                <div>
                    <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Selling Price</span>
                    <div class="font-headline text-3xl font-bold text-primary font-numeric mt-1">
                        {{ $product->formatted_price }}
                    </div>
                    <span class="text-xs text-secondary font-numeric mt-0.5 block">Includes 18% standard VAT</span>
                </div>

                <!-- Stock Controls -->
                <div class="bg-bg-subtle rounded-xl p-4 border border-border-quiet space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-text-rich">On-Hand Stock Count</span>
                        <span class="font-numeric font-extrabold text-base {{ $product->stock_quantity <= 10 ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ number_format($product->stock_quantity) }} units
                        </span>
                    </div>

                    <a href="{{ route('inventory.index', ['search' => $product->sku]) }}" class="w-full py-2 bg-surface hover:bg-slate-200 border border-border-quiet rounded-lg text-xs font-bold text-text-rich shadow-2xs flex items-center justify-center gap-1.5 transition-colors">
                        <span class="material-symbols-outlined text-sm">tune</span>
                        <span>Adjust Stock in Inventory</span>
                    </a>
                </div>

                <!-- Commercial Document Links -->
                <div class="space-y-2 pt-2 border-t border-border-quiet text-xs font-bold">
                    <a href="{{ route('sales.quotes.create') }}" class="w-full py-2.5 bg-primary hover:bg-primary-container text-white rounded-lg shadow-sm flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-base">request_quote</span>
                        <span>Create Quote with this Item</span>
                    </a>

                    <a href="{{ route('invoices.create') }}" class="w-full py-2.5 bg-surface hover:bg-bg-subtle border border-border-quiet text-text-rich rounded-lg shadow-2xs flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-base">receipt_long</span>
                        <span>Issue Invoice with this Item</span>
                    </a>

                    <a href="{{ route('purchases.bills.create') }}" class="w-full py-2.5 bg-surface hover:bg-bg-subtle border border-border-quiet text-text-rich rounded-lg shadow-2xs flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-base">shopping_bag</span>
                        <span>Record Supplier Bill to Restock</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
