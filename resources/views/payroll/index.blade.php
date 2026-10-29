@extends('layouts.app')

@section('title', 'Payroll & Payslips • Uganda UGX')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Payroll & Staff Compensation</h1>
            <p class="text-xs text-secondary mt-0.5">Automated Uganda statutory PAYE tax brackets & NSSF (5% employee + 10% employer) calculations.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('payroll.staff.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">person_add</span>
                <span>Add Employee</span>
            </a>
            <button type="button" onclick="document.getElementById('payrollModal').classList.remove('hidden')" class="bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">receipt</span>
                <span>Process Monthly Payroll</span>
            </button>
        </div>
    </div>

    <!-- Statutory Totals -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Monthly Gross Payroll</span>
            <div class="font-headline text-2xl font-extrabold text-primary font-numeric mt-1">
                UGX {{ number_format($totalMonthlyGrossUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">{{ $staffMembers->count() }} active staff on record</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Uganda PAYE Tax Withheld</span>
            <div class="font-headline text-2xl font-extrabold text-danger font-numeric mt-1">
                UGX {{ number_format($totalPayeUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">Payable to URA monthly</span>
        </div>

        <div class="bg-surface border border-border-quiet rounded-xl p-5 tbm-shadow">
            <span class="text-xs font-bold text-secondary uppercase">Total NSSF Contribution (15%)</span>
            <div class="font-headline text-2xl font-extrabold text-amber-700 font-numeric mt-1">
                UGX {{ number_format($totalNssfUgx) }}
            </div>
            <span class="text-[11px] text-secondary mt-1 block">5% Employee + 10% Employer</span>
        </div>
    </div>

    <!-- Staff Directory & Payroll Processing -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Staff List (6 cols) -->
        <div class="lg:col-span-6 bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
                <span class="font-headline font-bold text-sm text-text-rich">Staff Directory</span>
                <span class="text-xs font-semibold text-secondary">{{ $staffMembers->count() }} Employees</span>
            </div>
            <div class="divide-y divide-border-quiet max-h-96 overflow-y-auto">
                @foreach($staffMembers as $s)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-bg-subtle/50 transition-colors">
                        <div>
                            <span class="font-bold text-text-rich block">{{ $s->name }}</span>
                            <span class="text-[11px] text-secondary">{{ $s->designation }} • {{ $s->department }}</span>
                            <span class="text-[10px] text-primary font-semibold block">NSSF: {{ $s->nssf_number ?? 'N/A' }} | TIN: {{ $s->tin_number ?? 'N/A' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-text-rich font-numeric block">UGX {{ number_format($s->gross_salary) }}</span>
                            <span class="text-[10px] text-secondary">Gross Pay</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payroll Runs & Payslips (6 cols) -->
        <div class="lg:col-span-6 bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle flex items-center justify-between">
                <span class="font-headline font-bold text-sm text-text-rich">Processed Payslips</span>
            </div>
            <div class="divide-y divide-border-quiet max-h-96 overflow-y-auto">
                @forelse($payrolls as $pr)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-bg-subtle/50 transition-colors">
                        <div>
                            <span class="font-bold text-text-rich block">{{ $pr->staff->name ?? 'Staff' }}</span>
                            <span class="text-[11px] text-secondary">Period: {{ $pr->payroll_period }} • Ref: {{ $pr->reference_no }}</span>
                            <div class="text-[10px] text-secondary mt-0.5 space-x-2">
                                <span>PAYE: <strong class="font-numeric text-danger">UGX {{ number_format($pr->paye_tax_ugx) }}</strong></span>
                                <span>NSSF (5%): <strong class="font-numeric text-amber-800">UGX {{ number_format($pr->nssf_employee_ugx) }}</strong></span>
                            </div>
                        </div>
                        <div class="text-right space-y-1">
                            <span class="font-bold text-success font-numeric block text-sm">UGX {{ number_format($pr->net_salary_ugx) }}</span>
                            <a href="{{ route('payroll.payslip', $pr) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-primary hover:underline">
                                <span class="material-symbols-outlined text-xs">receipt</span>
                                <span>View Payslip</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-secondary text-xs">
                        No payroll runs processed yet. Click "Process Monthly Payroll".
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Process Payroll Modal -->
<div id="payrollModal" class="fixed inset-0 bg-slate-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-surface rounded-2xl border border-border-quiet p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-headline font-bold text-base text-text-rich">Process Employee Payroll</h3>
            <button type="button" onclick="document.getElementById('payrollModal').classList.add('hidden')" class="text-secondary hover:text-text-rich">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <form action="{{ route('payroll.process') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Select Employee <span class="text-danger">*</span></label>
                <select name="staff_id" required class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                    @foreach($staffMembers as $sm)
                        <option value="{{ $sm->id }}">{{ $sm->name }} (Gross: UGX {{ number_format($sm->gross_salary) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Payroll Period <span class="text-danger">*</span></label>
                <input type="text" name="payroll_period" required value="August 2026" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Disbursement Method <span class="text-danger">*</span></label>
                <select name="payment_method" required class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                    <option value="Bank Transfer">Bank Transfer (Stanbic/Centenary)</option>
                    <option value="MTN Mobile Money">MTN Mobile Money</option>
                    <option value="Airtel Money">Airtel Money</option>
                    <option value="Cash">Cash Voucher</option>
                </select>
            </div>

            <div class="p-3 bg-bg-subtle rounded-xl text-[11px] text-secondary space-y-1">
                <div class="flex items-center gap-1 font-bold text-text-rich">
                    <span class="material-symbols-outlined text-sm text-primary">calculate</span>
                    <span>Statutory Uganda Deduction Rules</span>
                </div>
                <p>NSSF (5% Employee + 10% Employer) and URA PAYE progressive tax brackets will be computed automatically.</p>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('payrollModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold shadow-sm hover:bg-primary-container">Calculate & Pay</button>
            </div>
        </form>
    </div>
</div>
@endsection
