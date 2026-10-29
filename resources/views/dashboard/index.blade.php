@extends('layouts.app')

@section('title', 'Business Dashboard • TBM Enterprise Suite Uganda')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">{{ $business->name ?? 'Victoria Logistics & Trading Ltd' }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-primary/10 text-primary border border-primary/20">
                    {{ $subscription->plan->name ?? 'Premium' }} Plan
                </span>
            </div>
            <p class="text-xs text-secondary mt-0.5">Commercial performance overview, sales ledger, cashflow telemetry, and statutory compliance in Uganda Shillings (UGX).</p>
        </div>

        <!-- Quick Actions Bar -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('pos') }}" class="bg-primary hover:bg-primary-container text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">point_of_sale</span>
                <span>New Sale / POS</span>
            </a>
            <a href="{{ route('invoices.create') }}" class="bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add</span>
                <span>New Invoice</span>
            </a>
            <a href="{{ route('expenses.create') }}" class="bg-surface hover:bg-bg-subtle text-secondary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">payments</span>
                <span>Record Expense</span>
            </a>
        </div>
    </div>

    <!-- Active Subscription Warning Alert (If applicable) -->
    @if($expirationAlert)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-amber-600 text-2xl shrink-0">notification_important</span>
                <div>
                    <span class="font-bold text-amber-900 block">{{ $expirationAlert['title'] }}</span>
                    <span class="text-amber-800">{{ $expirationAlert['message'] }}</span>
                </div>
            </div>
            <a href="{{ route('subscription.checkout', ['plan' => 'premium', 'cycle' => 'yearly']) }}" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-container text-white font-bold transition-colors shrink-0 shadow-sm">
                Upgrade / Renew in UGX →
            </a>
        </div>
    @endif

    <!-- Core Business Financial KPIs (Uganda Shillings UGX) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <!-- Gross Sales -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Gross Sales Revenue</span>
                <span class="material-symbols-outlined text-primary text-xl">trending_up</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric">
                UGX {{ number_format($totalSalesUgx) }}
            </div>
            <div class="flex items-center gap-1 mt-1 text-xs text-success font-semibold">
                <span class="material-symbols-outlined text-sm">arrow_upward</span>
                <span>+14.8% vs last month</span>
            </div>
        </div>

        <!-- Total Outflow / Expenses + Payroll -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Operating Expenses</span>
                <span class="material-symbols-outlined text-danger text-xl">payments</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric">
                UGX {{ number_format($totalOutflowUgx) }}
            </div>
            <span class="text-xs text-secondary mt-1 block">Expenses: UGX {{ number_format($totalExpensesUgx) }} • Payroll: UGX {{ number_format($totalPayrollUgx) }}</span>
        </div>

        <!-- Net Operating Profit -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Net Operating Profit</span>
                <span class="material-symbols-outlined text-success text-xl">account_balance_wallet</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-success font-numeric">
                UGX {{ number_format($netProfitUgx) }}
            </div>
            <span class="text-xs text-secondary mt-1 block">Net Profit Margin: ~{{ round(($netProfitUgx / max(1, $totalSalesUgx)) * 100, 1) }}%</span>
        </div>

        <!-- Cash & Bank Flow -->
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <span class="text-xs font-bold text-secondary uppercase">Cash & Bank Balances</span>
                <span class="material-symbols-outlined text-primary text-xl">savings</span>
            </div>
            <div class="font-headline text-2xl font-extrabold text-primary font-numeric">
                UGX {{ number_format($cashAndBankUgx) }}
            </div>
            <span class="text-xs text-success font-semibold mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-success"></span>
                <span>Stanbic + MTN MoMo Liquid</span>
            </span>
        </div>
    </div>

    <!-- Interactive Visuals (ApexCharts) Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- 1. Revenue & Expense Trend Area Chart (8 cols) -->
        <div class="lg:col-span-8 bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="font-headline font-bold text-base text-text-rich">Sales vs Operating Outflow (UGX)</h3>
                    <p class="text-xs text-secondary">Interactive monthly performance tracking</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 text-xs text-secondary">
                        <span class="w-3 h-3 rounded-sm bg-primary"></span>
                        <span>Gross Sales</span>
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-secondary">
                        <span class="w-3 h-3 rounded-sm bg-amber-500"></span>
                        <span>Outflow</span>
                    </span>
                </div>
            </div>
            <div id="salesExpenseChart" class="w-full h-72"></div>
        </div>

        <!-- 2. Outflow Breakdown Donut Chart (4 cols) -->
        <div class="lg:col-span-4 bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col justify-between">
            <div>
                <h3 class="font-headline font-bold text-base text-text-rich">Outflow Composition</h3>
                <p class="text-xs text-secondary">Distribution across expenses & salaries</p>
            </div>
            <div id="outflowDonutChart" class="w-full h-64 flex items-center justify-center my-auto"></div>
            <div class="grid grid-cols-2 gap-2 text-center text-xs pt-3 border-t border-border-quiet">
                <div>
                    <span class="text-secondary text-[11px] block">Operations</span>
                    <span class="font-bold text-text-rich font-numeric">UGX {{ number_format($totalExpensesUgx) }}</span>
                </div>
                <div>
                    <span class="text-secondary text-[11px] block">Staff Payroll</span>
                    <span class="font-bold text-text-rich font-numeric">UGX {{ number_format($totalPayrollUgx) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Operations & Commercial Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Invoices Table -->
        <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
                <span class="font-headline font-bold text-sm text-text-rich">Recent Customer Invoices</span>
                <a href="{{ route('invoices.index') }}" class="text-xs font-bold text-primary hover:underline">View All Invoices →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-bg-subtle/50 text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                        <tr>
                            <th class="px-4 py-2.5">Invoice #</th>
                            <th class="px-4 py-2.5">Customer</th>
                            <th class="px-4 py-2.5 text-right">Amount (UGX)</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-quiet">
                        @foreach($recentInvoices as $inv)
                            <tr class="hover:bg-bg-subtle/40 transition-colors">
                                <td class="px-4 py-3 font-bold font-numeric text-primary">
                                    <a href="{{ route('invoices.show', $inv) }}" class="hover:underline">{{ $inv->invoice_number }}</a>
                                </td>
                                <td class="px-4 py-3 font-medium text-text-rich truncate max-w-[150px]">{{ $inv->customer_name }}</td>
                                <td class="px-4 py-3 text-right font-numeric font-bold text-text-rich">
                                    {{ $currencyService->format($inv->total_cents) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $inv->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $inv->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Sales / Purchase Orders -->
        <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
                <span class="font-headline font-bold text-sm text-text-rich">Sales & Purchase Orders</span>
                <a href="{{ route('orders.index') }}" class="text-xs font-bold text-primary hover:underline">View All Orders →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-bg-subtle/50 text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                        <tr>
                            <th class="px-4 py-2.5">Order #</th>
                            <th class="px-4 py-2.5">Party / Entity</th>
                            <th class="px-4 py-2.5 text-right">Value (UGX)</th>
                            <th class="px-4 py-2.5 text-center">Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-quiet">
                        @foreach($recentOrders as $ord)
                            <tr class="hover:bg-bg-subtle/40 transition-colors">
                                <td class="px-4 py-3 font-bold font-numeric text-primary">{{ $ord->order_number }}</td>
                                <td class="px-4 py-3 font-medium text-text-rich truncate max-w-[150px]">{{ $ord->party_name }}</td>
                                <td class="px-4 py-3 text-right font-numeric font-bold text-text-rich">
                                    UGX {{ number_format($ord->total_amount_ugx) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ord->type === 'sales_order' ? 'bg-primary/10 text-primary' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $ord->type === 'sales_order' ? 'Sales' : 'Purchase' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Sales vs Outflow Area Chart
        const salesExpenseOptions = {
            series: [
                { name: 'Gross Sales (UGX)', data: @json($salesTrend) },
                { name: 'Operating Outflow (UGX)', data: @json($expenseTrend) }
            ],
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#003e97', '#F59E0B'],
            dataLabels: { enabled: false },
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
                categories: @json($months),
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

        const salesExpenseChart = new ApexCharts(document.querySelector("#salesExpenseChart"), salesExpenseOptions);
        salesExpenseChart.render();

        // 2. Outflow Breakdown Donut Chart (Strictly from real database)
        const totalOutflow = {{ (int) $totalOutflowUgx }};
        const totalExpenses = {{ (int) $totalExpensesUgx }};
        const totalPayroll = {{ (int) $totalPayrollUgx }};

        if (totalOutflow > 0) {
            const outflowDonutOptions = {
                series: [totalExpenses, totalPayroll],
                labels: ['General Expenses', 'Staff Payroll'],
                chart: {
                    type: 'donut',
                    height: 240,
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#003e97', '#cea72c'],
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
                                    label: 'Total Outflow',
                                    formatter: function () {
                                        return 'UGX ' + new Intl.NumberFormat().format(totalOutflow);
                                    }
                                }
                            }
                        }
                    }
                }
            };

            const outflowDonutChart = new ApexCharts(document.querySelector("#outflowDonutChart"), outflowDonutOptions);
            outflowDonutChart.render();
        } else {
            document.querySelector("#outflowDonutChart").innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-center p-4 space-y-2">
                    <span class="material-symbols-outlined text-3xl text-secondary opacity-40">pie_chart</span>
                    <p class="text-xs text-secondary font-medium">No recorded outflows in database yet.</p>
                    <a href="{{ route('expenses.create') }}" class="text-[11px] font-bold text-primary hover:underline">+ Record First Expense</a>
                </div>
            `;
        }
    });
</script>
@endpush
@endsection
