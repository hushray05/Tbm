@extends('layouts.admin')

@section('title', 'Add User • Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Add New User</h1>
            <p class="text-xs text-secondary mt-0.5">Create a new platform user.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-primary hover:underline">← Back to Users</a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Role <span class="text-danger">*</span></label>
                    <select name="role" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                        <option value="Business Owner" {{ old('role') === 'Business Owner' ? 'selected' : '' }}>Business Owner</option>
                        <option value="Manager" {{ old('role') === 'Manager' ? 'selected' : '' }}>Manager</option>
                        <option value="Accountant" {{ old('role') === 'Accountant' ? 'selected' : '' }}>Accountant</option>
                        <option value="Cashier" {{ old('role') === 'Cashier' ? 'selected' : '' }}>Cashier</option>
                        <option value="Staff" {{ old('role') === 'Staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Status</label>
                    <select name="status" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Job Title</label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Department</label>
                    <input type="text" name="department" value="{{ old('department') }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
            </div>

            @if(auth()->user()->isSuperAdmin())
            <div class="flex items-center gap-2 pt-2 border-t border-border-quiet">
                <input type="checkbox" name="is_super_admin" value="1" id="is_super_admin" class="rounded border-border-quiet text-primary focus:ring-1 focus:ring-primary" {{ old('is_super_admin') ? 'checked' : '' }}>
                <label for="is_super_admin" class="text-xs font-semibold text-text-rich">Grant Super Admin</label>
            </div>
            @endif

            <div class="pt-3 border-t border-border-quiet flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary hover:bg-bg-subtle">Cancel</a>
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">person_add</span>
                    <span>Create User</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection