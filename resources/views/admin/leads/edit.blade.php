@extends('layouts.admin')

@section('title', 'Edit Lead: ' . $lead->name . ' • TBM Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Edit Lead: {{ $lead->name }}</h1>
            <p class="text-xs text-secondary mt-0.5">Update commercial opportunity metrics.</p>
        </div>
        <a href="{{ route('admin.leads.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Leads</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Lead Contact Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $lead->name) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Avatar / Logo URL</label>
                    <div class="flex gap-2 items-center">
                        <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-900 border border-border-quiet shrink-0">
                            <img src="{{ asset($lead->avatar) }}" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <input type="text" name="avatar_url" value="{{ old('avatar_url', $lead->avatar_url) }}" placeholder="/images/avatars/kwame-mensah.svg or https://..." class="flex-1 h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Company / Entity</label>
                    <input type="text" name="company" value="{{ old('company', $lead->company) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Pipeline Stage</label>
                    <select name="status" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="proposal" {{ $lead->status === 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="won" {{ $lead->status === 'won' ? 'selected' : '' }}>Won</option>
                        <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-border-quiet">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Est. Deal Value ($ USD) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="estimated_value" required value="{{ old('estimated_value', number_format($lead->estimated_value_cents / 100, 2, '.', '')) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Assigned Account Lead</label>
                    <input type="text" name="assigned_to" value="{{ $lead->assigned_to }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
                <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-secondary">Cancel</a>
                <button type="submit" class="bg-primary text-white font-bold text-xs px-5 py-2 rounded-lg shadow-sm hover:bg-primary-container">Update Lead</button>
            </div>
        </form>
    </div>
</div>
@endsection
