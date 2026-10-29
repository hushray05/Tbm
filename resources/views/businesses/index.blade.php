@extends('layouts.app')

@section('title', 'My Businesses & Organizations • TBM Enterprise')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">My Businesses & Workspaces</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-primary/10 text-primary border border-primary/20 font-numeric">
                    {{ $businesses->count() }} {{ Str::plural('Entity', $businesses->count()) }}
                </span>
            </div>
            <p class="text-xs text-secondary mt-1">Multi-tenant management: view your registered commercial enterprises, branch locations, and switch workspace contexts.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('businesses.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
                <span class="material-symbols-outlined text-base">add_business</span>
                <span>Register New Business</span>
            </a>
        </div>
    </div>

    <!-- Active Workspace Spotlight Banner -->
    @if($activeBusiness)
        <div class="bg-gradient-to-r from-primary/10 via-surface to-surface border-2 border-primary/30 rounded-2xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center font-headline font-black text-xl shadow-md shrink-0">
                        {{ strtoupper(substr($activeBusiness->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                Currently Active Workspace
                            </span>
                            <span class="text-xs text-secondary font-numeric">ID: #BIZ-{{ str_pad($activeBusiness->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h2 class="font-headline font-bold text-xl text-text-rich mt-0.5">{{ $activeBusiness->name }}</h2>
                        <div class="flex items-center gap-3 text-xs text-secondary mt-0.5 flex-wrap">
                            <span>{{ $activeBusiness->city ?? 'Kampala' }}, {{ $activeBusiness->country ?? 'Uganda' }}</span>
                            <span>•</span>
                            <span>Currency: <strong class="text-text-rich">{{ $activeBusiness->currency_code ?? 'UGX' }} ({{ $activeBusiness->currency_symbol ?? 'USh' }})</strong></span>
                            @if($activeBusiness->tin_number)
                                <span>•</span>
                                <span>TIN: <strong class="text-text-rich font-numeric">{{ $activeBusiness->tin_number }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl bg-surface hover:bg-bg-subtle text-primary border border-border-quiet text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('invoices.create') }}" class="px-3.5 py-2 rounded-xl bg-surface hover:bg-bg-subtle text-primary border border-border-quiet text-xs font-bold transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">receipt_long</span>
                        <span>New Invoice</span>
                    </a>
                    <a href="{{ route('pos') }}" class="px-3.5 py-2 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-base">point_of_sale</span>
                        <span>Open POS</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- All Businesses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($businesses as $b)
            @php $isActive = $activeBusiness && $activeBusiness->id === $b->id; @endphp
            <div class="bg-surface rounded-2xl border {{ $isActive ? 'border-primary ring-2 ring-primary/20' : 'border-border-quiet hover:border-primary/40' }} p-5 tbm-shadow flex flex-col justify-between transition-all hover:translate-y-[-2px]">
                <div class="space-y-4">
                    <!-- Business Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-xl {{ $isActive ? 'bg-primary text-white' : 'bg-bg-subtle text-primary border border-border-quiet' }} flex items-center justify-center font-headline font-bold text-base shadow-sm shrink-0">
                                {{ strtoupper(substr($b->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-headline font-bold text-base text-text-rich truncate" title="{{ $b->name }}">
                                    {{ $b->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[11px] text-secondary truncate">{{ $b->city ?? 'Uganda' }}</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded bg-bg-subtle border border-border-quiet font-bold text-secondary uppercase">{{ $b->currency_code ?? 'UGX' }}</span>
                                </div>
                            </div>
                        </div>

                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase shrink-0 {{ $b->status === 'active' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                            {{ $b->status ?? 'active' }}
                        </span>
                    </div>

                    <!-- Contact Details -->
                    <div class="p-3 bg-bg-subtle rounded-xl border border-border-quiet space-y-1.5 text-xs text-secondary">
                        @if($b->email)
                            <div class="flex items-center gap-2 truncate">
                                <span class="material-symbols-outlined text-sm text-primary shrink-0">mail</span>
                                <span class="truncate">{{ $b->email }}</span>
                            </div>
                        @endif
                        @if($b->phone)
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-primary shrink-0">call</span>
                                <span class="font-numeric">{{ $b->phone }}</span>
                            </div>
                        @endif
                        @if($b->address)
                            <div class="flex items-center gap-2 truncate">
                                <span class="material-symbols-outlined text-sm text-primary shrink-0">location_on</span>
                                <span class="truncate">{{ $b->address }}</span>
                            </div>
                        @endif
                        @if($b->tin_number)
                            <div class="flex items-center gap-2 text-[11px]">
                                <span class="material-symbols-outlined text-sm text-primary shrink-0">badge</span>
                                <span>TIN: <strong class="text-text-rich font-numeric">{{ $b->tin_number }}</strong></span>
                            </div>
                        @endif
                    </div>

                    <!-- 3-Metric Counter Grid -->
                    <div class="grid grid-cols-3 gap-2 py-1">
                        <div class="bg-bg-subtle/70 rounded-xl p-2.5 text-center border border-border-quiet">
                            <span class="text-[10px] uppercase font-bold text-secondary block">Branches</span>
                            <span class="font-headline font-bold text-base text-text-rich font-numeric">
                                {{ $b->locations->count() ?: 1 }}
                            </span>
                        </div>
                        <div class="bg-bg-subtle/70 rounded-xl p-2.5 text-center border border-border-quiet">
                            <span class="text-[10px] uppercase font-bold text-secondary block">Products</span>
                            <span class="font-headline font-bold text-base text-text-rich font-numeric">
                                {{ $b->products_count ?? 0 }}
                            </span>
                        </div>
                        <div class="bg-bg-subtle/70 rounded-xl p-2.5 text-center border border-border-quiet">
                            <span class="text-[10px] uppercase font-bold text-secondary block">Invoices</span>
                            <span class="font-headline font-bold text-base text-text-rich font-numeric">
                                {{ $b->invoices_count ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Footer Card Actions -->
                <div class="pt-4 mt-4 border-t border-border-quiet flex items-center justify-between gap-2">
                    @if($isActive)
                        <div class="flex-1 py-2 px-3 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-emerald-600">check_circle</span>
                            <span>Active Workspace</span>
                        </div>
                    @else
                        <form action="{{ route('businesses.switch') }}" method="POST" class="flex-1 m-0">
                            @csrf
                            <input type="hidden" name="business_id" value="{{ $b->id }}">
                            <button type="submit" class="w-full py-2 px-3 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-sm">swap_horiz</span>
                                <span>Switch Workspace</span>
                            </button>
                        </form>
                    @endif

                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.businesses.edit', $b) }}" class="p-2 rounded-xl bg-bg-subtle hover:bg-surface border border-border-quiet text-secondary hover:text-primary transition-all" title="Edit Business Settings">
                            <span class="material-symbols-outlined text-base">settings</span>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-surface rounded-2xl border border-border-quiet p-8 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-3xl">storefront</span>
                </div>
                <div>
                    <h3 class="font-headline font-bold text-lg text-text-rich">No Businesses Registered</h3>
                    <p class="text-xs text-secondary mt-1 max-w-sm mx-auto">Register your commercial company or store to start issuing invoices, tracking inventory, and managing branch outlets.</p>
                </div>
                <a href="{{ route('businesses.create') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md transition-all">
                    <span class="material-symbols-outlined text-base">add</span>
                    <span>Create Your First Business</span>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection