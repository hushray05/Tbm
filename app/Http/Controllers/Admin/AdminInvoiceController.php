<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminInvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with('items')->latest()->paginate(15);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function updateStatus(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:draft,pending,paid,overdue,cancelled',
        ]);

        $invoice->update(['status' => $request->input('status')]);

        return back()->with('success', "Invoice #{$invoice->invoice_number} status updated to " . ucfirst($invoice->status));
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $num = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', "Invoice #{$num} was permanently removed.");
    }
}
