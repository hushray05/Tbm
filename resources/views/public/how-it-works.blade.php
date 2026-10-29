@extends('layouts.public')

@section('title', 'How It Works • TBM Enterprise Suite')

@section('content')
<div class="space-y-0">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-primary/5 via-transparent to-transparent py-16 md:py-24 lg:py-32">
        <div class="absolute inset-0 hero-gradient-overlay"></div>
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20 mb-6 inline-block">
                <span class="material-symbols-outlined text-sm">route</span>
                {{ $page->content['hero_badge'] ?? 'Get Started in Minutes' }}
            </span>
            
            <h1 class="font-headline text-4xl md:text-5xl lg:text-6xl font-extrabold text-text-rich leading-tight text-balance mb-6">
                {{ $page->content['hero_title'] ?? 'From sign-up to first sale in under 15 minutes' }}
            </h1>
            
            <p class="text-secondary text-lg leading-relaxed max-w-3xl mx-auto">
                {{ $page->content['hero_text'] ?? 'No implementation consultants, no months of setup. TBM is designed for Ugandan businesses to start transacting immediately — with URA compliance and mobile money integrated from day one.' }}
            </p>
        </div>
    </section>

    <!-- Steps -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="space-y-10">
                @php
                    $steps = [
                        [
                            'number' => '01',
                            'title' => 'Create Your Account',
                            'description' => 'Sign up with your business email. Choose your plan (Starter, Professional, or Premium). Your 30-day free trial starts instantly — no credit card required.',
                            'icon' => 'person_add',
                            'details' => ['Business name & TIN', 'Select plan', 'Email verification', 'Instant access'],
                        ],
                        [
                            'number' => '02',
                            'title' => 'Set Up Your Business',
                            'description' => 'Enter your business details: legal name, TIN number, address, and currency (UGX). Add your branches or locations. TBM auto-configures URA tax rates (18% VAT, PAYE brackets, NSSF 15%).',
                            'icon' => 'business',
                            'details' => ['TIN & legal name', 'Branches/locations', 'UGX currency', 'Auto tax config'],
                        ],
                        [
                            'number' => '03',
                            'title' => 'Connect Mobile Money',
                            'description' => 'Link your MTN Mobile Money or Airtel Money merchant account in two clicks. TBM handles the API integration — you just authorize. Test with a sandbox transaction.',
                            'icon' => 'payments',
                            'details' => ['MTN MoMo connect', 'Airtel Money connect', 'Sandbox test', 'Live in minutes'],
                        ],
                        [
                            'number' => '04',
                            'title' => 'Add Products & Stock',
                            'description' => 'Import your catalog via CSV or add manually. Set opening stock per warehouse. Barcode labels print with one click. Stock moves automatically on every sale and purchase.',
                            'icon' => 'inventory_2',
                            'details' => ['CSV import', 'Warehouse stock', 'Barcode labels', 'Auto stock moves'],
                        ],
                        [
                            'number' => '05',
                            'title' => 'Make Your First Sale',
                            'description' => 'Open the POS, scan a product, choose payment (Cash, MoMo, Card). The receipt prints with 18% VAT, stock decrements, and the invoice is ready for URA. Done.',
                            'icon' => 'point_of_sale',
                            'details' => ['POS interface', 'MoMo/Card/Cash', 'VAT receipt', 'Auto invoice'],
                        ],
                        [
                            'number' => '06',
                            'title' => 'Stay Compliant Automatically',
                            'description' => 'At month-end, your VAT return, PAYE schedule, and NSSF contribution are pre-calculated. Download URA-ready reports with PRN numbers. File in minutes, not days.',
                            'icon' => 'verified',
                            'details' => ['VAT return', 'PAYE schedule', 'NSSF return', 'PRN generation'],
                        ],
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="flex flex-col lg:flex-row gap-8 items-start {{ $index % 2 === 1 ? 'lg:flex-row-reverse' : '' }}">
                        <!-- Step Number & Icon -->
                        <div class="flex-shrink-0 w-20 lg:w-24 text-center lg:text-right pt-2">
                            <span class="font-headline text-4xl font-extrabold text-primary/20">{{ $step['number'] }}</span>
                            <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto lg:mx-0 lg:ml-auto mt-3">
                                <span class="material-symbols-outlined text-primary text-2xl">{{ $step['icon'] }}</span>
                            </div>
                        </div>

                        <!-- Step Content -->
                        <div class="flex-1 space-y-4">
                            <h3 class="font-headline text-xl font-bold text-text-rich">{{ $step['title'] }}</h3>
                            <p class="text-secondary text-base leading-relaxed">{{ $step['description'] }}</p>
                            
                            <div class="flex flex-wrap gap-2">
                                @foreach($step['details'] as $detail)
                                    <span class="px-3 py-1.5 rounded-lg bg-bg-subtle border border-border-quiet text-xs font-semibold text-text-rich">
                                        {{ $detail }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Visual: Getting Started -->
    <section class="py-16 md:py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                        Simple Setup
                    </span>
                    <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">From sign-up to first sale in minutes</h2>
                    <p class="text-secondary text-base leading-relaxed">
                        No implementation consultants, no months of setup. TBM is designed for Ugandan businesses to start 
                        transacting immediately — with URA compliance and mobile money integrated from day one.
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <span class="font-headline text-3xl font-extrabold text-primary block">2 min</span>
                            <span class="text-xs text-secondary">Sign-up</span>
                        </div>
                        <div class="w-px h-12 bg-border-quiet"></div>
                        <div class="text-center">
                            <span class="font-headline text-3xl font-extrabold text-primary block">15 min</span>
                            <span class="text-xs text-secondary">First sale</span>
                        </div>
                        <div class="w-px h-12 bg-border-quiet"></div>
                        <div class="text-center">
                            <span class="font-headline text-3xl font-extrabold text-primary block">30 days</span>
                            <span class="text-xs text-secondary">Free trial</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?fm=jpg&q=60&w=1200&auto=format&fit=crop" alt="Getting started with TBM" class="w-full h-auto rounded-2xl shadow-xl border border-border-quiet" loading="lazy">
                    <div class="absolute -bottom-4 -right-4 md:-bottom-6 md:-right-6 bg-white/90 backdrop-blur-sm border border-border-quiet rounded-xl p-4 tbm-shadow flex items-center gap-3 shadow-xl">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-xl">rocket_launch</span>
                        </div>
                        <div>
                            <span class="font-headline font-bold text-text-rich text-sm block">Quick Start</span>
                            <span class="text-xs text-secondary">Go live today</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What You Get -->
    <section class="py-16 md:py-24 lg:py-32 bg-bg-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    What You Get From Day One
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">No hidden setup, no surprises</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
                @php
                    $included = [
                        ['icon' => 'check_circle', 'title' => 'Full Module Access', 'text' => 'All 13 modules unlocked during trial'],
                        ['icon' => 'check_circle', 'title' => 'UGX Native Billing', 'text' => 'Priced and transacted in Ugandan Shillings'],
                        ['icon' => 'check_circle', 'title' => 'MTN & Airtel Money', 'text' => 'Integrated mobile money checkout'],
                        ['icon' => 'check_circle', 'title' => 'URA Tax Engine', 'text' => 'VAT, PAYE, NSSF, LED calculated'],
                        ['icon' => 'check_circle', 'title' => 'Unlimited Users (Trial)', 'text' => 'Add your whole team free'],
                        ['icon' => 'check_circle', 'title' => 'Email & Chat Support', 'text' => 'Kampala-based support team'],
                        ['icon' => 'check_circle', 'title' => 'Data Export', 'text' => 'CSV/Excel/PDF anytime'],
                        ['icon' => 'check_circle', 'title' => 'Cancel Anytime', 'text' => 'No lock-in, no questions'],
                    ];
                @endphp

                @foreach($included as $item)
                    <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow text-center space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center mx-auto">
                            <span class="material-symbols-outlined text-success text-xl">{{ $item['icon'] }}</span>
                        </div>
                        <h4 class="font-headline font-bold text-text-rich">{{ $item['title'] }}</h4>
                        <p class="text-sm text-secondary">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-primary py-16 md:py-24 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center text-white">
            <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-balance mb-4">Ready to see it in action?</h2>
            <p class="text-lg text-primary-fixed-dim mb-8 max-w-2xl mx-auto">Create your free account in 2 minutes. Start selling in 15.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-primary hover:bg-primary-fixed px-8 py-4 rounded-xl text-sm font-bold shadow-md transition-all">
                <span class="material-symbols-outlined text-base">rocket_launch</span>
                <span>Start Free Trial</span>
            </a>
        </div>
    </section>

</div>
@endsection