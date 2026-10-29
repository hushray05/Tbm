@extends('layouts.app')

@section('title', 'Balance Sheet • Uganda UGX')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Balance Sheet</h1>
            <p class="text-xs text-secondary mt-0.5">{{ $business->name ?? 'Victoria Logistics & Trading Ltd' }} • Statement of Financial Position</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports') }}" class="text-xs font-semibold text-secondary hover:underline">
                ← Back to Reports
            </a>
            <button onclick="window.print()" class="bg-primary hover:bg-primary-container text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">print</span>
                <span>Print</span>
            </button>
        </div>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl p-8 tbm-shadow space-y-6">
        <!-- Assets -->
        <div class="space-y-3">
            <h3 class="font-headline font-bold text-xs uppercase tracking-wider text-primary border-b border-border-quiet pb-2">Assets</h3>
            
            <div class="space-y-1.5 text-xs">
                <span class="font-bold text-secondary uppercase text-[11px] block">Current Assets</span>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Cash & Bank Balances (Stanbic / MTN MoMo)</span>
                    <span class="font-numeric font-semibold">UGX {{ number_format($cashAndBankUgx) }}</span>
                </div>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Accounts Receivable (Trade Debtors)</span>
                    <span class="font-numeric font-semibold">UGX {{ number_format($accountsReceivableUgx) }}</span>
                </div>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Merchandise Inventory Stock</span>
                    <span class="font-numeric font-semibold">UGX {{ number_format($inventoryValuationUgx) }}</span>
                </div>
                <div class="flex justify-between text-xs font-bold text-text-rich pl-3 pt-1 border-t border-border-quiet/50">
                    <span>Total Current Assets</span>
                    <span class="font-numeric">UGX {{ number_format($totalCurrentAssetsUgx) }}</span>
                </div>
            </div>

            <div class="space-y-1.5 text-xs pt-2">
                <span class="font-bold text-secondary uppercase text-[11px] block">Non-Current Assets</span>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Warehouse Plant, Machinery & Fleet</span>
                    <span class="font-numeric font-semibold">UGX {{ number_format($propertyAndEquipmentUgx) }}</span>
                </div>
            </div>

            <div class="flex justify-between text-sm font-extrabold text-primary bg-primary/5 px-4 py-2.5 rounded-xl border border-primary/20">
                <span>TOTAL ASSETS</span>
                <span class="font-numeric">UGX {{ number_format($totalAssetsUgx) }}</span>
            </div>
        </div>

        <!-- Liabilities & Equity -->
        <div class="space-y-3 pt-4 border-t border-border-quiet">
            <h3 class="font-headline font-bold text-xs uppercase tracking-wider text-primary border-b border-border-quiet pb-2">Liabilities & Equity</h3>
            
            <div class="space-y-1.5 text-xs">
                <span class="font-bold text-secondary uppercase text-[11px] block">Current Liabilities</span>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Accounts Payable (Suppliers / Trade Creditors)</span>
                    <span class="font-numeric font-semibold">UGX {{ number_format($accountsPayableUgx) }}</span>
                </div>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Statutory Tax Liabilities (URA PAYE & VAT)</span>
                    <span class="font-numeric font-semibold">UGX {{ number_format($taxPayableUgx) }}</span>
                </div>
            </div>

            <div class="space-y-1.5 text-xs pt-2">
                <span class="font-bold text-secondary uppercase text-[11px] block">Long-Term Liabilities</span>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Commercial Bank Term Loan</span>
                    <span class="font-numeric font-semibold">UGX {{ number_format($longTermDebtUgx) }}</span>
                </div>
            </div>

            <div class="space-y-1.5 text-xs pt-2">
                <span class="font-bold text-secondary uppercase text-[11px] block">Owners Equity</span>
                <div class="flex justify-between text-text-rich pl-3">
                    <span>Retained Earnings & Reserves</span>
                    <span class="font-numeric font-semibold text-success">UGX {{ number_format($retainedEarningsUgx) }}</span>
                </div>
            </div>

            <div class="flex justify-between text-sm font-extrabold text-text-rich bg-bg-subtle px-4 py-2.5 rounded-xl border border-border-quiet">
                <span>TOTAL LIABILITIES & EQUITY</span>
                <span class="font-numeric text-primary">UGX {{ number_format($totalAssetsUgx) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
