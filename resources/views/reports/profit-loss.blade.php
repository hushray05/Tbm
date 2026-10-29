@extends('layouts.app')

@section('title', 'Profit & Loss Statement • Uganda UGX')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Statement of Profit & Loss (P&L)</h1>
            <p class="text-xs text-secondary mt-0.5">{{ $business->name ?? 'Victoria Logistics & Trading Ltd' }} • For the Year Ended August 2026</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports') }}" class="text-xs font-semibold text-secondary hover:underline">
                ← Back to Reports
            </a>
            <button onclick="window.print()" class="bg-primary hover:bg-primary-container text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">print</span>
                <span>Print Statement</span>
            </button>
        </div>
    </div>

    <!-- P&L Financial Statement Card -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-8 tbm-shadow space-y-6">
        <!-- Revenue Section -->
        <div class="space-y-3">
            <h3 class="font-headline font-bold text-xs uppercase tracking-wider text-primary border-b border-border-quiet pb-2">1. Operating Revenue</h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between text-text-rich">
                    <span>Gross Sales Revenue (Commercial Trade & Logistics)</span>
                    <span class="font-numeric font-bold">UGX {{ number_format($salesRevenueUgx) }}</span>
                </div>
                <div class="flex justify-between text-secondary">
                    <span>Less: Cost of Goods Sold (Purchases & Freight COGS)</span>
                    <span class="font-numeric text-danger">- UGX {{ number_format($directCostSalesUgx) }}</span>
                </div>
                <div class="flex justify-between text-sm font-extrabold text-text-rich pt-2 border-t border-border-quiet bg-bg-subtle/50 px-3 py-2 rounded-lg">
                    <span>Gross Operating Profit</span>
                    <span class="font-numeric text-primary">UGX {{ number_format($grossProfitUgx) }}</span>
                </div>
            </div>
        </div>

        <!-- Operating Expenses Section -->
        <div class="space-y-3">
            <h3 class="font-headline font-bold text-xs uppercase tracking-wider text-primary border-b border-border-quiet pb-2">2. Operating & Administrative Expenses</h3>
            <div class="space-y-2 text-xs">
                @foreach($expensesByCategory as $cat => $amount)
                    <div class="flex justify-between text-text-rich">
                        <span class="text-secondary">{{ $cat }}</span>
                        <span class="font-numeric font-semibold">UGX {{ number_format($amount) }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between text-sm font-extrabold text-danger pt-2 border-t border-border-quiet bg-red-50/50 px-3 py-2 rounded-lg">
                    <span>Total Operating Outflow</span>
                    <span class="font-numeric">- UGX {{ number_format($totalOperatingExpensesUgx) }}</span>
                </div>
            </div>
        </div>

        <!-- Operating Income -->
        <div class="space-y-3 pt-2">
            <div class="flex justify-between text-sm font-bold text-text-rich bg-bg-subtle px-4 py-3 rounded-xl">
                <span>Operating Income (EBITDA)</span>
                <span class="font-numeric text-text-rich">UGX {{ number_format($operatingIncomeUgx) }}</span>
            </div>
            <div class="flex justify-between text-xs text-secondary px-4">
                <span>Less: Statutory Tax Expense (Uganda URA PAYE / Withholding)</span>
                <span class="font-numeric text-danger">- UGX {{ number_format($taxExpenseUgx) }}</span>
            </div>
        </div>

        <!-- Net Profit Bottom Line -->
        <div class="p-5 bg-gradient-to-r from-primary to-primary-container text-white rounded-2xl flex items-center justify-between shadow-md">
            <div>
                <span class="text-xs uppercase tracking-wider text-blue-200 block font-bold">Net Profit / (Loss) for the Period</span>
                <span class="text-[11px] text-blue-300">Audited Statutory Net Margin: ~{{ round(($netProfitUgx / max(1, $salesRevenueUgx)) * 100, 1) }}%</span>
            </div>
            <div class="font-headline font-extrabold text-2xl md:text-3xl font-numeric text-white">
                UGX {{ number_format($netProfitUgx) }}
            </div>
        </div>
    </div>
</div>
@endsection
