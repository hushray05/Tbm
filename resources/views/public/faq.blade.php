@extends('layouts.public')

@section('title', 'FAQ & Support • TBM Enterprise Suite')

@section('content')
<div class="space-y-0">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-primary/5 via-transparent to-transparent py-16 md:py-24 lg:py-32">
        <div class="absolute inset-0 hero-gradient-overlay"></div>
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20 mb-6 inline-block">
                <span class="material-symbols-outlined text-sm">help</span>
                {{ $page->content['hero_badge'] ?? 'Frequently Asked Questions' }}
            </span>
            
            <h1 class="font-headline text-4xl md:text-5xl lg:text-6xl font-extrabold text-text-rich leading-tight text-balance mb-6">
                {{ $page->content['hero_title'] ?? 'Quick answers to common questions' }}
            </h1>
            
            <p class="text-secondary text-lg leading-relaxed max-w-3xl mx-auto mb-10">
                {{ $page->content['hero_text'] ?? "Can't find what you're looking for? Our Kampala-based support team is here to help." }}
            </p>

            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-container transition-colors">
                <span>Contact Support</span>
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </section>

    <!-- Support Team Image -->
    <section class="py-12 bg-surface border-y border-border-quiet">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?fm=jpg&q=60&w=1200&auto=format&fit=crop" alt="TBM support team" class="w-full h-64 object-cover rounded-2xl shadow-xl border border-border-quiet" loading="lazy">
                </div>
                <div class="space-y-4">
                    <h2 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">We're here to help</h2>
                    <p class="text-secondary text-base leading-relaxed">
                        Our Kampala-based support team understands Ugandan business realities. 
                        Whether it's URA compliance, mobile money integration, or getting your 
                        team onboarded — we've got you covered.
                    </p>
                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-base">schedule</span>
                            <span class="text-secondary">2hr response time</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-base">support_agent</span>
                            <span class="text-secondary">Live chat available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Categories -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
                <!-- Category Sidebar -->
                <aside class="lg:col-span-1 space-y-4">
                    <div class="bg-bg-subtle border border-border-quiet rounded-xl p-5 tbm-shadow sticky top-24">
                        <h3 class="font-headline font-bold text-text-rich mb-4">Categories</h3>
                        <nav class="space-y-1">
                            @php
                                $categories = [
                                    ['id' => 'getting-started', 'label' => 'Getting Started', 'count' => 5],
                                    ['id' => 'billing', 'label' => 'Billing & Plans', 'count' => 4],
                                    ['id' => 'features', 'label' => 'Features & Modules', 'count' => 6],
                                    ['id' => 'compliance', 'label' => 'URA & Compliance', 'count' => 5],
                                    ['id' => 'technical', 'label' => 'Technical & Integrations', 'count' => 4],
                                    ['id' => 'account', 'label' => 'Account & Security', 'count' => 3],
                                ];
                            @endphp
                            @foreach($categories as $cat)
                                <a href="#{{ $cat['id'] }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold text-secondary hover:bg-surface hover:text-text-rich transition-all">
                                    {{ $cat['label'] }}
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary/10 text-primary">{{ $cat['count'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                <!-- FAQ Content -->
                <div class="lg:col-span-2 space-y-8">
                    @php
                        $faqs = [
                            'getting-started' => [
                                'label' => 'Getting Started',
                                'items' => [
                                    ['q' => 'How do I sign up for TBM Enterprise?', 'a' => 'Click "Start Free Trial" on any page. Enter your business email, choose a plan, and verify your email. Your 30-day trial starts immediately with full access to all modules. No payment method required.'],
                                    ['q' => 'What information do I need to register?', 'a' => 'Your business legal name, TIN (Tax Identification Number), physical address, and contact details. If you have multiple branches, you can add them during setup or later from Settings.'],
                                    ['q' => 'Can I try TBM before committing?', 'a' => 'Yes. Every plan includes a 30-day free trial with full feature access. Add unlimited users, process real transactions, and test mobile money integration. No credit card required to start.'],
                                    ['q' => 'How long does setup take?', 'a' => 'Most businesses are live in under 15 minutes. The guided setup walks you through: business details → tax config → mobile money → products → first sale. CSV import makes product onboarding fast.'],
                                    ['q' => 'Do I need technical skills to use TBM?', 'a' => 'No. TBM is designed for business users, not IT specialists. The interface uses familiar Ugandan business terminology. Our support team offers free onboarding calls for new customers.'],
                                ],
                            ],
                            'billing' => [
                                'label' => 'Billing & Plans',
                                'items' => [
                                    ['q' => 'What currencies are supported?', 'a' => 'UGX is the native billing currency. The platform also supports 10+ African currencies (KES, TZS, RWF, ZAR, NGN, GHS, EGP, MAD, USD, EUR, GBP) with live FX rates for multi-currency businesses.'],
                                    ['q' => 'Can I change plans later?', 'a' => 'Yes. Upgrade or downgrade anytime from Settings → Subscription. Changes take effect at your next billing cycle. Prorated credits apply for upgrades; downgrades process at cycle end.'],
                                    ['q' => 'What payment methods do you accept?', 'a' => 'MTN Mobile Money, Airtel Money Uganda, Visa/Mastercard (via Paystack/Flutterwave), and direct Bank Transfer (Stanbic, Centenary, ABSA, Equity). All payments reconciled automatically in UGX.'],
                                    ['q' => 'Are there any hidden fees?', 'a' => 'No. The monthly price includes all modules, users (up to plan limits), support, and updates. Mobile money transaction fees are passed through at cost from the provider (typically 1.5–2.5%).'],
                                ],
                            ],
                            'features' => [
                                'label' => 'Features & Modules',
                                'items' => [
                                    ['q' => 'Which modules are included in my plan?', 'a' => 'All 13 modules are available on every plan. The difference is usage limits: users, locations, products, invoices, and advanced features (Payroll, URA reports, Custom Roles) are gated by plan tier.'],
                                    ['q' => 'Can I manage multiple branches?', 'a' => 'Yes. Starter: 1 location. Professional: 2 locations. Premium: 10 locations. Each branch has independent stock, sales, and reporting while rolling up to consolidated financials.'],
                                    ['q' => 'Does TBM work offline?', 'a' => 'The POS has offline mode for cash sales. Data syncs automatically when connectivity returns. Mobile money and card payments require internet.'],
                                    ['q' => 'Can I import my existing data?', 'a' => 'Yes. CSV import for products, customers, and suppliers. Opening balances for stock and ledgers. Our team assists with larger migrations from QuickBooks, Xero, or legacy systems.'],
                                    ['q' => 'Is there an API for custom integrations?', 'a' => 'Yes. RESTful API with webhooks for real-time events (sale.created, payment.received, stock.low). API keys managed in Settings. Rate limited to 100 req/min. Documentation at developer.tbm.ug.'],
                                    ['q' => 'What hardware does POS support?', 'a' => 'Star Micronics, Epson, and generic ESC/POS receipt printers. Bluetooth barcode scanners (Zebra, Honeywell). Cash drawers triggered via printer. Android tablets and Windows PCs supported.'],
                                ],
                            ],
                            'compliance' => [
                                'label' => 'URA & Compliance',
                                'items' => [
                                    ['q' => 'Does TBM calculate URA taxes automatically?', 'a' => 'Yes. 18% VAT on invoices, PAYE on payroll (progressive brackets), NSSF 15% (5% employee + 10% employer), and Local Excise Duty (LED) where applicable. All rates update automatically when URA gazettes changes.'],
                                    ['q' => 'Can I file VAT returns directly from TBM?', 'a' => 'TBM generates the URA VAT Return (VAT 101) in the exact format required, with PRN (Payment Registration Number) ready. Download the JSON/CSV and upload to the URA portal. Direct e-filing integration is on our roadmap.'],
                                    ['q' => 'How are PAYE and NSSF handled?', 'a' => 'Run monthly payroll → TBM calculates PAYE (progressive rates per URA) and NSSF (15% total). Generates payslips, bank payment file, and URA PAYE return. NSSF schedule ready for employer portal upload.'],
                                    ['q' => 'What about Local Excise Duty (LED)?', 'a' => 'LED is calculated on applicable goods (beer, spirits, soft drinks, cement, etc.) at the gazetted rates. LED return generated alongside VAT return.'],
                                    ['q' => 'Are tax rates updated automatically?', 'a' => 'Yes. Our compliance team monitors URA gazettes. When rates change (VAT, PAYE brackets, NSSF ceiling, LED), we push updates to all tenants within 24 hours. You\'ll see a notification in-app.'],
                                ],
                            ],
                            'technical' => [
                                'label' => 'Technical & Integrations',
                                'items' => [
                                    ['q' => 'What are the system requirements?', 'a' => 'Any modern browser (Chrome, Firefox, Safari, Edge). POS works on Android 8+ tablets, Windows 10+, and macOS. Internet required for mobile money/card; POS offline mode for cash.'],
                                    ['q' => 'Is my data secure?', 'a' => 'Yes. Data encrypted at rest (AES-256) and in transit (TLS 1.3). Hosted in Tier III data centers in Kenya and South Africa with 99.9% uptime SLA. Daily automated backups with 30-day retention.'],
                                    ['q' => 'Can I export my data?', 'a' => 'Anytime. CSV/Excel/PDF exports for all modules: sales, purchases, inventory, payroll, tax reports, customer lists. API access for automated extraction. No vendor lock-in.'],
                                    ['q' => 'Do you integrate with accounting software?', 'a' => 'TBM includes full accounting (P&L, Balance Sheet, Trial Balance, General Ledger). For external accountants, we export URA-compliant trial balances and journal entries. Direct Xero/QuickBooks sync in beta.'],
                                ],
                            ],
                            'account' => [
                                'label' => 'Account & Security',
                                'items' => [
                                    ['q' => 'How do I reset my password?', 'a' => 'Click "Forgot Password" on the login page. A reset link is sent to your registered email. Link expires in 60 minutes. Contact support if you no longer have email access.'],
                                    ['q' => 'Is two-factor authentication available?', 'a' => 'Yes. Enable 2FA in Settings → Security. Supports authenticator apps (Google Authenticator, Authy, Microsoft Authenticator) and SMS backup codes. Mandatory for Admin and Accountant roles on Premium plan.'],
                                    ['q' => 'What happens to my data if I cancel?', 'a' => 'Your data remains accessible in read-only mode for 90 days after cancellation. Full exports available anytime. After 90 days, data is permanently deleted per our retention policy. You can request early deletion.'],
                                ],
                            ],
                        ];
                    @endphp

                    @foreach($faqs as $catId => $category)
                        <section id="{{ $catId }}" class="space-y-4">
                            <h3 class="font-headline text-xl font-bold text-text-rich flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">{{ $catId === 'getting-started' ? 'rocket_launch' : ($catId === 'billing' ? 'attach_money' : ($catId === 'features' ? 'widgets' : ($catId === 'compliance' ? 'verified' : ($catId === 'technical' ? 'api' : 'shield')))) }}</span>
                                {{ $category['label'] }}
                            </h3>
                            <div class="space-y-3">
                                @foreach($category['items'] as $item)
                                    <details class="group bg-bg-subtle border border-border-quiet rounded-xl p-5 tbm-shadow">
                                        <summary class="flex items-center justify-between cursor-pointer list-none text-base font-semibold text-text-rich">
                                            {{ $item['q'] }}
                                            <span class="material-symbols-outlined text-primary transition-transform group-open:rotate-180 shrink-0 ml-4">expand_more</span>
                                        </summary>
                                        <div class="mt-4 text-base text-secondary leading-relaxed border-t border-border-quiet pt-4">
                                            {{ $item['a'] }}
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="bg-primary py-16 md:py-24 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center text-white">
            <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-balance mb-4">Still have questions?</h2>
            <p class="text-lg text-primary-fixed-dim mb-8 max-w-2xl mx-auto">Our Kampala support team responds within 2 hours during business hours.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary hover:bg-primary-fixed px-8 py-4 rounded-xl text-sm font-bold shadow-md transition-all">
                <span class="material-symbols-outlined text-base">chat</span>
                <span>Contact Support</span>
            </a>
        </div>
    </section>

</div>
@endsection