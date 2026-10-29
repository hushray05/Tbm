@extends('layouts.app')

@section('title', 'Customers & Vendors • TBM Enterprise')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">{{ ucfirst($type) }} Directory & Ledger</h1>
            <p class="text-xs text-secondary mt-0.5">Manage {{ $type === 'vendor' ? 'suppliers and vendors' : 'corporate customers and clients' }} in Uganda.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Type Tabs Switcher -->
            <div class="flex items-center bg-bg-subtle p-1 rounded-xl border border-border-quiet text-xs font-bold">
                <a href="{{ route('parties.index', ['type' => 'customer']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ $type === 'customer' ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:text-text-rich' }}">
                    Customers
                </a>
                <a href="{{ route('parties.index', ['type' => 'vendor']) }}" class="px-3.5 py-1.5 rounded-lg transition-all {{ $type === 'vendor' ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:text-text-rich' }}">
                    Vendors
                </a>
            </div>

            <button type="button" onclick="document.getElementById('partyModal').classList.remove('hidden')" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">person_add</span>
                <span>Add {{ ucfirst($type) }}</span>
            </button>
        </div>
    </div>

    <!-- Parties Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Entity / Name</th>
                        <th class="px-5 py-3.5">Company Group</th>
                        <th class="px-5 py-3.5">Contact Details</th>
                        <th class="px-5 py-3.5">Uganda TIN</th>
                        <th class="px-5 py-3.5">Location</th>
                        <th class="px-5 py-3.5 text-right">Outstanding (UGX)</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($parties as $p)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-text-rich block text-sm">{{ $p->name }}</span>
                                <span class="text-[10px] text-secondary capitalize">{{ $p->type }} Account</span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-text-rich">{{ $p->company ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-text-rich block">{{ $p->email ?? 'N/A' }}</span>
                                <span class="text-secondary font-numeric">{{ $p->phone ?? 'N/A' }}</span>
                            </td>
                            <td class="px-5 py-3.5 font-numeric text-primary font-bold">{{ $p->tin_number ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-secondary">{{ $p->city }}, Uganda</td>
                            <td class="px-5 py-3.5 text-right font-numeric font-bold text-text-rich">
                                UGX {{ number_format($p->balance_ugx) }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <form action="{{ route('parties.destroy', $p) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-secondary hover:text-danger hover:bg-red-50" title="Delete">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-secondary">
                                No {{ $type }}s found in directory.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Party Modal -->
<div id="partyModal" class="fixed inset-0 bg-slate-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-surface rounded-2xl border border-border-quiet p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-headline font-bold text-base text-text-rich">Add New {{ ucfirst($type) }}</h3>
            <button type="button" onclick="document.getElementById('partyModal').classList.add('hidden')" class="text-secondary hover:text-text-rich">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <form action="{{ route('parties.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Contact Name / Title <span class="text-danger">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Kakira Sugar Works Ltd" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Company / Organization</label>
                <input type="text" name="company" placeholder="e.g. Madhvani Group" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="sales@kakirasugar.com" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone" placeholder="+256 434 121000" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Uganda TIN</label>
                    <input type="text" name="tin_number" placeholder="1003344556" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">City / Region</label>
                    <input type="text" name="city" value="Kampala" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('partyModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold shadow-sm hover:bg-primary-container">Save Contact</button>
            </div>
        </form>
    </div>
</div>
@endsection
