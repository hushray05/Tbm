<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Order;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display Sales Orders or Purchase Orders.
     */
    public function index(Request $request): View
    {
        $type = $request->query('type', 'sales_order'); // sales_order, purchase_order
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        $orders = Order::where('business_id', $business ? $business->id : 1)
            ->where('type', $type)
            ->orderBy('order_date', 'desc')
            ->get();

        $totalOrderVolumeUgx = $orders->sum('total_amount_ugx');

        return view('orders.index', compact('orders', 'type', 'totalOrderVolumeUgx', 'business'));
    }

    /**
     * Store a new sales or purchase order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:sales_order,purchase_order',
            'party_name' => 'required|string|max:255',
            'total_amount_ugx' => 'required|numeric|min:1',
            'order_date' => 'required|date',
            'status' => 'required|in:draft,confirmed,fulfilled,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $prefix = ($validated['type'] === 'sales_order') ? 'SO' : 'PO';
        $orderNumber = $prefix . '-' . date('Y') . '-' . rand(1000, 9999);

        Order::create([
            'business_id' => $business ? $business->id : 1,
            'order_number' => $orderNumber,
            'type' => $validated['type'],
            'party_name' => $validated['party_name'],
            'total_amount_ugx' => (int) $validated['total_amount_ugx'],
            'status' => $validated['status'],
            'order_date' => $validated['order_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $label = ($validated['type'] === 'sales_order') ? 'Sales Order' : 'Purchase Order';
        return back()->with('success', "{$label} {$orderNumber} recorded successfully.");
    }
}
