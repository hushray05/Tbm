@extends('layouts.app')

@section('title', 'Edit Task: ' . $task->title . ' • TBM Enterprise')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Edit Operational Task</h1>
            <p class="text-sm text-secondary mt-0.5">Modify specifications, timeline, or currency-linked budget for #{{ $task->id }}.</p>
        </div>
        <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Tasks</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Task Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" required value="{{ old('title', $task->title) }}" class="w-full h-11 px-4 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Scope / Description</label>
                <textarea name="description" id="description" rows="3" class="w-full p-4 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="project_name" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Project / Stream <span class="text-danger">*</span></label>
                    <input type="text" name="project_name" id="project_name" required value="{{ old('project_name', $task->project_name) }}" class="w-full h-11 px-4 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                </div>

                <div>
                    <label for="client_name" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Client / Business Entity</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $task->client_name) }}" class="w-full h-11 px-4 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="priority" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Priority</label>
                    <select name="priority" id="priority" class="w-full h-11 px-3 rounded-lg border border-border-quiet bg-surface text-sm text-text-rich focus:ring-1 focus:ring-primary outline-none">
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('priority', $task->priority) === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Status</label>
                    <select name="status" id="status" class="w-full h-11 px-3 rounded-lg border border-border-quiet bg-surface text-sm text-text-rich focus:ring-1 focus:ring-primary outline-none">
                        <option value="backlog" {{ old('status', $task->status) === 'backlog' ? 'selected' : '' }}>Backlog</option>
                        <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="review" {{ old('status', $task->status) === 'review' ? 'selected' : '' }}>In Review</option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div>
                    <label for="due_date" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Due Date</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="w-full h-11 px-3 rounded-lg border border-border-quiet bg-surface text-sm text-text-rich focus:ring-1 focus:ring-primary outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-border-quiet">
                <div>
                    <label for="price" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">
                        Budget / Fee (Base USD $) <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-bold text-secondary">$</span>
                        <input type="number" step="0.01" name="price" id="price" required value="{{ old('price', number_format($task->price_cents / 100, 2, '.', '')) }}" class="w-full h-11 pl-8 pr-4 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm font-numeric text-text-rich outline-none">
                    </div>
                    <p class="text-[11px] text-secondary mt-1">Converts to: <strong>{{ $currencyService->format($task->price_cents) }}</strong> in active session currency.</p>
                </div>

                <div>
                    <label for="assigned_user" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Assigned Personnel</label>
                    <input type="text" name="assigned_user" id="assigned_user" value="{{ old('assigned_user', $task->assigned_user) }}" class="w-full h-11 px-4 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border-quiet">
                <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-secondary hover:bg-bg-subtle text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
