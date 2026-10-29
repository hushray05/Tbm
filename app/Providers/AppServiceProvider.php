<?php

namespace App\Providers;

use App\Models\Currency;
use App\Services\CurrencyService;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Globally share vital services and active currency to all views
        View::composer('*', function ($view) {
            try {
                $currencyService = app(CurrencyService::class);
                $subscriptionService = app(SubscriptionService::class);
                $currentCurrency = $currencyService->getCurrentCurrencyModel();
                $activeCurrencies = Currency::where('is_active', true)->get();

                $view->with([
                    'currencyService' => $currencyService,
                    'subscriptionService' => $subscriptionService,
                    'currentCurrency' => $currentCurrency,
                    'activeCurrencies' => $activeCurrencies,
                ]);
            } catch (\Throwable $e) {
                // Ignore during early migrations or CLI
            }
        });
    }
}
