@extends('layouts.app')

@section('title', 'POS Terminal & Checkout • TBM Enterprise')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">POS Terminal & Queue</h1>
            <p class="text-sm text-secondary mt-0.5">Quick item dispatch, hardware billing, and multi-currency transaction terminal.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-secondary">Active FX:</span>
            <span class="px-2.5 py-1 rounded-full bg-primary-fixed text-primary font-bold text-xs flex items-center gap-1.5">
                <img src="{{ $currentCurrency->flag_image_url }}" srcset="{{ $currentCurrency->flag_image_url_2x }} 2x" alt="{{ $currentCurrency->country }}" class="w-5 h-3.5 object-cover rounded-sm shadow-sm" loading="lazy">
                {{ $currentCurrency->code }}
            </span>
        </div>
    </div>

    <!-- POS Split Grid (Matching Stitch POS Checkout screen de64cac34708466b94baf6c87139e8c0) -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Left 8 Cols: Product / Task Fast Selector -->
        <div class="col-span-12 lg:col-span-7 space-y-6">
            <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow space-y-4">
                <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                    <span class="font-headline font-bold text-sm text-text-rich uppercase tracking-wider">Catalog Terminal Items</span>
                    <span class="text-xs text-secondary">Click to append to register</span>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[520px] overflow-y-auto pr-1">
                    @foreach($products as $prod)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="product">
                            <input type="hidden" name="id" value="{{ $prod->id }}">
                            <button type="submit" class="w-full text-left p-3 rounded-xl border border-border-quiet hover:border-primary/50 hover:bg-bg-subtle/50 transition-all flex gap-3 h-28 bg-surface group items-center">
                                @if($prod->image_url)
                                    <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-slate-900 border border-border-quiet">
                                        <img src="{{ asset($prod->image_url) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    </div>
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-bg-subtle border border-border-quiet flex items-center justify-center shrink-0 text-secondary">
                                        <span class="material-symbols-outlined text-2xl">inventory_2</span>
                                    </div>
                                @endif
                                <div class="flex flex-col justify-between h-full flex-1 min-w-0 py-0.5">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <span class="font-bold text-xs text-text-rich group-hover:text-primary transition-colors line-clamp-1">{{ $prod->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-secondary font-numeric">{{ $prod->sku }}</span>
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <span class="text-[10px] text-secondary truncate max-w-[80px]">{{ $prod->category }}</span>
                                        <span class="font-headline font-bold text-xs md:text-sm text-primary font-numeric">
                                            {{ $prod->formatted_price }}
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @endforeach

                    @foreach($tasks as $t)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="task">
                            <input type="hidden" name="id" value="{{ $t->id }}">
                            <button type="submit" class="w-full text-left p-3 rounded-xl border border-border-quiet hover:border-primary/50 hover:bg-bg-subtle/50 transition-all flex gap-3 h-28 bg-surface group items-center">
                                <div class="w-20 h-20 rounded-lg bg-purple-900/10 border border-purple-200 flex flex-col items-center justify-center shrink-0 text-purple-700">
                                    <span class="material-symbols-outlined text-2xl">assignment</span>
                                    <span class="text-[9px] font-bold uppercase mt-0.5">Task</span>
                                </div>
                                <div class="flex flex-col justify-between h-full flex-1 min-w-0 py-0.5">
                                    <div>
                                        <span class="font-bold text-xs text-text-rich group-hover:text-primary transition-colors line-clamp-1">{{ $t->title }}</span>
                                        <span class="text-[10px] text-secondary font-numeric">#{{ $t->id }}</span>
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <span class="text-[10px] text-purple-700 bg-purple-100 px-1.5 py-0.5 rounded font-semibold truncate max-w-[90px]">{{ $t->project_name }}</span>
                                        <span class="font-headline font-bold text-xs md:text-sm text-primary font-numeric">
                                            {{ $currencyService->format($t->price_cents) }}
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right 5 Cols: Register Terminal Cart -->
        <div class="col-span-12 lg:col-span-5 space-y-6">
            <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow space-y-5 sticky top-20">
                <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">receipt</span>
                        <span class="font-headline font-bold text-base text-text-rich">Register Register</span>
                    </div>
                    @if(count($cart) > 0)
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-danger font-semibold hover:underline">Clear</button>
                        </form>
                    @endif
                </div>

                <!-- Items in Register -->
                <div class="max-h-60 overflow-y-auto space-y-2.5 divide-y divide-border-quiet/50">
                    @forelse($cart as $key => $item)
                        <div class="pt-2.5 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                @if(!empty($item['image_url']))
                                    <img src="{{ asset($item['image_url']) }}" alt="{{ $item['name'] }}" class="w-8 h-8 rounded object-cover border border-border-quiet shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded bg-bg-subtle border border-border-quiet flex items-center justify-center shrink-0 text-secondary">
                                        <span class="material-symbols-outlined text-sm">{{ ($item['type'] ?? '') === 'task' ? 'assignment' : 'inventory_2' }}</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span class="font-semibold text-text-rich truncate block">{{ $item['name'] }}</span>
                                    <span class="text-[11px] text-secondary font-numeric">{{ $item['quantity'] }} × {{ $currencyService->format($item['price_cents']) }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-headline font-bold text-text-rich font-numeric block">
                                    {{ $currencyService->format($item['price_cents'] * $item['quantity']) }}
                                </span>
                                <form action="{{ route('cart.remove', $key) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-danger hover:underline">Remove</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-secondary">
                            <span class="material-symbols-outlined text-3xl text-outline mb-1">point_of_sale</span>
                            <p class="text-xs">Register is currently empty.<br>Click items on the left to add.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Calculation Summary -->
                <div class="pt-4 border-t border-border-quiet space-y-2 text-xs">
                    <div class="flex justify-between text-secondary">
                        <span>Subtotal:</span>
                        <span class="font-numeric font-semibold text-text-rich">{{ $currencyService->format($subtotalCents) }}</span>
                    </div>
                    <div class="flex justify-between text-secondary">
                        <span>VAT (8%):</span>
                        <span class="font-numeric font-semibold text-text-rich">{{ $currencyService->format($taxCents) }}</span>
                    </div>
                    <div class="flex justify-between items-baseline pt-2 border-t border-border-quiet text-sm font-bold text-text-rich">
                        <span>Total Due:</span>
                        <span class="font-headline text-2xl text-primary font-numeric">{{ $currencyService->format($totalCents) }}</span>
                    </div>
                </div>

                <!-- Checkout Trigger Form -->
                @if(count($cart) > 0)
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-primary hover:bg-primary-container text-white py-3 px-4 rounded-lg font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">payments</span>
                            <span>Complete Settlement ({{ $currentCurrency->code }})</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
