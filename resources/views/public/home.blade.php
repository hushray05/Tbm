@extends('layouts.public')

@section('title', 'TBM Enterprise Suite • Uganda Business Management OS')

@section('content')
<div class="space-y-0">

    <!-- ══════════════════ HERO SECTION ══════════════════ -->
    <section class="relative overflow-hidden bg-gradient-to-b from-primary/5 via-transparent to-transparent py-16 md:py-24 lg:py-32">
        <div class="absolute inset-0 hero-gradient-overlay"></div>
        
        <!-- Floating decorative elements -->
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-primary-container/20 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <!-- Copy -->
                <div class="space-y-8 text-center lg:text-left">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                        <span class="material-symbols-outlined text-sm">verified</span>
                        {{ $page->content['hero_badge'] ?? "Uganda's #1 Business Management Platform" }}
                    </span>
                    
                    <h1 class="font-headline text-4xl md:text-5xl lg:text-6xl font-extrabold text-text-rich leading-tight text-balance">
                        {{ $page->content['hero_title'] ?? 'Run your entire Ugandan business from one operating system' }}
                    </h1>
                    
                    <p class="text-secondary text-base md:text-lg leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        {{ $page->content['hero_text'] ?? 'Sales, purchases, inventory, invoicing, payroll, URA tax compliance and multi-branch operations — unified in UGX with MTN Mobile Money and Airtel Money support.' }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center sm:items-center gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto bg-primary hover:bg-primary-container text-white px-8 py-4 rounded-xl text-sm font-bold shadow-lg transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">rocket_launch</span>
                            <span>Start {{ $defaultTrialDays }}-Day Free Trial</span>
                        </a>
                        <a href="{{ route('subscription.pricing') }}" class="w-full sm:w-auto bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-8 py-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">sell</span>
                            <span>View Plans in UGX</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-center lg:justify-start gap-2 text-sm text-secondary">
                        <span class="material-symbols-outlined text-success text-base">check_circle</span>
                        <span>No payment method required — full access from day one.</span>
                    </div>
                </div>

                <!-- Visual: Hero Image -->
                <div class="relative">
                    <img src="{{ $page->content['hero_image'] ?? 'https://images.unsplash.com/photo-1556745753-b2904692b3cd?fm=jpg&q=60&w=1200&auto=format&fit=crop' }}" alt="Smiling businesswoman using laptop at desk" class="w-full h-auto rounded-2xl shadow-2xl border border-border-quiet" loading="eager">
                    <!-- Floating trust badge -->
                    <div class="absolute -bottom-6 -right-6 md:-bottom-8 md:-right-8 bg-white/90 backdrop-blur-sm border border-border-quiet rounded-xl p-4 tbm-shadow flex items-center gap-3 shadow-xl">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-700 text-xl">shield</span>
                        </div>
                        <div>
                            <span class="font-headline font-bold text-text-rich text-sm block">{{ $page->content['trust_badge_text'] ?? 'URA Compliant' }}</span>
                            <span class="text-xs text-secondary">{{ $page->content['trust_badge_subtext'] ?? 'PAYE, NSSF, VAT 18%, LED' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ TRUST / NUMBERS BAR ══════════════════ -->
    <section class="bg-surface border-y border-border-quiet py-10">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-gutter">
                <div class="text-center">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-primary text-2xl">widgets</span>
                    </div>
                    <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric">13+</span>
                    <span class="block text-xs font-bold text-secondary uppercase tracking-wider mt-1">Business Modules</span>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-emerald-700 text-2xl">currency_exchange</span>
                    </div>
                    <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric">11</span>
                    <span class="block text-xs font-bold text-secondary uppercase tracking-wider mt-1">African Currencies</span>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-amber-700 text-2xl">payments</span>
                    </div>
                    <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric">100%</span>
                    <span class="block text-xs font-bold text-secondary uppercase tracking-wider mt-1">UGX Native Billing</span>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-purple-700 text-2xl">timer</span>
                    </div>
                    <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric">{{ $defaultTrialDays }}</span>
                    <span class="block text-xs font-bold text-secondary uppercase tracking-wider mt-1">Day Free Trial</span>
                    <div id="trialCountdown" class="mt-2 flex items-center justify-center gap-1.5 text-[10px] font-bold text-primary/80 uppercase tracking-wider">
                        <span id="cd-days">00</span>d :
                        <span id="cd-hours">00</span>h :
                        <span id="cd-mins">00</span>m :
                        <span id="cd-secs">00</span>s
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ SERVICES / MODULES ══════════════════ -->
    <section class="py-16 md:py-24 lg:py-32 bg-bg-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    Everything In One Place
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">One platform for the full commercial cycle</h2>
                <p class="text-base text-secondary">From a point-of-sale sale in Jinja to a supplier bill in Kampala, every transaction flows into a single UGX ledger.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
                @foreach([
                    ['icon' => 'inventory_2', 'title' => 'Inventory & Stock', 'text' => 'Live stock quantities, warehouse locations, and automatic stock-in/out on every sale or purchase bill.', 'tone' => 'bg-primary/10 text-primary border-primary/20'],
                    ['icon' => 'point_of_sale', 'title' => 'POS & Checkout', 'text' => 'Ring up sales at the counter, build a cart, and generate instant receipts in UGX.', 'tone' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                    ['icon' => 'receipt_long', 'title' => 'Invoicing & Billing', 'text' => 'Send professional customer invoices with Uganda 18% VAT, payment tracking, and early-settlement discounts.', 'tone' => 'bg-amber-50 text-amber-800 border-amber-200'],
                    ['icon' => 'local_shipping', 'title' => 'Purchases & Bills', 'text' => 'Raise purchase orders, convert to vendor bills, and track creditors with automatic stock replenishment.', 'tone' => 'bg-blue-50 text-blue-800 border-blue-200'],
                    ['icon' => 'badge', 'title' => 'Payroll & Payslips', 'text' => 'Staff records, monthly payroll runs, and PDF payslips with PAYE & NSSF statutory deductions.', 'tone' => 'bg-purple-50 text-purple-800 border-purple-200'],
                    ['icon' => 'account_balance', 'title' => 'Tax Compliance (URA)', 'text' => 'PAYE, NSSF, 18% VAT and LED — all calculated automatically for Uganda Revenue Authority compliance.', 'tone' => 'bg-rose-50 text-rose-800 border-rose-200'],
                    ['icon' => 'query_stats', 'title' => 'Reports & P&L', 'text' => 'Real-time Profit & Loss, Balance Sheet, and Trial Balance with monthly trend charts in UGX.', 'tone' => 'bg-cyan-50 text-cyan-800 border-cyan-200'],
                    ['icon' => 'hub', 'title' => 'CRM & Lead Pipeline', 'text' => 'Track leads, opportunities and support tickets from first contact through to a closed sale.', 'tone' => 'bg-indigo-50 text-indigo-800 border-indigo-200'],
                    ['icon' => 'store', 'title' => 'Multi-Business & Branches', 'text' => 'Run multiple companies and branch locations, each with its own books, settings and permissions.', 'tone' => 'bg-teal-50 text-teal-800 border-teal-200'],
                ] as $module)
                    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow hover:translate-y-[-4px] transition-all group">
                        <div class="w-11 h-11 rounded-xl {{ $module['tone'] }} border flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">{{ $module['icon'] }}</span>
                        </div>
                        <h3 class="font-headline font-bold text-base text-text-rich mb-1.5">{{ $module['title'] }}</h3>
                        <p class="text-sm text-secondary leading-relaxed">{{ $module['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ══════════════════ ABOUT TEASER ══════════════════ -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                        About TBM Enterprise
                    </span>
                    <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Built in Uganda, for African enterprises</h2>
                    <p class="text-secondary text-base leading-relaxed">
                        TBM Enterprise Suite was born from the realization that Ugandan businesses — from Kampala traders to Mbarara manufacturers — 
                        were forced to cobble together spreadsheets, disconnected POS systems, and manual tax calculations. 
                        We built a single platform that speaks UGX natively, integrates MTN Mobile Money and Airtel Money at the core, 
                        and handles URA compliance (PAYE, NSSF, 18% VAT, LED) automatically.
                    </p>
                    <p class="text-secondary text-base leading-relaxed">
                        Today, thousands of transactions flow through TBM every month — from a boda-boda spare parts shop in Nateete 
                        to a regional logistics firm with depots in Entebbe and Jinja. Our mission: make enterprise-grade 
                        business management accessible to every Ugandan entrepreneur.
                    </p>
                    <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-container transition-colors mt-2">
                        <span>Learn our story</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>

                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?fm=jpg&q=60&w=1200&auto=format&fit=crop" alt="Business analytics dashboard" class="w-full h-auto rounded-2xl shadow-xl border border-border-quiet" loading="lazy">
                    <div class="absolute -bottom-4 -left-4 md:-bottom-6 md:-left-6 bg-white/90 backdrop-blur-sm border border-border-quiet rounded-xl p-4 tbm-shadow flex items-center gap-3 shadow-xl">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-xl">trending_up</span>
                        </div>
                        <div>
                            <span class="font-headline font-bold text-text-rich text-sm block">UGX 4.2B+</span>
                            <span class="text-xs text-secondary">Processed Monthly</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ PRICING PLANS ══════════════════ -->
    <section class="py-16 md:py-24 lg:py-32 bg-bg-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    Simple, Predictable Plans
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Priced in UGX for Ugandan business</h2>
                <p class="text-base text-secondary">Every plan includes a <strong>{{ $defaultTrialDays }}-Day Free Trial</strong> with zero upfront payment. Switch or cancel anytime.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="bg-surface rounded-2xl border {{ $plan->badge === 'MOST POPULAR' ? 'border-primary shadow-xl ring-2 ring-primary/20 relative' : 'border-border-quiet tbm-shadow' }} p-7 flex flex-col justify-between transition-all hover:translate-y-[-4px]">
                        @if($plan->badge)
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-primary text-white text-[11px] font-bold px-3.5 py-0.5 rounded-full uppercase tracking-wider shadow-sm">
                                {{ $plan->badge }}
                            </div>
                        @endif

                        <div class="space-y-5">
                            <div>
                                <h3 class="font-headline font-bold text-xl text-text-rich">{{ $plan->name }}</h3>
                                <p class="text-sm text-secondary mt-1 min-h-[32px]">{{ $plan->description }}</p>
                            </div>

                            <div class="py-3 border-y border-border-quiet">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xs font-bold text-secondary">UGX</span>
                                    <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric"> {{ number_format($plan->monthly_price) }} </span>
                                    <span class="text-xs text-secondary font-medium">/ month</span>
                                </div>
                                <div class="mt-1 flex items-center gap-1.5 text-xs text-success font-semibold">
                                    <span class="material-symbols-outlined text-sm">verified</span>
                                    <span>{{ $plan->trial_duration }}-Day Free Trial Included</span>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm">
                                <span class="text-[11px] font-bold text-outline uppercase tracking-wider block">Usage Limits</span>
                                <div class="flex items-center justify-between text-text-rich">
                                    <span class="text-secondary">Users:</span>
                                    <span class="font-bold">{{ $plan->max_users ? $plan->max_users . ' User' . ($plan->max_users > 1 ? 's' : '') : 'Unlimited' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-text-rich">
                                    <span class="text-secondary">Locations:</span>
                                    <span class="font-bold">{{ $plan->max_locations ? $plan->max_locations . ' Location' . ($plan->max_locations > 1 ? 's' : '') : 'Multi-Location' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-text-rich">
                                    <span class="text-secondary">Products Catalog:</span>
                                    <span class="font-bold">{{ $plan->max_products ? number_format($plan->max_products) . ' items' : 'Unlimited' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-text-rich">
                                    <span class="text-secondary">Monthly Invoices:</span>
                                    <span class="font-bold">{{ $plan->max_invoices ? number_format($plan->max_invoices) : 'Unlimited' }}</span>
                                </div>
                            </div>

                            <div class="space-y-2.5 pt-2 border-t border-border-quiet">
                                <span class="text-[11px] font-bold text-outline uppercase tracking-wider block">Included Features</span>
                                <div class="space-y-2 text-xs">
                                    @foreach($features as $f)
                                        @php $isEnabled = $plan->hasFeature($f->key); @endphp
                                        <div class="flex items-center gap-2.5 {{ $isEnabled ? 'text-text-rich' : 'text-outline/70' }}">
                                            @if($isEnabled)
                                                <span class="material-symbols-outlined text-success text-base shrink-0">check_circle</span>
                                                <span>{{ $f->name }}</span>
                                            @else
                                                <span class="material-symbols-outlined text-outline/50 text-base shrink-0">cancel</span>
                                                <span class="line-through">{{ $f->name }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-border-quiet">
                            <a href="{{ route('register', ['plan' => $plan->slug]) }}" class="w-full py-3 px-4 rounded-xl text-center font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 {{ $plan->badge === 'MOST POPULAR' ? 'bg-primary hover:bg-primary-container text-white' : 'bg-bg-subtle hover:bg-primary hover:text-white text-primary border border-primary/20' }}">
                                <span>Start {{ $plan->trial_duration }}-Day Free Trial</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                            <span class="block text-center text-[11px] text-secondary mt-2">No payment method required to start</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('subscription.pricing') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-container transition-colors">
                    <span>Compare all plans in detail</span>
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ══════════════════ TESTIMONIALS ══════════════════ -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    Trusted by Enterprises
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Running real businesses across Uganda</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                @php
                    $testimonials = [
                        [
                            'quote' => '"We moved our entire logistics operation onto TBM. Stock across Kampala and Entebbe, invoicing and payroll all reconcile in one UGX ledger."',
                            'author' => 'David Mukasa',
                            'role' => 'Managing Director, Victoria Logistics & Trading Ltd',
                            'avatar' => 'images/avatars/kwame-mensah.svg',
                            'initials' => 'DM',
                            'color' => 'bg-primary',
                        ],
                        [
                            'quote' => '"The URA tax compliance and 18% VAT handling is exactly what our finance team needed. Payslips with PAYE and NSSF run in minutes, not days."',
                            'author' => 'Flavia Nalubega',
                            'role' => 'Chief Accountant, Pearl Agri-Supplies',
                            'avatar' => 'images/avatars/amina-diallo.svg',
                            'initials' => 'FN',
                            'color' => 'bg-success',
                        ],
                        [
                            'quote' => '"MTN Mobile Money checkout integrated at point-of-sale. Our sales team closes faster and every payment is instantly reconciled to the customer\'s invoice."',
                            'author' => 'Brian Kiggundu',
                            'role' => 'Operations Lead, Rwenzori Tech Innovators',
                            'avatar' => 'images/avatars/michael-chen.svg',
                            'initials' => 'BK',
                            'color' => 'bg-primary-container',
                        ],
                    ];
                @endphp

                @foreach($testimonials as $t)
                    <div class="bg-bg-subtle border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4 hover:border-primary/30 transition-colors">
                        <div class="flex gap-0.5 text-amber-400 text-sm">★★★★★</div>
                        <p class="text-sm text-secondary leading-relaxed">{{ $t['quote'] }}</p>
                        <div class="flex items-center gap-3 pt-2 border-t border-border-quiet">
                            <div class="w-10 h-10 rounded-full {{ $t['color'] }} text-white flex items-center justify-center font-bold text-sm overflow-hidden">
                                <img src="{{ asset($t['avatar']) }}" alt="{{ $t['author'] }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-text-rich">{{ $t['author'] }}</span>
                                <span class="block text-xs text-secondary">{{ $t['role'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ══════════════════ TRUSTED COMPANIES MARQUEE ══════════════════ -->
    @if($companies->count() > 0)
    <section class="py-12 bg-primary/5 border-y border-border-quiet">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center mb-8">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    Trusted By
                </span>
                <h3 class="font-headline text-2xl md:text-3xl font-bold text-text-rich mt-2">Companies running on TBM</h3>
            </div>

            <!-- Marquee / Scrolling Logos -->
            <div class="overflow-hidden">
                <div class="marquee-track flex gap-8 md:gap-12 lg:gap-16 items-center whitespace-nowrap">
                    @foreach($companies as $company)
                        @if($company->is_active)
                            <div class="flex-shrink-0 flex items-center gap-3 px-4 py-2 bg-white/80 backdrop-blur-sm rounded-xl border border-border-quiet tbm-shadow transition-all hover:scale-105 hover:border-primary/30" style="min-width: 200px;">
                                @if($company->logo_url)
                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="h-8 md:h-10 max-w-32 object-contain" loading="lazy">
                                @else
                                    <div class="w-14 h-8 md:w-16 md:h-10 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center font-bold text-primary text-xs md:text-sm">
                                        {{ $company->monogram }}
                                    </div>
                                @endif
                                <span class="text-xs md:text-sm font-semibold text-text-rich hidden sm:block">{{ $company->name }}</span>
                            </div>
                        @endif
                    @endforeach
                    <!-- Duplicate for seamless loop -->
                    @foreach($companies as $company)
                        @if($company->is_active)
                            <div class="flex-shrink-0 flex items-center gap-3 px-4 py-2 bg-white/80 backdrop-blur-sm rounded-xl border border-border-quiet tbm-shadow transition-all hover:scale-105 hover:border-primary/30" style="min-width: 200px;">
                                @if($company->logo_url)
                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="h-8 md:h-10 max-w-32 object-contain" loading="lazy">
                                @else
                                    <div class="w-14 h-8 md:w-16 md:h-10 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center font-bold text-primary text-xs md:text-sm">
                                        {{ $company->monogram }}
                                    </div>
                                @endif
                                <span class="text-xs md:text-sm font-semibold text-text-rich hidden sm:block">{{ $company->name }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ══════════════════ FAQ TEASER ══════════════════ -->
    <section class="py-16 md:py-24 lg:py-32 bg-bg-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    Frequently Asked
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Quick answers to common questions</h2>
            </div>

            <div class="max-w-3xl mx-auto space-y-3">
                @php
                    $faqs = [
                        ['q' => 'Is there a free trial?', 'a' => 'Yes. Every plan includes a 30-day free trial with full access to all modules. No payment method required to start.'],
                        ['q' => 'Can I pay in Ugandan Shillings (UGX)?', 'a' => 'Absolutely. TBM is built natively for UGX billing. All plans are priced in UGX, and MTN Mobile Money / Airtel Money payments are integrated directly.'],
                        ['q' => 'Does TBM handle URA tax compliance?', 'a' => 'Yes. PAYE, NSSF (15%), 18% VAT, and Local Excise Duty (LED) are calculated automatically. URA-compliant reports and PRN generation are included.'],
                        ['q' => 'Can I manage multiple branches?', 'a' => 'Yes. The Professional plan supports 2 locations, Premium supports 10. Each branch has independent stock, sales, and reporting while rolling up to the parent business.'],
                        ['q' => 'What payment gateways are supported?', 'a' => 'MTN Mobile Money, Airtel Money Uganda, Visa/Mastercard via Paystack/Flutterwave, and direct Bank Transfer. All reconciled automatically in UGX.'],
                    ];
                @endphp

                @foreach($faqs as $faq)
                    <details class="group bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
                        <summary class="flex items-center justify-between cursor-pointer list-none text-sm font-semibold text-text-rich">
                            {{ $faq['q'] }}
                            <span class="material-symbols-outlined text-primary transition-transform group-open:rotate-180">expand_more</span>
                        </summary>
                        <div class="mt-3 text-sm text-secondary leading-relaxed border-t border-border-quiet pt-3">
                            {{ $faq['a'] }}
                        </div>
                    </details>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('faq') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-container transition-colors">
                    <span>View all FAQs</span>
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ══════════════════ PAYMENT GATEWAYS BAR ══════════════════ -->
    <section class="bg-surface border-y border-border-quiet py-8">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <div>
                    <h4 class="font-headline font-bold text-text-rich text-base">Ugandan Payment Gateways Supported</h4>
                    <p class="text-xs text-secondary mt-0.5">Pay seamlessly with MTN Mobile Money, Airtel Money Uganda, Visa, Mastercard, or Bank Transfer.</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap justify-center">
                    <span class="px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold">MTN Mobile Money</span>
                    <span class="px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-red-900 text-xs font-bold">Airtel Money</span>
                    <span class="px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold">Visa / Mastercard</span>
                    <span class="px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-900 text-xs font-bold">Bank Transfer</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════ FINAL CTA ══════════════════ -->
    <section class="bg-primary rounded-none py-16 md:py-24 lg:py-32 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="relative max-w-7xl mx-auto px-4 md:px-8">
            <div class="relative space-y-5 max-w-2xl mx-auto text-center text-white">
                <h2 class="font-headline text-3xl md:text-4xl lg:text-5xl font-extrabold text-balance">Start your {{ $defaultTrialDays }}-day free trial today</h2>
                <p class="text-base md:text-lg text-primary-fixed-dim">Get full access to every module for {{ $defaultTrialDays }} days. No payment method required, no lock-in.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white text-primary hover:bg-primary-fixed px-8 py-4 rounded-xl text-sm font-bold shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">rocket_launch</span>
                        <span>Create Free Account</span>
                    </a>
                    <a href="{{ route('contact') }}" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white border border-white/30 px-8 py-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">chat</span>
                        <span>Talk to Sales</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
(function(){
    const trialDays = {{ $defaultTrialDays }};
    const stored = localStorage.getItem('tbm_trial_start');
    let start;
    if (stored) {
        start = new Date(parseInt(stored));
    } else {
        start = new Date();
        localStorage.setItem('tbm_trial_start', start.getTime());
    }
    const end = new Date(start.getTime() + trialDays * 24 * 60 * 60 * 1000);

    function pad(n){ return n < 10 ? '0' + n : n; }

    function tick(){
        const now = new Date();
        let diff = end - now;
        if(diff <= 0){
            document.getElementById('trialCountdown').innerHTML = '<span class="text-danger">Trial expired</span>';
            return;
        }
        const d = Math.floor(diff / 86400000); diff %= 86400000;
        const h = Math.floor(diff / 3600000); diff %= 3600000;
        const m = Math.floor(diff / 60000); diff %= 60000;
        const s = Math.floor(diff / 1000);
        document.getElementById('cd-days').textContent = pad(d);
        document.getElementById('cd-hours').textContent = pad(h);
        document.getElementById('cd-mins').textContent = pad(m);
        document.getElementById('cd-secs').textContent = pad(s);
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endpush