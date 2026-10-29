@extends('layouts.public')

@section('title', 'Features & Modules • TBM Enterprise Suite')

@section('content')
<div class="space-y-0">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-primary/5 via-transparent to-transparent py-16 md:py-24 lg:py-32">
        <div class="absolute inset-0 hero-gradient-overlay"></div>
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20 mb-6 inline-block">
                <span class="material-symbols-outlined text-sm">widgets</span>
                {{ $page->content['hero_badge'] ?? 'Complete Feature Set' }}
            </span>
            
            <h1 class="font-headline text-4xl md:text-5xl lg:text-6xl font-extrabold text-text-rich leading-tight text-balance mb-6">
                {{ $page->content['hero_title'] ?? 'Everything you need to run a modern Ugandan enterprise' }}
            </h1>
            
            <p class="text-secondary text-lg leading-relaxed max-w-3xl mx-auto mb-10">
                {{ $page->content['hero_text'] ?? 'Thirteen integrated modules covering the full commercial cycle — from POS sales in Kampala to supplier bills in Jinja, all reconciling in a single UGX ledger with URA compliance built in.' }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-primary hover:bg-primary-container text-white px-8 py-4 rounded-xl text-sm font-bold shadow-lg transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">rocket_launch</span>
                    <span>Start {{ $defaultTrialDays }}-Day Free Trial</span>
                </a>
                <a href="{{ route('subscription.pricing') }}" class="w-full sm:w-auto bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-8 py-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">sell</span>
                    <span>View Plans</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Modules Grid -->
    <section class="py-16 md:py-24 lg:py-32 bg-bg-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
                @foreach([
                    ['icon' => 'inventory_2', 'title' => 'Inventory & Stock', 'text' => 'Live stock quantities, warehouse locations, and automatic stock-in/out on every sale or purchase bill. Barcode scanning and stock alerts included.', 'tone' => 'bg-primary/10 text-primary border-primary/20', 'features' => ['Multi-warehouse stock', 'Barcode/QR support', 'Low stock alerts', 'Stock transfer', 'Valuation reports']],
                    ['icon' => 'point_of_sale', 'title' => 'POS & Checkout', 'text' => 'Ring up sales at the counter, build a cart, and generate instant receipts in UGX. Offline mode and hardware integration ready.', 'tone' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'features' => ['Touch-optimized UI', 'Cash/MoMo/Card', 'Offline sync', 'Receipt printer', 'Shift management']],
                    ['icon' => 'receipt_long', 'title' => 'Invoicing & Billing', 'text' => 'Send professional customer invoices with Uganda 18% VAT, payment tracking, and early-settlement discounts. Email/WhatsApp delivery.', 'tone' => 'bg-amber-50 text-amber-800 border-amber-200', 'features' => ['UGX native', '18% VAT auto', 'Payment links', 'Recurring invoices', 'Credit notes']],
                    ['icon' => 'local_shipping', 'title' => 'Purchases & Bills', 'text' => 'Raise purchase orders, convert to vendor bills, and track creditors with automatic stock replenishment. 3-way matching.', 'tone' => 'bg-blue-50 text-blue-800 border-blue-200', 'features' => ['PO to Bill flow', 'Creditor ledger', 'Auto stock-in', '3-way matching', 'Payment scheduling']],
                    ['icon' => 'badge', 'title' => 'Payroll & Payslips', 'text' => 'Staff records, monthly payroll runs, and PDF payslips with PAYE & NSSF statutory deductions. Bank payment files.', 'tone' => 'bg-purple-50 text-purple-800 border-purple-200', 'features' => ['PAYE/NSSF calc', 'PDF payslips', 'Bank payment file', 'Leave tracking', 'Statutory reports']],
                    ['icon' => 'account_balance', 'title' => 'Tax Compliance (URA)', 'text' => 'PAYE, NSSF, 18% VAT and LED — all calculated automatically for Uganda Revenue Authority compliance. PRN ready.', 'tone' => 'bg-rose-50 text-rose-800 border-rose-200', 'features' => ['VAT 18% returns', 'PAYE monthly', 'NSSF 15%', 'LED calc', 'PRN generation']],
                    ['icon' => 'query_stats', 'title' => 'Reports & P&L', 'text' => 'Real-time Profit & Loss, Balance Sheet, and Trial Balance with monthly trend charts in UGX. Export to Excel/PDF.', 'tone' => 'bg-cyan-50 text-cyan-800 border-cyan-200', 'features' => ['P&L statement', 'Balance sheet', 'Trial balance', 'Cash flow', 'Drill-down']],
                    ['icon' => 'hub', 'title' => 'CRM & Lead Pipeline', 'text' => 'Track leads, opportunities and support tickets from first contact through to a closed sale. Kanban pipeline view.', 'tone' => 'bg-indigo-50 text-indigo-800 border-indigo-200', 'features' => ['Kanban pipeline', 'Lead scoring', 'Email tracking', 'Ticket system', 'SLA timers']],
                    ['icon' => 'store', 'title' => 'Multi-Business & Branches', 'text' => 'Run multiple companies and branch locations, each with its own books, settings and permissions. Consolidated reporting.', 'tone' => 'bg-teal-50 text-teal-800 border-teal-200', 'features' => ['Multi-entity', 'Branch stock', 'Consolidated P&L', 'Inter-branch transfer', 'Role-based access']],
                    ['icon' => 'attach_money', 'title' => 'Expenses & Petty Cash', 'text' => 'Capture business expenses with receipts, categorize by project, and reimburse staff. Mobile receipt capture.', 'tone' => 'bg-orange-50 text-orange-800 border-orange-200', 'features' => ['Receipt OCR', 'Categories', 'Approval workflow', 'Petty cash', 'Mileage tracking']],
                    ['icon' => 'settings', 'title' => 'Custom User Roles', 'text' => 'Create custom roles and fine-grained permissions for staff. Module-level and action-level access control.', 'tone' => 'bg-slate-50 text-slate-800 border-slate-200', 'features' => ['Role builder', 'Permission matrix', 'Audit trail', '2FA support', 'SSO ready']],
                    ['icon' => 'api', 'title' => 'API & Integrations', 'text' => 'RESTful API for custom integrations. Webhooks for real-time events. Pre-built connectors for banking and e-commerce.', 'tone' => 'bg-violet-50 text-violet-800 border-violet-200', 'features' => ['REST API', 'Webhooks', 'Bank feeds', 'E-commerce sync', 'Rate limiting']],
                ] as $module)
                    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow hover:translate-y-[-4px] transition-all group">
                        <div class="w-11 h-11 rounded-xl {{ $module['tone'] }} border flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">{{ $module['icon'] }}</span>
                        </div>
                        <h3 class="font-headline font-bold text-base text-text-rich mb-2">{{ $module['title'] }}</h3>
                        <p class="text-sm text-secondary leading-relaxed mb-4">{{ $module['text'] }}</p>
                        <ul class="space-y-2 text-xs text-secondary border-t border-border-quiet pt-4">
                            @foreach($module['features'] as $f)
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-success text-[14px] shrink-0">check</span>
                                    <span>{{ $f }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Feature Showcase Image -->
    <section class="py-16 md:py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?fm=jpg&q=60&w=1200&auto=format&fit=crop" alt="Business analytics dashboard" class="w-full h-auto rounded-2xl shadow-xl border border-border-quiet" loading="lazy">
                    <div class="absolute -top-4 -right-4 md:-top-6 md:-right-6 bg-white/90 backdrop-blur-sm border border-border-quiet rounded-xl p-4 tbm-shadow flex items-center gap-3 shadow-xl">
                        <div class="w-10 h-10 rounded-full bg-success/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-success text-xl">speed</span>
                        </div>
                        <div>
                            <span class="font-headline font-bold text-text-rich text-sm block">Real-Time</span>
                            <span class="text-xs text-secondary">Live Analytics</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                        Powerful Analytics
                    </span>
                    <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Real-time insights across your entire business</h2>
                    <p class="text-secondary text-base leading-relaxed">
                        From sales trends in Kampala to inventory levels in Jinja, TBM gives you a single dashboard 
                        to monitor every metric that matters. UGX-native reports, URA-ready exports, and drill-down 
                        capability from summary to individual transaction.
                    </p>
                    <ul class="space-y-3 text-sm text-secondary">
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-success text-base">check_circle</span>
                            <span>Profit & Loss, Balance Sheet, Trial Balance in real-time</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-success text-base">check_circle</span>
                            <span>Export to Excel, PDF, or URA-compatible format</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-success text-base">check_circle</span>
                            <span>Multi-branch consolidated reporting</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Matrix Table -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 lg:mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
                    Plan Feature Matrix
                </span>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Compare features across plans</h2>
                <p class="text-base text-secondary">Choose the plan that fits your business stage. All plans include the {{ $defaultTrialDays }}-day free trial.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5 sticky left-0 bg-bg-subtle z-10">Feature</th>
                            <th class="px-4 py-3.5 text-center">Starter</th>
                            <th class="px-4 py-3.5 text-center">Professional</th>
                            <th class="px-4 py-3.5 text-center">Premium</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-quiet font-sans">
                        @foreach($features as $feature)
                            <tr class="hover:bg-bg-subtle/60 transition-colors">
                                <td class="px-4 py-3.5 font-medium text-text-rich">{{ $feature->name }}</td>
                                @foreach($plans as $plan)
                                    @php $enabled = $plan->hasFeature($feature->key); @endphp
                                    <td class="px-4 py-3.5 text-center">
                                        @if($enabled)
                                            <span class="material-symbols-outlined text-success text-lg">check_circle</span>
                                        @else
                                            <span class="material-symbols-outlined text-outline/50 text-lg">cancel</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-primary py-16 md:py-24 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center text-white">
            <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-balance mb-4">Ready to explore all features?</h2>
            <p class="text-lg text-primary-fixed-dim mb-8 max-w-2xl mx-auto">Start your {{ $defaultTrialDays }}-day free trial. No payment method required. Cancel anytime.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-primary hover:bg-primary-fixed px-8 py-4 rounded-xl text-sm font-bold shadow-md transition-all">
                <span class="material-symbols-outlined text-base">rocket_launch</span>
                <span>Start Free Trial</span>
            </a>
        </div>
    </section>

</div>
@endsection