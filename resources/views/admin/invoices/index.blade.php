@extends('layouts.admin')

@section('title', 'Admin Invoices Audit • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Invoices Audit & Ledger</h1>
            <p class="text-sm text-secondary mt-0.5">Manage issued invoices, update settlement statuses, and audit billing records.</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            <span>Issue New Invoice</span>
        </a>
    </div>

    <!-- Invoices Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Invoice #</th>
                        <th class="px-4 py-3.5">Client & Email</th>
                        <th class="px-4 py-3.5">Currency</th>
                        <th class="px-4 py-3.5 text-right">Total Amount</th>
                        <th class="px-4 py-3.5">Issue Date</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5 font-bold font-numeric text-primary">
                                <a href="{{ route('invoices.show', $inv) }}" class="hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-text-rich block">{{ $inv->customer_name }}</span>
                                <span class="text-secondary text-[11px]">{{ $inv->customer_email }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-primary/10 text-primary">
                                    {{ $inv->currency_code }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-numeric font-bold text-text-rich">
                                {{ $inv->formatted_total }}
                            </td>
                            <td class="px-4 py-3.5 text-secondary font-numeric">
                                {{ $inv->invoice_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3.5">
                                <form action="{{ route('admin.invoices.status', $inv) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded border border-border-quiet px-2 py-1 bg-surface focus:ring-1 focus:ring-primary outline-none cursor-pointer">
                                        <option value="draft" {{ $inv->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ $inv->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $inv->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="overdue" {{ $inv->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        <option value="cancelled" {{ $inv->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('invoices.show', $inv) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="View Statement">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>

                                    <form action="{{ route('admin.invoices.destroy', $inv) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-danger hover:bg-red-50 transition-colors" title="Delete Invoice">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-secondary">
                                No invoice records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
