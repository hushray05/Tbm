<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    protected SubscriptionService $subscriptionService;

    // Routes that are always exempt from trial/subscription expiration
    protected array $exemptRoutes = [
        'subscription.portal',
        'subscription.checkout',
        'subscription.pay',
        'subscription.pricing',
        'profile',
        'profile.update',
        'profile.avatar',
        'profile.password',
        'logout',
        'contact',
        'contact.submit',
        'feedback.create',
        'feedback.store',
        'currency.switch',
        'businesses.switch',
    ];

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle an incoming request.
     * Restricts access to workspace if the 30-day trial or active subscription has expired.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. If not authenticated, let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // 2. Super Administrators always have unrestricted access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // 3. Check if current route is exempt
        $currentRoute = $request->route() ? $request->route()->getName() : null;
        if ($currentRoute && in_array($currentRoute, $this->exemptRoutes)) {
            return $next($request);
        }

        // 4. Verify subscription / trial validity
        $subscription = $this->subscriptionService->getCurrentSubscription();

        if ($subscription && $subscription->isValid()) {
            return $next($request);
        }

        // 5. If expired or no valid subscription:
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Subscription Required',
                'message' => 'Your 30-day free trial has expired. Please subscribe to continue accessing your workspace.',
                'upgrade_url' => route('subscription.portal'),
                'checkout_url' => 'https://store.pesapal.com/taskbusinessmanagerpos',
            ], 403);
        }

        // Redirect to subscription portal with warning message
        return redirect()->route('subscription.portal')->with('error', 'Your 30-day free trial has expired. Please subscribe to regain full access to your business records, invoices, and accounting suite.');
    }
}
