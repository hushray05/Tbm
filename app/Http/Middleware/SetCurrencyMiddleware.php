<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use App\Services\CurrencyService;
use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetCurrencyMiddleware
{
    protected CurrencyService $currencyService;
    protected SubscriptionService $subscriptionService;

    public function __construct(CurrencyService $currencyService, SubscriptionService $subscriptionService)
    {
        $this->currencyService = $currencyService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if explicit currency was passed (query param or POST body)
        if ($request->has('currency')) {
            $code = strtoupper($request->input('currency'));
            if (!empty($code)) {
                $this->currencyService->setUserCurrency($code);
            }
        }

        // 2. Ensure session has a currency (default to UGX)
        if (!session()->has('user_currency') || empty(session('user_currency'))) {
            session(['user_currency' => 'UGX']);
        }

        // 3. Retrieve current currency and list of active currencies
        $currentCurrency = $this->currencyService->getCurrentCurrencyModel();
        
        try {
            $activeCurrencies = Currency::where('is_active', true)
                ->orderBy('country', 'asc')
                ->get();
        } catch (\Throwable $e) {
            $activeCurrencies = collect([$currentCurrency]);
        }

        // 4. Retrieve current business & subscription status for banners & access
        try {
            $currentSubscription = $this->subscriptionService->getCurrentSubscription();
            $expirationAlert = $this->subscriptionService->getExpirationNotification($currentSubscription);
        } catch (\Throwable $e) {
            $currentSubscription = null;
            $expirationAlert = null;
        }

        // 5. Share globally with all Blade views
        View::share('currentCurrency', $currentCurrency);
        View::share('activeCurrencies', $activeCurrencies);
        View::share('currencyService', $this->currencyService);
        View::share('subscriptionService', $this->subscriptionService);
        View::share('currentSubscription', $currentSubscription);
        View::share('expirationAlert', $expirationAlert);

        return $next($request);
    }
}
