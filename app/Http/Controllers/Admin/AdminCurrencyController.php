<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * List all supported currencies and exchange rates.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $query = Currency::query();

        if ($search) {
            $query->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
        }

        $currencies = $query->orderBy('country')->paginate(20);
        $totalCurrencies = Currency::count();
        $activeCurrencies = Currency::where('is_active', true)->count();
        $lastSynced = cache('exchange_rates_last_synced', 'Default Seeder');

        return view('admin.currencies.index', compact('currencies', 'totalCurrencies', 'activeCurrencies', 'lastSynced', 'search'));
    }

    /**
     * Show edit form for currency.
     */
    public function edit(Currency $currency): View
    {
        return view('admin.currencies.edit', compact('currency'));
    }

    /**
     * Update currency rate or parameters.
     */
    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001',
            'country' => 'required|string|max:255',
            'flag' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $currency->update($validated);

        return redirect()->route('admin.currencies.index')->with('success', "Currency {$currency->code} ({$currency->name}) conversion parameters updated successfully.");
    }

    /**
     * Toggle currency active status.
     */
    public function toggle(Currency $currency): RedirectResponse
    {
        if ($currency->code === 'USD') {
            return back()->with('warning', 'Base benchmark currency USD cannot be disabled.');
        }

        $currency->update(['is_active' => !$currency->is_active]);
        $status = $currency->is_active ? 'enabled' : 'disabled';

        return back()->with('success', "Currency {$currency->code} is now {$status}.");
    }

    /**
     * Trigger live API exchange rate synchronization.
     */
    public function syncRates(): RedirectResponse
    {
        $result = $this->currencyService->syncLiveRates();

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('warning', $result['message']);
    }
}
