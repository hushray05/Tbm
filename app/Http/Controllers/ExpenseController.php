<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * List all expenses for the active business.
     */
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $expenses = Expense::where('business_id', $business ? $business->id : 1)
            ->orderBy('expense_date', 'desc')
            ->get();

        $totalExpensesUgx = $expenses->sum('amount_ugx');
        $thisMonthExpensesUgx = $expenses->filter(function ($e) {
            return $e->expense_date && $e->expense_date->isCurrentMonth();
        })->sum('amount_ugx');

        // Category breakdown for ApexCharts
        $categoryBreakdown = $expenses->groupBy('category')->map(function ($items) {
            return $items->sum('amount_ugx');
        });

        return view('expenses.index', compact('expenses', 'totalExpensesUgx', 'thisMonthExpensesUgx', 'categoryBreakdown', 'business'));
    }

    /**
     * Show form to record a new expense.
     */
    public function create(): View
    {
        return view('expenses.create');
    }

    /**
     * Store new expense.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'amount_ugx' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:100',
            'reference_no' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        Expense::create([
            'business_id' => $business ? $business->id : 1,
            'category' => $validated['category'],
            'amount_ugx' => (int) $validated['amount_ugx'],
            'expense_date' => $validated['expense_date'],
            'vendor_name' => $validated['vendor_name'] ?? null,
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'] ?? null,
            'description' => $validated['description'] ?? null,
            'created_by' => auth()->id() ?? 1,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense voucher recorded successfully.');
    }

    /**
     * Delete an expense record.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense record removed.');
    }
}
