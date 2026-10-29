@extends('layouts.admin')

@section('title', 'Add CRM Lead • TBM Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Add Commercial Lead</h1>
            <p class="text-xs text-secondary mt-0.5">Register a new client prospect into pipeline.</p>
        </div>
        <a href="{{ route('admin.leads.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Leads</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('admin.leads.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Lead Contact Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Full Name" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Avatar / Logo URL</label>
                    <input type="text" name="avatar_url" value="{{ old('avatar_url') }}" placeholder="/images/avatars/kwame-mensah.svg or https://..." class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Company / Entity</label>
                    <input type="text" name="company" value="{{ old('company') }}" placeholder="Organization name" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="client@corp.com" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+234..." class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Pipeline Stage</label>
                    <select name="status" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="qualified" selected>Qualified</option>
                        <option value="proposal">Proposal</option>
                        <option value="won">Won</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-border-quiet">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Est. Deal Value ($ USD) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="estimated_value" required value="{{ old('estimated_value', '2500.00') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Assigned Account Lead</label>
                    <input type="text" name="assigned_to" value="Sarah Jenkins" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
                <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-secondary">Cancel</a>
                <button type="submit" class="bg-primary text-white font-bold text-xs px-5 py-2 rounded-lg shadow-sm hover:bg-primary-container">Save Lead</button>
            </div>
        </form>
    </div>
</div>
@endsection
