<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Party;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartyController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display directory of Customers or Vendors.
     */
    public function index(Request $request): View
    {
        $type = $request->query('type', 'customer'); // customer or vendor
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        $parties = Party::where('business_id', $business ? $business->id : 1)
            ->where('type', $type)
            ->orderBy('name')
            ->get();

        $limitInfo = $this->subscriptionService->checkUsageLimit($type === 'vendor' ? 'vendors' : 'customers', $business);

        return view('parties.index', compact('parties', 'type', 'limitInfo', 'business'));
    }

    /**
     * Store new party (Customer / Vendor).
     */
    public function store(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'customer');
        $business = auth()->user() ? auth()->user()->getActiveBusiness() : Business::first();

        $limitInfo = $this->subscriptionService->checkUsageLimit($type === 'vendor' ? 'vendors' : 'customers', $business);
        if (!$limitInfo['allowed']) {
            return back()->with('error', ucfirst($type) . " limit reached on your {$limitInfo['plan_name']} plan. Please upgrade to add more.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'tin_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'city' => 'required|string|max:100',
            'type' => 'required|in:customer,vendor',
        ]);

        Party::create(array_merge($validated, [
            'business_id' => $business ? $business->id : 1,
            'balance_ugx' => 0,
        ]));

        return back()->with('success', ucfirst($validated['type']) . " '{$validated['name']}' added to directory.");
    }

    /**
     * Delete a party.
     */
    public function destroy(Party $party): RedirectResponse
    {
        $type = $party->type;
        $party->delete();
        return back()->with('success', ucfirst($type) . " removed.");
    }
}
