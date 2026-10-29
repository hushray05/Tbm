@extends('layouts.public')

@section('title', 'Start Free Trial • TBM Enterprise Suite Uganda')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white border border-border-quiet rounded-2xl p-6 md:p-8 shadow-md space-y-6 my-8">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-xl bg-primary-container text-white flex items-center justify-center mx-auto shadow-sm">
                <span class="material-symbols-outlined text-2xl">account_balance</span>
            </div>
            <h1 class="font-headline text-2xl font-bold text-primary">Start Your {{ $defaultTrialDays }}-Day Free Trial</h1>
            <p class="text-xs text-secondary">Uganda multi-business accounting, inventory, and task workspace</p>
        </div>

        @if($errors->any())
            <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <!-- 1. Choose Plan -->
            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-2">Select Your Subscription Plan</label>
                <div class="grid grid-cols-3 gap-2.5">
                    @foreach($plans as $p)
                        <label class="border-2 rounded-xl p-3 flex flex-col items-center justify-between cursor-pointer transition-all plan-card {{ $selectedPlanSlug === $p->slug ? 'border-primary bg-primary/5' : 'border-border-quiet hover:bg-bg-subtle' }}" onclick="selectPlanCard(this, '{{ $p->slug }}')">
                            <input type="radio" name="plan_slug" value="{{ $p->slug }}" {{ $selectedPlanSlug === $p->slug ? 'checked' : '' }} class="hidden">
                            <span class="font-headline font-bold text-xs text-text-rich">{{ $p->name }}</span>
                            <span class="font-numeric font-extrabold text-xs text-primary mt-1">UGX {{ number_format($p->monthly_price) }}</span>
                            <span class="text-[10px] text-secondary">/ month</span>
                            <span class="mt-1 px-1.5 py-0.5 rounded bg-green-100 text-green-800 text-[9px] font-bold">{{ $p->trial_duration }}d Free</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 2. Organization / Business Name -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="business_name" class="block text-xs font-bold text-secondary uppercase mb-1">Business Name <span class="text-red-500">*</span></label>
                    <input type="text" name="business_name" id="business_name" required value="{{ old('business_name') }}" placeholder="e.g. Nile Traders Ltd" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label for="city" class="block text-xs font-bold text-secondary uppercase mb-1">City / Region</label>
                    <input type="text" name="city" id="city" value="{{ old('city', 'Kampala') }}" placeholder="e.g. Kampala" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <!-- 3. Owner Information -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="name" class="block text-xs font-bold text-secondary uppercase mb-1">Owner / Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. David Mukasa" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label for="phone" class="block text-xs font-bold text-secondary uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+256 772 000000" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-secondary uppercase mb-1">Work Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="david@niletraders.co.ug" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-bold text-secondary uppercase mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required placeholder="Minimum 8 chars" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-secondary uppercase mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Re-enter password" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <!-- Free Trial Info Box -->
            <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-xs text-green-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-success text-base">verified</span>
                <span><strong>No payment required today.</strong> Your {{ $defaultTrialDays }}-day full access trial begins immediately.</span>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-container text-white py-3.5 rounded-xl font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2">
                <span>Start {{ $defaultTrialDays }}-Day Free Trial</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </form>

        <div class="pt-3 border-t border-border-quiet text-center text-xs text-secondary space-y-1">
            <p>Already have a business workspace?</p>
            <a href="{{ route('login') }}" class="font-bold text-primary hover:underline block">
                Sign In to Existing Account &rarr;
            </a>
        </div>
    </div>
</div>

<script>
    function selectPlanCard(cardEl, slug) {
        document.querySelectorAll('.plan-card').forEach(c => {
            c.classList.remove('border-primary', 'bg-primary/5');
            c.classList.add('border-border-quiet');
        });
        cardEl.classList.remove('border-border-quiet');
        cardEl.classList.add('border-primary', 'bg-primary/5');
        cardEl.querySelector('input').checked = true;
    }
</script>
@endsection