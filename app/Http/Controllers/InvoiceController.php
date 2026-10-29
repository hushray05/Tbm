<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Services\CurrencyService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    protected CurrencyService $currencyService;
    protected SubscriptionService $subscriptionService;

    public function __construct(CurrencyService $currencyService, SubscriptionService $subscriptionService)
    {
        $this->currencyService = $currencyService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display a listing of invoices for the current business.
     */
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $bizId = $business ? $business->id : null;

        $invoices = $bizId ? Invoice::where('business_id', $bizId)->with('items')->latest('id')->paginate(10) : collect();
        $totalBilledCents = $bizId ? Invoice::where('business_id', $bizId)->sum('total_cents') : 0;
        $paidBilledCents = $bizId ? Invoice::where('business_id', $bizId)->where('status', 'paid')->sum('total_cents') : 0;
        $pendingBilledCents = $bizId ? Invoice::where('business_id', $bizId)->where('status', 'pending')->sum('total_cents') : 0;
        $currencyService = $this->currencyService;

        return view('invoices.index', compact('invoices', 'totalBilledCents', 'paidBilledCents', 'pendingBilledCents', 'currencyService'));
    }

    /**
     * Show the form for creating a new multi-currency invoice.
     */
    public function create(): View|RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $bizId = $business ? $business->id : 1;

        // Check subscription limit on invoices
        $limitInfo = $this->subscriptionService->checkUsageLimit('invoices', $business);
        if (!$limitInfo['allowed']) {
            return redirect()->route('invoices.index')->with('error', "Monthly invoice quota reached ({$limitInfo['current']}/{$limitInfo['max']}) on your {$limitInfo['plan_name']} plan. Please upgrade to issue more invoices.");
        }

        $products = Product::where('business_id', $bizId)->where('is_active', true)->get();
        $currencies = Currency::where('is_active', true)->get();
        $currentCurrency = $this->currencyService->getCurrentCurrencyModel();
        $nextInvoiceNum = 'INV-' . date('Y') . '-' . str_pad((string)(Invoice::where('business_id', $bizId)->count() + 1), 3, '0', STR_PAD_LEFT);

        return view('invoices.create', compact('products', 'currencies', 'currentCurrency', 'nextInvoiceNum', 'limitInfo'));
    }

    /**
     * Store a newly created invoice with 18% VAT and business isolation.
     */
    public function store(Request $request): RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $bizId = $business ? $business->id : 1;

        // Enforce subscription invoice quota
        $limitInfo = $this->subscriptionService->checkUsageLimit('invoices', $business);
        if (!$limitInfo['allowed']) {
            return redirect()->route('invoices.index')->with('error', "Monthly invoice quota reached ({$limitInfo['current']}/{$limitInfo['max']}) on your {$limitInfo['plan_name']} plan. Please upgrade to continue.");
        }

        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'billing_address' => 'nullable|string',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'currency_code' => 'required|string|exists:currencies,code',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $currency = Currency::where('code', $validated['currency_code'])->first();
        $rate = $currency ? (float) $currency->exchange_rate : 1.0;

        $subtotalCents = 0;
        $itemsToInsert = [];

        foreach ($validated['items'] as $item) {
            $unitCents = (int) round($item['unit_price'] * 100);
            $totalItemCents = $unitCents * (int) $item['quantity'];
            $subtotalCents += $totalItemCents;

            $itemsToInsert[] = [
                'description' => $item['description'],
                'quantity' => (int) $item['quantity'],
                'unit_price_cents' => $unitCents,
                'total_price_cents' => $totalItemCents,
            ];
        }

        // Apply standard Uganda 18% VAT
        $vatRate = (float) config('business.vat_rate', 18.00);
        $taxCents = (int) round($subtotalCents * ($vatRate / 100));
        $totalCents = $subtotalCents + $taxCents;

        $invoice = Invoice::create([
            'business_id' => $bizId,
            'invoice_number' => $validated['invoice_number'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'billing_address' => $validated['billing_address'] ?? null,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'currency_code' => $validated['currency_code'],
            'exchange_rate_applied' => $rate,
            'subtotal_cents' => $subtotalCents,
            'tax_cents' => $taxCents,
            'total_cents' => $totalCents,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($itemsToInsert as $item) {
            $invoice->items()->create($item);
        }

        return redirect()->route('invoices.show', $invoice)->with('success', "Invoice #{$invoice->invoice_number} generated successfully with 18% Uganda VAT!");
    }

    /**
     * Display the specified invoice details.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load('items', 'business');
        $currencyService = $this->currencyService;
        $payee = $invoice->business;
        return view('invoices.show', compact('invoice', 'currencyService', 'payee'));
    }
}
