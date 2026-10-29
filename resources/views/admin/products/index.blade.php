@extends('layouts.admin')

@section('title', 'Products Management CRUD • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Products Management CRUD</h1>
            <p class="text-sm text-secondary mt-0.5">Maintain catalog items, inventory levels, and base integer cents pricing.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            <span>Create New Product</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-surface border border-border-quiet rounded-xl p-4 tbm-shadow flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form action="{{ route('admin.products.index') }}" method="GET" class="relative flex-1 max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, SKU, category..." class="w-full h-9 pl-9 pr-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary text-xs text-text-rich outline-none">
        </form>

        <div class="flex items-center gap-2 text-xs text-secondary">
            <span>Total Catalog Items: <strong class="text-text-rich font-numeric">{{ $products->total() }}</strong></span>
        </div>
    </div>

    <!-- Products Data Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Item</th>
                        <th class="px-4 py-3.5">Product Name</th>
                        <th class="px-4 py-3.5">Category</th>
                        <th class="px-4 py-3.5">Stock</th>
                        <th class="px-4 py-3.5 text-right">Price (USD / Converted)</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">CRUD Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($products as $p)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    @if($p->image_url)
                                        <img src="{{ asset($p->image_url) }}" alt="{{ $p->name }}" class="w-10 h-10 rounded-lg object-cover border border-border-quiet shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-bg-subtle border border-border-quiet flex items-center justify-center shrink-0 text-secondary">
                                            <span class="material-symbols-outlined text-lg">inventory_2</span>
                                        </div>
                                    @endif
                                    <span class="font-numeric font-bold text-primary">{{ $p->sku }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-text-rich">{{ $p->name }}</td>
                            <td class="px-4 py-3.5 text-secondary">{{ $p->category }}</td>
                            <td class="px-4 py-3.5 font-numeric text-text-rich">{{ $p->stock_quantity }} units</td>
                            <td class="px-4 py-3.5 text-right font-numeric font-bold text-text-rich">
                                {{ $currencyService->format($p->price_cents) }}
                                <span class="block text-[10px] text-secondary font-normal font-numeric">(${{ number_format($p->price_cents / 100, 2) }} USD)</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $p->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Edit Item">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-danger hover:bg-red-50 transition-colors" title="Delete Item">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-secondary">
                                No products found. Click "Create New Product" to populate the catalog.
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
</div>
@endsection
