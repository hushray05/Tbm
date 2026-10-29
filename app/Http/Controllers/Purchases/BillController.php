<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Business;
use App\Models\Order;
use App\Models\Party;
use App\Models\Product;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $bills = Bill::where('business_id', $business ? $business->id : 1)
            ->with('vendor', 'items')
            ->latest('id')
            ->paginate(15);

        $totalBillVolume = Bill::where('business_id', $business ? $business->id : 1)->sum('total_amount_ugx');
        $outstandingBillBalance = Bill::where('business_id', $business ? $business->id : 1)->sum('balance_ugx');

        return view('purchases.bills.index', compact('bills', 'totalBillVolume', 'outstandingBillBalance', 'business'));
    }

    public function create(Request $request): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $vendors = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'vendor')
            ->orderBy('name')
            ->get();
        $products = Product::all();
        $purchaseOrders = Order::where('business_id', $business ? $business->id : 1)
            ->where('type', 'purchase_order')
            ->where('status', '!=', 'fulfilled')
            ->get();

        $nextBillNum = 'BILL-' . date('Y') . '-' . rand(1000, 9999);
        $vatRate = config('business.vat_rate', 18);

        return view('purchases.bills.create', compact('vendors', 'products', 'purchaseOrders', 'nextBillNum', 'vatRate'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bill_number' => 'required|string|unique:bills,bill_number',
            'vendor_name' => 'required|string|max:255',
            'party_id' => 'nullable|exists:parties,id',
            'order_id' => 'nullable|exists:orders,id',
            'bill_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $vatRate = config('business.vat_rate', 18);

        $subtotal = 0;
        $itemsToInsert = [];

        foreach ($validated['items'] as $it) {
            $unitCost = (int) $it['unit_cost'];
            $qty = (int) $it['quantity'];
            $lineTotal = $unitCost * $qty;
            $subtotal += $lineTotal;

            $itemsToInsert[] = [
                'product_id' => $it['product_id'] ?? null,
                'item_name' => $it['item_name'],
                'quantity' => $qty,
                'unit_cost_ugx' => $unitCost,
                'total_cost_ugx' => $lineTotal,
            ];
        }

        $vatAmount = (int) round($subtotal * ($vatRate / 100));
        $totalAmount = $subtotal + $vatAmount;

        $bill = Bill::create([
            'business_id' => $business ? $business->id : 1,
            'bill_number' => $validated['bill_number'],
            'party_id' => $validated['party_id'] ?? null,
            'order_id' => $validated['order_id'] ?? null,
            'vendor_name' => $validated['vendor_name'],
            'bill_date' => $validated['bill_date'],
            'due_date' => $validated['due_date'] ?? null,
            'subtotal_ugx' => $subtotal,
            'vat_rate_percent' => $vatRate,
            'vat_amount_ugx' => $vatAmount,
            'total_amount_ugx' => $totalAmount,
            'paid_amount_ugx' => 0,
            'balance_ugx' => $totalAmount,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Insert items and AUTOMATICALLY INCREASE INVENTORY STOCK
        foreach ($itemsToInsert as $itemData) {
            $bill->items()->create($itemData);

            if (!empty($itemData['product_id'])) {
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    $product->adjustStock(
                        $itemData['quantity'],
                        'purchase',
                        $bill->bill_number,
                        "Stock received via Vendor Bill #{$bill->bill_number} ({$validated['vendor_name']})"
                    );
                }
            }
        }

        // If linked to PO, mark PO as fulfilled
        if (!empty($validated['order_id'])) {
            Order::where('id', $validated['order_id'])->update(['status' => 'fulfilled']);
        }

        return redirect()->route('purchases.bills.show', $bill)->with('success', "Vendor Bill {$bill->bill_number} recorded! Inventory stock has been automatically increased.");
    }

    public function show(Bill $bill): View
    {
        $bill->load('items', 'vendor', 'payments');
        $business = $bill->business ?? (auth()->user() ? auth()->user()->getActiveBusiness() : Business::first());
        return view('purchases.bills.show', compact('bill', 'business'));
    }
}
