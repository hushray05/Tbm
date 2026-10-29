<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Curated preset avatars for quick one-click selection.
     */
    protected array $presetAvatars = [
        ['name' => 'Sarah Jenkins (Finance)', 'url' => '/images/avatars/sarah-jenkins.svg'],
        ['name' => 'Michael Chen (Operations)', 'url' => '/images/avatars/michael-chen.svg'],
        ['name' => 'Amina Diallo (Sales Lead)', 'url' => '/images/avatars/amina-diallo.svg'],
        ['name' => 'Kwame Mensah (Inventory)', 'url' => '/images/avatars/kwame-mensah.svg'],
        ['name' => 'Ngozi Eze (Logistics)', 'url' => '/images/avatars/ngozi-eze.svg'],
        ['name' => 'David Ochieng (Accountant)', 'url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80'],
        ['name' => 'Grace Nakato (Cashier)', 'url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80'],
        ['name' => 'Brian Mukasa (Manager)', 'url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80'],
    ];

    /**
     * Display the authenticated user's profile page with workspace & activity metrics.
     */
    public function index(): View
    {
        $user = auth()->user() ?? User::first();
        $business = $user->getActiveBusiness() ?? $user->business;
        $businessId = $business ? $business->id : $user->business_id;

        // Current Subscription & Usage
        $subscriptionService = app(\App\Services\SubscriptionService::class);
        $subscription = $subscriptionService->getCurrentSubscription($business);

        // Recent Invoices in this business
        $recentInvoices = \App\Models\Invoice::when($businessId, fn($q) => $q->where('business_id', $businessId))
            ->latest()
            ->take(5)
            ->get();

        // Recent Tasks for this user
        $recentTasks = \App\Models\Task::where('assigned_user', $user->name)
            ->orWhere('assigned_user', $user->email)
            ->latest('id')
            ->take(5)
            ->get();

        if ($recentTasks->isEmpty()) {
            $recentTasks = \App\Models\Task::latest('id')->take(4)->get();
        }

        $presetAvatars = $this->presetAvatars;

        return view('profile.index', compact('user', 'business', 'subscription', 'recentInvoices', 'recentTasks', 'presetAvatars'));
    }

    /**
     * Update the user's profile information (name, phone, bio, job_title, department).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user() ?? User::first();

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'phone'      => 'nullable|string|max:50',
            'bio'        => 'nullable|string|max:1000',
            'job_title'  => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload and update the user's avatar image from a file, preset, or URL.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = auth()->user() ?? User::first();

        // 1. File Upload
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar_url' => '/storage/' . $path]);

            return back()->with('success', 'Profile photo uploaded successfully!');
        }

        // 2. Preset or Custom URL
        if ($request->filled('avatar_url')) {
            $request->validate([
                'avatar_url' => 'required|string|max:500',
            ]);

            $user->update(['avatar_url' => $request->avatar_url]);

            return back()->with('success', 'Profile avatar updated successfully!');
        }

        return back()->with('error', 'Please select an avatar or upload an image.');
    }

    /**
     * Update the user's account password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user() ?? User::first();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password you entered is incorrect.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password changed successfully! Please use the new password next time you sign in.');
    }
}
