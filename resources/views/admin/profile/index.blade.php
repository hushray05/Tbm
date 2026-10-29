@extends('layouts.admin')

@section('title', 'Admin Profile & Account Settings • TBM Enterprise')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Executive Profile & Account Settings</h1>
            <p class="text-xs text-secondary mt-1">Manage administrative identity, profile avatar, role permissions, and security credentials.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-primary-fixed text-primary font-bold text-xs flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">verified_user</span>
                <span>{{ $user->role ?? 'Super Administrator' }}</span>
            </span>
        </div>
    </div>

    <!-- Executive Identity Hero Banner -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 md:p-8 tbm-shadow relative overflow-hidden flex flex-col md:flex-row items-center md:items-start gap-6">
        <div class="relative group">
            <div class="w-28 h-28 md:w-32 md:h-32 rounded-2xl overflow-hidden border-2 border-primary/40 shadow-md bg-slate-900 flex items-center justify-center">
                <img id="header-avatar-preview" src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
            </div>
            <label for="avatar_file" class="absolute -bottom-2 -right-2 bg-primary hover:bg-primary-container text-white p-2 rounded-xl shadow cursor-pointer transition-transform hover:scale-105" title="Upload new photo">
                <span class="material-symbols-outlined text-sm">photo_camera</span>
            </label>
        </div>

        <div class="flex-1 text-center md:text-left space-y-2">
            <div class="flex flex-col md:flex-row md:items-center gap-2 justify-between">
                <div>
                    <h2 class="font-headline text-2xl font-bold text-text-rich">{{ $user->name }}</h2>
                    <p class="text-sm font-semibold text-primary">{{ $user->job_title ?? 'Chief Operations & Treasury Executive' }}</p>
                </div>
                <span class="text-xs font-numeric text-secondary bg-bg-subtle px-3 py-1 rounded-lg border border-border-quiet self-center md:self-auto">
                    ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <p class="text-xs text-secondary leading-relaxed max-w-2xl">
                {{ $user->bio ?? 'Enterprise administrator responsible for multi-currency settlement clearing, high-availability ledger operations, and ISO 20022 compliance governance across African regional gateways.' }}
            </p>

            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-2 text-xs text-secondary">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-outline text-sm">mail</span>
                    <strong class="text-text-rich">{{ $user->email }}</strong>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-outline text-sm">phone</span>
                    <span>{{ $user->phone ?? '+1 (555) 349-2041' }}</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-outline text-sm">corporate_fare</span>
                    <span>{{ $user->department ?? 'Executive Operations' }}</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Grid: Profile Settings & Security -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left 7 Cols: Personal Info & Avatar Picker -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 md:p-7 tbm-shadow space-y-6">
                <div class="border-b border-border-quiet pb-4">
                    <h3 class="font-headline font-bold text-base text-text-rich">Administrative Profile Information</h3>
                    <p class="text-xs text-secondary mt-0.5">Update your name, contact email, avatar image, and operational role.</p>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Picker Section -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-wider">Profile Avatar Image</label>
                        
                        <!-- Preset Avatars -->
                        <div class="p-3 bg-bg-subtle rounded-xl border border-border-quiet space-y-2">
                            <span class="text-[11px] font-semibold text-secondary block">Choose from Official Preset Avatars:</span>
                            <div class="flex flex-wrap items-center gap-3">
                                @foreach($presetAvatars as $preset)
                                    <button type="button" onclick="selectPresetAvatar('{{ $preset['url'] }}')" class="flex flex-col items-center gap-1 p-1.5 rounded-xl hover:bg-surface border border-transparent hover:border-primary/50 transition-all group" title="{{ $preset['name'] }}">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-900 border border-border-quiet group-hover:scale-105 transition-transform">
                                            <img src="{{ asset($preset['url']) }}" alt="{{ $preset['name'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <span class="text-[10px] text-secondary group-hover:text-primary transition-colors max-w-[70px] truncate">{{ explode(' ', $preset['name'])[0] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Custom Avatar URL / Upload -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div>
                                <label for="avatar_url_input" class="block text-[11px] font-semibold text-secondary mb-1">Or Avatar Image URL / Path:</label>
                                <input type="text" name="avatar_url" id="avatar_url_input" value="{{ old('avatar_url', $user->avatar_url) }}" placeholder="/images/avatars/sarah-jenkins.svg or https://..." class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                            </div>
                            <div>
                                <label for="avatar_file" class="block text-[11px] font-semibold text-secondary mb-1">Or Upload Image File (PNG/JPG):</label>
                                <input type="file" name="avatar_file" id="avatar_file" accept="image/*" onchange="previewUploadedImage(event)" class="w-full h-10 px-2 py-1.5 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-secondary file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-container cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Personal Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase mb-1">Full Legal Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', $user->name) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase mb-1">Official Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" required value="{{ old('email', $user->email) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase mb-1">Job Title</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $user->job_title) }}" placeholder="e.g. Chief Operating Officer" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase mb-1">Department</label>
                            <input type="text" name="department" value="{{ old('department', $user->department) }}" placeholder="e.g. Treasury Operations" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-secondary uppercase mb-1">Direct Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 349-2041" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Executive Bio & Responsibilities</label>
                        <textarea name="bio" rows="3" placeholder="Overview of operational responsibilities..." class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="flex justify-end pt-3 border-t border-border-quiet">
                        <button type="submit" class="bg-primary hover:bg-primary-container text-white px-6 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">save</span>
                            <span>Save Profile & Avatar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right 5 Cols: Password & Security Credentials -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Security Card -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 md:p-7 tbm-shadow space-y-6">
                <div class="border-b border-border-quiet pb-4">
                    <h3 class="font-headline font-bold text-base text-text-rich">Security Credentials</h3>
                    <p class="text-xs text-secondary mt-0.5">Update administrative password and cryptographic credentials.</p>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" required placeholder="Minimum 6 characters" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Repeat new password" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2.5 px-4 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">lock_reset</span>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Role & Compliance Matrix -->
            <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">shield</span>
                    <h4 class="font-headline font-bold text-sm text-text-rich">Governance & Permissions</h4>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between p-2.5 bg-bg-subtle rounded-lg">
                        <span class="text-secondary font-medium">Clearance Level</span>
                        <span class="font-bold text-primary font-numeric">Tier-1 Full Master</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-bg-subtle rounded-lg">
                        <span class="text-secondary font-medium">FX Rate Sync Trigger</span>
                        <span class="font-bold text-success flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">check_circle</span> Authorized
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-bg-subtle rounded-lg">
                        <span class="text-secondary font-medium">Ledger Audit Access</span>
                        <span class="font-bold text-success flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">check_circle</span> Unlimited
                        </span>
                    </div>
                </div>
            </div>

            <!-- Session & Logout Card -->
            <div class="bg-surface border border-red-200 rounded-2xl p-6 tbm-shadow space-y-4">
                <div class="flex items-center gap-2 text-danger">
                    <span class="material-symbols-outlined text-xl">power_settings_new</span>
                    <h4 class="font-headline font-bold text-sm">Active Session Termination</h4>
                </div>
                <p class="text-xs text-secondary leading-relaxed">
                    Terminate your current authentication session on this device and return to the secure sign-in portal.
                </p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-danger border border-red-200 py-2.5 px-4 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">logout</span>
                        <span>Sign Out of Account</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function selectPresetAvatar(url) {
    document.getElementById('avatar_url_input').value = url;
    document.getElementById('header-avatar-preview').src = url;
}

function previewUploadedImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('header-avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Live URL input preview
document.getElementById('avatar_url_input').addEventListener('input', function(e) {
    if (e.target.value) {
        document.getElementById('header-avatar-preview').src = e.target.value;
    }
});
</script>
@endsection
