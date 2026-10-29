<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Product;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display a listing of active products for the current business.
     */
    public function index(Request $request): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $bizId = $business ? $business->id : 1;

        $category = $request->query('category');
        $search = $request->query('search');

        $query = Product::where('business_id', $bizId);

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest('id')->paginate(12)->withQueryString();
        $categories = Product::where('business_id', $bizId)->distinct()->pluck('category')->filter()->values();
        $totalProductsCount = Product::where('business_id', $bizId)->count();

        return view('products.index', compact('products', 'categories', 'category', 'search', 'totalProductsCount', 'business'));
    }

    /**
     * Show form to add a new product.
     */
    public function create(): View|RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $bizId = $business ? $business->id : 1;

        // Check subscription limit on products
        $subscription = $this->subscriptionService->getCurrentSubscription($business);
        $planSlug = $subscription && $subscription->plan ? $subscription->plan->slug : 'starter';
        $packageConfig = config("business.packages.{$planSlug}", config('business.packages.starter'));
        $maxProducts = $packageConfig['max_products'] ?? 100;
        $currentProductCount = Product::where('business_id', $bizId)->count();

        if ($maxProducts !== -1 && $currentProductCount >= $maxProducts) {
            return redirect()->route('products.index')->with('error', "You have reached your limit of {$maxProducts} products on the " . ucfirst($planSlug) . " plan. Please upgrade to add more items.");
        }

        $categories = Product::where('business_id', $bizId)->distinct()->pluck('category')->filter()->values();
        $defaultVat = config('business.vat_rate', 18.00);
        $generatedSku = 'SKU-' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $business->name ?? 'PRD'), 0, 3)) . '-' . rand(1000, 9999);

        return view('products.create', compact('categories', 'defaultVat', 'generatedSku', 'maxProducts', 'currentProductCount'));
    }

    /**
     * Store a new product with optional image upload.
     */
    public function store(Request $request): RedirectResponse
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $bizId = $business ? $business->id : 1;

        // Enforce subscription product limits
        $subscription = $this->subscriptionService->getCurrentSubscription($business);
        $planSlug = $subscription && $subscription->plan ? $subscription->plan->slug : 'starter';
        $packageConfig = config("business.packages.{$planSlug}", config('business.packages.starter'));
        $maxProducts = $packageConfig['max_products'] ?? 100;
        $currentProductCount = Product::where('business_id', $bizId)->count();

        if ($maxProducts !== -1 && $currentProductCount >= $maxProducts) {
            return redirect()->route('products.index')->with('error', "You have reached your limit of {$maxProducts} products on your current plan. Please upgrade to continue.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category' => 'required|string|max:100',
            'price_cents' => 'required|numeric|min:0',
            'cost_price_cents' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $product = Product::create([
            'business_id' => $bizId,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'category' => $validated['category'],
            'price_cents' => (int) $validated['price_cents'],
            'cost_price_cents' => (int) ($validated['cost_price_cents'] ?? $validated['price_cents']),
            'stock_quantity' => (int) $validated['stock_quantity'],
            'vat_rate' => $validated['vat_rate'] ?? config('business.vat_rate', 18.00),
            'description' => $validated['description'] ?? null,
            'image_url' => $imageUrl,
            'is_active' => true,
        ]);

        // If initial stock is entered, log opening stock movement
        if ((int) $validated['stock_quantity'] > 0) {
            $product->adjustStock(
                (int) $validated['stock_quantity'],
                'opening',
                'INIT-' . $product->sku,
                'Initial opening inventory stock on item creation',
                auth()->id()
            );
        }

        return redirect()->route('products.index')->with('success', "Item '{$product->name}' added successfully to your catalog!");
    }

    /**
     * Display product details.
     */
    public function show(Product $product): View
    {
        $relatedProducts = Product::where('business_id', $product->business_id)
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->take(3)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show form to edit product.
     */
    public function edit(Product $product): View
    {
        $categories = Product::where('business_id', $product->business_id)->distinct()->pluck('category')->filter()->values();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update product details and optionally change image.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category' => 'required|string|max:100',
            'price_cents' => 'required|numeric|min:0',
            'cost_price_cents' => 'nullable|numeric|min:0',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'category' => $validated['category'],
            'price_cents' => (int) $validated['price_cents'],
            'cost_price_cents' => (int) ($validated['cost_price_cents'] ?? $product->cost_price_cents),
            'vat_rate' => $validated['vat_rate'] ?? $product->vat_rate,
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $updateData['image_url'] = 'storage/' . $path;
        }

        $product->update($updateData);

        return redirect()->route('products.show', $product)->with('success', "Product '{$product->name}' updated successfully.");
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', "Item removed from catalog.");
    }
}
