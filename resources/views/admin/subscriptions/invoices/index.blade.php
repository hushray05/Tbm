@extends('layouts.admin')

@section('title', 'Subscription Invoices • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Subscription Invoices</h1>
            <p class="text-xs text-secondary mt-0.5">Billing invoices generated for business subscription renewals and tier upgrades in UGX.</p>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Invoice #</th>
                        <th class="px-5 py-3.5">Subscriber Organization</th>
                        <th class="px-5 py-3.5">Plan</th>
                        <th class="px-5 py-3.5">Issued Date</th>
                        <th class="px-5 py-3.5">Due Date</th>
                        <th class="px-5 py-3.5 text-right">Total (UGX)</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-bold text-primary">{{ $inv->invoice_number }}</td>
                            <td class="px-5 py-3.5 font-semibold text-text-rich">{{ $inv->business->name ?? 'Business' }}</td>
                            <td class="px-5 py-3.5">{{ $inv->plan->name ?? 'Starter' }}</td>
                            <td class="px-5 py-3.5 font-numeric text-secondary">{{ $inv->issued_date ? $inv->issued_date->format('d M Y') : 'N/A' }}</td>
                            <td class="px-5 py-3.5 font-numeric text-secondary">{{ $inv->due_date ? $inv->due_date->format('d M Y') : 'N/A' }}</td>
                            <td class="px-5 py-3.5 text-right font-numeric font-extrabold text-text-rich text-sm">
                                UGX {{ number_format($inv->total) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $inv->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $inv->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-secondary">
                                No subscription invoices generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-border-quiet">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
