@extends('layouts.admin')

@section('title', 'Currency Engine & FX Clearing • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header & Live Sync CTA -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Multi-Currency Engine & FX</h1>
            <p class="text-sm text-secondary mt-0.5">Manage exchange conversion metrics for all 54 African currencies + global benchmarks.</p>
        </div>
        <a href="{{ route('admin.currencies.sync') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
            <span class="material-symbols-outlined text-sm">sync</span>
            <span>Sync Live FX API Rates</span>
        </a>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase block mb-1">Supported Currencies</span>
            <div class="font-headline text-2xl font-bold text-text-rich font-numeric">{{ $totalCurrencies }} Currencies</div>
            <span class="text-xs text-secondary mt-1 block">African & Global Matrix</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase block mb-1">Active FX Pairs</span>
            <div class="font-headline text-2xl font-bold text-success font-numeric">{{ $activeCurrencies }} Enabled</div>
            <span class="text-xs text-success mt-1 block">Live in dropdown selector</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase block mb-1">Exchange Base Reference</span>
            <div class="font-headline text-2xl font-bold text-primary font-numeric">1.00 USD ($)</div>
            <span class="text-xs text-secondary mt-1 block">Sub-cent integer math</span>
        </div>
    </div>

    <!-- Search Filter Bar -->
    <div class="bg-surface border border-border-quiet rounded-xl p-4 tbm-shadow flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form action="{{ route('admin.currencies.index') }}" method="GET" class="relative flex-1 max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Filter by code (NGN, ZAR, KES...), country, name..." class="w-full h-9 pl-9 pr-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary text-xs text-text-rich outline-none">
        </form>

        <span class="text-xs text-secondary">Exchange precision: 6 decimal places</span>
    </div>

    <!-- Currencies Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Flag</th>
                        <th class="px-4 py-3.5">Code</th>
                        <th class="px-4 py-3.5">Currency Name</th>
                        <th class="px-4 py-3.5">Country / Region</th>
                        <th class="px-4 py-3.5">Symbol</th>
                        <th class="px-4 py-3.5 text-right">Exchange Rate (Per 1 USD)</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($currencies as $curr)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <img src="{{ $curr->flag_image_url }}" srcset="{{ $curr->flag_image_url_2x }} 2x" alt="{{ $curr->country }}" class="w-8 h-5 object-cover rounded shadow-sm border border-border-quiet" loading="lazy">
                            </td>
                            <td class="px-4 py-3.5 font-bold font-numeric text-primary text-sm">{{ $curr->code }}</td>
                            <td class="px-4 py-3.5 font-semibold text-text-rich">{{ $curr->name }}</td>
                            <td class="px-4 py-3.5 text-secondary">{{ $curr->country }}</td>
                            <td class="px-4 py-3.5 font-numeric font-bold text-text-rich">{{ $curr->symbol }}</td>
                            <td class="px-4 py-3.5 text-right font-numeric font-bold text-text-rich">
                                {{ number_format($curr->exchange_rate, 4) }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase {{ $curr->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $curr->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.currencies.edit', $curr) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Edit Rate Override">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>

                                    @if($curr->code !== 'USD')
                                        <form action="{{ route('admin.currencies.toggle', $curr) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Toggle Active">
                                                <span class="material-symbols-outlined text-base">{{ $curr->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-secondary">
                                No currency records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($currencies->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $currencies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
