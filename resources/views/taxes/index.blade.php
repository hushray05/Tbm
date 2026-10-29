@extends('layouts.app')

@section('title', 'Uganda URA Tax Compliance • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Tax Compliance & URA Filing</h1>
            <p class="text-xs text-secondary mt-0.5">Uganda Revenue Authority (URA) statutory compliance: PAYE, NSSF, VAT 18% threshold, and Local Excise Duty (LED).</p>
        </div>
        <div>
            <button type="button" onclick="document.getElementById('taxFilingModal').classList.remove('hidden')" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">post_add</span>
                <span>Record Tax Assessment / PRN</span>
            </button>
        </div>
    </div>

    <!-- Uganda VAT Statutory Threshold Tracking Banner (UGX 150,000,000) -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="text-xs font-bold text-primary uppercase tracking-wider">Uganda VAT Statutory Threshold Tracking</span>
                <h3 class="font-headline font-bold text-lg text-text-rich mt-0.5">Annual Taxable Turnover: UGX {{ number_format($annualTurnoverUgx) }}</h3>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold font-numeric text-text-rich">{{ $vatProgressPercent }}%</span>
                <span class="text-[11px] text-secondary block">of UGX {{ number_format($vatThresholdUgx) }} VAT threshold</span>
            </div>
        </div>

        <div class="w-full h-3 bg-bg-subtle rounded-full overflow-hidden border border-border-quiet">
            <div class="h-full bg-gradient-to-r from-primary to-amber-500 rounded-full transition-all duration-500" style="width: {{ $vatProgressPercent }}%"></div>
        </div>
        <p class="text-[11px] text-secondary">Under the Uganda Value Added Tax Act, businesses with annual gross turnover exceeding UGX 150,000,000 are required to register for statutory 18% VAT collection and monthly electronic returns.</p>
    </div>

    <!-- Tax Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Total Taxes Settled (Paid)</span>
            <div class="font-headline text-2xl font-extrabold text-success font-numeric mt-1">
                UGX {{ number_format($totalTaxesPaidUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">Cleared via URA Payment Registration Numbers (PRNs)</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Pending Statutory Obligations</span>
            <div class="font-headline text-2xl font-extrabold text-amber-700 font-numeric mt-1">
                UGX {{ number_format($totalPendingTaxUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">Awaiting monthly banking settlement</span>
        </div>
    </div>

    <!-- Tax Compliance Records Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
            <span class="font-headline font-bold text-sm text-text-rich">URA Tax Assessments & Filing Ledger</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle/50 text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3">Tax Type</th>
                        <th class="px-5 py-3">Period</th>
                        <th class="px-5 py-3">Taxable Base (UGX)</th>
                        <th class="px-5 py-3">Rate</th>
                        <th class="px-5 py-3 text-right">Tax Amount (UGX)</th>
                        <th class="px-5 py-3">URA PRN #</th>
                        <th class="px-5 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @foreach($taxRecords as $tr)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-text-rich">{{ $tr->tax_type }}</td>
                            <td class="px-5 py-3.5 font-medium text-secondary">{{ $tr->period }}</td>
                            <td class="px-5 py-3.5 font-numeric font-semibold text-text-rich">UGX {{ number_format($tr->taxable_amount_ugx) }}</td>
                            <td class="px-5 py-3.5 font-numeric text-secondary">{{ number_format($tr->tax_rate_percent, 1) }}%</td>
                            <td class="px-5 py-3.5 text-right font-numeric font-extrabold text-danger text-sm">
                                UGX {{ number_format($tr->tax_amount_ugx) }}
                            </td>
                            <td class="px-5 py-3.5 font-numeric text-primary font-bold">{{ $tr->ura_prn ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $tr->status === 'paid' ? 'bg-green-100 text-green-800' : ($tr->status === 'filed' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $tr->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tax Filing Modal -->
<div id="taxFilingModal" class="fixed inset-0 bg-slate-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-surface rounded-2xl border border-border-quiet p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-headline font-bold text-base text-text-rich">Record Tax Assessment</h3>
            <button type="button" onclick="document.getElementById('taxFilingModal').classList.add('hidden')" class="text-secondary hover:text-text-rich">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <form action="{{ route('taxes.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Tax Type <span class="text-danger">*</span></label>
                <select name="tax_type" required class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                    <option value="PAYE">PAYE (Pay-As-You-Earn Withholding)</option>
                    <option value="NSSF (15% Total)">NSSF (15% Employee & Employer)</option>
                    <option value="VAT (18%)">VAT (18% Output/Input Net)</option>
                    <option value="Local Excise Duty (LED)">Local Excise Duty (LED)</option>
                    <option value="Corporation Tax (30%)">Corporation Income Tax</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Period <span class="text-danger">*</span></label>
                    <input type="text" name="period" required value="August 2026" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Tax Rate (%) <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" name="tax_rate_percent" required value="18" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Taxable Base Amount (UGX) <span class="text-danger">*</span></label>
                <input type="number" name="taxable_amount_ugx" required placeholder="e.g. 50000000" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs font-numeric text-text-rich outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Status <span class="text-danger">*</span></label>
                    <select name="status" required class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                        <option value="pending">Pending</option>
                        <option value="filed" selected>Filed</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">URA PRN #</label>
                    <input type="text" name="ura_prn" placeholder="226001..." class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('taxFilingModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold shadow-sm hover:bg-primary-container">Save Assessment</button>
            </div>
        </form>
    </div>
</div>
@endsection
