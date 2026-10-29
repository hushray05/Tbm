@extends(auth()->check() ? 'layouts.app' : 'layouts.public')

@section('title', 'Subscription Plans & Pricing • Uganda UGX')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 py-6">
    <!-- Pricing Header -->
    <div class="text-center space-y-4 max-w-2xl mx-auto">
        <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">
            Uganda Enterprise SaaS Pricing
        </span>
        <h1 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Simple, Predictable Plans in UGX</h1>
        <p class="text-secondary text-sm md:text-base">
            Every plan includes a <strong>{{ $defaultTrialDays }}-Day Free Trial</strong> with zero upfront payment required. Cancel or switch anytime.
        </p>

        <!-- Monthly / Annual Billing Toggle Switch -->
        <div class="flex items-center justify-center gap-3 pt-4">
            <span class="text-xs font-bold cursor-pointer" id="labelMonthly" onclick="setBillingCycle('monthly')">Monthly Billing</span>
            <button type="button" id="billingToggleBtn" class="w-14 h-7 rounded-full bg-primary relative p-1 transition-colors focus:outline-none" onclick="toggleBillingCycle()">
                <div id="toggleBall" class="w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-200"></div>
            </button>
            <span class="text-xs font-bold text-secondary flex items-center gap-1.5 cursor-pointer" id="labelAnnual" onclick="setBillingCycle('annual')">
                <span>Annual Billing</span>
                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">Save ~17%</span>
            </span>
        </div>
    </div>

    <!-- Pricing Cards Grid -->
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
                        <p class="text-xs text-secondary mt-1 min-h-[32px]">{{ $plan->description }}</p>
                    </div>

                    <!-- Dynamic Price Display (UGX) -->
                    <div class="py-3 border-y border-border-quiet">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs font-bold text-secondary">UGX</span>
                            <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric plan-price-monthly">
                                {{ number_format($plan->monthly_price) }}
                            </span>
                            <span class="font-headline text-3xl md:text-4xl font-extrabold text-primary font-numeric plan-price-annual hidden">
                                {{ number_format($plan->annual_price) }}
                            </span>
                            <span class="text-xs text-secondary font-medium plan-period-monthly">/ month</span>
                            <span class="text-xs text-secondary font-medium plan-period-annual hidden">/ year</span>
                        </div>
                        <div class="mt-1 flex items-center gap-1.5 text-xs text-success font-semibold">
                            <span class="material-symbols-outlined text-sm">verified</span>
                            <span>{{ $plan->trial_duration }}-Day Free Trial Included</span>
                        </div>
                    </div>

                    <!-- Usage Limits -->
                    <div class="space-y-2 text-xs">
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

                    <!-- Feature Matrix -->
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

                <!-- Call to Action Button -->
                <div class="pt-6 mt-6 border-t border-border-quiet">
                    @auth
                        <a href="{{ route('subscription.checkout', ['plan' => $plan->slug, 'cycle' => 'monthly']) }}" class="w-full py-3 px-4 rounded-xl text-center font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 {{ $plan->badge === 'MOST POPULAR' ? 'bg-primary hover:bg-primary-container text-white' : 'bg-bg-subtle hover:bg-primary hover:text-white text-primary border border-primary/20' }}">
                            <span class="material-symbols-outlined text-sm">rocket_launch</span>
                            <span>Upgrade to {{ $plan->name }}</span>
                        </a>
                        <span class="block text-center text-[11px] text-secondary mt-2">Instant checkout & activation</span>
                    @else
                        <a href="{{ route('register', ['plan' => $plan->slug]) }}" class="w-full py-3 px-4 rounded-xl text-center font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 {{ $plan->badge === 'MOST POPULAR' ? 'bg-primary hover:bg-primary-container text-white' : 'bg-bg-subtle hover:bg-primary hover:text-white text-primary border border-primary/20' }}">
                            <span>Start {{ $plan->trial_duration }}-Day Free Trial</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                        <span class="block text-center text-[11px] text-secondary mt-2">No payment method required to start</span>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>

    <!-- Payment Gateways Supported Banner -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
        <div>
            <h4 class="font-headline font-bold text-text-rich text-base">Ugandan Payment Gateways Supported</h4>
            <p class="text-xs text-secondary mt-0.5">Pay seamlessly with MTN Mobile Money, Airtel Money Uganda, Visa, Mastercard, or Bank Transfer.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold">MTN Mobile Money</span>
            <span class="px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-red-900 text-xs font-bold">Airtel Money</span>
            <span class="px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold">Visa / Mastercard</span>
        </div>
    </div>
</div>

<script>
    let currentCycle = 'monthly';

    function setBillingCycle(cycle) {
        currentCycle = cycle;
        const ball = document.getElementById('toggleBall');
        const labelMonthly = document.getElementById('labelMonthly');
        const labelAnnual = document.getElementById('labelAnnual');

        const monthlyPrices = document.querySelectorAll('.plan-price-monthly');
        const annualPrices = document.querySelectorAll('.plan-price-annual');
        const monthlyPeriods = document.querySelectorAll('.plan-period-monthly');
        const annualPeriods = document.querySelectorAll('.plan-period-annual');

        if (cycle === 'annual') {
            ball.style.transform = 'translateX(28px)';
            labelMonthly.classList.remove('text-primary');
            labelMonthly.classList.add('text-secondary');
            labelAnnual.classList.remove('text-secondary');
            labelAnnual.classList.add('text-primary');

            monthlyPrices.forEach(el => el.classList.add('hidden'));
            annualPrices.forEach(el => el.classList.remove('hidden'));
            monthlyPeriods.forEach(el => el.classList.add('hidden'));
            annualPeriods.forEach(el => el.classList.remove('hidden'));
        } else {
            ball.style.transform = 'translateX(0px)';
            labelAnnual.classList.remove('text-primary');
            labelAnnual.classList.add('text-secondary');
            labelMonthly.classList.remove('text-secondary');
            labelMonthly.classList.add('text-primary');

            monthlyPrices.forEach(el => el.classList.remove('hidden'));
            annualPrices.forEach(el => el.classList.add('hidden'));
            monthlyPeriods.forEach(el => el.classList.remove('hidden'));
            annualPeriods.forEach(el => el.classList.add('hidden'));
        }
    }

    function toggleBillingCycle() {
        setBillingCycle(currentCycle === 'monthly' ? 'annual' : 'monthly');
    }
</script>
@endsection
