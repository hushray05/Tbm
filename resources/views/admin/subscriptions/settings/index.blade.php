@extends('layouts.admin')

@section('title', 'Subscription & Billing Settings • Super Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Subscription & Billing Settings</h1>
            <p class="text-xs text-secondary mt-0.5">Control global commercial parameters, trial periods, payment gateways, and renewal reminder triggers without altering source code.</p>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="bg-surface border border-border-quiet rounded-2xl p-6 md:p-8 tbm-shadow">
        <form action="{{ route('admin.subscriptions.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Free Trial Settings -->
            <div class="space-y-4">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    1. Global Free Trial Configuration
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Default Trial Duration (Days) <span class="text-danger">*</span></label>
                        <select name="default_trial_days" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                            <option value="7" {{ ($settings['default_trial_days']->value ?? '30') == '7' ? 'selected' : '' }}>7 Days</option>
                            <option value="14" {{ ($settings['default_trial_days']->value ?? '30') == '14' ? 'selected' : '' }}>14 Days</option>
                            <option value="30" {{ ($settings['default_trial_days']->value ?? '30') == '30' ? 'selected' : '' }}>30 Days (Standard Free Trial)</option>
                            <option value="60" {{ ($settings['default_trial_days']->value ?? '30') == '60' ? 'selected' : '' }}>60 Days</option>
                            <option value="90" {{ ($settings['default_trial_days']->value ?? '30') == '90' ? 'selected' : '' }}>90 Days</option>
                        </select>
                        <span class="text-[10px] text-secondary mt-1 block">Applies automatically when new Ugandan businesses register.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Require Credit Card / Mobile Money on Registration?</label>
                        <select name="trial_require_payment_method" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                            <option value="false" {{ ($settings['trial_require_payment_method']->value ?? 'false') == 'false' ? 'selected' : '' }}>No Upfront Payment Required (Recommended)</option>
                            <option value="true" {{ ($settings['trial_require_payment_method']->value ?? 'false') == 'true' ? 'selected' : '' }}>Yes, Require Payment Info</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2. System Currency & Statutory VAT -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    2. Primary Currency & Taxation
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Primary Currency Code</label>
                        <input type="text" name="currency" value="{{ $settings['currency']->value ?? 'UGX' }}" readonly class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-slate-100 text-xs font-bold text-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol']->value ?? 'UGX' }}" readonly class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-slate-100 text-xs font-bold text-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Uganda VAT Rate (%)</label>
                        <input type="number" step="0.1" name="vat_rate_percent" value="{{ $settings['vat_rate_percent']->value ?? '18' }}" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-numeric text-text-rich outline-none">
                    </div>
                </div>
            </div>

            <!-- 3. Expiration Reminders & Alerts -->
            <div class="space-y-4 pt-2">
                <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                    3. Expiration & Renewal Reminders
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Expiration Reminder Days</label>
                        <input type="text" name="expiry_alert_days" value="{{ $settings['expiry_alert_days']->value ?? '7,3,1' }}" placeholder="7,3,1" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs font-mono text-text-rich outline-none">
                        <span class="text-[10px] text-secondary mt-1 block">Comma-separated days before trial/subscription expires to show top alert banner.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase mb-1">Automatic Invoice Generation</label>
                        <select name="auto_generate_invoices" class="w-full h-10 px-3.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white focus:border-primary text-xs text-text-rich outline-none">
                            <option value="true" {{ ($settings['auto_generate_invoices']->value ?? 'true') == 'true' ? 'selected' : '' }}>Enabled (Generate 7 days before due date)</option>
                            <option value="false" {{ ($settings['auto_generate_invoices']->value ?? 'true') == 'false' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-4 border-t border-border-quiet flex items-center justify-end">
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-6 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">save</span>
                    <span>Save All Commercial Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
