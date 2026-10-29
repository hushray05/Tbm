<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BusinessController extends Controller
{
    /**
     * Display a listing of the businesses the user can access.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Get businesses the user can access with relationships
        if ($user->isSuperAdmin()) {
            $businesses = Business::with(['locations', 'subscription.plan'])
                ->withCount(['products', 'invoices', 'users'])
                ->latest('id')
                ->get();
        } else {
            $businesses = Business::where('id', $user->business_id)
                ->orWhere('owner_id', $user->id)
                ->with(['locations', 'subscription.plan'])
                ->withCount(['products', 'invoices', 'users'])
                ->latest('id')
                ->get();
        }

        $activeBusiness = $user->getActiveBusiness();

        return view('businesses.index', compact('businesses', 'activeBusiness'));
    }

    /**
     * Show the form for creating a new business.
     */
    public function create(): View
    {
        return view('businesses.create');
    }

    /**
     * Store a newly created business.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'currency_code' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
        ]);

        $business = Business::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . rand(100, 999),
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'city' => $validated['city'] ?? 'Kampala',
            'country' => $validated['country'] ?? 'Uganda',
            'currency_code' => $validated['currency_code'] ?? 'UGX',
            'currency_symbol' => $validated['currency_symbol'] ?? 'UGX',
            'status' => 'active',
            'owner_id' => auth()->id(),
        ]);

        // Create default location
        BusinessLocation::create([
            'business_id' => $business->id,
            'name' => 'Headquarters',
            'city' => $business->city,
            'phone' => $business->phone,
            'is_primary' => true,
        ]);

        // Assign user to this business
        auth()->user()->update(['business_id' => $business->id]);

        return redirect()->route('businesses.index')
            ->with('success', 'Business created successfully!');
    }

    /**
     * Store a new location for an existing business.
     */
    public function storeLocation(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'is_primary' => 'nullable|boolean',
        ]);

        // If this location is primary, unset any existing primary
        if ($validated['is_primary'] ?? false) {
            $business->locations()->update(['is_primary' => false]);
        }

        $business->locations()->create($validated);

        return back()->with('success', 'Location added successfully.');
    }

    /**
     * Switch the active business for the authenticated user.
     */
    public function switchBusiness(Request $request): RedirectResponse
    {
        $businessId = $request->input('business_id');
        $user = auth()->user();

        // Verify access
        if (!$user->isSuperAdmin() && $user->business_id != $businessId) {
            return back()->with('error', 'You do not have access to that business.');
        }

        // Store in session or update user's business_id
        $user->update(['business_id' => $businessId]);

        return back()->with('success', 'Switched to ' . Business::find($businessId)->name);
    }
}