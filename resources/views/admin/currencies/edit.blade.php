@extends('layouts.admin')

@section('title', 'Edit Currency FX: ' . $currency->code . ' • TBM Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Edit FX Rate: {{ $currency->code }}</h1>
            <p class="text-xs text-secondary mt-0.5">Manual override for {{ $currency->name }} exchange metric.</p>
        </div>
        <a href="{{ route('admin.currencies.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Currencies</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('admin.currencies.update', $currency) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-3 p-3 bg-bg-subtle rounded-lg border border-border-quiet mb-2">
                <img src="{{ $currency->flag_image_url }}" srcset="{{ $currency->flag_image_url_2x }} 2x" alt="{{ $currency->country }}" class="w-12 h-8 object-cover rounded shadow-md border border-border-quiet" loading="lazy">
                <div>
                    <span class="font-bold text-sm text-text-rich block">{{ $currency->code }} - {{ $currency->name }}</span>
                    <span class="text-xs text-secondary">{{ $currency->country }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Currency Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $currency->name) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Symbol <span class="text-danger">*</span></label>
                    <input type="text" name="symbol" required value="{{ old('symbol', $currency->symbol) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Country / Region <span class="text-danger">*</span></label>
                    <input type="text" name="country" required value="{{ old('country', $currency->country) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Flag Emoji (backup display)</label>
                    <input type="text" name="flag" value="{{ old('flag', $currency->flag) }}" placeholder="e.g. 🇳🇬" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                    <p class="text-[10px] text-secondary mt-0.5">Flag images auto-generated from currency code via flagcdn.com</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Exchange Rate (Units per 1.00 USD) <span class="text-danger">*</span></label>
                <input type="number" step="0.000001" name="exchange_rate" required value="{{ old('exchange_rate', $currency->exchange_rate) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none focus:border-primary">
                <p class="text-[11px] text-secondary mt-1">e.g. 1520.500000 NGN per 1 USD, 18.350000 ZAR per 1 USD.</p>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 text-xs font-semibold text-text-rich cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $currency->is_active ? 'checked' : '' }} class="rounded border-border-quiet text-primary focus:ring-primary">
                    <span>Enabled in Live Session Currency Dropdown</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
                <a href="{{ route('admin.currencies.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-secondary">Cancel</a>
                <button type="submit" class="bg-primary text-white font-bold text-xs px-5 py-2 rounded-lg shadow-sm hover:bg-primary-container">Save Currency Parameters</button>
            </div>
        </form>
    </div>
</div>
@endsection
