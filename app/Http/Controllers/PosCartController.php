<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Task;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosCartController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * POS Terminal Interface (matches Stitch screen de64cac34708466b94baf6c87139e8c0).
     */
    public function pos(): View
    {
        $products = Product::where('is_active', true)->get();
        $tasks = Task::whereIn('status', ['backlog', 'in_progress'])->get();
        $cart = session('cart', []);

        $subtotalCents = 0;
        foreach ($cart as $item) {
            $subtotalCents += ($item['price_cents'] * $item['quantity']);
        }

        $taxCents = (int) round($subtotalCents * 0.08);
        $totalCents = $subtotalCents + $taxCents;

        return view('pos.index', compact('products', 'tasks', 'cart', 'subtotalCents', 'taxCents', 'totalCents'));
    }

    /**
     * Shopping Cart / Task Queue view.
     */
    public function cart(): View
    {
        $cart = session('cart', []);

        $subtotalCents = 0;
        foreach ($cart as $item) {
            $subtotalCents += ($item['price_cents'] * $item['quantity']);
        }

        $taxCents = (int) round($subtotalCents * 0.08);
        $totalCents = $subtotalCents + $taxCents;

        return view('cart.index', compact('cart', 'subtotalCents', 'taxCents', 'totalCents'));
    }

    /**
     * Add item (Product or Task) to queue/cart.
     */
    public function addToCart(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:product,task',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $type = $request->input('type');
        $id = $request->input('id');
        $quantity = (int) ($request->input('quantity', 1));

        $cart = session('cart', []);
        $cartKey = "{$type}_{$id}";

        if ($type === 'product') {
            $product = Product::findOrFail($id);
            $name = $product->name;
            $sku = $product->sku;
            $priceCents = $product->price_cents;
        } else {
            $task = Task::findOrFail($id);
            $name = "Task: {$task->title}";
            $sku = "TSK-{$task->id}";
            $priceCents = $task->price_cents;
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $id,
                'type' => $type,
                'name' => $name,
                'sku' => $sku,
                'price_cents' => $priceCents,
                'quantity' => $quantity,
                'image_url' => $type === 'product' ? $product->image_url : null,
            ];
        }

        session(['cart' => $cart]);

        $formatted = $this->currencyService->format($priceCents);
        return back()->with('success', "'{$name}' ({$formatted}) added to the queue!");
    }

    /**
     * Remove item from queue/cart.
     */
    public function removeFromCart(string $key): RedirectResponse
    {
        $cart = session('cart', []);
        if (isset($cart[$key])) {
            $name = $cart[$key]['name'];
            unset($cart[$key]);
            session(['cart' => $cart]);
            return back()->with('info', "'{$name}' removed from checkout queue.");
        }

        return back();
    }

    /**
     * Clear the whole cart.
     */
    public function clearCart(): RedirectResponse
    {
        session()->forget('cart');
        return back()->with('info', 'Checkout queue has been cleared.');
    }

    /**
     * Process checkout.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Checkout queue is empty.');
        }

        session()->forget('cart');

        $activeCurrency = $this->currencyService->getCurrentCurrencyModel();
        return redirect()->route('dashboard')->with('success', "Checkout completed successfully in {$activeCurrency->code} ({$activeCurrency->symbol})! Receipt generated.");
    }
}
