<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Party;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $quotes = Quote::where('business_id', $business ? $business->id : 1)
            ->with('items', 'customer')
            ->latest('id')
            ->paginate(12);

        $totalQuotesVal = Quote::where('business_id', $business ? $business->id : 1)->sum('total_amount_ugx');

        return view('sales.quotes.index', compact('quotes', 'totalQuotesVal', 'business'));
    }

    public function create(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $customers = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'customer')
            ->orderBy('name')
            ->get();
        $products = Product::where('is_active', true)->get();
        $nextQuoteNum = 'QUO-' . date('Y') . '-' . rand(1000, 9999);
        $vatRate = config('business.vat_rate', 18);

        return view('sales.quotes.create', compact('customers', 'products', 'nextQuoteNum', 'vatRate'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quote_number' => 'required|string|unique:quotes,quote_number',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'party_id' => 'nullable|exists:parties,id',
            'quote_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $vatRate = config('business.vat_rate', 18);

        $subtotal = 0;
        $itemsToInsert = [];

        foreach ($validated['items'] as $it) {
            $unitPrice = (int) $it['unit_price'];
            $qty = (int) $it['quantity'];
            $lineTotal = $unitPrice * $qty;
            $subtotal += $lineTotal;

            $itemsToInsert[] = [
                'product_id' => $it['product_id'] ?? null,
                'item_name' => $it['item_name'],
                'quantity' => $qty,
                'unit_price_ugx' => $unitPrice,
                'total_price_ugx' => $lineTotal,
            ];
        }

        $vatAmount = (int) round($subtotal * ($vatRate / 100));
        $totalAmount = $subtotal + $vatAmount;

        $quote = Quote::create([
            'business_id' => $business ? $business->id : 1,
            'quote_number' => $validated['quote_number'],
            'party_id' => $validated['party_id'] ?? null,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'quote_date' => $validated['quote_date'],
            'expiry_date' => $validated['expiry_date'] ?? null,
            'subtotal_ugx' => $subtotal,
            'vat_rate_percent' => $vatRate,
            'vat_amount_ugx' => $vatAmount,
            'discount_amount_ugx' => 0,
            'total_amount_ugx' => $totalAmount,
            'status' => 'sent',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($itemsToInsert as $itemData) {
            $quote->items()->create($itemData);
        }

        return redirect()->route('sales.quotes.show', $quote)->with('success', "Quote {$quote->quote_number} generated successfully with 18% VAT.");
    }

    public function show(Quote $quote): View
    {
        $quote->load('items', 'customer');
        return view('sales.quotes.show', compact('quote'));
    }

    /**
     * Convert Quote to Sales Order.
     */
    public function convertToOrder(Quote $quote): RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $orderNumber = 'SO-' . date('Y') . '-' . rand(1000, 9999);

        Order::create([
            'business_id' => $business ? $business->id : 1,
            'order_number' => $orderNumber,
            'type' => 'sales_order',
            'party_name' => $quote->customer_name,
            'total_amount_ugx' => $quote->total_amount_ugx,
            'status' => 'confirmed',
            'order_date' => now()->toDateString(),
            'notes' => "Converted from Quote #{$quote->quote_number}. " . ($quote->notes ?? ''),
        ]);

        $quote->update(['status' => 'converted']);

        return redirect()->route('sales.orders.index')->with('success', "Quote {$quote->quote_number} converted into Sales Order {$orderNumber}!");
    }

    /**
     * Convert Quote to Invoice.
     */
    public function convertToInvoice(Quote $quote): RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $invNumber = 'INV-' . date('Y') . '-' . rand(1000, 9999);

        $invoice = Invoice::create([
            'business_id' => $business ? $business->id : 1,
            'invoice_number' => $invNumber,
            'customer_name' => $quote->customer_name,
            'customer_email' => $quote->customer_email ?? 'billing@client.com',
            'billing_address' => 'Uganda Operations',
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'currency_code' => 'UGX',
            'exchange_rate_applied' => 1.0,
            'subtotal_cents' => $quote->subtotal_ugx,
            'tax_cents' => $quote->vat_amount_ugx,
            'total_cents' => $quote->total_amount_ugx,
            'status' => 'pending',
            'notes' => "Converted from Quote #{$quote->quote_number}",
        ]);

        foreach ($quote->items as $it) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $it->item_name,
                'quantity' => $it->quantity,
                'unit_price_cents' => $it->unit_price_ugx,
                'total_price_cents' => $it->total_price_ugx,
            ]);
        }

        $quote->update(['status' => 'converted']);

        return redirect()->route('invoices.show', $invoice)->with('success', "Quote {$quote->quote_number} converted into Invoice #{$invoice->invoice_number}!");
    }
}
