<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeature
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        if ($this->subscriptionService->checkFeatureAccess($featureKey)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Upgrade Required',
                'message' => "The feature '{$featureKey}' is not included in your current subscription plan.",
                'upgrade_url' => route('subscription.pricing'),
            ], 403);
        }

        return response()->view('errors.upgrade-required', [
            'featureKey' => $featureKey,
            'subscription' => $this->subscriptionService->getCurrentSubscription(),
        ], 403);
    }
}