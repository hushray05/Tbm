@extends('layouts.admin')

@section('title', 'Subscriber Overview • ' . ($subscription->business->name ?? 'Business'))

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="font-headline text-2xl font-bold text-text-rich">{{ $subscription->business->name ?? 'Business Workspace' }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $subscription->status }}
                </span>
            </div>
            <p class="text-xs text-secondary mt-0.5">Managing Director: {{ $subscription->business->owner->name ?? 'N/A' }} ({{ $subscription->business->email }}) • Kampala, Uganda</p>
        </div>
        <a href="{{ route('admin.subscriptions.subscribers.index') }}" class="text-xs font-semibold text-primary hover:underline">
            ← Back to Subscribers
        </a>
    </div>

    <!-- Overview Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- 1. Subscription & Plan Info (1 col) -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
            <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                Subscription Details
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-secondary">Assigned Plan:</span>
                    <span class="font-bold text-text-rich">{{ $subscription->plan->name ?? 'Starter' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">Billing Cycle:</span>
                    <span class="capitalize font-semibold text-text-rich">{{ $subscription->billing_cycle }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">Amount Payable:</span>
                    <span class="font-numeric font-bold text-primary">UGX {{ number_format($subscription->amount) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">Payment Status:</span>
                    <span class="capitalize font-semibold text-text-rich">{{ $subscription->payment_status ?? 'Pending' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">Trial Start Date:</span>
                    <span class="font-numeric">{{ $subscription->trial_starts_at ? $subscription->trial_starts_at->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">Trial Expiry:</span>
                    <span class="font-numeric font-bold text-amber-700">{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">Next Renewal Date:</span>
                    <span class="font-numeric font-bold text-text-rich">{{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d M Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Live Usage Quotas & Limits (1 col) -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
            <h3 class="font-headline font-bold text-sm text-primary uppercase tracking-wider border-b border-border-quiet pb-2">
                Live Usage Quotas
            </h3>

            <div class="space-y-3 text-xs">
                <div>
                    <div class="flex justify-between text-secondary mb-1">
                        <span>Team Users:</span>
                        <strong class="text-text-rich font-numeric">{{ $userLimit['current'] }} / {{ $userLimit['max'] ?? '∞' }}</strong>
                    </div>
                    <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                        <div class="h-full bg-primary" style="width: {{ $userLimit['max'] ? min(100, ($userLimit['current'] / $userLimit['max']) * 100) : 20 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-secondary mb-1">
                        <span>Products Catalog:</span>
                        <strong class="text-text-rich font-numeric">{{ $productLimit['current'] }} / {{ $productLimit['max'] ?? '∞' }}</strong>
                    </div>
                    <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                        <div class="h-full bg-primary" style="width: {{ $productLimit['max'] ? min(100, ($productLimit['current'] / $productLimit['max']) * 100) : 10 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-secondary mb-1">
                        <span>Monthly Invoices:</span>
                        <strong class="text-text-rich font-numeric">{{ $invoiceLimit['current'] }} / {{ $invoiceLimit['max'] ?? '∞' }}</strong>
                    </div>
                    <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                        <div class="h-full bg-primary" style="width: {{ $invoiceLimit['max'] ? min(100, ($invoiceLimit['current'] / $invoiceLimit['max']) * 100) : 5 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-secondary mb-1">
                        <span>Branch Locations:</span>
                        <strong class="text-text-rich font-numeric">{{ $locationLimit['current'] }} / {{ $locationLimit['max'] ?? '∞' }}</strong>
                    </div>
                    <div class="w-full h-2 bg-bg-subtle rounded-full overflow-hidden">
                        <div class="h-full bg-primary" style="width: {{ $locationLimit['max'] ? min(100, ($locationLimit['current'] / $locationLimit['max']) * 100) : 25 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Super Admin Manual Controls (1 col) -->
        <div class="bg-surface border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-4">
            <h3 class="font-headline font-bold text-sm text-danger uppercase tracking-wider border-b border-border-quiet pb-2">
                Super Admin Overrides
            </h3>

            <div class="space-y-3">
                <!-- Extend Trial Form -->
                <form action="{{ route('admin.subscriptions.subscribers.action', $subscription) }}" method="POST" class="p-3 bg-bg-subtle rounded-xl space-y-2">
                    @csrf
                    <input type="hidden" name="action" value="extend_trial">
                    <span class="text-[11px] font-bold text-text-rich block">Extend Free Trial</span>
                    <div class="flex gap-2">
                        <select name="additional_days" class="flex-1 h-8 px-2 rounded border border-border-quiet text-xs">
                            <option value="7">+7 Days</option>
                            <option value="14">+14 Days</option>
                            <option value="30">+30 Days</option>
                        </select>
                        <button type="submit" class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded transition-colors">Extend</button>
                    </div>
                </form>

                <!-- Change Plan Form -->
                <form action="{{ route('admin.subscriptions.subscribers.action', $subscription) }}" method="POST" class="p-3 bg-bg-subtle rounded-xl space-y-2">
                    @csrf
                    <input type="hidden" name="action" value="change_plan">
                    <span class="text-[11px] font-bold text-text-rich block">Switch Plan</span>
                    <div class="flex gap-2">
                        <select name="plan_id" class="flex-1 h-8 px-2 rounded border border-border-quiet text-xs">
                            @foreach($plans as $p)
                                <option value="{{ $p->id }}" {{ $subscription->subscription_plan_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-1 bg-primary hover:bg-primary-container text-white font-bold text-xs rounded transition-colors">Apply</button>
                    </div>
                </form>

                <!-- Direct Status Buttons -->
                <div class="flex items-center gap-2 pt-1">
                    <form action="{{ route('admin.subscriptions.subscribers.action', $subscription) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="action" value="activate_paid">
                        <button type="submit" class="w-full py-2 rounded bg-green-600 hover:bg-green-700 text-white font-bold text-xs shadow-sm">
                            Activate (Paid)
                        </button>
                    </form>

                    <form action="{{ route('admin.subscriptions.subscribers.action', $subscription) }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="action" value="suspend">
                        <button type="submit" class="w-full py-2 rounded bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-sm">
                            Suspend
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Audit Log History & Payments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Event Audit Log -->
        <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle font-headline font-bold text-sm text-text-rich">
                Subscription Lifecycle Events
            </div>
            <div class="p-4 divide-y divide-border-quiet text-xs max-h-80 overflow-y-auto">
                @forelse($subscription->events as $ev)
                    <div class="py-2.5 flex items-start justify-between">
                        <div>
                            <span class="font-bold text-text-rich block capitalize">{{ str_replace('_', ' ', $ev->event_type) }}</span>
                            <span class="text-secondary">{{ $ev->description }}</span>
                        </div>
                        <span class="text-[10px] text-secondary shrink-0 font-numeric">{{ $ev->created_at->format('d M H:i') }}</span>
                    </div>
                @empty
                    <div class="py-6 text-center text-secondary">No lifecycle events recorded yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Payments Ledger -->
        <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
            <div class="p-4 border-b border-border-quiet bg-bg-subtle font-headline font-bold text-sm text-text-rich">
                Transaction History
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-bg-subtle/50 text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                        <tr>
                            <th class="px-4 py-2.5">Reference</th>
                            <th class="px-4 py-2.5">Gateway</th>
                            <th class="px-4 py-2.5 text-right">Amount (UGX)</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-quiet">
                        @forelse($subscription->payments as $p)
                            <tr>
                                <td class="px-4 py-3 font-numeric text-primary font-bold">{{ $p->transaction_reference }}</td>
                                <td class="px-4 py-3 text-secondary">{{ $p->gateway }}</td>
                                <td class="px-4 py-3 text-right font-numeric font-bold text-text-rich">UGX {{ number_format($p->amount) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-green-100 text-green-800">{{ $p->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-secondary">No payments processed yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
