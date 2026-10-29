@extends('layouts.admin')

@section('title', 'Subscription Features Matrix • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Plan Features & Entitlements Matrix</h1>
            <p class="text-xs text-secondary mt-0.5">Control feature gating, backend permissions, and commercial plan inclusions without writing code.</p>
        </div>
        <div>
            <button type="button" onclick="document.getElementById('featureModal').classList.remove('hidden')" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                <span>Add Feature Flag</span>
            </button>
        </div>
    </div>

    <!-- Features Matrix Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Feature Name / Key</th>
                        <th class="px-5 py-3.5">Category</th>
                        <th class="px-5 py-3.5">Description</th>
                        @foreach($plans as $p)
                            <th class="px-5 py-3.5 text-center font-headline">{{ $p->name }}</th>
                        @endforeach
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @foreach($features as $feat)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="font-headline font-bold text-text-rich text-xs block">{{ $feat->name }}</span>
                                <span class="text-[10px] font-mono text-primary">{{ $feat->key }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800">
                                    {{ $feat->category }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-secondary max-w-xs">{{ $feat->description }}</td>
                            
                            @foreach($plans as $p)
                                @php $isPlanEnabled = $p->hasFeature($feat->key); @endphp
                                <td class="px-5 py-3.5 text-center">
                                    @if($isPlanEnabled)
                                        <span class="material-symbols-outlined text-success text-lg">check_circle</span>
                                    @else
                                        <span class="material-symbols-outlined text-slate-300 text-lg">cancel</span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-5 py-3.5 text-center">
                                <form action="{{ route('admin.subscriptions.features.destroy', $feat) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-secondary hover:text-danger hover:bg-red-50" title="Delete Feature">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Feature Modal -->
<div id="featureModal" class="fixed inset-0 bg-slate-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-surface rounded-2xl border border-border-quiet p-6 max-w-md w-full shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-headline font-bold text-base text-text-rich">Add New Feature Flag</h3>
            <button type="button" onclick="document.getElementById('featureModal').classList.add('hidden')" class="text-secondary hover:text-text-rich">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <form action="{{ route('admin.subscriptions.features.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Feature Name <span class="text-danger">*</span></label>
                <input type="text" name="name" required placeholder="e.g. AI Financial Forecasting" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Feature Key / Code <span class="text-danger">*</span></label>
                <input type="text" name="key" required placeholder="e.g. ai_financial_forecasting" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs font-mono text-text-rich outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Category <span class="text-danger">*</span></label>
                <select name="category" required class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none">
                    <option value="Accounting">Accounting & Finance</option>
                    <option value="Operations">Operations & Inventory</option>
                    <option value="Compliance">Statutory Tax & Payroll</option>
                    <option value="Integration">Integrations & API</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Brief feature summary..." class="w-full p-2.5 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-white text-xs text-text-rich outline-none"></textarea>
            </div>

            <div class="space-y-2 pt-1">
                <label class="block text-[11px] font-bold text-secondary uppercase">Assign to Plans</label>
                <div class="space-y-1.5">
                    @foreach($plans as $p)
                        <label class="flex items-center gap-2 text-xs font-semibold text-text-rich cursor-pointer">
                            <input type="checkbox" name="plans[]" value="{{ $p->id }}" class="w-4 h-4 text-primary rounded">
                            <span>{{ $p->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('featureModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border border-border-quiet text-xs font-semibold text-secondary">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold shadow-sm hover:bg-primary-container">Save Feature</button>
            </div>
        </form>
    </div>
</div>
@endsection
