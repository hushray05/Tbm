<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Business;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreditorController extends Controller
{
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        // Get all vendors that have pending/partially_paid bills or balances
        $vendors = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', 'vendor')
            ->get();

        $openBills = Bill::where('business_id', $business ? $business->id : 1)
            ->whereIn('status', ['pending', 'partially_paid', 'overdue'])
            ->with('vendor')
            ->orderBy('due_date', 'asc')
            ->get();

        $totalCreditorDebt = $openBills->sum('balance_ugx');
        $overdueCount = $openBills->where('due_date', '<', now())->count();

        return view('purchases.creditors.index', compact('vendors', 'openBills', 'totalCreditorDebt', 'overdueCount', 'business'));
    }
}
