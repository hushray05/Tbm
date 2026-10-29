<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin Console • TBM Enterprise Suite Uganda')</title>
<!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <!-- Google Fonts: Manrope (Headlines) & Inter (Body & Numbers) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS with Complete TBM Design Tokens -->
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
                        'sidebar-width': '270px',
                        'header-height': '64px',
                        'gutter': '24px',
                        'margin-desktop': '32px',
                    }
                }
            }
        }
    </script>

    <!-- ApexCharts for Interactive Real-Time Visuals -->
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
        $superAdminUser = auth()->user();
    @endphp

    <!-- Mobile Sidebar Overlay -->
    <div id="adminMobileSidebarOverlay" class="fixed inset-0 bg-slate-900/40 z-[55] md:hidden hidden backdrop-blur-sm transition-opacity"></div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- ADMIN MOBILE NAVBAR 1 (Sticky: Brand Logo/Name, Currency, Notifications, Fancy Toggler) -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="md:hidden flex items-center justify-between px-3.5 h-14 bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-[45] shadow-md">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center font-bold shadow-sm">
                <span class="material-symbols-outlined text-lg fill">admin_panel_settings</span>
            </div>
            <span class="font-headline font-bold text-white text-base tracking-tight">Super Admin</span>
        </div>
        
        <div class="flex items-center gap-2">
            <!-- Mobile Admin Currency Switcher -->
            <div class="relative" id="mobileAdminCurrencySelectorWrapper">
                <button type="button" id="mobileAdminCurrencyDropdownBtn" class="flex items-center gap-1 px-2 py-1.5 rounded-lg bg-slate-800 border border-slate-700 hover:border-amber-400/50 text-slate-200 transition-colors text-[11px] font-bold cursor-pointer">
                    <img src="{{ $currentCurrency->flag_image_url }}" alt="{{ $currentCurrency->country }}" class="w-4 h-3 object-cover rounded-2xs shadow-2xs" loading="lazy">
                    <span>{{ $currentCurrency->code }}</span>
                    <span class="material-symbols-outlined text-[14px] text-slate-400">expand_more</span>
                </button>

                <div id="mobileAdminCurrencyDropdownMenu" class="hidden absolute right-0 mt-2 w-64 max-h-72 overflow-hidden bg-slate-900 rounded-xl border border-slate-700 shadow-2xl z-50 flex flex-col text-slate-200">
                    <div class="p-2 border-b border-slate-800 bg-slate-950 flex items-center justify-between text-[11px] font-bold">
                        <span>Admin Currency</span>
                        <span class="text-[9px] text-amber-400 font-extrabold">Default: UGX</span>
                    </div>
                    <div class="overflow-y-auto max-h-48 p-1 space-y-0.5">
                        @foreach($activeCurrencies as $curr)
                            <form action="{{ route('currency.switch') }}" method="POST">
                                @csrf
                                <input type="hidden" name="currency" value="{{ $curr->code }}">
                                <button type="submit" class="w-full text-left px-2 py-1.5 rounded-md flex items-center justify-between hover:bg-slate-800 text-xs transition-colors {{ $currentCurrency->code === $curr->code ? 'bg-amber-400/10 text-amber-400 font-bold' : 'text-slate-300' }}">
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

            <!-- Mobile Admin Notification Bell -->
            <a href="{{ route('admin.notifications') }}" class="relative p-2 text-slate-400 hover:text-amber-400 hover:bg-slate-800 rounded-lg transition-colors" title="Notifications">
                <span class="material-symbols-outlined text-[22px]">notifications</span>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-slate-900"></span>
            </a>

            <!-- Fancy Mobile Menu Toggler (morphs between segment and close) -->
            <button id="adminMobileMenuToggle" class="p-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-xl transition-all border border-slate-700 bg-slate-800 active:scale-95 flex items-center justify-center" aria-label="Toggle navigation">
                <span class="material-symbols-outlined text-[22px] font-bold" id="adminMobileMenuIcon">segment</span>
            </button>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- ADMIN MOBILE NAVBAR 2 (Non-Sticky: Platform Status & Admin Profile) -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <div class="md:hidden flex items-center justify-between px-3.5 py-2 bg-slate-800/90 border-b border-slate-700 relative z-20 text-slate-200">
        <!-- Platform Status -->
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-xs font-bold text-amber-400">Platform Governance</span>
        </div>

        <!-- Super Admin Profile Pill -->
        <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 pl-2 border-l border-slate-700 hover:opacity-80 transition-opacity shrink-0">
            <div class="w-7 h-7 rounded-full overflow-hidden bg-slate-900 border border-amber-400/40 shadow-sm shrink-0">
                <img src="{{ $superAdminUser->avatar }}" alt="Super Admin" class="w-full h-full object-cover">
            </div>
            <span class="text-xs font-bold text-slate-100 truncate max-w-[110px]">{{ $superAdminUser->name ?? 'Admin' }}</span>
        </a>
    </div>

    <!-- Admin Sidebar Navigation -->
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-[60] w-sidebar-width bg-surface border-r border-border-quiet flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0">
        <!-- Header with Close 'X' Button on Mobile -->
        <div class="h-header-height px-4 flex items-center justify-between border-b border-border-quiet bg-surface">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-primary-container flex items-center justify-center text-white shadow-sm shrink-0">
                    <span class="material-symbols-outlined text-xl fill">admin_panel_settings</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-headline font-bold text-primary text-xs truncate">Super Admin Console</span>
                    <span class="text-[9px] font-semibold text-secondary uppercase tracking-wider">Platform Governance</span>
                </div>
            </div>
            <button type="button" id="adminSidebarCloseBtn" class="md:hidden p-1.5 text-secondary hover:text-danger hover:bg-red-50 rounded-lg transition-colors" title="Close Menu">
                <span class="material-symbols-outlined text-xl font-bold">close</span>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-3 py-1.5 space-y-0.5">
            <div class="px-3 py-1 text-[10px] font-bold text-outline uppercase tracking-wider">Platform Administration</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.dashboard') ? 'fill' : '' }}">space_dashboard</span>
                <span>Platform Telemetry</span>
            </a>

            <a href="{{ route('admin.businesses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.businesses.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.businesses.*') ? 'fill' : '' }}">domain</span>
                <span>Tenant Businesses</span>
                <span class="ml-auto text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-amber-400/20 text-amber-900">Manage</span>
            </a>

            <!-- Users Management (Admin) -->
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.users.*') ? 'fill' : '' }}">group</span>
                <span>Users Management</span>
            </a>

            <!-- Dedicated Subscriptions Section -->
            <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-primary uppercase tracking-wider flex items-center justify-between">
                <span>SUBSCRIPTIONS & PLANS</span>
                <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded bg-primary/10 text-primary">SaaS</span>
            </div>

            <a href="{{ route('admin.subscriptions.dashboard') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.dashboard') ? 'fill' : '' }}">analytics</span>
                <span>Subscription Dashboard</span>
            </a>

            <a href="{{ route('admin.subscriptions.plans.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.plans.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.plans.*') ? 'fill' : '' }}">layers</span>
                <span>Subscription Plans</span>
            </a>

            <a href="{{ route('admin.subscriptions.features.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.features.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.features.*') ? 'fill' : '' }}">toggle_on</span>
                <span>Plan Features Matrix</span>
            </a>

            <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.subscribers.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.subscribers.*') ? 'fill' : '' }}">group</span>
                <span>Subscribers List</span>
            </a>

            <a href="{{ route('admin.subscriptions.trials.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.trials.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.trials.*') ? 'fill' : '' }}">schedule</span>
                <span>Trial Accounts</span>
            </a>

            <a href="{{ route('admin.subscriptions.payments.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.payments.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.payments.*') ? 'fill' : '' }}">paid</span>
                <span>Subscription Payments</span>
            </a>

            <a href="{{ route('admin.subscriptions.invoices.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.invoices.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.invoices.*') ? 'fill' : '' }}">receipt_long</span>
                <span>Subscription Invoices</span>
            </a>

            <a href="{{ route('admin.subscriptions.settings.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.subscriptions.settings.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.subscriptions.settings.*') ? 'fill' : '' }}">tune</span>
                <span>Billing & Subscription Settings</span>
            </a>

            <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-outline uppercase tracking-wider">Catalog & System Control</div>

            <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.pages.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.pages.*') ? 'fill' : '' }}">article</span>
                <span>Public Pages</span>
            </a>

            <a href="{{ route('admin.feedback.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.feedback.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.feedback.*') ? 'fill' : '' }}">feedback</span>
                <span>User Feedback & Inquiries</span>
            </a>

            <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.audit-logs.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.audit-logs.*') ? 'fill' : '' }}">history</span>
                <span>Audit Logs & Overrides</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.products.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.products.*') ? 'fill' : '' }}">inventory</span>
                <span>Products Catalog CRUD</span>
            </a>

            <a href="{{ route('admin.currencies.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.currencies.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.currencies.*') ? 'fill' : '' }}">currency_exchange</span>
                <span>Currency Engine & FX</span>
            </a>

            <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.companies.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.companies.*') ? 'fill' : '' }}">corporate_fare</span>
                <span>Featured Companies</span>
            </a>

            <a href="{{ route('admin.leads.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.leads.*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.leads.*') ? 'fill' : '' }}">contacts</span>
                <span>CRM Leads Pipeline</span>
            </a>

            <a href="{{ route('admin.profile') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.profile*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg {{ request()->routeIs('admin.profile*') ? 'fill' : '' }}">account_circle</span>
                <span>Executive Profile</span>
            </a>

            <a href="{{ route('admin.notifications') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request()->routeIs('admin.notifications') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                <span class="material-symbols-outlined text-lg">notifications</span>
                <span>Notifications</span>
                <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
            </a>
        </nav>

        <!-- Sidebar Footer & Logout -->
        <div class="p-3 border-t border-border-quiet bg-bg-subtle space-y-2">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-2.5 flex-1 min-w-0 hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-900 border border-primary/30 shrink-0">
                        <img src="{{ $superAdminUser->avatar }}" alt="Super Admin" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <span class="font-bold text-xs text-text-rich truncate block leading-none">{{ $superAdminUser->name ?? 'Admin' }}</span>
                        <span class="text-[10px] text-secondary truncate block mt-0.5">Super Administrator</span>
                    </div>
                </a>
                
                <form id="adminLogoutForm" action="{{ route('logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="button" id="adminLogoutBtn" class="p-1.5 rounded-lg text-secondary hover:text-danger hover:bg-red-50 transition-colors" title="Sign Out">
                        <span class="material-symbols-outlined text-lg">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-sidebar-width flex flex-col min-h-screen">
        <!-- Top Admin Header (Desktop: Super Administrator Executive Console) -->
        <header class="h-header-height sticky top-0 z-30 bg-slate-900 text-white border-b border-slate-800 px-4 md:px-margin-desktop hidden md:flex items-center justify-between gap-4 shadow-md">
            <!-- Left: Super Admin Platform Badge -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-amber-400 text-slate-900 flex items-center gap-1 shadow-xs">
                        <span class="material-symbols-outlined text-xs fill">shield</span>
                        SUPER ADMIN
                    </span>
                    <span class="hidden xl:inline text-xs font-semibold text-slate-300">Platform Control Engine</span>
                </div>
            </div>

            <!-- Middle: Super Admin Command Search Bar -->
            <div class="flex-1 max-w-lg hidden sm:block">
                <div class="relative">
                    <span class="material-symbols-outlined text-slate-400 absolute left-3 top-2.5 text-lg">search</span>
                    <input type="text" id="adminGlobalSearchInput" placeholder="Search subscribers, plans, payments, audit logs... (Ctrl+K)" 
                        class="w-full h-9 pl-9 pr-14 rounded-lg border border-slate-700 bg-slate-800 focus:bg-slate-750 focus:border-amber-400 text-xs text-slate-100 placeholder-slate-400 outline-none transition-all">
                    <kbd class="absolute right-2.5 top-2 text-[10px] font-mono text-slate-400 bg-slate-900 border border-slate-700 px-1.5 py-0.5 rounded">Ctrl K</kbd>
                </div>
            </div>

            <!-- Right: System Status, Interactive Currency Switcher & Profile -->
            <div class="flex items-center gap-3 shrink-0">
                <!-- System Health Status Badge -->
                <div class="hidden lg:flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[11px] font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>System 99.98% Live</span>
                </div>

                <!-- Admin Interactive Currency Switcher -->
                <div class="relative" id="adminCurrencySelectorWrapper">
                    <button type="button" id="adminCurrencyDropdownBtn" class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 hover:border-amber-400/50 text-xs font-semibold text-slate-200 transition-colors cursor-pointer">
                        <img src="{{ $currentCurrency->flag_image_url }}" srcset="{{ $currentCurrency->flag_image_url_2x ?? $currentCurrency->flag_image_url }} 2x" alt="{{ $currentCurrency->country }}" class="w-4 h-3 object-cover rounded-sm shadow-sm" loading="lazy">
                        <span class="font-bold">{{ $currentCurrency->code }}</span>
                        <span class="material-symbols-outlined text-xs text-slate-400">expand_more</span>
                    </button>

                    <div id="adminCurrencyDropdownMenu" class="hidden absolute right-0 mt-2 w-72 max-h-80 overflow-hidden bg-slate-900 rounded-xl border border-slate-700 shadow-2xl z-50 flex flex-col text-slate-200">
                        <div class="p-2.5 border-b border-slate-800 bg-slate-950 flex items-center justify-between text-xs font-bold">
                            <span>Admin Switch Currency</span>
                            <span class="text-[10px] text-amber-400 font-extrabold">Default: UGX</span>
                        </div>
                        <div class="overflow-y-auto max-h-56 p-1 space-y-0.5">
                            @foreach($activeCurrencies as $curr)
                                <form action="{{ route('currency.switch') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="currency" value="{{ $curr->code }}">
                                    <button type="submit" class="w-full text-left px-2.5 py-1.5 rounded-md flex items-center justify-between hover:bg-slate-800 text-xs transition-colors {{ $currentCurrency->code === $curr->code ? 'bg-amber-400/10 text-amber-400 font-bold' : 'text-slate-300' }}">
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $curr->flag_image_url }}" alt="{{ $curr->country }}" class="w-4 h-3 object-cover rounded-sm shadow-sm" loading="lazy">
                                            <span>{{ $curr->code }} - {{ $curr->name }}</span>
                                        </div>
                                        <span class="font-numeric font-bold text-[11px]">{{ $curr->symbol }}</span>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Admin Notification Bell -->
                <a href="{{ route('admin.notifications') }}" class="relative p-1.5 text-slate-400 hover:text-amber-400 hover:bg-slate-800 rounded-lg transition-colors mx-1">
                    <span class="material-symbols-outlined text-[22px]">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-slate-900"></span>
                </a>

                <!-- Super Admin Avatar Pill -->
                <div class="flex items-center gap-2 pl-2 border-l border-slate-800">
                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 hover:opacity-90 transition-opacity">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-800 border border-amber-400/40 shadow-sm shrink-0">
                            <img src="{{ $superAdminUser->avatar }}" alt="Super Admin" class="w-full h-full object-cover">
                        </div>
                        <div class="hidden xl:flex flex-col text-left">
                            <span class="text-xs font-bold text-slate-100 leading-none">{{ $superAdminUser->name ?? 'Admin' }}</span>
                            <span class="text-[10px] text-amber-400 font-semibold leading-tight mt-0.5">Super Admin</span>
                        </div>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Body Canvas -->
        <main class="flex-1 p-4 md:p-margin-desktop bg-bg-subtle overflow-x-hidden pb-24 md:pb-4">
            @yield('content')
        </main>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- ADMIN MOBILE BOTTOM NAVIGATION BAR (hidden on md+)          -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <nav id="adminMobileBottomNav" class="md:hidden fixed bottom-0 left-0 right-0 z-40">
        <!-- Frosted glass outer container -->
        <div class="mx-3 mb-3 bg-slate-900/95 backdrop-blur-xl border border-slate-700/60 rounded-3xl shadow-2xl overflow-hidden" style="box-shadow: 0 8px 32px rgba(0,0,0,0.3), 0 2px 8px rgba(0,0,0,0.20);">
            <div class="flex items-center justify-around px-1 py-1.5 text-slate-400">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('admin.dashboard') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.dashboard') ? 'fill' : '' }}">dashboard</span>
                    <span class="text-[9px] font-bold leading-none">Home</span>
                </a>

                <!-- Businesses -->
                <a href="{{ route('admin.businesses.index') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('admin.businesses.*') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.businesses.*') ? 'fill' : '' }}">domain</span>
                    <span class="text-[9px] font-bold leading-none">Tenants</span>
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('admin.users.*') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.users.*') ? 'fill' : '' }}">group</span>
                    <span class="text-[9px] font-bold leading-none">Users</span>
                </a>

                <!-- Products -->
                <a href="{{ route('admin.products.index') }}" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all {{ request()->routeIs('admin.products.*') ? 'mobile-nav-active' : '' }}">
                    <span class="material-symbols-outlined text-xl {{ request()->routeIs('admin.products.*') ? 'fill' : '' }}">inventory</span>
                    <span class="text-[9px] font-bold leading-none">Products</span>
                </a>

                <!-- More Menu (opens sidebar) -->
                <button type="button" id="adminMobileMoreBtn" class="mobile-nav-item flex flex-col justify-center items-center gap-0.5 w-14 h-14 rounded-full transition-all text-slate-400">
                    <span class="material-symbols-outlined text-xl">apps</span>
                    <span class="text-[9px] font-bold leading-none">More</span>
                </button>

            </div>
        </div>
    </nav>

    <!-- SweetAlert & Delete Interceptor Scripts -->
    <style>
        /* Mobile Bottom Nav Active State - Gradient Blue Circle */
        .mobile-nav-item {
            color: #94a3b8; /* slate-400 */
        }
        .mobile-nav-item.mobile-nav-active {
            background: linear-gradient(135deg, #003e97 0%, #1455c0 60%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(0, 62, 151, 0.40);
        }
        .mobile-nav-item:not(.mobile-nav-active):active {
            background: rgba(15, 23, 42, 0.5); /* slate-900 */
            color: #fbbf24; /* amber-400 */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ── Mobile Sidebar Toggle ──
            const adminToggleBtn = document.getElementById('adminMobileMenuToggle');
            const adminCloseBtn  = document.getElementById('adminSidebarCloseBtn');
            const adminMoreBtn   = document.getElementById('adminMobileMoreBtn');
            const adminSidebar   = document.getElementById('adminSidebar');
            const adminOverlay   = document.getElementById('adminMobileSidebarOverlay');
            const adminMenuIcon  = document.getElementById('adminMobileMenuIcon');

            function openAdminSidebar() {
                adminSidebar.classList.remove('-translate-x-full');
                adminOverlay.classList.remove('hidden');
                if (adminMenuIcon) adminMenuIcon.textContent = 'close';
            }
            function closeAdminSidebar() {
                adminSidebar.classList.add('-translate-x-full');
                adminOverlay.classList.add('hidden');
                if (adminMenuIcon) adminMenuIcon.textContent = 'segment';
            }

            if (adminToggleBtn) adminToggleBtn.addEventListener('click', function() {
                adminSidebar.classList.contains('-translate-x-full') ? openAdminSidebar() : closeAdminSidebar();
            });
            if (adminCloseBtn) adminCloseBtn.addEventListener('click', closeAdminSidebar);
            if (adminMoreBtn) adminMoreBtn.addEventListener('click', openAdminSidebar);
            if (adminOverlay) adminOverlay.addEventListener('click', closeAdminSidebar);

            // Close sidebar when a nav link inside it is tapped on mobile
            document.querySelectorAll('#adminSidebar a, #adminSidebar button[type="button"]').forEach(el => {
                el.addEventListener('click', function() {
                    if (window.innerWidth < 768) closeAdminSidebar();
                });
            });

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
                    title: 'Error',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#003e97'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: "{{ session('warning') }}",
                    confirmButtonColor: '#003e97'
                });
            @endif

            // Desktop Admin Currency Dropdown Toggle
            const adminCurrencyBtn = document.getElementById('adminCurrencyDropdownBtn');
            const adminCurrencyMenu = document.getElementById('adminCurrencyDropdownMenu');
            if (adminCurrencyBtn && adminCurrencyMenu) {
                adminCurrencyBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    adminCurrencyMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (!document.getElementById('adminCurrencySelectorWrapper')?.contains(e.target)) {
                        adminCurrencyMenu.classList.add('hidden');
                    }
                });
            }

            // Mobile Admin Currency Dropdown Toggle
            const mobileAdminCurrencyBtn = document.getElementById('mobileAdminCurrencyDropdownBtn');
            const mobileAdminCurrencyMenu = document.getElementById('mobileAdminCurrencyDropdownMenu');
            if (mobileAdminCurrencyBtn && mobileAdminCurrencyMenu) {
                mobileAdminCurrencyBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileAdminCurrencyMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (!document.getElementById('mobileAdminCurrencySelectorWrapper')?.contains(e.target)) {
                        mobileAdminCurrencyMenu.classList.add('hidden');
                    }
                });
            }

            // Global Delete Confirmation Interceptor
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This platform record will be deleted. This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#BA1A1A',
                        cancelButtonColor: '#4d5f7d',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
            // ── Admin Logout Confirmation ──
            const adminLogoutBtn = document.getElementById('adminLogoutBtn');
            if (adminLogoutBtn) {
                adminLogoutBtn.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Sign Out of Admin Console?',
                        text: 'Are you sure you want to securely end your Super Administrator session?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#003e97',
                        cancelButtonColor: '#64748B',
                        confirmButtonText: 'Yes, Sign Out',
                        cancelButtonText: 'Stay'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            document.getElementById('adminLogoutForm').submit();
                        }
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>