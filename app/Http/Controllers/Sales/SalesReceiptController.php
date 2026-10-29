<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\SalesReceipt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesReceiptController extends Controller
{
    public function index(): View
    {
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();
        $receipts = SalesReceipt::where('business_id', $business ? $business->id : 1)
            ->with('invoice')
            ->latest('id')
            ->paginate(15);

        $totalReceiptVolume = SalesReceipt::where('business_id', $business ? $business->id : 1)->sum('total');

        return view('sales.receipts.index', compact('receipts', 'totalReceiptVolume', 'business'));
    }

    public function show(SalesReceipt $receipt): View
    {
        $receipt->load('invoice');
        $business = $receipt->business ?? (auth()->user() ? auth()->user()->getActiveBusiness() : Business::first());
        return view('sales.receipts.show', compact('receipt', 'business'));
    }
}
