<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Display Admin Profile & Account Settings.
     */
    public function index(): View
    {
        $user = Auth::user() ?? User::first();

        // Sample preset avatars for quick picking
        $presetAvatars = [
            ['name' => 'Sarah Jenkins (Admin)', 'url' => '/images/avatars/sarah-jenkins.svg'],
            ['name' => 'Michael Chen (CFO)', 'url' => '/images/avatars/michael-chen.svg'],
            ['name' => 'Amina Diallo (VP Eng)', 'url' => '/images/avatars/amina-diallo.svg'],
            ['name' => 'Kwame Mensah (Lead)', 'url' => '/images/avatars/kwame-mensah.svg'],
            ['name' => 'Ngozi Eze (COO)', 'url' => '/images/avatars/ngozi-eze.svg'],
            ['name' => 'David Ochieng (Director)', 'url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80'],
            ['name' => 'Grace Nakato (Governance)', 'url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80'],
            ['name' => 'Brian Mukasa (Tech Lead)', 'url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80'],
        ];

        return view('admin.profile.index', compact('user', 'presetAvatars'));
    }

    /**
     * Update Profile Details & Avatar.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user() ?? User::first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'avatar_url' => 'nullable|string|max:500',
            'avatar_file' => 'nullable|image|max:2048', // 2MB max
        ]);

        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/avatars'), $filename);
            $validated['avatar_url'] = '/images/avatars/' . $filename;
        }

        unset($validated['avatar_file']);

        $user->update($validated);

        return back()->with('success', 'Profile and avatar preferences updated successfully!');
    }

    /**
     * Update Account Security Password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user() ?? User::first();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Security credentials updated successfully.');
    }
}
