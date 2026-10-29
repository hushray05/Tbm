@extends('layouts.admin')

@section('title', 'Subscription Payments • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Subscription Payment Transactions</h1>
            <p class="text-xs text-secondary mt-0.5">Real-time ledger of Mobile Money (MTN/Airtel) and card checkout settlements in UGX.</p>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Transaction Ref</th>
                        <th class="px-5 py-3.5">Business / Subscriber</th>
                        <th class="px-5 py-3.5">Plan</th>
                        <th class="px-5 py-3.5">Payment Gateway</th>
                        <th class="px-5 py-3.5">Date & Time</th>
                        <th class="px-5 py-3.5 text-right">Amount (UGX)</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5 font-mono font-bold text-primary">{{ $pay->transaction_reference }}</td>
                            <td class="px-5 py-3.5 font-semibold text-text-rich">{{ $pay->business->name ?? 'Business' }}</td>
                            <td class="px-5 py-3.5">{{ $pay->plan->name ?? 'Starter' }}</td>
                            <td class="px-5 py-3.5 text-secondary">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800">
                                    {{ $pay->gateway }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-numeric text-secondary">{{ $pay->paid_at ? $pay->paid_at->format('d M Y H:i') : 'N/A' }}</td>
                            <td class="px-5 py-3.5 text-right font-numeric font-extrabold text-success text-sm">
                                UGX {{ number_format($pay->amount) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $pay->status === 'successful' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $pay->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-secondary">
                                No payment records logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-border-quiet">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
