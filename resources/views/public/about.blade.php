@extends('layouts.public')

@section('title', 'About TBM Enterprise • Uganda Business Management OS')

@section('content')
<div class="space-y-0">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-primary/5 via-transparent to-transparent py-16 md:py-24 lg:py-32">
        <div class="absolute inset-0 hero-gradient-overlay"></div>
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20 mb-6 inline-block">
                <span class="material-symbols-outlined text-sm">business_center</span>
                {{ $page->content['hero_badge'] ?? 'About TBM Enterprise' }}
            </span>
            
            <h1 class="font-headline text-4xl md:text-5xl lg:text-6xl font-extrabold text-text-rich leading-tight text-balance mb-6">
                {{ $page->content['hero_title'] ?? 'Built in Uganda, for African enterprises' }}
            </h1>
            
            <p class="text-secondary text-lg leading-relaxed max-w-3xl mx-auto">
                {{ $page->content['hero_text'] ?? "We're on a mission to make enterprise-grade business management accessible to every Ugandan entrepreneur — from a boda-boda spare parts shop in Nateete to a regional logistics firm with depots across East Africa." }}
            </p>
        </div>
    </section>

    <!-- Mission / Story -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?fm=jpg&q=60&w=1200&auto=format&fit=crop" alt="TBM team collaborating" class="w-full h-auto rounded-2xl shadow-xl border border-border-quiet" loading="lazy">
                    <div class="absolute -bottom-4 -left-4 md:-bottom-6 md:-left-6 bg-white/90 backdrop-blur-sm border border-border-quiet rounded-xl p-4 tbm-shadow flex items-center gap-3 shadow-xl">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-xl">groups</span>
                        </div>
                        <div>
                            <span class="font-headline font-bold text-text-rich text-sm block">247+ Businesses</span>
                            <span class="text-xs text-secondary">Across Uganda</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                        Our Story
                    </span>
                    <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Why we built TBM</h2>
                    <p class="text-secondary text-base leading-relaxed">
                        In 2022, our founders watched Ugandan business owners wrestle with disconnected tools: 
                        a POS system that couldn't talk to their accounting software, spreadsheets for inventory 
                        that never matched reality, and manual URA tax calculations that consumed days every month.
                    </p>
                    <p class="text-secondary text-base leading-relaxed">
                        We asked: what if one platform handled it all? Sales, purchases, stock, invoicing, payroll, 
                        tax — all in UGX, with MTN Mobile Money and Airtel Money built in, and URA compliance automated?
                    </p>
                    <p class="text-secondary text-base leading-relaxed font-medium">
                        TBM Enterprise Suite was born. Today, thousands of transactions flow through TBM every month 
                        across Uganda, Kenya, Tanzania, and Rwanda.
                    </p>
                </div>

                <div class="bg-bg-subtle border border-border-quiet rounded-2xl p-8 space-y-4 tbm-shadow">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['label' => 'Businesses Active', 'value' => '247+', 'icon' => 'business'],
                            ['label' => 'Transactions / Month', 'value' => '12,840+', 'icon' => 'swap_horiz'],
                            ['label' => 'UGX Processed', 'value' => 'UGX 4.2B+', 'icon' => 'currency_exchange'],
                            ['label' => 'URA Filings Automated', 'value' => '98%', 'icon' => 'verified'],
                        ] as $stat)
                            <div class="bg-surface border border-border-quiet rounded-xl p-4 text-center">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-primary text-xl">{{ $stat['icon'] }}</span>
                                </div>
                                <span class="font-headline text-2xl font-extrabold text-text-rich font-numeric block">{{ $stat['value'] }}</span>
                                <span class="text-xs text-secondary block mt-1">{{ $stat['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Vision Values -->
    <section class="py-16 md:py-24 lg:py-32 bg-bg-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    What Drives Us
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Mission, Vision & Values</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow text-center space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto">
                        <span class="material-symbols-outlined text-primary text-2xl">flag</span>
                    </div>
                    <h3 class="font-headline font-bold text-xl text-text-rich">Mission</h3>
                    <p class="text-secondary text-base leading-relaxed">
                        Empower every Ugandan business with a unified, UGX-native operating system that automates compliance, 
                        accelerates growth, and eliminates spreadsheet chaos.
                    </p>
                </div>

                <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow text-center space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-success/10 flex items-center justify-center mx-auto">
                        <span class="material-symbols-outlined text-success text-2xl">visibility</span>
                    </div>
                    <h3 class="font-headline font-bold text-xl text-text-rich">Vision</h3>
                    <p class="text-secondary text-base leading-relaxed">
                        Become the default business management platform for East Africa — where running a compliant, 
                        profitable enterprise is as simple as opening an app.
                    </p>
                </div>

                <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow text-center space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-amber-500/10 flex items-center justify-center mx-auto">
                        <span class="material-symbols-outlined text-amber-700 text-2xl">diamond</span>
                    </div>
                    <h3 class="font-headline font-bold text-xl text-text-rich">Values</h3>
                    <p class="text-secondary text-base leading-relaxed">
                        <strong class="text-text-rich">Uganda First:</strong> Built locally, for local realities.<br>
                        <strong class="text-text-rich">Compliance by Default:</strong> URA rules baked in, not bolted on.<br>
                        <strong class="text-text-rich">Customer Obsessed:</strong> Your success is our only metric.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team / Leadership -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    Leadership
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">The team behind TBM</h2>
                <p class="text-base text-secondary">Experienced operators, engineers, and compliance experts who've built and scaled businesses across East Africa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
                @php
                    $team = [
                        ['name' => 'Sarah Jenkins', 'role' => 'Founder & CEO', 'bio' => 'Ex-Standard Bank, KPMG. 15+ years fintech & regulatory compliance across East Africa.', 'avatar' => 'images/avatars/sarah-jenkins.svg', 'initials' => 'SJ', 'color' => 'bg-primary'],
                        ['name' => 'David Mukasa', 'role' => 'Chief Product Officer', 'bio' => 'Ex-Jumia, SafeBoda. Product leader who scaled marketplace platforms to 1M+ users.', 'avatar' => 'images/avatars/kwame-mensah.svg', 'initials' => 'DM', 'color' => 'bg-success'],
                        ['name' => 'Flavia Nalubega', 'role' => 'Head of Compliance', 'bio' => 'CPA Uganda, ex-URA. Architected the automated PAYE/VAT/NSSF engine powering TBM.', 'avatar' => 'images/avatars/amina-diallo.svg', 'initials' => 'FN', 'color' => 'bg-amber-500'],
                        ['name' => 'Brian Kiggundu', 'role' => 'VP Engineering', 'bio' => 'Ex-Andela, Flutterwave. Full-stack architect specializing in offline-first fintech systems.', 'avatar' => 'images/avatars/michael-chen.svg', 'initials' => 'BK', 'color' => 'bg-purple-600'],
                    ];
                @endphp

                @foreach($team as $member)
                    <div class="bg-bg-subtle border border-border-quiet rounded-2xl p-6 tbm-shadow text-center space-y-3 hover:translate-y-[-4px] transition-all">
                        <div class="w-20 h-20 rounded-full {{ $member['color'] }} text-white flex items-center justify-center mx-auto overflow-hidden border-2 border-white shadow-md">
                            <img src="{{ asset($member['avatar']) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <h4 class="font-headline font-bold text-text-rich">{{ $member['name'] }}</h4>
                        <span class="text-sm font-semibold text-primary">{{ $member['role'] }}</span>
                        <p class="text-xs text-secondary leading-relaxed">{{ $member['bio'] }}</p>
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
            <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-balance mb-4">Join 247+ Ugandan businesses on TBM</h2>
            <p class="text-lg text-primary-fixed-dim mb-8 max-w-2xl mx-auto">Start your 30-day free trial today. No payment method required.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-primary hover:bg-primary-fixed px-8 py-4 rounded-xl text-sm font-bold shadow-md transition-all">
                <span class="material-symbols-outlined text-base">rocket_launch</span>
                <span>Start Free Trial</span>
            </a>
        </div>
    </section>

</div>
@endsection