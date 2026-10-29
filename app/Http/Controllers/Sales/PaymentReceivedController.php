<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Invoice;
use App\Models\Party;
use App\Models\PaymentReceived;
use App\Models\SalesReceipt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentReceivedController extends Controller
{
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $payments = PaymentReceived::where('business_id', $business ? $business->id : 1)
            ->with('invoice', 'customer', 'creator')
            ->latest('id')
            ->paginate(15);

        $totalPaymentsReceived = PaymentReceived::where('business_id', $business ? $business->id : 1)->sum('amount_ugx');

        return view('sales.payments.index', compact('payments', 'totalPaymentsReceived', 'business'));
    }

    public function create(Request $request): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $invoiceId = $request->query('invoice_id');
        $selectedInvoice = $invoiceId ? Invoice::find($invoiceId) : null;

        $invoices = Invoice::where('business_id', $business ? $business->id : 1)
            ->whereIn('status', ['pending', 'draft', 'overdue'])
            ->orderBy('id', 'desc')
            ->get();

        $customers = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'customer')
            ->get();

        $nextPaymentNum = 'PAY-' . date('Ymd') . '-' . rand(100, 999);

        return view('sales.payments.create', compact('invoices', 'selectedInvoice', 'customers', 'nextPaymentNum'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_number' => 'required|string|unique:payments_received,payment_number',
            'invoice_id' => 'nullable|exists:invoices,id',
            'party_id' => 'nullable|exists:parties,id',
            'customer_name' => 'required|string|max:255',
            'amount_ugx' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $amount = (int) $validated['amount_ugx'];

        $payment = PaymentReceived::create([
            'business_id' => $business ? $business->id : 1,
            'payment_number' => $validated['payment_number'],
            'invoice_id' => $validated['invoice_id'] ?? null,
            'party_id' => $validated['party_id'] ?? null,
            'customer_name' => $validated['customer_name'],
            'amount_ugx' => $amount,
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // If payment is linked to an invoice, update invoice status and generate sales receipt
        if (!empty($validated['invoice_id'])) {
            $invoice = Invoice::find($validated['invoice_id']);
            if ($invoice) {
                $totalPaidSoFar = PaymentReceived::where('invoice_id', $invoice->id)->sum('amount_ugx');
                if ($totalPaidSoFar >= $invoice->total_cents) {
                    $invoice->update(['status' => 'paid']);
                }
            }
        }

        // Automatically issue Sales Receipt
        $receiptNumber = 'REC-' . date('Ymd') . '-' . rand(100, 999);
        $receipt = SalesReceipt::create([
            'business_id' => $business ? $business->id : 1,
            'receipt_number' => $receiptNumber,
            'customer_name' => $validated['customer_name'],
            'invoice_id' => $validated['invoice_id'] ?? null,
            'date' => $validated['payment_date'],
            'payment_mode' => $validated['payment_method'],
            'subtotal_ugx' => $amount,
            'vat_amount_ugx' => 0,
            'total' => $amount,
            'notes' => "Issued for Payment {$validated['payment_number']}. Ref: " . ($validated['reference_no'] ?? 'N/A'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('sales.receipts.show', $receipt)->with('success', "Payment {$validated['payment_number']} recorded! Official Receipt #{$receipt->receipt_number} generated.");
    }
}
