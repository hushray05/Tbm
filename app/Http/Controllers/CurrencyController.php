<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Switch the user's active session currency.
     */
    public function switchCurrency(Request $request): RedirectResponse
    {
        $request->validate([
            'currency' => 'required|string|max:3',
        ]);

        $code = strtoupper($request->input('currency'));
        $success = $this->currencyService->setUserCurrency($code);

        if ($success) {
            $currency = Currency::where('code', $code)->first();
            $name = $currency ? "{$currency->name} ({$currency->symbol})" : $code;
            return back()->with('success', "Active currency successfully switched to {$name}. All prices have been dynamically recalculated.");
        }

        return back()->with('error', "Selected currency '{$code}' is currently unavailable.");
    }
}
