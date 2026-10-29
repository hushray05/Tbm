<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;

class CurrencyService
{
    /**
     * Get the active session currency code (defaults to UGX Uganda Shilling).
     */
    public function getUserCurrency(): string
    {
        return session('user_currency', 'UGX');
    }

    /**
     * Set the user's session currency.
     */
    public function setUserCurrency(string $code): bool
    {
        $code = strtoupper(trim($code));
        $currency = Currency::where('code', $code)->where('is_active', true)->first();

        if ($currency) {
            session(['user_currency' => $currency->code]);
            return true;
        }

        return false;
    }

    /**
     * Get active Currency model for the current user.
     */
    public function getCurrentCurrencyModel(): Currency
    {
        $code = $this->getUserCurrency();
        try {
            return Currency::where('code', $code)->first() ?? Currency::where('code', 'UGX')->first() ?? new Currency([
                'code' => 'UGX',
                'name' => 'Ugandan Shilling',
                'symbol' => 'UGX',
                'exchange_rate' => 3710.0,
                'is_active' => true,
                'country' => 'Uganda',
                'flag' => '🇺🇬',
            ]);
        } catch (\Throwable $e) {
            return new Currency([
                'code' => 'UGX',
                'name' => 'Ugandan Shilling',
                'symbol' => 'UGX',
                'exchange_rate' => 3710.0,
                'is_active' => true,
                'country' => 'Uganda',
                'flag' => '🇺🇬',
            ]);
        }
    }

    /**
     * Convert integer base USD cents to the target currency amount in float.
     */
    public function convertFromCents(int|float $usdCents, ?string $targetCurrencyCode = null): float
    {
        $targetCode = $targetCurrencyCode ?? $this->getUserCurrency();
        $baseDollars = $usdCents / 100.0;

        if ($targetCode === 'USD') {
            return $baseDollars;
        }

        $currency = Currency::where('code', $targetCode)->first();
        $rate = $currency ? (float) $currency->exchange_rate : 1.0;

        return $baseDollars * $rate;
    }

    /**
     * Format a price stored in integer cents (USD base) to the active session currency or specified currency.
     * Uses Laravel's native Number::currency helper with fallbacks.
     */
    public function format(int|float $usdCents, ?string $currencyCode = null): string
    {
        $code = $currencyCode ?? $this->getUserCurrency();
        $amount = $this->convertFromCents($usdCents, $code);

        $currencyModel = Currency::where('code', $code)->first();
        $symbol = $currencyModel ? $currencyModel->symbol : '$';

        try {
            // Laravel's native Number::currency helper
            return Number::currency($amount, in: $code);
        } catch (\Throwable $e) {
            // Graceful fallback with proper symbol formatting
            $decimals = in_array($code, ['UGX', 'RWF', 'XOF', 'XAF', 'BIF', 'GNF', 'KMF', 'JPY']) ? 0 : 2;
            return $symbol . ' ' . number_format($amount, $decimals);
        }
    }

    /**
     * Fetch latest live exchange rates from a free public API and update database.
     */
    public function syncLiveRates(): array
    {
        try {
            // Free exchange rates endpoint against USD base
            $response = Http::timeout(6)->get('https://open.er-api.com/v6/latest/USD');

            if ($response->successful()) {
                $data = $response->json();
                $rates = $data['rates'] ?? [];

                $updatedCount = 0;
                foreach ($rates as $code => $rate) {
                    $updated = Currency::where('code', $code)->update([
                        'exchange_rate' => $rate,
                        'updated_at' => now(),
                    ]);
                    if ($updated) {
                        $updatedCount++;
                    }
                }

                Cache::put('exchange_rates_last_synced', now(), now()->addHours(6));

                return [
                    'success' => true,
                    'message' => "Successfully synchronized {$updatedCount} currency exchange rates from Open Exchange API.",
                    'rates_count' => $updatedCount,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Exchange rate sync failed: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'message' => 'Could not contact live exchange rate server. Fallback database rates remain active.',
            'rates_count' => 0,
        ];
    }
}
