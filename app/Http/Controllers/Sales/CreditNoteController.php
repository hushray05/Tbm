<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\Party;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreditNoteController extends Controller
{
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $creditNotes = CreditNote::where('business_id', $business ? $business->id : 1)
            ->with('customer', 'invoice')
            ->latest('id')
            ->paginate(15);

        $totalCredits = CreditNote::where('business_id', $business ? $business->id : 1)->sum('amount_ugx');

        return view('sales.credit_notes.index', compact('creditNotes', 'totalCredits', 'business'));
    }

    public function create(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $customers = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'customer')
            ->get();
        $invoices = Invoice::where('business_id', $business ? $business->id : 1)->latest('id')->take(30)->get();
        $products = Product::where('is_active', true)->get();
        $nextCreditNum = 'CRN-' . date('Y') . '-' . rand(1000, 9999);

        return view('sales.credit_notes.create', compact('customers', 'invoices', 'products', 'nextCreditNum'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'credit_note_number' => 'required|string|unique:credit_notes,credit_note_number',
            'customer_name' => 'required|string|max:255',
            'party_id' => 'nullable|exists:parties,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'product_id' => 'nullable|exists:products,id',
            'restock_quantity' => 'nullable|integer|min:0',
            'amount_ugx' => 'required|numeric|min:1',
            'credit_date' => 'required|date',
            'reason' => 'nullable|string|max:500',
            'restock_inventory' => 'nullable|boolean',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $shouldRestock = $request->boolean('restock_inventory', true);

        $creditNote = CreditNote::create([
            'business_id' => $business ? $business->id : 1,
            'credit_note_number' => $validated['credit_note_number'],
            'customer_name' => $validated['customer_name'],
            'party_id' => $validated['party_id'] ?? null,
            'invoice_id' => $validated['invoice_id'] ?? null,
            'amount_ugx' => (int) $validated['amount_ugx'],
            'credit_date' => $validated['credit_date'],
            'reason' => $validated['reason'] ?? 'Customer Return / Credit Adjustment',
            'status' => 'issued',
            'restock_inventory' => $shouldRestock,
        ]);

        // Automatically restock product if product was specified and restock requested
        if ($shouldRestock && !empty($validated['product_id']) && !empty($validated['restock_quantity'])) {
            $product = Product::find($validated['product_id']);
            if ($product) {
                $product->adjustStock(
                    (int) $validated['restock_quantity'],
                    'return',
                    $creditNote->credit_note_number,
                    "Restocked from Credit Note #{$creditNote->credit_note_number} ({$validated['customer_name']})"
                );
            }
        }

        return redirect()->route('sales.credit-notes.index')->with('success', "Credit Note {$creditNote->credit_note_number} recorded successfully and balance/inventory updated.");
    }
}
