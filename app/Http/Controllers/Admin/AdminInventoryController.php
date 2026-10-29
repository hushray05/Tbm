<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminInventoryController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Admin Inventory Control & Stock Audit.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($status === 'low') {
            $query->where('stock_quantity', '<=', 100);
        } elseif ($status === 'out') {
            $query->where('stock_quantity', '<=', 0);
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $allProducts = Product::all();
        $totalUnits = $allProducts->sum('stock_quantity');
        $lowStockCount = $allProducts->where('stock_quantity', '<=', 100)->count();
        $outOfStockCount = $allProducts->where('stock_quantity', '<=', 0)->count();

        $totalValuationCents = 0;
        foreach ($allProducts as $p) {
            $totalValuationCents += ($p->stock_quantity * $p->price_cents);
        }

        return view('admin.inventory.index', compact(
            'products',
            'search',
            'status',
            'totalUnits',
            'lowStockCount',
            'outOfStockCount',
            'totalValuationCents'
        ));
    }

    /**
     * Quick Stock Adjustment / Restock Action.
     */
    public function adjust(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,set,subtract',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $qty = (int) $validated['quantity'];
        $oldStock = $product->stock_quantity;

        if ($validated['adjustment_type'] === 'add') {
            $newStock = $oldStock + $qty;
        } elseif ($validated['adjustment_type'] === 'subtract') {
            $newStock = max(0, $oldStock - $qty);
        } else {
            $newStock = $qty;
        }

        $product->update([
            'stock_quantity' => $newStock,
        ]);

        $reasonText = $validated['reason'] ? " (Reason: {$validated['reason']})" : '';
        return back()->with('success', "Stock for '{$product->name}' updated from {$oldStock} to {$newStock} units.{$reasonText}");
    }
}
