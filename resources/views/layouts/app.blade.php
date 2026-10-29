<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TBM Enterprise Suite • Uganda UGX Business Management')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries,typography"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary': '#003e97',
                        'primary-container': '#1455c0',
                        'on-primary': '#ffffff',
                        'on-primary-container': '#c4d3ff',
                        'primary-fixed': '#dae2ff',
                        'primary-fixed-dim': '#b1c5ff',
                        'on-primary-fixed': '#001946',
                        'secondary': '#4d5f7d',
                        'secondary-container': '#c8dbfe',
                        'on-secondary': '#ffffff',
                        'on-secondary-container': '#4e607e',
                        'surface': '#FFFFFF',
                        'surface-dim': '#d0daf0',
                        'surface-bright': '#f9f9ff',
                        'surface-variant': '#d9e3f9',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f0f3ff',
                        'surface-container': '#e7eeff',
                        'surface-container-high': '#dee8fe',
                        'surface-container-highest': '#d9e3f9',
                        'bg-subtle': '#F7F9FC',
                        'border-quiet': '#E7EBF2',
                        'text-rich': '#172033',
                        'on-surface': '#121c2b',
                        'on-surface-variant': '#434653',
                        'inverse-surface': '#273141',
                        'inverse-on-surface': '#ebf1ff',
                        'outline': '#737784',
                        'outline-variant': '#c3c6d5',
                        'tertiary': '#755b00',
                        'tertiary-container': '#cea72c',
                        'tertiary-fixed': '#ffe08e',
                        'highlight-gold': '#F5E7B2',
                        'success': '#16A34A',
                        'warning': '#F59E0B',
                        'danger': '#DC2626',
                        'error': '#ba1a1a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        headline: ['Manrope', 'sans-serif'],
                        numeric: ['Inter', 'monospace'],
                    },
                    spacing: {
                        'sidebar-width': '265px',
                        'header-height': '64px',
                        'gutter': '24px',
                        'margin-desktop': '32px',
                    }
                }
            }
        }
    </script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .font-numeric {
            font-variant-numeric: tabular-nums;
        }
        .tbm-shadow {
            box-shadow: 0 4px 20px rgba(11, 31, 58, 0.04);
        }
        .tbm-modal-shadow {
            box-shadow: 0 20px 40px -15px rgba(0, 62, 151, 0.12);
        }
        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 1rem !important;
            padding: 1.5rem !important;
            border: 1px solid #E7EBF2 !important;
        }
        .swal2-title {
            font-family: 'Manrope', sans-serif !important;
            font-weight: 700 !important;
            color: #172033 !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-bg-subtle text-on-surface font-sans antialiased min-h-screen flex flex-col md:flex-row">
    @php
        $activeUser = auth()->user() ?? \App\Models\User::first();
        $activeBiz = $activeUser ? $activeUser->getActiveBusiness() : \App\Models\Business::first();

        // ✅ Safe business collection – works even if method is missing
        if ($activeUser && $activeUser->isSuperAdmin()) {
            $allBusinesses = \App\Models\Business::all();
        } else {
            $allBusinesses = $activeUser ? \App\Models\Business::where('id', $activeUser->business_id)->get() : collect();
        }
    @endphp

    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-900/40 z-[55] md:hidden hidden backdrop-blur-sm transition-opacity"></div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- MOBILE NAVBAR 1 (Sticky: Logo, Currency, Notifications, Toggler) -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="md:hidden flex items-center justify-between px-3.5 h-14 bg-surface border-b border-border-quiet sticky top-0 z-[45] shadow-xs">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="TBM Suite" class="h-8 w-auto object-contain">
        </div>
        <div class="flex items-center gap-2">
            <!-- Mobile Currency -->
            <div class="relative" id="mobileCurrencySelectorWrapper">
                <button type="button" id="mobileCurrencyDropdownBtn" class="flex items-center gap-1 px-2 py-1.5 rounded-lg border border-primary/20 bg-primary-fixed-dim/20 hover:bg-primary-fixed-dim/40 text-primary transition-colors text-[11px] font-bold cursor-pointer">
                    <img src="{{ $currentCurrency->flag_image_url }}" alt="{{ $currentCurrency->country }}" class="w-4 h-3 object-cover rounded-2xs shadow-2xs" loading="lazy">
                    <span>{{ $currentCurrency->code }}</span>
                    <span class="material-symbols-outlined text-[14px]">expand_more</span>
                </button>
                <div id="mobileCurrencyDropdownMenu" class="hidden absolute right-0 mt-2 w-64 max-h-72 overflow-hidden bg-surface rounded-xl border border-border-quiet tbm-modal-shadow z-50 flex flex-col shadow-xl">
                    <div class="p-2 border-b border-border-quiet bg-bg-subtle flex items-center justify-between text-[11px] font-bold text-text-rich">
                        <span>Select Currency</span>
                        <span class="text-[9px] text-secondary">UGX Base</span>
                    </div>
                    <div class="overflow-y-auto max-h-48 p-1 space-y-0.5">
                        @foreach($activeCurrencies as $curr)
                            <form action="{{ route('currency.switch') }}" method="POST">
                                @csrf
                                <input type="hidden" name="currency" value="{{ $curr->code }}">
                                <button type="submit" class="w-full text-left px-2 py-1.5 rounded-md flex items-center justify-between hover:bg-bg-subtle text-xs transition-colors {{ $currentCurrency->code === $curr->code ? 'bg-primary/10 text-primary font-bold' : 'text-text-rich' }}">
                                    <div class="flex items-center gap-1.5">
                                        <img src="{{ $curr->flag_image_url }}" alt="{{ $curr->country }}" class="w-4 h-3 object-cover rounded-2xs" loading="lazy">
                                        <span class="text-[11px]">{{ $curr->code }}</span>
                                    </div>
                                    <span class="font-numeric font-bold text-[10px]">{{ $curr->symbol }}</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Mobile Notification Bell -->
            <a href="{{ route('notifications') }}" class="relative p-2 text-secondary hover:text-primary hover:bg-bg-subtle rounded-lg transition-colors" title="Notifications">
                <span class="material-symbols-outlined text-[22px]">notifications</span>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-surface"></span>
            </a>

            <!-- Mobile Menu Toggler -->
            <button id="mobileMenuToggle" class="p-2 text-secondary hover:text-primary hover:bg-bg-subtle rounded-xl transition-all border border-border-quiet bg-bg-subtle active:scale-95 flex items-center justify-center" aria-label="Toggle navigation">
                <span class="material-symbols-outlined text-[22px] font-bold" id="mobileMenuIcon">segment</span>
            </button>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- MOBILE NAVBAR 2 (Non-Sticky: Business Switcher & Profile) -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="md:hidden flex items-center justify-between px-3.5 py-2 bg-surface/90 border-b border-border-quiet relative z-20">
        <div class="flex-1 max-w-[65%] min-w-0">
            <form action="{{ route('businesses.switch') }}" method="POST" id="mobileBusinessSwitchForm" class="flex items-center">
                @csrf
                <div class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-border-quiet bg-bg-subtle hover:bg-surface transition-colors cursor-pointer text-xs font-semibold text-text-rich w-full shadow-2xs">
                    <span class="material-symbols-outlined text-primary text-base shrink-0">store</span>
                    <select name="business_id" onchange="document.getElementById('mobileBusinessSwitchForm').submit()" class="bg-transparent border-none outline-none font-bold text-xs cursor-pointer py-0 pl-0 w-full truncate">
                        @foreach($allBusinesses as $b)
                            <option value="{{ $b->id }}" {{ $activeBiz && $activeBiz->id == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <a href="{{ route('profile') }}" class="flex items-center gap-2 pl-2 border-l border-border-quiet hover:opacity-80 transition-opacity shrink-0">
            <div class="w-7 h-7 rounded-full overflow-hidden bg-slate-900 border border-primary/30 shrink-0">
                <img src="{{ $activeUser->avatar }}" alt="{{ $activeUser->name ?? 'User' }}" class="w-full h-full object-cover">
            </div>
            <span class="text-xs font-bold text-text-rich truncate max-w-[90px]">{{ $activeUser->name ?? 'User' }}</span>
        </a>
    </div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-[60] w-sidebar-width bg-surface border-r border-border-quiet flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0">
        <div class="h-header-height px-4 flex items-center justify-between border-b border-border-quiet bg-surface">
            <div class="flex items-center gap-2.5 min-w-0">
                <img src="{{ asset('images/logo.png') }}" alt="TBM Enterprise" class="h-9 w-auto object-contain">
            </div>
            <button type="button" id="sidebarCloseBtn" class="md:hidden p-1.5 text-secondary hover:text-danger hover:bg-red-50 rounded-lg transition-colors" title="Close Menu">
                <span class="material-symbols-outlined text-xl font-bold">close</span>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-0.5">
            <div class="px-3 py-1 text-[10px] font-bold text-outline uppercase tracking-wider">Core Workspace</div>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('dashboard') ? 'fill' : '' }}">space_dashboard</span>
                <span>Business Dashboard</span>
            </a>

            {{-- User management link is reserved for super admin console --}}

            <a href="{{ route('businesses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('businesses.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('businesses.*') ? 'fill' : '' }}">store</span>
                <span>Multi-Business & Branches</span>
            </a>

            <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-outline uppercase tracking-wider">Commerce & Operations</div>

            <a href="{{ route('crm') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('crm') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('crm') ? 'fill' : '' }}">hub</span>
                <span>CRM & Lead Pipeline</span>
            </a>

            {{-- Items & Inventory --}}
            @php $itemsActive = request()->is('products*') || request()->is('inventory*'); @endphp
            <div>
                <button type="button" onclick="toggleNavGroup('itemsNav', this)" class="dropdown-toggle w-full flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ $itemsActive ? 'bg-indigo-600 text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-lg {{ $itemsActive ? 'fill' : '' }}">inventory_2</span>
                        <span>Items & Stock</span>
                    </div>
                    <span id="itemsNavIcon" class="material-symbols-outlined text-sm transition-transform {{ $itemsActive ? 'rotate-180' : '' }}">expand_more</span>
                </button>
                <div id="itemsNav" class="{{ $itemsActive ? '' : 'hidden' }} ml-4 mt-0.5 space-y-0.5 border-l-2 border-indigo-200 pl-3">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('products.index') || request()->routeIs('products.show') ? 'text-indigo-700 bg-indigo-50 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">grid_view</span>
                        <span>Products & Items Catalog</span>
                    </a>
                    <a href="{{ route('products.create') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('products.create') ? 'text-indigo-700 bg-indigo-50 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">add_box</span>
                        <span>Add New Item (with Photo)</span>
                    </a>
                    <a href="{{ route('inventory.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('inventory.*') ? 'text-indigo-700 bg-indigo-50 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">warehouse</span>
                        <span>Inventory & Stock Control</span>
                    </a>
                </div>
            </div>

            {{-- Sales Module --}}
            @php $salesActive = request()->is('sales/*') || request()->is('invoices*') || (request()->is('parties*') && request()->query('type') === 'customer'); @endphp
            <div>
                <button type="button" onclick="toggleNavGroup('salesNav', this)" class="dropdown-toggle w-full flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ $salesActive ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-lg {{ $salesActive ? 'fill' : '' }}">trending_up</span>
                        <span>Sales Module</span>
                    </div>
                    <span id="salesNavIcon" class="material-symbols-outlined text-sm transition-transform {{ $salesActive ? 'rotate-180' : '' }}">expand_more</span>
                </button>
                <div id="salesNav" class="{{ $salesActive ? '' : 'hidden' }} ml-4 mt-0.5 space-y-0.5 border-l-2 border-primary/20 pl-3">
                    <a href="{{ route('parties.index', ['type' => 'customer']) }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('parties.*') && request()->query('type') === 'customer' ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">person</span>
                        <span>Customers</span>
                    </a>
                    <a href="{{ route('sales.orders.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('sales.orders.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">shopping_cart</span>
                        <span>Sales Orders</span>
                    </a>
                    <a href="{{ route('sales.receipts.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('sales.receipts.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">receipt</span>
                        <span>Sales Receipts</span>
                    </a>
                    <a href="{{ route('sales.payments.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('sales.payments.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">payments</span>
                        <span>Payments Received</span>
                    </a>
                    <a href="{{ route('sales.quotes.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('sales.quotes.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">request_quote</span>
                        <span>Quotes</span>
                    </a>
                    <a href="{{ route('invoices.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('invoices.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">receipt_long</span>
                        <span>Invoices</span>
                    </a>
                    <a href="{{ route('sales.credit-notes.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('sales.credit-notes.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">assignment_return</span>
                        <span>Credit Notes</span>
                    </a>
                </div>
            </div>

            {{-- Purchases Module --}}
            @php $purchasesActive = request()->is('purchases/*') || request()->is('expenses*') || (request()->is('parties*') && request()->query('type') === 'vendor'); @endphp
            <div>
                <button type="button" onclick="toggleNavGroup('purchasesNav', this)" class="dropdown-toggle w-full flex items-center justify-between gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ $purchasesActive ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-lg {{ $purchasesActive ? 'fill' : '' }}">local_shipping</span>
                        <span>Purchases Module</span>
                    </div>
                    <span id="purchasesNavIcon" class="material-symbols-outlined text-sm transition-transform {{ $purchasesActive ? 'rotate-180' : '' }}">expand_more</span>
                </button>
                <div id="purchasesNav" class="{{ $purchasesActive ? '' : 'hidden' }} ml-4 mt-0.5 space-y-0.5 border-l-2 border-primary/20 pl-3">
                    <a href="{{ route('parties.index', ['type' => 'vendor']) }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('parties.*') && request()->query('type') === 'vendor' ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">store</span>
                        <span>Vendors</span>
                    </a>
                    <a href="{{ route('expenses.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('expenses.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">receipt</span>
                        <span>Expenditures</span>
                    </a>
                    <a href="{{ route('purchases.orders.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('purchases.orders.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">assignment</span>
                        <span>Purchase Orders</span>
                    </a>
                    <a href="{{ route('purchases.bills.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('purchases.bills.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">receipt_long</span>
                        <span>Bills</span>
                    </a>
                    <a href="{{ route('purchases.payments.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('purchases.payments.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">payments</span>
                        <span>Payments Made</span>
                    </a>
                    <a href="{{ route('purchases.creditors.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs font-medium transition-all {{ request()->routeIs('purchases.creditors.*') ? 'text-primary bg-primary/10 font-bold' : 'text-secondary hover:text-text-rich hover:bg-bg-subtle' }}">
                        <span class="material-symbols-outlined text-sm">account_balance</span>
                        <span>Creditors</span>
                    </a>
                </div>
            </div>

            <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-outline uppercase tracking-wider">Accounting & Compliance</div>

            <a href="{{ route('expenses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('expenses.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('expenses.*') ? 'fill' : '' }}">payments</span>
                <span>Expense Management</span>
            </a>

            <a href="{{ route('payroll.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('payroll.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('payroll.*') ? 'fill' : '' }}">badge</span>
                <span>Payroll & Payslips</span>
            </a>

            <a href="{{ route('taxes.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('taxes.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('taxes.*') ? 'fill' : '' }}">account_balance</span>
                <span>Tax Compliance (URA)</span>
            </a>

            <a href="{{ route('reports') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('reports*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('reports*') ? 'fill' : '' }}">query_stats</span>
                <span>Reports & P&L Statement</span>
            </a>

            <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-outline uppercase tracking-wider">Account & Billing</div>

            <a href="{{ route('subscription.portal') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('subscription.portal') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('subscription.portal') ? 'fill' : '' }}">credit_card</span>
                <span>Subscription Plan</span>
                <span class="ml-auto text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-primary/10 text-primary">UGX</span>
            </a>

            <a href="{{ route('subscription.pricing') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('subscription.pricing') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg">rocket_launch</span>
                <span>Upgrade Plans</span>
            </a>

            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('profile') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('profile') ? 'fill' : '' }}">account_circle</span>
                <span>My Profile</span>
            </a>

            <a href="{{ route('notifications') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('notifications') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg">notifications</span>
                <span>Notifications</span>
                <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
            </a>

            <a href="{{ route('feedback.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('feedback.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('feedback.*') ? 'fill' : '' }}">feedback</span>
                <span>Submit Feedback</span>
                <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded bg-primary/10 text-primary">Admin</span>
            </a>

            <a href="{{ route('settings') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('settings') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('settings') ? 'fill' : '' }}">settings</span>
                <span>Organization Settings</span>
            </a>
        </nav>

        <!-- Sidebar Footer & Logout -->
        <div class="p-3 border-t border-border-quiet bg-bg-subtle space-y-2">
            <div class="flex items-center justify-between">
                <a href="{{ route('profile') }}" class="flex items-center gap-2 flex-1 min-w-0 hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-900 border border-primary/30 shrink-0">
                        <img src="{{ $activeUser->avatar }}" alt="{{ $activeUser->name ?? 'User' }}" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <span class="font-bold text-xs text-text-rich truncate block leading-none">{{ $activeUser->name ?? 'User' }}</span>
                        <span class="text-[10px] text-secondary truncate block mt-0.5">{{ $activeUser->role ?? 'Member' }}</span>
                    </div>
                </a>
                <form id="userLogoutForm" action="{{ route('logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="button" id="userLogoutBtn" data-logout="user" class="p-1.5 rounded-lg text-secondary hover:text-danger hover:bg-red-50 transition-colors" title="Sign Out">
                        <span class="material-symbols-outlined text-lg">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Canvas -->
    <div class="flex-1 md:ml-sidebar-width flex flex-col min-h-screen">
        <!-- Top Navbar Header (Desktop) -->
        <header class="h-header-height sticky top-0 z-30 bg-surface border-b border-border-quiet px-4 md:px-margin-desktop hidden md:flex items-center justify-between gap-4 shadow-sm">
            <!-- Left: Business Switcher -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="relative" id="businessSwitcherWrapper">
                    <form action="{{ route('businesses.switch') }}" method="POST" id="businessSwitchForm" class="flex items-center">
                        @csrf
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border-quiet bg-bg-subtle hover:bg-surface transition-colors cursor-pointer text-xs font-semibold text-text-rich">
                            <span class="material-symbols-outlined text-primary text-base">domain</span>
                            <select name="business_id" onchange="document.getElementById('businessSwitchForm').submit()" class="bg-transparent border-none outline-none font-bold text-xs cursor-pointer py-0 pr-6 pl-0">
                                @foreach($allBusinesses as $b)
                                    <option value="{{ $b->id }}" {{ $activeBiz && $activeBiz->id == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Middle: Search Bar -->
            <div class="flex-1 max-w-md hidden sm:block">
                <div class="relative">
                    <span class="material-symbols-outlined text-secondary absolute left-3 top-2.5 text-lg">search</span>
                    <input type="text" id="userGlobalSearchInput" placeholder="Search products, invoices, customers, tasks... (Ctrl+K)" 
                        class="w-full h-9 pl-9 pr-14 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none transition-all">
                    <kbd class="absolute right-2.5 top-2 text-[10px] font-mono text-secondary bg-surface border border-border-quiet px-1.5 py-0.5 rounded shadow-2xs">Ctrl K</kbd>
                </div>
            </div>

            <!-- Right: Currency & Profile -->
            <div class="flex items-center gap-2.5 shrink-0">
                <div class="relative" id="currencySelectorWrapper">
                    <button type="button" id="currencyDropdownBtn" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-primary/20 bg-primary-fixed-dim/20 hover:bg-primary-fixed-dim/40 text-primary transition-colors text-xs font-semibold cursor-pointer">
                        <img src="{{ $currentCurrency->flag_image_url }}" srcset="{{ $currentCurrency->flag_image_url_2x }} 2x" alt="{{ $currentCurrency->country }}" class="w-4 h-3 object-cover rounded-sm shadow-sm" loading="lazy">
                        <span>{{ $currentCurrency->code }}</span>
                        <span class="material-symbols-outlined text-xs">expand_more</span>
                    </button>
                    <div id="currencyDropdownMenu" class="hidden absolute right-0 mt-2 w-72 max-h-80 overflow-hidden bg-surface rounded-xl border border-border-quiet tbm-modal-shadow z-50 flex flex-col">
                        <div class="p-2.5 border-b border-border-quiet bg-bg-subtle flex items-center justify-between text-xs font-bold text-text-rich">
                            <span>Select Currency</span>
                            <span class="text-[10px] text-secondary">Default: UGX</span>
                        </div>
                        <div class="overflow-y-auto max-h-56 p-1 space-y-0.5">
                            @foreach($activeCurrencies as $curr)
                                <form action="{{ route('currency.switch') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="currency" value="{{ $curr->code }}">
                                    <button type="submit" class="w-full text-left px-2.5 py-1.5 rounded-md flex items-center justify-between hover:bg-bg-subtle text-xs transition-colors {{ $currentCurrency->code === $curr->code ? 'bg-primary/10 text-primary font-bold' : 'text-text-rich' }}">
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $curr->flag_image_url }}" alt="{{ $curr->country }}" class="w-5 h-3.5 object-cover rounded-sm shadow-sm" loading="lazy">
                                            <span>{{ $curr->code }} - {{ $curr->name }}</span>
                                        </div>
                                        <span class="font-numeric font-bold text-[11px]">{{ $curr->symbol }}</span>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('notifications') }}" class="relative p-1.5 text-secondary hover:text-primary hover:bg-bg-subtle rounded-lg transition-colors mx-1">
                    <span class="material-symbols-outlined text-[22px]">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-surface"></span>
                </a>

                <a href="{{ route('profile') }}" class="flex items-center gap-2 pl-2 border-l border-border-quiet hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-900 border border-primary/30 shrink-0">
                        <img src="{{ $activeUser->avatar }}" alt="{{ $activeUser->name ?? 'User' }}" class="w-full h-full object-cover">
                    </div>
                    <div class="hidden lg:flex flex-col text-left">
                        <span class="text-xs font-bold text-text-rich leading-none">{{ $activeUser->name ?? 'Member' }}</span>
                        <span class="text-[10px] text-primary font-semibold leading-tight mt-0.5 truncate max-w-[120px]">{{ $activeBiz->name ?? 'My Business' }}</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Expiration Alert -->
        @if(!empty($expirationAlert))
            <div class="bg-gradient-to-r {{ $expirationAlert['urgency'] === 'critical' ? 'from-red-600 to-red-700 text-white' : 'from-amber-500 to-amber-600 text-white' }} px-4 py-2.5 px-md:px-margin-desktop flex flex-col sm:flex-row items-center justify-between gap-2 shadow-sm text-xs">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">notification_important</span>
                    <span><strong>{{ $expirationAlert['title'] }}:</strong> {{ $expirationAlert['message'] }}</span>
                </div>
                <a href="{{ route('subscription.checkout', ['plan' => 'premium', 'cycle' => 'monthly']) }}" class="px-3 py-1 rounded-md bg-white text-slate-900 font-bold hover:bg-slate-100 transition-colors shrink-0 shadow-sm">
                    Upgrade Subscription Plan →
                </a>
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-margin-desktop bg-bg-subtle overflow-x-hidden pb-24 md:pb-4">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Bottom Nav -->
    <nav id="mobileBottomNav" class="md:hidden fixed bottom-0 left-0 right-0 z-40">
        <div class="mx-3 mb-3 bg-white/95 backdrop-blur-xl border border-white/60 rounded-3xl shadow-2xl overflow-hidden" style="box-shadow: 0 8px 32px rgba(0,62,151,0.18), 0 2px 8px rgba(0,0,0,0.10);">
            <div class="flex items-center justify-around px-1 py-1.5">
                <a href="{{ route('dashboard') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('dashboard') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('dashboard') ? 'fill' : '' }}">space_dashboard</span>
                    <span class="text-[9px] font-bold leading-none">Home</span>
                </a>
                <a href="{{ route('products.index') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('products.*') || request()->routeIs('inventory.*') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('products.*') ? 'fill' : '' }}">inventory_2</span>
                    <span class="text-[9px] font-bold leading-none">Items</span>
                </a>
                <a href="{{ route('invoices.index') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('invoices.*') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('invoices.*') ? 'fill' : '' }}">receipt_long</span>
                    <span class="text-[9px] font-bold leading-none">Invoices</span>
                </a>
                <a href="{{ route('reports') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('reports*') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('reports*') ? 'fill' : '' }}">query_stats</span>
                    <span class="text-[9px] font-bold leading-none">Reports</span>
                </a>
                <button type="button" id="mobileMoreBtn" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all text-secondary">
                    <span class="material-symbols-outlined text-xl">apps</span>
                    <span class="text-[9px] font-bold leading-none">More</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Styles for Mobile Nav -->
    <style>
        .mobile-nav-item {
            color: #7a8faf;
        }
        .mobile-nav-item.mobile-nav-active {
            background: linear-gradient(135deg, #003e97 0%, #1455c0 60%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(0, 62, 151, 0.40);
        }
        .mobile-nav-item:not(.mobile-nav-active):active {
            background: rgba(0, 62, 151, 0.08);
            color: #003e97;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Sidebar Toggle
            const toggleBtn   = document.getElementById('mobileMenuToggle');
            const closeBtn    = document.getElementById('sidebarCloseBtn');
            const moreBtn     = document.getElementById('mobileMoreBtn');
            const sidebar     = document.getElementById('sidebar');
            const overlay     = document.getElementById('mobileSidebarOverlay');
            const menuIcon    = document.getElementById('mobileMenuIcon');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                if (menuIcon) menuIcon.textContent = 'close';
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                if (menuIcon) menuIcon.textContent = 'segment';
            }

            if (toggleBtn) toggleBtn.addEventListener('click', function() {
                sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
            });
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (moreBtn) moreBtn.addEventListener('click', openSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            // Close sidebar when tapping a link OR a button that is NOT a dropdown toggle
            document.querySelectorAll('#sidebar a, #sidebar button:not(.dropdown-toggle)').forEach(el => {
                el.addEventListener('click', function() {
                    if (window.innerWidth < 768) closeSidebar();
                });
            });

            // Currency Dropdowns
            const currencyBtn = document.getElementById('currencyDropdownBtn');
            const currencyMenu = document.getElementById('currencyDropdownMenu');
            if (currencyBtn && currencyMenu) {
                currencyBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currencyMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', () => {
                    currencyMenu.classList.add('hidden');
                });
            }

            const mobileCurrencyBtn = document.getElementById('mobileCurrencyDropdownBtn');
            const mobileCurrencyMenu = document.getElementById('mobileCurrencyDropdownMenu');
            if (mobileCurrencyBtn && mobileCurrencyMenu) {
                mobileCurrencyBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    mobileCurrencyMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', () => {
                    mobileCurrencyMenu.classList.add('hidden');
                });
            }

            // SweetAlert messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#003e97',
                    timer: 3500,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Alert',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#003e97'
                });
            @endif

            // Logout confirmation
            const userLogoutBtn = document.getElementById('userLogoutBtn');
            if (userLogoutBtn) {
                userLogoutBtn.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Sign Out?',
                        text: 'Are you sure you want to end your active workspace session?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#003e97',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Yes, Sign Out',
                        cancelButtonText: 'Stay'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            document.getElementById('userLogoutForm').submit();
                        }
                    });
                });
            }
        });

        // Sidebar Accordion Toggles
        function toggleNavGroup(navId, btn) {
            const panel = document.getElementById(navId);
            const icon = btn.querySelector('span.material-symbols-outlined:last-child');
            if (!panel) return;
            const isHidden = panel.classList.contains('hidden');
            panel.classList.toggle('hidden', !isHidden);
            if (icon) icon.classList.toggle('rotate-180', isHidden);
        }
    </script>
    @stack('scripts')
</body>
</html>