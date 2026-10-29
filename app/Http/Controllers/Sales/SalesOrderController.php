<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Party;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SalesOrderController extends Controller
{
    public function index(Request $request): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $orders = Order::where('business_id', $business ? $business->id : 1)
            ->where('type', 'sales_order')
            ->orderBy('order_date', 'desc')
            ->paginate(15);

        $totalSalesOrderVolume = Order::where('business_id', $business ? $business->id : 1)
            ->where('type', 'sales_order')
            ->sum('total_amount_ugx');

        return view('sales.orders.index', compact('orders', 'totalSalesOrderVolume', 'business'));
    }

    public function create(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $customers = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'customer')
            ->orderBy('name')
            ->get();
        $nextOrderNumber = 'SO-' . date('Y') . '-' . rand(1000, 9999);
        $products = Product::where('is_active', true)->get();

        return view('sales.orders.create', compact('customers', 'nextOrderNumber', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => 'required|string|unique:orders,order_number',
            'party_name' => 'required|string|max:255',
            'total_amount_ugx' => 'required|numeric|min:1',
            'order_date' => 'required|date',
            'status' => 'required|in:draft,confirmed,fulfilled,cancelled',
            'notes' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'order-' . Str::slug($validated['order_number']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/orders'), $filename);
            $imageUrl = 'images/orders/' . $filename;
        }

        Order::create([
            'business_id' => $business ? $business->id : 1,
            'order_number' => $validated['order_number'],
            'type' => 'sales_order',
            'party_name' => $validated['party_name'],
            'total_amount_ugx' => (int) $validated['total_amount_ugx'],
            'status' => $validated['status'],
            'order_date' => $validated['order_date'],
            'notes' => $validated['notes'] ?? null,
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('sales.orders.index')->with('success', "Sales Order {$validated['order_number']} created successfully.");
    }

    /**
     * Convert Sales Order to Invoice.
     */
    public function convertToInvoice(Order $order): RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $invNumber = 'INV-' . date('Y') . '-' . rand(1000, 9999);
        $vatRate = config('business.vat_rate', 18);

        $subtotal = (int) round($order->total_amount_ugx / (1 + ($vatRate / 100)));
        $tax = $order->total_amount_ugx - $subtotal;

        $invoice = Invoice::create([
            'business_id' => $business ? $business->id : 1,
            'invoice_number' => $invNumber,
            'customer_name' => $order->party_name,
            'customer_email' => 'client@orders.ug',
            'billing_address' => 'Uganda Commerce',
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'currency_code' => 'UGX',
            'exchange_rate_applied' => 1.0,
            'subtotal_cents' => $subtotal,
            'tax_cents' => $tax,
            'total_cents' => $order->total_amount_ugx,
            'status' => 'pending',
            'notes' => "Generated from Sales Order #{$order->order_number}",
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => "Order items as specified in SO #{$order->order_number}",
            'quantity' => 1,
            'unit_price_cents' => $subtotal,
            'total_price_cents' => $subtotal,
        ]);

        $order->update(['status' => 'fulfilled']);

        return redirect()->route('invoices.show', $invoice)->with('success', "Sales Order {$order->order_number} converted into Invoice #{$invoice->invoice_number}!");
    }
}
