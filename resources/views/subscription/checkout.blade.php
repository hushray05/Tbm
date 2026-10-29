@extends('layouts.app')

@section('title', 'Subscription Checkout • Uganda UGX')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 py-4">
    <!-- Header -->
    <div class="text-center space-y-1">
        <span class="text-xs font-bold uppercase tracking-wider text-primary">Uganda Payment Gateway</span>
        <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Complete Subscription Checkout</h1>
        <p class="text-xs text-secondary">Upgrade to {{ $plan->name }} ({{ ucfirst($billingCycle) }} Billing) in Uganda Shillings (UGX).</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Plan Summary Sidebar (4 cols) -->
        <div class="md:col-span-5 bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
            <div class="border-b border-border-quiet pb-4">
                <span class="text-xs font-bold text-secondary uppercase">Order Summary</span>
                <h3 class="font-headline font-bold text-lg text-text-rich mt-1">{{ $plan->name }} Plan</h3>
                <span class="text-xs text-secondary">{{ ucfirst($billingCycle) }} Subscription Cycle</span>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between text-secondary">
                    <span>Base Subscription</span>
                    <span class="font-numeric font-semibold text-text-rich">UGX {{ number_format($amount) }}</span>
                </div>
                <div class="flex justify-between text-secondary">
                    <span>Uganda VAT (18% Included)</span>
                    <span class="font-numeric font-semibold text-text-rich">UGX {{ number_format(($amount * 0.18) / 1.18) }}</span>
                </div>
                <div class="flex justify-between text-secondary">
                    <span>Mobile Money Convenience Fee</span>
                    <span class="font-semibold text-success font-numeric">UGX 0 (Waived)</span>
                </div>
            </div>

            <div class="border-t border-border-quiet pt-4 flex justify-between items-baseline">
                <span class="text-sm font-bold text-text-rich">Total Payable:</span>
                <span class="font-headline text-2xl font-extrabold text-primary font-numeric">
                    UGX {{ number_format($amount) }}
                </span>
            </div>

            <div class="bg-bg-subtle p-3 rounded-xl text-[11px] text-secondary space-y-1">
                <div class="flex items-center gap-1.5 font-bold text-text-rich">
                    <span class="material-symbols-outlined text-sm text-success">lock</span>
                    <span>Secure Ugandan FX Clearing</span>
                </div>
                <p>Transactions are processed in real-time. Instant activation upon PIN authorization.</p>
            </div>
        </div>

        <!-- Payment Methods Form (7 cols) -->
        <div class="md:col-span-7 bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-6">
            <!-- Official Pesapal Gateway -->
            <div class="bg-gradient-to-r from-blue-900 to-primary text-white rounded-2xl p-6 shadow-lg relative overflow-hidden space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-black text-lg">P</div>
                        <div>
                            <span class="font-headline font-bold text-lg text-white block">Official Pesapal Merchant Gateway</span>
                            <span class="text-[11px] text-blue-200">Sole Authorized Payment Portal for TBM Enterprise</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-900 text-[10px] font-black uppercase tracking-wider">Official Gateway</span>
                </div>

                <p class="text-xs text-blue-100 leading-relaxed">
                    Pay securely using <strong>Visa, Mastercard, MTN Mobile Money, Airtel Money, or M-Pesa</strong> directly on our official verified Pesapal merchant store checkout.
                </p>

                <div class="bg-white/10 rounded-xl p-3.5 flex items-center justify-between text-xs">
                    <span class="text-blue-200">Amount Due:</span>
                    <span class="font-headline text-xl font-extrabold text-white font-numeric">UGX {{ number_format($amount) }}</span>
                </div>

                <a href="https://store.pesapal.com/taskbusinessmanagerpos" target="_blank" rel="noopener noreferrer" class="w-full bg-amber-400 hover:bg-amber-300 text-slate-900 font-extrabold py-3.5 px-4 rounded-xl text-xs flex items-center justify-center gap-2 shadow-md transition-all text-center group">
                    <span>1. Click to Pay UGX {{ number_format($amount) }} on Pesapal</span>
                    <span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">open_in_new</span>
                </a>
            </div>

            <!-- Submission Form for Admin Approval -->
            <div class="bg-bg-subtle rounded-2xl p-6 border border-border-quiet space-y-4">
                <div class="border-b border-border-quiet pb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">verified_user</span>
                        <h3 class="font-headline font-bold text-base text-text-rich">2. Submit Payment Details for Admin Approval</h3>
                    </div>
                    <p class="text-xs text-secondary mt-1">Once you complete payment on Pesapal, submit your transaction reference or phone number below. Our administrator will verify and approve your subscription.</p>
                </div>

                <form action="{{ route('subscription.pay') }}" method="POST" class="space-y-4" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">
                    <input type="hidden" name="gateway" value="Pesapal Merchant Store">

                    <div>
                        <label for="payment_reference" class="block text-xs font-bold text-secondary uppercase mb-1.5">
                            Pesapal Tracking ID / Reference / Transaction ID <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="payment_reference" id="payment_reference" required placeholder="e.g. PP-8239401 or MoMo Transaction ID" class="w-full h-11 px-4 rounded-lg border border-border-quiet bg-surface focus:border-primary text-sm font-numeric text-text-rich outline-none">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-xs font-bold text-secondary uppercase mb-1.5">
                            Payer Phone Number / Email Used on Pesapal <span class="text-secondary">(Optional)</span>
                        </label>
                        <input type="text" name="phone_number" id="phone_number" placeholder="e.g. +256 772 123456 or payer@email.com" class="w-full h-11 px-4 rounded-lg border border-border-quiet bg-surface focus:border-primary text-sm font-numeric text-text-rich outline-none">
                    </div>

                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-800 flex items-start gap-2">
                        <span class="material-symbols-outlined text-base text-amber-600 shrink-0 mt-0.5">info</span>
                        <span>Subscriptions paid via Pesapal are reviewed and activated by our administrators upon confirmation.</span>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary-container text-white py-3.5 rounded-xl font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">send</span>
                        <span>Submit for Administrator Approval</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
