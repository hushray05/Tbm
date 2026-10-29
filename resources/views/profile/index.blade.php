@extends('layouts.app')

@section('title', 'My Profile & Team Hub • TBM Enterprise')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">My Profile & Workspace Hub</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $user->is_super_admin ? 'bg-amber-100 text-amber-900 border border-amber-300' : ($user->role === 'Business Owner' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200') }}">
                    {{ $user->role ?? 'Member' }}
                </span>
            </div>
            <p class="text-xs text-secondary mt-1">Manage your personal identity, avatar pictures, team connections, and customer relationships.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $user->status === 'active' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-600 border-gray-200' }}">
                ● {{ $user->status ?? 'active' }}
            </span>
            <form id="profileLogoutForm" action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="button" id="profileLogoutBtn" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-200 shadow-sm text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- User Identity Hero Banner -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 md:p-8 tbm-shadow relative overflow-hidden flex flex-col md:flex-row items-center md:items-start gap-6">
        <div class="relative group">
            <div class="w-28 h-28 md:w-32 md:h-32 rounded-2xl overflow-hidden border-2 border-primary/30 shadow-md bg-slate-900 flex items-center justify-center shrink-0">
                <img id="mainAvatarDisplay" src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
            </div>
            <button type="button" onclick="document.getElementById('avatarFileInput').click()" class="absolute -bottom-2 -right-2 bg-primary hover:bg-primary-container text-white p-2 rounded-xl shadow cursor-pointer transition-transform hover:scale-105" title="Upload new photo">
                <span class="material-symbols-outlined text-sm">photo_camera</span>
            </button>
        </div>

        <div class="flex-1 text-center md:text-left space-y-2">
            <div class="flex flex-col md:flex-row md:items-center gap-2 justify-between">
                <div>
                    <h2 class="font-headline text-2xl font-bold text-text-rich">{{ $user->name }}</h2>
                    <p class="text-sm font-semibold text-primary">{{ $user->job_title ?? 'Business Professional' }}</p>
                </div>
                <span class="text-xs font-numeric text-secondary bg-bg-subtle px-3 py-1 rounded-lg border border-border-quiet self-center md:self-auto">
                    ID: #USR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <p class="text-xs text-secondary leading-relaxed max-w-2xl">
                @if($user->bio)
                    {{ $user->bio }}
                @else
                    <span class="italic text-secondary/70">No bio added yet. Fill in your professional summary below.</span>
                @endif
            </p>

            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-2 text-xs text-secondary">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-sm">mail</span>
                    <strong class="text-text-rich">{{ $user->email }}</strong>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-sm">phone</span>
                    <span class="font-numeric">{{ $user->phone ?? 'No phone added' }}</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-sm">apartment</span>
                    <span>{{ $user->department ?? 'General Operations' }}</span>
                </span>
                @if($business)
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-sm">domain</span>
                        <span class="font-semibold text-text-rich">{{ $business->name }}</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- MAIN GRID: Forms (Left) & Connections (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- LEFT 7 COLS: Edit Personal Info, Avatar Gallery, Password -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- 1. AVATAR PICKER GALLERY -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-5">
                <div class="border-b border-border-quiet pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">face</span>
                            Select Profile Avatar Picture
                        </h3>
                        <p class="text-xs text-secondary mt-0.5">Pick from curated gallery avatars or upload your custom photo.</p>
                    </div>
                </div>

                <!-- Preset Avatars Grid -->
                <div class="p-4 bg-bg-subtle rounded-xl border border-border-quiet space-y-3">
                    <span class="text-[11px] font-bold text-secondary uppercase block">Choose Preset Avatar (Click to Apply):</span>
                    <div class="grid grid-cols-4 sm:grid-cols-8 gap-2.5">
                        @foreach($presetAvatars as $preset)
                            <form action="{{ route('profile.avatar') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="avatar_url" value="{{ $preset['url'] }}">
                                <button type="submit" class="w-full flex flex-col items-center gap-1 p-1 rounded-xl hover:bg-surface border border-transparent hover:border-primary/50 transition-all group" title="{{ $preset['name'] }}">
                                    <div class="w-11 h-11 rounded-full overflow-hidden bg-slate-900 border-2 {{ $user->avatar_url === $preset['url'] ? 'border-primary shadow-sm ring-2 ring-primary/20' : 'border-border-quiet' }} group-hover:scale-105 transition-transform">
                                        <img src="{{ $preset['url'] }}" alt="{{ $preset['name'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[9px] text-secondary group-hover:text-primary transition-colors truncate max-w-[50px]">{{ explode(' ', $preset['name'])[0] }}</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

                <!-- Custom Photo Upload / URL -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarUploadForm">
                        @csrf
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Upload Image File:</label>
                        <input type="file" name="avatar" id="avatarFileInput" accept="image/*" class="hidden" onchange="document.getElementById('avatarUploadForm').submit()">
                        <button type="button" onclick="document.getElementById('avatarFileInput').click()" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle hover:bg-surface text-xs font-semibold text-text-rich flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined text-base text-primary">upload_file</span>
                            <span>Choose Photo from Device</span>
                        </button>
                    </form>

                    <form action="{{ route('profile.avatar') }}" method="POST">
                        @csrf
                        <label class="block text-[11px] font-bold text-secondary uppercase mb-1">Or Paste Image URL:</label>
                        <div class="flex gap-1.5">
                            <input type="url" name="avatar_url" placeholder="https://..." class="flex-1 h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                            <button type="submit" class="bg-primary hover:bg-primary-container text-white px-3 h-10 rounded-lg text-xs font-bold shrink-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. PERSONAL INFO FORM -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-5">
                <div class="border-b border-border-quiet pb-3">
                    <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">badge</span>
                        Personal Information
                    </h3>
                    <p class="text-xs text-secondary mt-0.5">Update your display name, official title, and bio.</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                        </div>
                        <div>
                            <label for="phone" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+256 700 000000"
                                class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary font-numeric">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="job_title" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">Job Title</label>
                            <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $user->job_title) }}" placeholder="e.g. Finance & Accounting Lead"
                                class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                        </div>
                        <div>
                            <label for="department" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">Department</label>
                            <input type="text" id="department" name="department" value="{{ old('department', $user->department) }}" placeholder="e.g. Treasury Operations"
                                class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label for="bio" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">Professional Bio</label>
                        <textarea id="bio" name="bio" rows="3" class="w-full px-3.5 py-3 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary resize-none">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-primary hover:bg-primary-container text-white px-6 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
                            <span class="material-symbols-outlined text-sm">save</span>
                            Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. CHANGE PASSWORD -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-5">
                <div class="border-b border-border-quiet pb-3">
                    <h3 class="font-headline font-bold text-base text-text-rich flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">lock</span>
                        Change Security Password
                    </h3>
                    <p class="text-xs text-secondary mt-0.5">Ensure your account is using a strong password.</p>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">Current Password *</label>
                        <input type="password" id="current_password" name="current_password" required placeholder="Enter current password"
                            class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">New Password *</label>
                            <input type="password" id="password" name="password" required placeholder="Minimum 8 characters"
                                class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-[11px] font-bold text-secondary uppercase mb-1.5">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat new password"
                                class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle text-sm text-text-rich outline-none focus:border-primary">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-danger hover:bg-red-700 text-white px-6 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-all">
                            <span class="material-symbols-outlined text-sm">lock_reset</span>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT 5 COLS: Real Workspace, Subscription, Invoices & Tasks -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- 1. WORKSPACE & SUBSCRIPTION STATUS -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
                <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">domain</span>
                        <h3 class="font-headline font-bold text-base text-text-rich">Active Workspace</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase">
                        {{ $user->role ?? 'Business Owner' }}
                    </span>
                </div>

                <div class="p-4 rounded-xl bg-bg-subtle border border-border-quiet space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-secondary">Organization:</span>
                        <strong class="text-xs text-text-rich font-headline">{{ $business->name ?? 'Default Workspace' }}</strong>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-secondary">Location:</span>
                        <span class="text-xs font-medium text-text-rich">{{ $business->city ?? 'Kampala' }}, {{ $business->country ?? 'Uganda' }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-secondary">Assigned Plan:</span>
                        <span class="text-xs font-bold text-primary">{{ $subscription->plan->name ?? 'Starter' }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-secondary">Status:</span>
                        @if($subscription && $subscription->payment_status === 'pending_approval')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                Pending Approval
                            </span>
                        @elseif($subscription && $subscription->status === 'active')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-800">
                                Active (Paid)
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-100 text-blue-800">
                                Free Trial ({{ $subscription ? $subscription->trial_remaining_days : 30 }}d left)
                            </span>
                        @endif
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('subscription.portal') }}" class="w-full py-2.5 px-3 rounded-xl bg-primary hover:bg-primary-container text-white text-xs font-bold flex items-center justify-center gap-1.5 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-sm">loyalty</span>
                        <span>Manage Subscription & Upgrades</span>
                    </a>
                </div>
            </div>

            <!-- 2. RECENT INVOICES IN THIS BUSINESS -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
                <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">receipt_long</span>
                        <h3 class="font-headline font-bold text-base text-text-rich">Recent Invoices</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold font-numeric">
                        {{ $recentInvoices->count() }}
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentInvoices as $inv)
                        <div class="p-3 rounded-xl bg-bg-subtle border border-border-quiet hover:border-primary/40 transition-all flex items-center justify-between">
                            <div class="min-w-0">
                                <a href="{{ route('invoices.show', $inv) }}" class="font-bold text-xs text-primary hover:underline block truncate">
                                    {{ $inv->invoice_number }}
                                </a>
                                <span class="text-[10px] text-secondary truncate block">{{ $inv->customer_name ?? 'Walk-in Customer' }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-numeric font-bold text-text-rich block">{{ $currencyService->format($inv->total_cents) }}</span>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ $inv->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $inv->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-secondary bg-bg-subtle rounded-xl">
                            No invoices recorded yet in this workspace.
                        </div>
                    @endforelse
                </div>

                <div class="pt-2 border-t border-border-quiet">
                    <a href="{{ route('invoices.index') }}" class="w-full py-2 px-3 rounded-lg bg-surface hover:bg-bg-subtle text-primary border border-border-quiet text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                        <span class="material-symbols-outlined text-sm">visibility</span>
                        <span>View All Invoices</span>
                    </a>
                </div>
            </div>

            <!-- 3. ASSIGNED TASKS & RECENT ACTIVITY -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
                <div class="flex items-center justify-between border-b border-border-quiet pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">task_alt</span>
                        <h3 class="font-headline font-bold text-base text-text-rich">Recent Tasks & Flow</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold font-numeric">
                        {{ $recentTasks->count() }}
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($recentTasks as $task)
                        <div class="p-3 rounded-xl bg-bg-subtle border border-border-quiet hover:border-primary/40 transition-all flex items-center justify-between">
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-text-rich truncate">{{ $task->title }}</h4>
                                <span class="text-[10px] text-secondary capitalize">{{ str_replace('_', ' ', $task->priority) }} Priority</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ str_replace('_', ' ', $task->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-secondary bg-bg-subtle rounded-xl">
                            No tasks currently assigned.
                        </div>
                    @endforelse
                </div>

                <div class="pt-2 border-t border-border-quiet">
                    <a href="{{ route('tasks.index') }}" class="w-full py-2 px-3 rounded-lg bg-surface hover:bg-bg-subtle text-primary border border-border-quiet text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                        <span class="material-symbols-outlined text-sm">checklist</span>
                        <span>Manage All Tasks</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileLogoutBtn = document.getElementById('profileLogoutBtn');
        if (profileLogoutBtn) {
            profileLogoutBtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Sign Out?',
                    text: 'Are you sure you want to sign out of your account?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#003e97',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, Sign Out'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('profileLogoutForm').submit();
                    }
                });
            });
        }
    });
</script>
@endpush