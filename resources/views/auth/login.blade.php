@extends('layouts.public')

@section('title', 'Sign In • TBM Enterprise Suite')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white border border-border-quiet rounded-2xl p-8 shadow-sm space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-xl bg-primary-container text-white flex items-center justify-center mx-auto shadow-sm">
                <span class="material-symbols-outlined text-2xl">account_balance</span>
            </div>
            <h1 class="font-headline text-2xl font-bold text-primary">TBM Enterprise</h1>
            <p class="text-xs text-secondary">Task Business Manager &amp; Multi-Currency Ledger</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-center gap-2">
                <span class="material-symbols-outlined text-base shrink-0">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="p-3.5 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-700 flex items-center gap-2">
                <span class="material-symbols-outlined text-base shrink-0">info</span>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-secondary uppercase mb-1.5">Email Address</label>
                <div class="relative">
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="name@company.com" autocomplete="email" class="w-full h-11 pl-10 pr-4 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-sm text-text-rich outline-none transition-colors">
                    <span class="material-symbols-outlined text-secondary absolute left-3 top-3 text-lg">mail</span>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="text-xs font-bold text-secondary uppercase">Password</label>
                </div>
                <div class="relative">
                    <input type="password" name="password" id="password" required placeholder="Enter your password" autocomplete="current-password" class="w-full h-11 pl-10 pr-10 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-sm text-text-rich outline-none transition-colors">
                    <span class="material-symbols-outlined text-secondary absolute left-3 top-3 text-lg">lock</span>
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 top-3 text-secondary hover:text-text-rich" title="Toggle visibility">
                        <span class="material-symbols-outlined text-lg" id="pwdToggleIcon">visibility</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-secondary">
                    <input type="checkbox" name="remember" class="rounded border-border-quiet text-primary focus:ring-primary">
                    <span>Remember this device</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-container text-white py-3 rounded-lg font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 mt-2">
                <span>Sign In to Account</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </form>

        <div class="pt-4 border-t border-border-quiet text-center text-xs text-secondary space-y-2">
            <p>Need a workspace for your business?</p>
            <a href="{{ route('register') }}" class="font-bold text-primary hover:underline block">
                Start 30-Day Free Trial &rarr;
            </a>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const pwdInput = document.getElementById('password');
        const icon = document.getElementById('pwdToggleIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            pwdInput.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endsection