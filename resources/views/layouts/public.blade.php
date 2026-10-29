@php
    $publicLinks = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Features', 'route' => 'features'],
        ['label' => 'Pricing', 'route' => 'subscription.pricing'],
        ['label' => 'How It Works', 'route' => 'how-it-works'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'FAQ', 'route' => 'faq'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TBM Enterprise Suite • Uganda Business Management OS')</title>

    <meta name="description" content="TBM Enterprise Suite - Uganda's complete business management platform. Sales, inventory, invoicing, payroll, URA tax compliance, and multi-branch operations in one UGX-native system.">
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
                        'gutter': '24px',
                        'margin-desktop': '32px',
                    }
                }
            }
        }
    </script>

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
        .text-balance { text-wrap: balance; }
        
        .hero-gradient-overlay {
            background: linear-gradient(135deg, rgba(0, 62, 151, 0.08) 0%, rgba(20, 85, 192, 0.04) 50%, transparent 100%);
        }
        
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .marquee-track {
            animation: marquee 30s linear infinite;
        }
        .marquee-track:hover {
            animation-play-state: paused;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-bg-subtle text-on-surface font-sans antialiased min-h-screen flex flex-col">

    <!-- Sticky Header / Navbar -->
    <header class="sticky top-0 z-50 bg-surface/90 backdrop-blur-md border-b border-border-quiet">
        <nav class="max-w-7xl mx-auto px-4 md:px-8 h-16 flex items-center justify-between gap-4" aria-label="Main navigation">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0" aria-label="TBM Enterprise Suite Home">
                <img src="{{ asset('images/logo.png') }}" alt="TBM Enterprise Suite Logo" class="h-10 w-auto object-contain">
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center gap-1">
                @foreach($publicLinks as $link)
                    <a href="{{ route($link['route']) }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs($link['route']) ? 'bg-primary/10 text-primary border border-primary/20' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <!-- Desktop Auth Actions -->
            <div class="hidden lg:flex items-center gap-2.5 shrink-0">
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-secondary border border-border-quiet hover:bg-bg-subtle hover:text-text-rich transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">login</span>
                    <span>Sign In</span>
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-primary hover:bg-primary-container shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">rocket_launch</span>
                    <span>Start Free Trial</span>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex items-center gap-2">
                <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-lg bg-primary text-white text-[11px] font-bold shadow-sm hidden sm:block">
                    Start Free Trial
                </a>
                <button id="mobileMenuToggle" class="p-2 text-secondary hover:text-primary hover:bg-bg-subtle rounded-xl transition-all border border-border-quiet bg-bg-subtle active:scale-95 flex items-center justify-center" aria-label="Toggle navigation" aria-expanded="false" aria-controls="mobileMenu">
                    <span class="material-symbols-outlined text-[22px] font-bold" id="mobileMenuIcon">density_medium</span>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="lg:hidden hidden bg-surface border-b border-border-quiet px-4 py-3 space-y-2">
            @foreach($publicLinks as $link)
                <a href="{{ route($link['route']) }}" class="block px-3 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs($link['route']) ? 'bg-primary/10 text-primary border border-primary/20' : 'text-secondary hover:bg-bg-subtle hover:text-text-rich' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <div class="pt-2 border-t border-border-quiet flex flex-col gap-2">
                <a href="{{ route('login') }}" class="w-full px-4 py-2 rounded-lg text-sm font-semibold text-secondary border border-border-quiet hover:bg-bg-subtle hover:text-text-rich transition-all text-center flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-base">login</span>
                    <span>Sign In</span>
                </a>
                <a href="{{ route('register') }}" class="w-full px-4 py-2 rounded-lg text-sm font-bold text-white bg-primary hover:bg-primary-container shadow-sm transition-all text-center flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-base">rocket_launch</span>
                    <span>Start Free Trial</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface border-t border-border-quiet">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
                <!-- Brand Column -->
                <div class="lg:col-span-2 space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="TBM Enterprise Suite Logo" class="h-12 w-auto object-contain">
                    </a>
                    <p class="text-sm text-secondary max-w-xs leading-relaxed">
                        The complete business management platform for Ugandan enterprises. Sales, inventory, invoicing, payroll, URA tax compliance, and multi-branch operations — unified in UGX.
                    </p>
                    <div class="flex items-center gap-3 text-xs text-secondary">
                        <span class="material-symbols-outlined text-primary text-base">verified</span>
                        <span>Built for Uganda • Powered by UGX</span>
                    </div>
                </div>

                <!-- Product Links -->
                <div>
                    <h4 class="font-headline font-bold text-sm text-text-rich mb-3">Product</h4>
                    <ul class="space-y-2 text-sm text-secondary">
                        <li><a href="{{ route('features') }}" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Features</a></li>
                        <li><a href="{{ route('subscription.pricing') }}" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Pricing</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>How It Works</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>About Us</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>FAQ</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h4 class="font-headline font-bold text-sm text-text-rich mb-3">Company</h4>
                    <ul class="space-y-2 text-sm text-secondary">
                        <li><a href="{{ route('contact') }}" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Contact Us</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Careers</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Press</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Blog</a></li>
                    </ul>
                </div>

                <!-- Legal & Compliance -->
                <div>
                    <h4 class="font-headline font-bold text-sm text-text-rich mb-3">Legal & Compliance</h4>
                    <ul class="space-y-2 text-sm text-secondary">
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Terms of Service</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>URA Compliance</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">chevron_right</span>Data Protection</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="mt-10 pt-8 border-t border-border-quiet flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex flex-col md:flex-row items-center md:items-center justify-between gap-3 text-xs text-secondary">
                    <p>&copy; {{ date('Y') }} TBM Enterprise Suite. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:text-primary transition-colors">Privacy</a>
                        <a href="#" class="hover:text-primary transition-colors">Terms</a>
                        <a href="#" class="hover:text-primary transition-colors">Cookies</a>
                    </div>
                </div>

                <!-- Payment Gateways -->
                <div class="flex flex-wrap items-center justify-center md:justify-end gap-2">
                    <span class="px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold flex items-center gap-1">
                        <img src="https://flagcdn.com/w20/ug.png" alt="Uganda" class="w-4 h-3 object-cover rounded">
                        <span>MTN Mobile Money</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-red-900 text-xs font-bold flex items-center gap-1">
                        <img src="https://flagcdn.com/w20/ug.png" alt="Uganda" class="w-4 h-3 object-cover rounded">
                        <span>Airtel Money</span>
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold">Visa / Mastercard</span>
                    <span class="px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-900 text-xs font-bold">Bank Transfer</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('mobileMenuIcon');

            function openMenu() {
                mobileMenu.classList.remove('hidden');
                if (menuIcon) menuIcon.textContent = 'close';
                toggleBtn.setAttribute('aria-expanded', 'true');
            }
            function closeMenu() {
                mobileMenu.classList.add('hidden');
                if (menuIcon) menuIcon.textContent = 'menu';
                toggleBtn.setAttribute('aria-expanded', 'false');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    mobileMenu.classList.contains('hidden') ? openMenu() : closeMenu();
                });
            }

            mobileMenu?.querySelectorAll('a').forEach(el => {
                el.addEventListener('click', closeMenu);
            });

            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Success', text: "{{ session('success') }}", confirmButtonColor: '#003e97', timer: 3500, timerProgressBar: true });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Alert', text: "{{ session('error') }}", confirmButtonColor: '#003e97' });
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>