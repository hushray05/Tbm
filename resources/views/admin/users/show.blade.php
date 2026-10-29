@extends('layouts.admin')

@section('title', 'User Details • Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">{{ $user->name }}</h1>
            <p class="text-xs text-secondary mt-0.5">User details.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-primary hover:underline">← Back to Users</a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Name</span>
                <span class="text-sm font-semibold text-text-rich">{{ $user->name }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Email</span>
                <span class="text-sm font-semibold text-text-rich">{{ $user->email }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Role</span>
                <span class="text-sm font-semibold text-text-rich">{{ $user->role ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Status</span>
                <span class="text-sm font-semibold text-text-rich">{{ ucfirst($user->status) }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Job Title</span>
                <span class="text-sm font-semibold text-text-rich">{{ $user->job_title ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Department</span>
                <span class="text-sm font-semibold text-text-rich">{{ $user->department ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Phone</span>
                <span class="text-sm font-semibold text-text-rich">{{ $user->phone ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="block text-xs font-bold text-secondary uppercase">Super Admin</span>
                <span class="text-sm font-semibold text-text-rich">{{ $user->is_super_admin ? '✅ Yes' : '❌ No' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection