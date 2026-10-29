@extends('layouts.app')

@section('title', 'Add Employee • Payroll Suite')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Register Employee Record</h1>
            <p class="text-xs text-secondary mt-0.5">Add staff compensation profile, NSSF number, and statutory tax parameters.</p>
        </div>
        <a href="{{ route('payroll.index') }}" class="text-xs font-semibold text-primary hover:underline">
            ← Back to Payroll
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <form action="{{ route('payroll.staff.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" name="employee_id" required value="EMP-UG-{{ rand(100, 999) }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Full Legal Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Grace Akello" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Department <span class="text-danger">*</span></label>
                    <input type="text" name="department" required value="Operations" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Designation / Role <span class="text-danger">*</span></label>
                    <input type="text" name="designation" required placeholder="e.g. Logistics Officer" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Monthly Basic Salary (UGX) <span class="text-danger">*</span></label>
                    <input type="number" name="basic_salary_ugx" required placeholder="e.g. 2500000" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Monthly Allowances (UGX)</label>
                    <input type="number" name="allowances_ugx" value="0" placeholder="0" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Uganda TIN Number</label>
                    <input type="text" name="tin_number" placeholder="1005678901" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Uganda NSSF Number</label>
                    <input type="text" name="nssf_number" placeholder="NS-443322-A" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Payment Method <span class="text-danger">*</span></label>
                    <select name="payment_method" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                        <option value="Bank Transfer">Bank Account Transfer</option>
                        <option value="MTN Mobile Money">MTN Mobile Money</option>
                        <option value="Airtel Money">Airtel Money</option>
                        <option value="Cash">Cash Voucher</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Bank Name / MM Account</label>
                    <input type="text" name="bank_name" placeholder="Stanbic Bank / +256 772 000000" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="pt-3 border-t border-border-quiet flex items-center justify-end gap-3">
                <a href="{{ route('payroll.index') }}" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary hover:bg-bg-subtle">
                    Cancel
                </a>
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">person_add</span>
                    <span>Save Employee Record</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
