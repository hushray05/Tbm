<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payslip • {{ $payroll->staff->name }} • {{ $payroll->payroll_period }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Manrope', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 p-4 md:p-10">
    <div class="max-w-2xl mx-auto space-y-4">
        <!-- Print Header Controls -->
        <div class="flex items-center justify-between no-print mb-4">
            <a href="{{ route('payroll.index') }}" class="text-xs font-bold text-blue-800 hover:underline">
                ← Back to Payroll Dashboard
            </a>
            <button onclick="window.print()" class="bg-blue-800 hover:bg-blue-900 text-white px-4 py-2 rounded-lg text-xs font-bold shadow flex items-center gap-1.5">
                <span>Print Official Payslip</span>
            </button>
        </div>

        <!-- Payslip Document Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm space-y-6">
            <!-- Organization Header -->
            <div class="flex items-start justify-between border-b border-slate-200 pb-6">
                <div>
                    <h1 class="font-headline font-extrabold text-xl text-blue-950">{{ $payroll->business->name ?? 'Victoria Logistics & Trading Ltd' }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Plot 42 Jinja Road, Industrial Area, Kampala, Uganda</p>
                    <p class="text-xs text-slate-500">TIN: {{ $payroll->business->tin_number ?? '1002345678' }} • Currency: UGX</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-blue-50 text-blue-900 text-xs font-extrabold rounded-full uppercase">Official Payslip</span>
                    <span class="block text-xs text-slate-500 mt-1 font-mono">Ref: {{ $payroll->reference_no }}</span>
                </div>
            </div>

            <!-- Employee Meta Grid -->
            <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl">
                <div>
                    <span class="text-slate-500 text-[11px] block uppercase font-bold">Employee Details</span>
                    <strong class="text-slate-900 text-sm block mt-0.5">{{ $payroll->staff->name }}</strong>
                    <span class="text-slate-600 block">{{ $payroll->staff->designation }} • {{ $payroll->staff->department }}</span>
                    <span class="text-slate-600 block">ID: {{ $payroll->staff->employee_id }}</span>
                </div>
                <div>
                    <span class="text-slate-500 text-[11px] block uppercase font-bold">Statutory Identifiers</span>
                    <span class="text-slate-700 block mt-0.5">NSSF #: <strong>{{ $payroll->staff->nssf_number ?? 'N/A' }}</strong></span>
                    <span class="text-slate-700 block">URA TIN: <strong>{{ $payroll->staff->tin_number ?? 'N/A' }}</strong></span>
                    <span class="text-slate-700 block">Pay Period: <strong>{{ $payroll->payroll_period }}</strong></span>
                </div>
            </div>

            <!-- Earnings vs Deductions Table -->
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Earnings -->
                    <div>
                        <h4 class="font-headline font-bold text-xs uppercase text-slate-500 border-b border-slate-200 pb-1.5 mb-2">Earnings</h4>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Basic Salary</span>
                                <span class="font-mono font-semibold">UGX {{ number_format($payroll->basic_salary_ugx) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Allowances</span>
                                <span class="font-mono font-semibold">UGX {{ number_format($payroll->allowances_ugx) }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-slate-200 font-bold text-slate-900">
                                <span>Gross Pay</span>
                                <span class="font-mono">UGX {{ number_format($payroll->gross_pay_ugx) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statutory Deductions -->
                    <div>
                        <h4 class="font-headline font-bold text-xs uppercase text-slate-500 border-b border-slate-200 pb-1.5 mb-2">Statutory Deductions</h4>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between text-red-700">
                                <span>PAYE Tax (URA)</span>
                                <span class="font-mono font-semibold">- UGX {{ number_format($payroll->paye_tax_ugx) }}</span>
                            </div>
                            <div class="flex justify-between text-amber-800">
                                <span>NSSF (5% Employee)</span>
                                <span class="font-mono font-semibold">- UGX {{ number_format($payroll->nssf_employee_ugx) }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-slate-200 font-bold text-red-900">
                                <span>Total Deductions</span>
                                <span class="font-mono">- UGX {{ number_format($payroll->paye_tax_ugx + $payroll->nssf_employee_ugx) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employer NSSF Note -->
                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-[11px] text-slate-600 flex justify-between">
                    <span>Employer NSSF Contribution (10% Paid by Company):</span>
                    <strong class="font-mono text-slate-900">UGX {{ number_format($payroll->nssf_employer_ugx) }}</strong>
                </div>

                <!-- Net Pay Box -->
                <div class="p-4 bg-blue-900 text-white rounded-xl flex items-center justify-between shadow">
                    <div>
                        <span class="text-xs uppercase tracking-wider text-blue-200 block font-bold">Net Salary Payable</span>
                        <span class="text-[11px] text-blue-300">Disbursed via {{ $payroll->payment_method }}</span>
                    </div>
                    <div class="font-headline font-extrabold text-2xl font-mono text-white">
                        UGX {{ number_format($payroll->net_salary_ugx) }}
                    </div>
                </div>
            </div>

            <!-- Footer Signatures -->
            <div class="pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs text-slate-500">
                <div>
                    <div class="border-b border-slate-300 pb-8 mb-1"></div>
                    <span>Prepared by (Finance & Accounts)</span>
                </div>
                <div>
                    <div class="border-b border-slate-300 pb-8 mb-1"></div>
                    <span>Employee Acknowledgement</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
