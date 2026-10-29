@extends('layouts.admin')

@section('title', 'Log Support Ticket • TBM Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Log Support Incident</h1>
            <p class="text-xs text-secondary mt-0.5">Create ticket #{{ $nextTicketNum }}.</p>
        </div>
        <a href="{{ route('admin.tickets.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Tickets</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('admin.tickets.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="ticket_number" value="{{ $nextTicketNum }}">

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Issue Headline <span class="text-danger">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Summary of incident..." class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Detailed Description</label>
                <textarea name="description" rows="3" class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Requester Name <span class="text-danger">*</span></label>
                    <input type="text" name="requester_name" required value="{{ old('requester_name') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Requester Email</label>
                    <input type="email" name="requester_email" value="{{ old('requester_email') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Priority</label>
                    <select name="priority" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Status</label>
                    <select name="status" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="open" selected>Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Assigned Agent</label>
                    <input type="text" name="assigned_agent" value="DevOps Team" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
                <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-secondary">Cancel</a>
                <button type="submit" class="bg-primary text-white font-bold text-xs px-5 py-2 rounded-lg shadow-sm hover:bg-primary-container">Save Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
