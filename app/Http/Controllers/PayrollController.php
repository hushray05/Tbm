<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Payroll;
use App\Models\Staff;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display Payroll Dashboard, staff list, and recent payroll runs.
     */
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $staffMembers = Staff::where('business_id', $business ? $business->id : 1)->orderBy('name')->get();
        $payrolls = Payroll::with('staff')
            ->where('business_id', $business ? $business->id : 1)
            ->orderBy('id', 'desc')
            ->get();

        $totalMonthlyGrossUgx = $staffMembers->sum('gross_salary');
        $totalPayeUgx = $payrolls->sum('paye_tax_ugx');
        $totalNssfUgx = $payrolls->sum('nssf_employee_ugx') + $payrolls->sum('nssf_employer_ugx');

        return view('payroll.index', compact('staffMembers', 'payrolls', 'totalMonthlyGrossUgx', 'totalPayeUgx', 'totalNssfUgx', 'business'));
    }

    /**
     * Show form to add an employee / staff record.
     */
    public function createStaff(): View
    {
        return view('payroll.create-staff');
    }

    /**
     * Store new employee record.
     */
    public function storeStaff(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:staff,employee_id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'basic_salary_ugx' => 'required|numeric|min:0',
            'allowances_ugx' => 'nullable|numeric|min:0',
            'tin_number' => 'nullable|string|max:50',
            'nssf_number' => 'nullable|string|max:50',
            'payment_method' => 'required|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'mobile_money_number' => 'nullable|string|max:50',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        Staff::create(array_merge($validated, [
            'business_id' => $business ? $business->id : 1,
            'basic_salary_ugx' => (int) $validated['basic_salary_ugx'],
            'allowances_ugx' => (int) ($validated['allowances_ugx'] ?? 0),
            'is_active' => true,
        ]));

        return redirect()->route('payroll.index')->with('success', "Staff record for {$validated['name']} registered successfully.");
    }

    /**
     * Process payroll for an employee or all employees for the given month.
     */
    public function process(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'payroll_period' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $staff = Staff::findOrFail($validated['staff_id']);
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        $gross = $staff->gross_salary;
        $nssfEmployee = (int) round($gross * 0.05); // 5% employee NSSF
        $nssfEmployer = (int) round($gross * 0.10); // 10% employer NSSF
        $chargeable = $gross - $nssfEmployee;
        $paye = Payroll::calculateUgandaPAYE($chargeable);
        $net = $gross - $nssfEmployee - $paye;

        Payroll::create([
            'business_id' => $business ? $business->id : 1,
            'staff_id' => $staff->id,
            'payroll_period' => $validated['payroll_period'],
            'basic_salary_ugx' => $staff->basic_salary_ugx,
            'allowances_ugx' => $staff->allowances_ugx,
            'gross_pay_ugx' => $gross,
            'nssf_employee_ugx' => $nssfEmployee,
            'nssf_employer_ugx' => $nssfEmployer,
            'paye_tax_ugx' => $paye,
            'other_deductions_ugx' => 0,
            'net_salary_ugx' => $net,
            'payment_status' => 'paid',
            'payment_date' => Carbon::now()->toDateString(),
            'payment_method' => $validated['payment_method'],
            'reference_no' => 'PR-' . strtoupper(substr($validated['payroll_period'], 0, 3)) . '-' . rand(1000, 9999),
        ]);

        return redirect()->route('payroll.index')->with('success', "Payroll processed for {$staff->name}. Net Pay: UGX " . number_format($net) . " (PAYE: UGX " . number_format($paye) . ", NSSF: UGX " . number_format($nssfEmployee) . ").");
    }

    /**
     * Generate printable Payslip view.
     */
    public function payslip(Payroll $payroll): View
    {
        $payroll->load(['staff', 'business']);
        return view('payroll.payslip', compact('payroll'));
    }
}
