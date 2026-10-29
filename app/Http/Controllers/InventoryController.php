<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Enterprise Inventory Overview & Warehouse Stock.
     */
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $search = $request->query('search');
        $stockFilter = $request->query('stock_status');
        $threshold = config('business.low_stock_threshold', 10);

        $query = Product::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($stockFilter === 'low') {
            $query->where('stock_quantity', '<=', $threshold)->where('stock_quantity', '>', 0);
        } elseif ($stockFilter === 'out') {
            $query->where('stock_quantity', '<=', 0);
        } elseif ($stockFilter === 'healthy') {
            $query->where('stock_quantity', '>', $threshold);
        }

        $products = $query->orderBy('stock_quantity', 'asc')->paginate(12)->withQueryString();

        // System-wide Metrics
        $allProducts = Product::all();
        $totalItems = $allProducts->count();
        $totalUnits = $allProducts->sum('stock_quantity');
        $lowStockCount = $allProducts->where('stock_quantity', '<=', $threshold)->where('stock_quantity', '>', 0)->count();
        $outOfStockCount = $allProducts->where('stock_quantity', '<=', 0)->count();
        
        $totalValuationCents = 0;
        $totalCostValuationCents = 0;
        foreach ($allProducts as $p) {
            $totalValuationCents += ($p->stock_quantity * $p->price_cents);
            $totalCostValuationCents += ($p->stock_quantity * ($p->cost_price_cents ?: $p->price_cents));
        }

        $categories = Product::distinct()->pluck('category')->filter()->values();

        // Recent Movements across all products
        $recentMovements = StockMovement::with('product', 'creator')
            ->latest('id')
            ->take(10)
            ->get();

        return view('inventory.index', compact(
            'products',
            'allProducts',
            'categories',
            'category',
            'search',
            'stockFilter',
            'totalItems',
            'totalUnits',
            'lowStockCount',
            'outOfStockCount',
            'totalValuationCents',
            'totalCostValuationCents',
            'recentMovements',
            'threshold'
        ));
    }

    /**
     * Perform Stock-In, Stock-Out, or Inventory Adjustment.
     */
    public function adjust(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|in:purchase,sale,adjustment,damage,return,count,opening',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $qty = (int) $validated['quantity'];
        $currentStock = $product->stock_quantity;

        $quantityChange = match($validated['adjustment_type']) {
            'add' => $qty,
            'subtract' => -$qty,
            'set' => $qty - $currentStock,
        };

        $reference = $validated['reference'] ?: ('ADJ-' . date('Ymd') . '-' . rand(100, 999));
        $notes = $validated['notes'] ?: "Stock adjustment via Inventory manager (" . ucfirst($validated['reason']) . ")";

        $product->adjustStock(
            $quantityChange,
            $validated['reason'],
            $reference,
            $notes,
            auth()->id()
        );

        return back()->with('success', "Stock for '{$product->name}' adjusted successfully. New balance: {$product->fresh()->stock_quantity} units.");
    }

    /**
     * Display stock movement ledger for a specific product.
     */
    public function history(Product $product): View
    {
        $movements = $product->stockMovements()->with('creator')->paginate(20);
        return view('inventory.history', compact('product', 'movements'));
    }
}
