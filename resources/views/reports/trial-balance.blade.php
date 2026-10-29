@extends('layouts.app')

@section('title', 'Trial Balance Ledger • Uganda UGX')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Trial Balance Ledger</h1>
            <p class="text-xs text-secondary mt-0.5">{{ $business->name ?? 'Victoria Logistics & Trading Ltd' }} • General Ledger Audit Compliance</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports') }}" class="text-xs font-semibold text-secondary hover:underline">
                ← Back to Reports
            </a>
            <button onclick="window.print()" class="bg-primary hover:bg-primary-container text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">print</span>
                <span>Print Ledger</span>
            </button>
        </div>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Account Code</th>
                        <th class="px-5 py-3.5">Account Title</th>
                        <th class="px-5 py-3.5 text-right">Debit (UGX)</th>
                        <th class="px-5 py-3.5 text-right">Credit (UGX)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @foreach($accounts as $acc)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3 font-mono font-bold text-primary">{{ $acc['code'] }}</td>
                            <td class="px-5 py-3 font-semibold text-text-rich">{{ $acc['name'] }}</td>
                            <td class="px-5 py-3 text-right font-numeric font-medium {{ $acc['debit'] > 0 ? 'text-text-rich' : 'text-slate-400' }}">
                                {{ $acc['debit'] > 0 ? 'UGX ' . number_format($acc['debit']) : '-' }}
                            </td>
                            <td class="px-5 py-3 text-right font-numeric font-medium {{ $acc['credit'] > 0 ? 'text-text-rich' : 'text-slate-400' }}">
                                {{ $acc['credit'] > 0 ? 'UGX ' . number_format($acc['credit']) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-primary/5 border-t-2 border-primary font-bold text-xs">
                    <tr>
                        <td colspan="2" class="px-5 py-3.5 text-text-rich uppercase tracking-wider font-headline font-extrabold">
                            Trial Balance Total (Balanced Ledger)
                        </td>
                        <td class="px-5 py-3.5 text-right font-numeric text-primary font-extrabold">
                            UGX {{ number_format($totalDebit) }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-numeric text-primary font-extrabold">
                            UGX {{ number_format($totalCredit) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
