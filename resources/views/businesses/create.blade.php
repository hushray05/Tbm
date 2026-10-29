@extends('layouts.app')

@section('title', 'Register New Business • TBM Enterprise')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Register Commercial Business</h1>
            <p class="text-xs text-secondary mt-0.5">Add a new company or subsidiary under your TBM account.</p>
        </div>
        <a href="{{ route('businesses.index') }}" class="text-xs font-semibold text-primary hover:underline">
            ← Back to Businesses
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow">
        <form action="{{ route('businesses.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Business Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Nile Agro-Export Ltd" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Uganda TIN Number</label>
                    <input type="text" name="tin_number" value="{{ old('tin_number') }}" placeholder="1009876543" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="contact@nileagro.ug" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+256 701 000000" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">City / Region <span class="text-danger">*</span></label>
                    <input type="text" name="city" required value="Kampala" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Primary Currency <span class="text-danger">*</span></label>
                    <select name="currency_code" required class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                        <option value="UGX" selected>UGX - Uganda Shillings</option>
                        <option value="KES">KES - Kenyan Shillings</option>
                        <option value="USD">USD - US Dollars</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Headquarters Address</label>
                <textarea name="address" rows="2" placeholder="Plot 12, Industrial Area, Kampala" class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-border-quiet flex items-center justify-end gap-3">
                <a href="{{ route('businesses.index') }}" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary hover:bg-bg-subtle">
                    Cancel
                </a>
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">add_business</span>
                    <span>Create Business</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
