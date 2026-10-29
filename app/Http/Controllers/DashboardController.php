<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Company;
use App\Models\ContactMessage;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Page;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Task;
use App\Models\TaxRecord;
use App\Models\Ticket;
use App\Models\User;
use App\Services\CurrencyService;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected CurrencyService $currencyService;
    protected SubscriptionService $subscriptionService;

    public function __construct(CurrencyService $currencyService, SubscriptionService $subscriptionService)
    {
        $this->currencyService = $currencyService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Business Dashboard with Sales, Expenses, Profit, Cash & Bank Flow, ApexCharts, and Trial Expiry Alerts.
     */
    public function index(): View|RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $business = $user->getActiveBusiness() ?? Business::first();
        $bizId = $business ? $business->id : 1;
        $subscription = $this->subscriptionService->getCurrentSubscription($business);

        // Core business metrics in UGX for this active business
        $productsCount = Product::where('business_id', $bizId)->count();
        $invoicesCount = Invoice::where('business_id', $bizId)->count();
        $tasksCount = Task::count();
        $inProgressTasks = Task::where('status', 'in_progress')->count();
        $completedTasks = Task::where('status', 'completed')->count();

        // Calculate Revenue & Expenses in UGX for this active business
        $paidInvoicesUgx = Invoice::where('business_id', $bizId)->where('status', 'paid')->sum('total_cents');
        $totalSalesUgx = (int) $paidInvoicesUgx;

        $totalExpensesUgx = (int) Expense::where('business_id', $bizId)->sum('amount_ugx');
        $totalPayrollUgx = (int) Payroll::where('business_id', $bizId)->sum('gross_pay_ugx');
        $totalOutflowUgx = $totalExpensesUgx + $totalPayrollUgx;

        $netProfitUgx = $totalSalesUgx - $totalOutflowUgx;
        $cashAndBankUgx = max(0, $netProfitUgx);

        // Monthly Trend Data for ApexCharts from real database records
        $months = [];
        $salesTrend = [];
        $expenseTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            $monthSales = (int) Invoice::where('business_id', $bizId)
                ->where('status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_cents');

            $monthExpenses = (int) Expense::where('business_id', $bizId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount_ugx');

            $monthPayroll = (int) Payroll::where('business_id', $bizId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('gross_pay_ugx');

            $salesTrend[] = $monthSales;
            $expenseTrend[] = $monthExpenses + $monthPayroll;
        }

        $recentInvoices = Invoice::where('business_id', $bizId)->latest()->take(5)->get();
        $recentTasks = Task::latest()->take(5)->get();
        $recentOrders = Order::where('business_id', $bizId)->latest()->take(4)->get();
        $recentExpenses = Expense::where('business_id', $bizId)->latest()->take(4)->get();

        // Expiration notification alert banner
        $expirationAlert = $this->subscriptionService->getExpirationNotification($subscription);

        return view('dashboard.index', compact(
            'business',
            'subscription',
            'productsCount',
            'invoicesCount',
            'tasksCount',
            'inProgressTasks',
            'completedTasks',
            'totalSalesUgx',
            'totalExpensesUgx',
            'totalPayrollUgx',
            'totalOutflowUgx',
            'netProfitUgx',
            'cashAndBankUgx',
            'months',
            'salesTrend',
            'expenseTrend',
            'recentInvoices',
            'recentTasks',
            'recentOrders',
            'recentExpenses',
            'expirationAlert'
        ));
    }

    /**
     * CRM & Lead Pipeline.
     */
    public function crm(): View
    {
        $leads = Lead::latest()->get();
        $totalPipelineValueCents = Lead::sum('estimated_value_cents');
        $wonLeadsCount = Lead::where('status', 'won')->count();
        $activeDealsCount = Lead::whereIn('status', ['new', 'contacted', 'qualified', 'proposal'])->count();

        return view('crm.index', compact('leads', 'totalPipelineValueCents', 'wonLeadsCount', 'activeDealsCount'));
    }

    /**
     * Organization & System Settings page (accessible to business owners and admin).
     */
    public function settings(): View
    {
        $user = auth()->user() ?? User::first();
        $business = $user ? $user->getActiveBusiness() : Business::first();
        $subscription = $this->subscriptionService->getCurrentSubscription($business);

        // Load all currencies for the currency switcher on settings page
        $currencies = Currency::orderBy('country')->get();

        return view('settings.index', compact('user', 'business', 'subscription', 'currencies'));
    }

    /**
     * Contact & Support Center.
     */
    public function contact(): View
    {
        $companies = Company::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $page = Page::where('slug', 'contact')->where('is_active', true)->first();

        return view('contact.index', compact('companies', 'page'));
    }

    /**
     * Handle Contact Us message submission.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you! Your enterprise inquiry has been registered. Our Kampala regional office will contact you shortly.');
    }

    /**
     * Show User Notifications / Recent Activity.
     */
    public function notifications(): View
    {
        $businessId = auth()->user()->business_id ?? null;
        
        $notifications = \App\Models\AuditLog::when($businessId, function($q) use ($businessId) {
            $q->whereHas('user', function($u) use ($businessId) {
                $u->where('business_id', $businessId);
            });
        })->latest()->take(20)->get();

        return view('notifications.index', compact('notifications'));
    }
}

