@extends('layouts.admin')

@section('title', 'Edit Ticket: ' . $ticket->ticket_number . ' • TBM Admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Edit Ticket: {{ $ticket->ticket_number }}</h1>
            <p class="text-xs text-secondary mt-0.5">Update incident resolution status.</p>
        </div>
        <a href="{{ route('admin.tickets.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Tickets</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Issue Headline <span class="text-danger">*</span></label>
                <input type="text" name="title" required value="{{ old('title', $ticket->title) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Detailed Description</label>
                <textarea name="description" rows="3" class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">{{ old('description', $ticket->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Requester Name <span class="text-danger">*</span></label>
                    <input type="text" name="requester_name" required value="{{ old('requester_name', $ticket->requester_name) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Requester Email</label>
                    <input type="email" name="requester_email" value="{{ old('requester_email', $ticket->requester_email) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Priority</label>
                    <select name="priority" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Status</label>
                    <select name="status" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Assigned Agent</label>
                    <input type="text" name="assigned_agent" value="{{ $ticket->assigned_agent }}" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
                <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-secondary">Cancel</a>
                <button type="submit" class="bg-primary text-white font-bold text-xs px-5 py-2 rounded-lg shadow-sm hover:bg-primary-container">Update Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
