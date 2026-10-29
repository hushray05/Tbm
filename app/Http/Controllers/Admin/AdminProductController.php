<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    /**
     * Admin Products List.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(15);

        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * Admin Create Product Form.
     */
    public function create(): View
    {
        return view('admin.products.create');
    }

    /**
     * Admin Store Product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['price_cents'] = (int) round($validated['price'] * 100);
        $validated['is_active'] = $request->boolean('is_active', true);
        unset($validated['price']);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'product-' . Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $validated['image_url'] = 'images/products/' . $filename;
        }
        unset($validated['image_file']);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product/Service successfully added to catalog.');
    }

    /**
     * Admin Edit Product Form.
     */
    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Admin Update Product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['price_cents'] = (int) round($validated['price'] * 100);
        $validated['is_active'] = $request->boolean('is_active', true);
        unset($validated['price']);

        if ($request->hasFile('image_file')) {
            if ($product->image_url && file_exists(public_path($product->image_url))) {
                @unlink(public_path($product->image_url));
            }
            $file = $request->file('image_file');
            $filename = 'product-' . Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $validated['image_url'] = 'images/products/' . $filename;
        }
        unset($validated['image_file']);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated successfully.");
    }

    /**
     * Admin Destroy Product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Product '{$name}' deleted permanently.");
    }
}
