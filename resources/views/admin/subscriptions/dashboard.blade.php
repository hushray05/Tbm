@extends('layouts.admin')

@section('title', 'Subscription Analytics Dashboard • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Subscription & Revenue Telemetry</h1>
            <p class="text-xs text-secondary mt-0.5">Commercial performance overview, active business subscriber cohorts, and ARR/MRR metrics in UGX.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.subscriptions.plans.create') }}" class="bg-primary hover:bg-primary-container text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                <span>Create New Plan</span>
            </a>
            <a href="{{ route('admin.subscriptions.settings.index') }}" class="bg-surface hover:bg-bg-subtle text-secondary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">tune</span>
                <span>Billing Settings</span>
            </a>
        </div>
    </div>

    <!-- Top KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <!-- Total Registered Businesses -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Total Businesses</span>
                <span class="material-symbols-outlined text-primary text-xl">domain</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric">{{ $totalBusinesses }}</div>
            <span class="text-xs text-success font-semibold mt-1 block">+3 registered this week</span>
        </div>

        <!-- Active Paid Subscriptions -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Active Paid Subscriptions</span>
                <span class="material-symbols-outlined text-success text-xl">verified</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-success font-numeric">{{ $activeSubscriptions }}</div>
            <span class="text-xs text-secondary mt-1 block">MRR: <strong class="text-text-rich font-numeric">UGX {{ number_format($monthlyRevenueUgx) }}</strong></span>
        </div>

        <!-- Free Trial Businesses -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Active Free Trials</span>
                <span class="material-symbols-outlined text-amber-500 text-xl">schedule</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-amber-600 font-numeric">{{ $trialBusinesses }}</div>
            <span class="text-xs text-secondary mt-1 block">Trial Conversion Rate: <strong class="text-primary">{{ $trialConversionRate }}%</strong></span>
        </div>

        <!-- Annual Recurring Run-rate (ARR) -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Annual Revenue Run-rate</span>
                <span class="material-symbols-outlined text-primary text-xl">payments</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-primary font-numeric">UGX {{ number_format($annualRevenueUgx) }}</div>
            <span class="text-xs text-secondary mt-1 block">Annualized subscription receipts</span>
        </div>
    </div>

    <!-- Interactive ApexCharts Visuals Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- 1. Subscription Growth & Revenue Trend Area Chart (8 cols) -->
        <div class="lg:col-span-8 bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-headline font-bold text-base text-text-rich">Subscription Revenue Growth (UGX)</h3>
                    <p class="text-xs text-secondary">Historical billing volume across all cohorts</p>
                </div>
                <span class="text-xs font-bold text-success bg-green-50 px-2.5 py-1 rounded-full">+28% YoY</span>
            </div>
            <div id="subRevenueChart" class="w-full h-72"></div>
        </div>

        <!-- 2. Subscribers by Plan Breakdown Donut Chart (4 cols) -->
        <div class="lg:col-span-4 bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col justify-between">
            <div>
                <h3 class="font-headline font-bold text-base text-text-rich">Plan Distribution</h3>
                <p class="text-xs text-secondary">Active subscriber distribution</p>
            </div>
            <div id="planDonutChart" class="w-full h-64 flex items-center justify-center my-auto"></div>
            <div class="grid grid-cols-3 gap-2 text-center text-xs pt-3 border-t border-border-quiet">
                <div>
                    <span class="text-[10px] text-secondary block">Starter</span>
                    <span class="font-bold text-text-rich font-numeric">{{ $starterCount }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-secondary block">Professional</span>
                    <span class="font-bold text-text-rich font-numeric">{{ $proCount }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-secondary block">Premium</span>
                    <span class="font-bold text-text-rich font-numeric">{{ $premiumCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Subscribers & Recent Payment Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Subscribers -->
        <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
                <span class="font-headline font-bold text-sm text-text-rich">Recent Subscriber Organizations</span>
                <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="text-xs font-bold text-primary hover:underline">Manage All Subscribers →</a>
            </div>
            <div class="divide-y divide-border-quiet">
                @foreach($recentSubscribers as $sub)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-bg-subtle/50 transition-colors">
                        <div>
                            <a href="{{ route('admin.subscriptions.subscribers.show', $sub) }}" class="font-bold text-text-rich hover:text-primary block">
                                {{ $sub->business->name ?? 'Business' }}
                            </a>
                            <span class="text-[11px] text-secondary">Owner: {{ $sub->business->owner->name ?? 'N/A' }} • Plan: <strong class="text-primary">{{ $sub->plan->name ?? 'Starter' }}</strong></span>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sub->status }}
                            </span>
                            <span class="block text-[10px] text-secondary mt-0.5 font-numeric">{{ $sub->billing_cycle }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
                <span class="font-headline font-bold text-sm text-text-rich">Recent Mobile Money & Card Receipts</span>
                <a href="{{ route('admin.subscriptions.payments.index') }}" class="text-xs font-bold text-primary hover:underline">View Payments Ledger →</a>
            </div>
            <div class="divide-y divide-border-quiet">
                @foreach($recentPayments as $pay)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-bg-subtle/50 transition-colors">
                        <div>
                            <span class="font-bold font-numeric text-primary block">{{ $pay->transaction_reference }}</span>
                            <span class="text-[11px] text-secondary">{{ $pay->business->name ?? 'Business' }} • Gateway: {{ $pay->gateway }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-success font-numeric text-sm block">UGX {{ number_format($pay->amount) }}</span>
                            <span class="text-[10px] text-secondary">{{ $pay->paid_at ? $pay->paid_at->format('d M Y') : 'N/A' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Growth Chart
        const subRevenueOptions = {
            series: [
                { name: 'Subscription Revenue (UGX)', data: [1200000, 1650000, 2100000, 2800000, 3400000, 4200000] }
            ],
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#003e97'],
            stroke: { curve: 'smooth', width: 2.5 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                labels: { style: { fontSize: '11px', colors: '#737784' } }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return 'UGX ' + (val / 1000000).toFixed(1) + 'M';
                    },
                    style: { fontSize: '11px', colors: '#737784' }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return 'UGX ' + new Intl.NumberFormat().format(val);
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#subRevenueChart"), subRevenueOptions).render();

        // Plan Donut Chart
        const planDonutOptions = {
            series: [{{ $starterCount ?: 1 }}, {{ $proCount ?: 1 }}, {{ $premiumCount ?: 1 }}],
            labels: ['Starter Plan', 'Professional Plan', 'Premium Plan'],
            chart: {
                type: 'donut',
                height: 240,
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#737784', '#003e97', '#cea72c'],
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Subscribers',
                                formatter: function () {
                                    return '{{ $totalBusinesses }}';
                                }
                            }
                        }
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#planDonutChart"), planDonutOptions).render();
    });
</script>
@endpush
@endsection
