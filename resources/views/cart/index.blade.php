@extends('layouts.app')

@section('title', 'Task Queue & Shopping Cart • TBM Enterprise')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Task Queue & Shopping Cart</h1>
            <p class="text-sm text-secondary mt-0.5">Review items, tasks, and services queued for enterprise settlement.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Continue Browsing</span>
        </a>
    </div>

    @if(count($cart) > 0)
        <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden">
            <div class="p-5 border-b border-border-quiet flex items-center justify-between bg-bg-subtle">
                <span class="font-headline font-bold text-sm text-text-rich">Queued Items ({{ count($cart) }})</span>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-danger font-semibold hover:underline">Clear Entire Queue</button>
                </form>
            </div>

            <div class="divide-y divide-border-quiet p-6 space-y-4">
                @foreach($cart as $key => $item)
                    <div class="pt-4 first:pt-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-primary/10 text-primary uppercase">{{ $item['type'] }}</span>
                            <h3 class="font-bold text-base text-text-rich">{{ $item['name'] }}</h3>
                            <span class="text-xs text-secondary font-numeric">Reference: {{ $item['sku'] }}</span>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-6">
                            <div class="text-right">
                                <span class="text-xs text-secondary font-numeric block">{{ $item['quantity'] }} × {{ $currencyService->format($item['price_cents']) }}</span>
                                <span class="font-headline font-bold text-lg text-primary font-numeric">
                                    {{ $currencyService->format($item['price_cents'] * $item['quantity']) }}
                                </span>
                            </div>

                            <form action="{{ route('cart.remove', $key) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 text-secondary hover:text-danger hover:bg-red-50 rounded-lg transition-colors" title="Remove Item">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-6 bg-bg-subtle border-t border-border-quiet flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="text-xs text-secondary">
                    <span>Active Settlement Currency: <strong class="text-primary font-bold">{{ $currentCurrency->code }} ({{ $currentCurrency->symbol }})</strong></span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-6 text-right">
                    <div>
                        <span class="text-xs text-secondary block">Grand Total with Tax (8%):</span>
                        <span class="font-headline text-2xl font-bold text-primary font-numeric">{{ $currencyService->format($totalCents) }}</span>
                    </div>

                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary-container text-white px-6 py-3 rounded-lg text-sm font-bold shadow-sm transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <span>Proceed to Checkout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="bg-surface border border-border-quiet rounded-xl p-12 text-center text-secondary tbm-shadow space-y-4">
            <span class="material-symbols-outlined text-5xl text-outline">shopping_cart</span>
            <h2 class="font-headline font-bold text-lg text-text-rich">Your checkout queue is empty</h2>
            <p class="text-xs max-w-sm mx-auto">Explore our operational task catalog or enterprise software subscriptions to populate your checkout queue.</p>
            <div class="pt-2 flex justify-center gap-3">
                <a href="{{ route('tasks.index') }}" class="bg-primary text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm">
                    Browse Tasks
                </a>
                <a href="{{ route('products.index') }}" class="bg-surface hover:bg-bg-subtle text-primary border border-border-quiet text-xs font-bold px-4 py-2 rounded-lg shadow-sm">
                    Products Catalog
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
