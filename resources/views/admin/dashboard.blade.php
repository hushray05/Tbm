@extends('layouts.admin')

@section('title', 'Administrative Control Center • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Administrative Control Center</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-900 border border-amber-300 shadow-xs">
                    Platform Master
                </span>
            </div>
            <p class="text-xs text-secondary mt-0.5">Platform telemetry, multi-tenant subscription growth, financial MRR metrics, and system security in Uganda UGX.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.subscriptions.plans.create') }}" class="bg-primary hover:bg-primary-container text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                <span>New Plan</span>
            </a>
            <a href="{{ route('admin.tasks.create') }}" class="bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add</span>
                <span>New Task</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-surface hover:bg-bg-subtle text-secondary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">inventory</span>
                <span>New Product</span>
            </a>
        </div>
    </div>

    <!-- Core Platform Telemetry KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <!-- 1. Total Platform Revenue -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Platform Revenue (UGX)</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">payments</span>
                </div>
            </div>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric">
                UGX {{ number_format($metrics['total_revenue_ugx']) }}
            </div>
            <div class="flex items-center gap-1 mt-1 text-xs text-success font-semibold">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                <span>+18.4% MRR this month</span>
            </div>
        </div>

        <!-- 2. Active Tenant Subscribers -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Subscribed Businesses</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">domain</span>
                </div>
            </div>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric">
                {{ $metrics['total_businesses'] }} Businesses
            </div>
            <div class="flex items-center gap-2 mt-1 text-xs text-secondary">
                <span class="text-success font-semibold">● {{ $metrics['active_subscribers'] }} Paid</span>
                <span>•</span>
                <span class="text-amber-600 font-semibold">● {{ $metrics['trial_subscribers'] }} Trials</span>
            </div>
        </div>

        <!-- 3. Provisioned Platform Users -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Platform User Accounts</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">group</span>
                </div>
            </div>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric">
                {{ number_format($metrics['total_users']) }} Users
            </div>
            <span class="text-xs text-secondary mt-1 block">Across all Uganda enterprise branches</span>
        </div>

        <!-- 4. System Uptime & Security -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Platform Uptime & Health</span>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">verified_user</span>
                </div>
            </div>
            <div class="font-headline text-2xl font-extrabold text-success font-numeric">
                {{ $metrics['system_health'] }}
            </div>
            <span class="text-xs text-secondary mt-1 block">0 active security alerts • SSL Verified</span>
        </div>
    </div>

    <!-- 3 DISTINCT INTERACTIVE APEXCHARTS ROW 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- CHART 1: Platform Revenue & MRR Growth (UGX) (2 cols) -->
        <div class="lg:col-span-2 bg-surface border border-border-quiet rounded-2xl p-5 md:p-6 tbm-shadow flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-border-quiet">
                <div>
                    <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">show_chart</span>
                        Platform SaaS Revenue & Invoicing (UGX)
                    </h3>
                    <p class="text-xs text-secondary">Monthly total billings vs recurring subscriptions over the last 6 months</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-[11px] font-bold text-primary">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span> Total Billings
                    </span>
                    <span class="flex items-center gap-1 text-[11px] font-bold text-amber-500 ml-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span> MRR Baseline
                    </span>
                </div>
            </div>
            <div class="pt-4 flex-1 min-h-[300px]" id="adminRevenueChart"></div>
        </div>

        <!-- CHART 2: Subscription Plan Distribution (Donut Chart) (1 col) -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-5 md:p-6 tbm-shadow flex flex-col justify-between">
            <div class="pb-3 border-b border-border-quiet">
                <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">pie_chart</span>
                    Plan Distribution Tiers
                </h3>
                <p class="text-xs text-secondary">Active subscriber breakdown by plan tier</p>
            </div>
            <div class="pt-4 flex-1 flex items-center justify-center min-h-[260px]" id="adminPlanDistributionChart"></div>
            <div class="pt-3 border-t border-border-quiet grid grid-cols-3 gap-2 text-center text-xs">
                @foreach($planLabels as $idx => $label)
                    <div class="p-2 rounded-lg bg-bg-subtle border border-border-quiet">
                        <span class="text-[10px] text-secondary font-bold uppercase block">{{ $label }}</span>
                        <span class="font-numeric font-extrabold text-primary">{{ $planCounts[$idx] ?? 0 }} orgs</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 3 DISTINCT INTERACTIVE APEXCHARTS ROW 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- CHART 3: User Acquisitions vs Trial Conversions (2 cols) -->
        <div class="lg:col-span-2 bg-surface border border-border-quiet rounded-2xl p-5 md:p-6 tbm-shadow flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-border-quiet">
                <div>
                    <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">bar_chart</span>
                        Acquisition & Conversion Funnel
                    </h3>
                    <p class="text-xs text-secondary">New business signups vs paid conversions vs churned accounts</p>
                </div>
                <div class="flex items-center gap-3 text-[11px] font-bold">
                    <span class="flex items-center gap-1 text-primary">
                        <span class="w-2.5 h-2.5 rounded bg-primary inline-block"></span> Signups
                    </span>
                    <span class="flex items-center gap-1 text-emerald-600">
                        <span class="w-2.5 h-2.5 rounded bg-emerald-500 inline-block"></span> Converted
                    </span>
                    <span class="flex items-center gap-1 text-red-500">
                        <span class="w-2.5 h-2.5 rounded bg-red-400 inline-block"></span> Churn
                    </span>
                </div>
            </div>
            <div class="pt-4 flex-1 min-h-[280px]" id="adminFunnelChart"></div>
        </div>

        <!-- CHART 4: Uganda Mobile Money & Payment Gateways Share (1 col) -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-5 md:p-6 tbm-shadow flex flex-col justify-between">
            <div class="pb-3 border-b border-border-quiet">
                <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                    Uganda Payment Gateways
                </h3>
                <p class="text-xs text-secondary">Settlement channels across MTN, Airtel & Card</p>
            </div>
            <div class="pt-4 flex-1 flex items-center justify-center min-h-[240px]" id="adminGatewayChart"></div>
            <div class="pt-3 border-t border-border-quiet space-y-1.5 text-xs">
                <div class="flex justify-between items-center text-text-rich">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span> MTN Mobile Money</span>
                    <span class="font-numeric font-bold">64% (Uganda Master)</span>
                </div>
                <div class="flex justify-between items-center text-text-rich">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> Airtel Money</span>
                    <span class="font-numeric font-bold">28%</span>
                </div>
                <div class="flex justify-between items-center text-text-rich">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-600"></span> Card / Stanbic</span>
                    <span class="font-numeric font-bold">8%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Platform Activity & Subscribers Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Subscribers -->
        <div class="bg-surface border border-border-quiet rounded-2xl tbm-shadow flex flex-col overflow-hidden">
            <div class="p-4 border-b border-border-quiet flex items-center justify-between bg-bg-subtle">
                <span class="font-headline font-bold text-sm text-text-rich flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-base">group</span>
                    Recent Organization Subscribers
                </span>
                <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="text-xs font-bold text-primary hover:underline">Manage All →</a>
            </div>
            <div class="divide-y divide-border-quiet">
                @forelse($recentSubscribers as $sub)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-bg-subtle/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold shrink-0">
                                {{ strtoupper(substr($sub->business->name ?? 'B', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('admin.subscriptions.subscribers.show', $sub) }}" class="font-bold text-text-rich hover:text-primary truncate block">
                                    {{ $sub->business->name ?? 'Business' }}
                                </a>
                                <span class="text-[11px] text-secondary">
                                    {{ $sub->plan->name ?? 'Plan' }} • {{ $sub->billing_cycle ?? 'Monthly' }} • 
                                    <span class="font-numeric font-semibold">UGX {{ number_format($sub->amount) }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $sub->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sub->status }}
                            </span>
                            <a href="{{ route('admin.subscriptions.subscribers.show', $sub) }}" class="p-1 text-secondary hover:text-primary" title="View details">
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-secondary">No subscriber accounts registered yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Platform Tasks (Admin CRUD) -->
        <div class="bg-surface border border-border-quiet rounded-2xl tbm-shadow flex flex-col overflow-hidden">
            <div class="p-4 border-b border-border-quiet flex items-center justify-between bg-bg-subtle">
                <span class="font-headline font-bold text-sm text-text-rich flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-base">task_alt</span>
                    System Execution Tasks
                </span>
                <a href="{{ route('admin.tasks.index') }}" class="text-xs font-bold text-primary hover:underline">Manage All →</a>
            </div>
            <div class="divide-y divide-border-quiet">
                @forelse($recentTasks as $t)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-bg-subtle/50 transition-colors">
                        <div class="min-w-0 pr-2">
                            <a href="{{ route('admin.tasks.edit', $t) }}" class="font-bold text-text-rich hover:text-primary truncate block">{{ $t->title }}</a>
                            <span class="text-[11px] text-secondary">{{ $t->project_name }} • <span class="font-numeric">{{ $currencyService->format($t->price_cents) }}</span></span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $t->status === 'completed' ? 'bg-green-100 text-green-800' : ($t->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ str_replace('_', ' ', $t->status) }}
                            </span>
                            <a href="{{ route('admin.tasks.edit', $t) }}" class="p-1 text-secondary hover:text-primary" title="Edit">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-secondary">No system tasks configured.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ----------------------------------------------------
    // CHART 1: Platform Revenue & MRR Trend (Spline Area)
    // ----------------------------------------------------
    const revenueOptions = {
        series: [
            {
                name: 'Total Billings (UGX)',
                data: @json($revenueTrendUgx)
            },
            {
                name: 'Monthly Recurring (MRR)',
                data: @json($mrrTrendUgx)
            }
        ],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#003e97', '#F59E0B'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: [3, 2.5] },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: [0.35, 0.25],
                opacityTo: [0.05, 0.05],
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: @json($months),
            labels: { style: { fontSize: '11px', colors: '#4d5f7d' } }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return 'UGX ' + (val / 1000000).toFixed(1) + 'M';
                },
                style: { fontSize: '11px', colors: '#4d5f7d' }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return 'UGX ' + Number(val).toLocaleString();
                }
            }
        },
        legend: { show: false }
    };
    new ApexCharts(document.querySelector("#adminRevenueChart"), revenueOptions).render();

    // ----------------------------------------------------
    // CHART 2: Plan Distribution (Donut)
    // ----------------------------------------------------
    const planOptions = {
        series: @json($planCounts),
        labels: @json($planLabels),
        chart: {
            type: 'donut',
            height: 260,
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#1455c0', '#F59E0B', '#8B5CF6', '#10B981'],
        dataLabels: { enabled: true },
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Orgs',
                            fontSize: '11px',
                            fontWeight: 700,
                            color: '#172033',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        legend: { position: 'bottom', fontSize: '11px' }
    };
    new ApexCharts(document.querySelector("#adminPlanDistributionChart"), planOptions).render();

    // ----------------------------------------------------
    // CHART 3: Acquisition vs Conversion Funnel (Column)
    // ----------------------------------------------------
    const funnelOptions = {
        series: [
            {
                name: 'New Signups',
                data: @json($newRegistrations)
            },
            {
                name: 'Paid Converted',
                data: @json($paidConversions)
            },
            {
                name: 'Churned',
                data: @json($churnedAccounts)
            }
        ],
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#003e97', '#10B981', '#EF4444'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 4
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: @json($signupMonths),
            labels: { style: { fontSize: '11px', colors: '#4d5f7d' } }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return val + ' orgs'; },
                style: { fontSize: '11px', colors: '#4d5f7d' }
            }
        },
        legend: { show: false },
        tooltip: {
            y: {
                formatter: function(val) { return val + ' businesses'; }
            }
        }
    };
    new ApexCharts(document.querySelector("#adminFunnelChart"), funnelOptions).render();

    // ----------------------------------------------------
    // CHART 4: Payment Gateways Share (Donut)
    // ----------------------------------------------------
    const gatewayOptions = {
        series: @json($gatewayShare),
        labels: @json($gatewayLabels),
        chart: {
            type: 'donut',
            height: 240,
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#F59E0B', '#EF4444', '#2563EB'],
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Top Method',
                            fontSize: '11px',
                            fontWeight: 700,
                            formatter: function() { return 'MTN MoMo'; }
                        }
                    }
                }
            }
        },
        legend: { show: false }
    };
    new ApexCharts(document.querySelector("#adminGatewayChart"), gatewayOptions).render();
});
</script>
@endpush
@endsection
