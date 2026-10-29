@extends('layouts.admin')

@section('title', 'Admin Edit Task #' . $task->id . ' • TBM Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">Admin Edit Task #{{ $task->id }}</h1>
            <p class="text-xs text-secondary mt-0.5">Update operational task record in database.</p>
        </div>
        <a href="{{ route('admin.tasks.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to CRUD Table</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow">
        <form action="{{ route('admin.tasks.update', $task) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Task Title <span class="text-danger">*</span></label>
                <input type="text" name="title" required value="{{ old('title', $task->title) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Project Stream <span class="text-danger">*</span></label>
                    <input type="text" name="project_name" required value="{{ old('project_name', $task->project_name) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Client Entity</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $task->client_name) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Priority</label>
                    <select name="priority" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ $task->priority === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Status</label>
                    <select name="status" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                        <option value="backlog" {{ $task->status === 'backlog' ? 'selected' : '' }}>Backlog</option>
                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Due Date</label>
                    <input type="date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" class="w-full h-10 px-2 rounded-lg border border-border-quiet bg-surface text-xs text-text-rich outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-border-quiet">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Price / Budget ($ USD) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" required value="{{ number_format($task->price_cents / 100, 2, '.', '') }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-numeric text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Assigned Specialist</label>
                    <input type="text" name="assigned_user" value="{{ $task->assigned_user }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Task Visual Banner / Attachment URL</label>
                <div class="flex gap-2 items-center">
                    @if($task->image_url)
                        <img src="{{ asset($task->image_url) }}" alt="Preview" class="w-9 h-9 rounded-lg object-cover border border-border-quiet shrink-0">
                    @endif
                    <input type="text" name="image_url" value="{{ old('image_url', $task->image_url) }}" placeholder="/images/products/cloud-node.jpg or https://..." class="flex-1 h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-border-quiet">
                <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 border border-border-quiet rounded-lg text-xs font-semibold text-secondary">Cancel</a>
                <button type="submit" class="bg-primary text-white font-bold text-xs px-5 py-2 rounded-lg shadow-sm hover:bg-primary-container">Update Task</button>
            </div>
        </form>
    </div>
</div>
@endsection
