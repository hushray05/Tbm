<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Business;
use App\Models\Party;
use App\Models\PaymentMade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentMadeController extends Controller
{
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $payments = PaymentMade::where('business_id', $business ? $business->id : 1)
            ->with('bill', 'vendor', 'creator')
            ->latest('id')
            ->paginate(15);

        $totalPaymentsMade = PaymentMade::where('business_id', $business ? $business->id : 1)->sum('amount_ugx');

        return view('purchases.payments.index', compact('payments', 'totalPaymentsMade', 'business'));
    }

    public function create(Request $request): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $billId = $request->query('bill_id');
        $selectedBill = $billId ? Bill::find($billId) : null;

        $bills = Bill::where('business_id', $business ? $business->id : 1)
            ->whereIn('status', ['pending', 'partially_paid', 'overdue'])
            ->orderBy('id', 'desc')
            ->get();

        $vendors = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'vendor')
            ->get();

        $nextPaymentNum = 'PMT-' . date('Ymd') . '-' . rand(100, 999);

        return view('purchases.payments.create', compact('bills', 'selectedBill', 'vendors', 'nextPaymentNum'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_number' => 'required|string|unique:payments_made,payment_number',
            'bill_id' => 'nullable|exists:bills,id',
            'party_id' => 'nullable|exists:parties,id',
            'vendor_name' => 'required|string|max:255',
            'amount_ugx' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $amount = (int) $validated['amount_ugx'];

        PaymentMade::create([
            'business_id' => $business ? $business->id : 1,
            'payment_number' => $validated['payment_number'],
            'bill_id' => $validated['bill_id'] ?? null,
            'party_id' => $validated['party_id'] ?? null,
            'vendor_name' => $validated['vendor_name'],
            'amount_ugx' => $amount,
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // If linked to Bill, update bill paid amount & balance
        if (!empty($validated['bill_id'])) {
            $bill = Bill::find($validated['bill_id']);
            if ($bill) {
                $newPaid = $bill->paid_amount_ugx + $amount;
                $newBalance = max(0, $bill->total_amount_ugx - $newPaid);
                $newStatus = ($newBalance <= 0) ? 'paid' : 'partially_paid';

                $bill->update([
                    'paid_amount_ugx' => $newPaid,
                    'balance_ugx' => $newBalance,
                    'status' => $newStatus,
                ]);
            }
        }

        return redirect()->route('purchases.payments.index')->with('success', "Payment {$validated['payment_number']} to supplier {$validated['vendor_name']} recorded successfully.");
    }
}
