<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome to Super Admin Console, ' . $user->name . '!');
            }

            return redirect()->intended(route('dashboard'))->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(Request $request): View
    {
        $selectedPlanSlug = $request->query('plan', 'starter');
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $defaultTrialDays = (int) SubscriptionSetting::get('default_trial_days', 30);

        return view('auth.register', compact('plans', 'selectedPlanSlug', 'defaultTrialDays'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'plan_slug' => 'required|exists:subscription_plans,slug',
        ]);

        $plan = SubscriptionPlan::where('slug', $validated['plan_slug'])->firstOrFail();

        // 1. Create Business
        $business = Business::create([
            'name' => $validated['business_name'],
            'slug' => Str::slug($validated['business_name']) . '-' . rand(100, 999),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'city' => $validated['city'] ?? 'Kampala',
            'country' => 'Uganda',
            'currency_code' => 'UGX',
            'currency_symbol' => 'UGX',
            'status' => 'active',
        ]);

        // 2. Create Business Location
        BusinessLocation::create([
            'business_id' => $business->id,
            'name' => 'Headquarters / Main Depot',
            'city' => $validated['city'] ?? 'Kampala',
            'phone' => $validated['phone'] ?? null,
            'is_primary' => true,
        ]);

        // 3. Create Business Owner User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'business_id' => $business->id,
            'is_super_admin' => false,
            'role' => 'Business Owner',
            'job_title' => 'Managing Director',
            'department' => 'Executive Management',
            'phone' => $validated['phone'] ?? null,
            'status' => 'active',
        ]);

        $business->update(['owner_id' => $user->id]);

        // 4. Provision Free Trial Subscription
        $subscription = $this->subscriptionService->createTrialSubscription($business, $user, $plan);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "Welcome to TBM Enterprise! Your {$subscription->plan->trial_duration}-Day Free Trial on the {$plan->name} Plan has started.");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been securely signed out.');
    }
}
