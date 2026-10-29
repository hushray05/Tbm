<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Business;
use App\Models\Order;
use App\Models\Party;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $orders = Order::where('business_id', $business ? $business->id : 1)
            ->where('type', 'purchase_order')
            ->orderBy('order_date', 'desc')
            ->paginate(15);

        $totalPOVolume = Order::where('business_id', $business ? $business->id : 1)
            ->where('type', 'purchase_order')
            ->sum('total_amount_ugx');

        return view('purchases.orders.index', compact('orders', 'totalPOVolume', 'business'));
    }

    public function create(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $vendors = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'vendor')
            ->orderBy('name')
            ->get();
        $products = Product::all();
        $nextPONumber = 'PO-' . date('Y') . '-' . rand(1000, 9999);

        return view('purchases.orders.create', compact('vendors', 'products', 'nextPONumber'));
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
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        Order::create([
            'business_id' => $business ? $business->id : 1,
            'order_number' => $validated['order_number'],
            'type' => 'purchase_order',
            'party_name' => $validated['party_name'],
            'total_amount_ugx' => (int) $validated['total_amount_ugx'],
            'status' => $validated['status'],
            'order_date' => $validated['order_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('purchases.orders.index')->with('success', "Purchase Order {$validated['order_number']} issued successfully.");
    }

    /**
     * Convert Purchase Order to Vendor Bill & Receive Inventory.
     */
    public function convertToBill(Order $order): RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $billNumber = 'BILL-' . date('Y') . '-' . rand(1000, 9999);
        $vatRate = config('business.vat_rate', 18);

        $subtotal = (int) round($order->total_amount_ugx / (1 + ($vatRate / 100)));
        $vat = $order->total_amount_ugx - $subtotal;

        $bill = Bill::create([
            'business_id' => $business ? $business->id : 1,
            'bill_number' => $billNumber,
            'order_id' => $order->id,
            'vendor_name' => $order->party_name,
            'bill_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'subtotal_ugx' => $subtotal,
            'vat_rate_percent' => $vatRate,
            'vat_amount_ugx' => $vat,
            'total_amount_ugx' => $order->total_amount_ugx,
            'paid_amount_ugx' => 0,
            'balance_ugx' => $order->total_amount_ugx,
            'status' => 'pending',
            'notes' => "Generated from Purchase Order #{$order->order_number}",
        ]);

        BillItem::create([
            'bill_id' => $bill->id,
            'item_name' => "Procurement items per PO #{$order->order_number}",
            'quantity' => 1,
            'unit_cost_ugx' => $subtotal,
            'total_cost_ugx' => $subtotal,
        ]);

        $order->update(['status' => 'fulfilled']);

        return redirect()->route('purchases.bills.show', $bill)->with('success', "Purchase Order {$order->order_number} converted into Bill #{$bill->bill_number}!");
    }
}
