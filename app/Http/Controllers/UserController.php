<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('business')->paginate(15);
        $business = auth()->user()->getActiveBusiness() ?? Business::first();
        $limitInfo = [
            'allowed' => true,
            'current' => $users->total(),
            'max' => null,
            'plan_name' => 'Unlimited',
            'limit_type' => 'users',
        ];
        return view('admin.users.index', compact('users', 'business', 'limitInfo'));
    }

    public function create()
    {
        $businesses = Business::all();
        $business = auth()->user()->getActiveBusiness() ?? Business::first();
        return view('admin.users.create', compact('businesses', 'business'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'business_id' => 'nullable|exists:businesses,id',
            'role' => 'nullable|string|max:255',
            'is_super_admin' => 'nullable|boolean',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_super_admin'] = $validated['is_super_admin'] ?? false;
        $validated['status'] = $validated['status'] ?? 'active';

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $business = auth()->user()->getActiveBusiness() ?? Business::first();
        return view('admin.users.show', compact('user', 'business'));
    }

    public function edit(User $user)
    {
        $businesses = Business::all();
        $business = auth()->user()->getActiveBusiness() ?? Business::first();
        return view('admin.users.edit', compact('user', 'businesses', 'business'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'business_id' => 'nullable|exists:businesses,id',
            'role' => 'nullable|string|max:255',
            'is_super_admin' => 'nullable|boolean',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_super_admin'] = $validated['is_super_admin'] ?? false;
        $validated['status'] = $validated['status'] ?? 'active';

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}