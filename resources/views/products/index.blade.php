@extends('layouts.app')

@section('title', 'Products & Items Catalog • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Products & Items Catalog</h1>
            <p class="text-sm text-secondary mt-1">Manage catalog items, pricing, photos, and live inventory connection.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('products.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-base">add_circle</span>
                <span>Add New Item</span>
            </a>
        </div>
    </div>

    <!-- Category Filters & Search -->
    <div class="bg-surface border border-border-quiet rounded-xl p-4 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Categories Pill Filter -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('products.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ empty($category) ? 'bg-primary text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                All Items ({{ $totalProductsCount }})
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat]) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $category === $cat ? 'bg-primary text-white' : 'bg-bg-subtle text-secondary hover:bg-slate-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- Search Box -->
        <form action="{{ route('products.index') }}" method="GET" class="relative max-w-xs w-full">
            @if($category)
                <input type="hidden" name="category" value="{{ $category }}">
            @endif
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search items, SKUs..." class="w-full h-9 pl-9 pr-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary text-xs text-text-rich outline-none">
        </form>
    </div>

    <!-- Product Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-surface border border-border-quiet rounded-xl overflow-hidden shadow-xs flex flex-col justify-between hover:border-primary/50 transition-all group">
                <!-- Product Image Banner -->
                <div class="h-48 w-full bg-slate-900 overflow-hidden relative group-hover:opacity-95 transition-opacity flex items-center justify-center">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-bg-subtle text-secondary">
                            <span class="material-symbols-outlined text-5xl">inventory_2</span>
                        </div>
                    @endif
                    <span class="absolute top-3 left-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-surface/90 backdrop-blur text-primary uppercase shadow-sm">
                        {{ $product->category }}
                    </span>
                    <span class="absolute top-3 right-3 text-[10px] font-numeric font-semibold px-2 py-1 rounded-md bg-black/60 backdrop-blur text-white">
                        {{ $product->sku }}
                    </span>
                </div>

                <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-headline font-bold text-base text-text-rich group-hover:text-primary transition-colors truncate">
                                <a href="{{ route('products.show', $product) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            @if($product->stock_quantity <= 0)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 shrink-0">Out of Stock</span>
                            @elseif($product->stock_quantity <= 10)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 shrink-0">Low Stock ({{ $product->stock_quantity }})</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 shrink-0">{{ $product->stock_quantity }} units</span>
                            @endif
                        </div>

                        <p class="text-xs text-secondary leading-relaxed mt-2 line-clamp-2">
                            {{ $product->description ?: 'Standard catalog commercial item.' }}
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-border-quiet flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-secondary uppercase tracking-wider block">Price (18% VAT inc.)</span>
                            <span class="font-headline text-lg font-bold text-primary font-numeric">
                                {{ $product->formatted_price }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="p-2 rounded-lg border border-border-quiet text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Edit Item">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <a href="{{ route('products.show', $product) }}" class="p-2 rounded-lg bg-bg-subtle hover:bg-slate-200 text-text-rich text-xs font-bold transition-colors flex items-center gap-1" title="View Details">
                                <span class="material-symbols-outlined text-base">visibility</span>
                                <span>Details</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-surface border border-border-quiet rounded-2xl p-8 space-y-4">
                <span class="material-symbols-outlined text-6xl text-secondary block">inventory_2</span>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-text-rich">Your Catalog is Empty</h3>
                    <p class="text-xs text-secondary max-w-md mx-auto">You haven't added any products or items yet. Add your first item with image, SKU, and buying/selling pricing to start selling and tracking stock.</p>
                </div>
                <div>
                    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-md">
                        <span class="material-symbols-outlined text-base">add_circle</span>
                        <span>Add Your First Item</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="p-4 bg-surface border border-border-quiet rounded-xl">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
