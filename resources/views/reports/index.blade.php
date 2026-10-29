@extends('layouts.app')

@section('title', 'Reports & Financial Statements • Uganda UGX')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Financial Reports & Accounting Suite</h1>
            <p class="text-xs text-secondary mt-0.5">Comprehensive ledger audit, Profit & Loss statements, Balance Sheet, and Trial Balance in Uganda Shillings (UGX).</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.profit-loss') }}" class="bg-primary hover:bg-primary-container text-white px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                <span>Profit & Loss</span>
            </a>
            <a href="{{ route('reports.balance-sheet') }}" class="bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">balance</span>
                <span>Balance Sheet</span>
            </a>
            <a href="{{ route('reports.trial-balance') }}" class="bg-surface hover:bg-bg-subtle text-secondary border border-border-quiet px-3.5 py-2 rounded-lg text-xs font-semibold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">menu_book</span>
                <span>Trial Balance</span>
            </a>
        </div>
    </div>

    <!-- Overview Financial Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Gross Invoiced Revenue</span>
            <div class="font-headline text-2xl font-extrabold text-primary font-numeric mt-1">
                {{ $currencyService->format($totalRevenueCents) }}
            </div>
            <span class="text-[11px] text-success font-semibold mt-1 block">Paid customer ledger settlements</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Accounts Receivable</span>
            <div class="font-headline text-2xl font-extrabold text-amber-700 font-numeric mt-1">
                {{ $currencyService->format($pendingRevenueCents) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">Pending invoices awaiting receipt</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Operating Expenses</span>
            <div class="font-headline text-2xl font-extrabold text-danger font-numeric mt-1">
                UGX {{ number_format($totalExpensesUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">Rent, utilities, fuel, maintenance</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Gross Staff Compensation</span>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric mt-1">
                UGX {{ number_format($totalPayrollUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">Monthly basic salaries & allowances</span>
        </div>
    </div>

    <!-- Financial Statements Directory Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- P&L Card -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col justify-between space-y-4 hover:border-primary transition-colors">
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">trending_up</span>
                </div>
                <h3 class="font-headline font-bold text-base text-text-rich">Profit & Loss Statement (P&L)</h3>
                <p class="text-xs text-secondary">Summary of sales revenues, cost of goods sold (COGS), operating expenses, and net profit before/after statutory tax.</p>
            </div>
            <a href="{{ route('reports.profit-loss') }}" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold text-center shadow-sm">
                Open P&L Statement →
            </a>
        </div>

        <!-- Balance Sheet Card -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col justify-between space-y-4 hover:border-primary transition-colors">
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">balance</span>
                </div>
                <h3 class="font-headline font-bold text-base text-text-rich">Balance Sheet</h3>
                <p class="text-xs text-secondary">Statement of financial position detailing current and non-current assets, accounts payable liabilities, and retained equity.</p>
            </div>
            <a href="{{ route('reports.balance-sheet') }}" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold text-center shadow-sm">
                Open Balance Sheet →
            </a>
        </div>

        <!-- Trial Balance Card -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow flex flex-col justify-between space-y-4 hover:border-primary transition-colors">
            <div class="space-y-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">menu_book</span>
                </div>
                <h3 class="font-headline font-bold text-base text-text-rich">Trial Balance Ledger</h3>
                <p class="text-xs text-secondary">Double-entry verification ledger ensuring total debit accounts strictly balance total credit balances for audit compliance.</p>
            </div>
            <a href="{{ route('reports.trial-balance') }}" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold text-center shadow-sm">
                Open Trial Balance →
            </a>
        </div>
    </div>
</div>
@endsection
