@extends('layouts.app')

@section('title', 'Expense Management • Uganda UGX')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Expense Management & Vouchers</h1>
            <p class="text-xs text-secondary mt-0.5">Track and categorize operating expenses, utility payments, and fuel vouchers in Uganda Shillings (UGX).</p>
        </div>
        <div>
            <a href="{{ route('expenses.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add_card</span>
                <span>Record New Expense</span>
            </a>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Total Operating Expenses</span>
            <div class="font-headline text-2xl font-extrabold text-danger font-numeric mt-1">
                UGX {{ number_format($totalExpensesUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">All registered vouchers</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">This Month Expenses</span>
            <div class="font-headline text-2xl font-extrabold text-text-rich font-numeric mt-1">
                UGX {{ number_format($thisMonthExpensesUgx ?: $totalExpensesUgx) }}
            </div>
            <span class="text-[11px] text-success font-semibold mt-1 block">Current monthly cycle</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Expense Categories</span>
            <div class="font-headline text-2xl font-extrabold text-primary font-numeric mt-1">
                {{ $categoryBreakdown->count() }} Categories
            </div>
            <span class="text-[11px] text-secondary mt-1 block">Rent, Utilities, Fuel, Supplies</span>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
            <span class="font-headline font-bold text-sm text-text-rich">Expense Records</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle/50 text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Vendor / Beneficiary</th>
                        <th class="px-5 py-3">Payment Method</th>
                        <th class="px-5 py-3">Reference / Voucher #</th>
                        <th class="px-5 py-3 text-right">Amount (UGX)</th>
                        <th class="px-5 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet">
                    @forelse($expenses as $e)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5 font-numeric font-medium text-text-rich">
                                {{ $e->expense_date ? $e->expense_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-900">
                                    {{ $e->category }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-text-rich">{{ $e->vendor_name ?? 'N/A' }}</td>
                            <td class="px-5 py-3.5 text-secondary">{{ $e->payment_method }}</td>
                            <td class="px-5 py-3.5 font-numeric text-primary font-semibold">{{ $e->reference_no ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-right font-numeric font-extrabold text-danger text-sm">
                                UGX {{ number_format($e->amount_ugx) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <form action="{{ route('expenses.destroy', $e) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-secondary hover:text-danger hover:bg-red-50" title="Delete">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-secondary">
                                No expenses recorded yet. Click "Record New Expense" to create a voucher.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
