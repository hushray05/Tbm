@extends('layouts.app')

@section('title', 'Task Business Manager • TBM Enterprise Suite')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich tracking-tight">Task Business Manager</h1>
            <p class="text-sm text-secondary mt-1">Orchestrate enterprise workflows, resource allocations, and currency-aware budgets.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('tasks.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-sm transition-all">
                <span class="material-symbols-outlined text-sm">add</span>
                <span>Create New Task</span>
            </a>
        </div>
    </div>

    <!-- Status Filters Bar & Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <a href="{{ route('tasks.index') }}" class="p-3.5 rounded-xl border transition-all {{ empty($status) ? 'bg-surface border-primary ring-1 ring-primary tbm-shadow' : 'bg-surface border-border-quiet hover:border-outline-variant' }}">
            <span class="text-xs font-semibold text-secondary uppercase block">All Tasks</span>
            <span class="font-headline text-xl font-bold text-text-rich font-numeric">{{ $metrics['total'] }}</span>
        </a>
        <a href="{{ route('tasks.index', ['status' => 'backlog']) }}" class="p-3.5 rounded-xl border transition-all {{ $status === 'backlog' ? 'bg-surface border-primary ring-1 ring-primary tbm-shadow' : 'bg-surface border-border-quiet hover:border-outline-variant' }}">
            <span class="text-xs font-semibold text-slate-500 uppercase block">Backlog</span>
            <span class="font-headline text-xl font-bold text-text-rich font-numeric">{{ $metrics['backlog'] }}</span>
        </a>
        <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" class="p-3.5 rounded-xl border transition-all {{ $status === 'in_progress' ? 'bg-surface border-primary ring-1 ring-primary tbm-shadow' : 'bg-surface border-border-quiet hover:border-outline-variant' }}">
            <span class="text-xs font-semibold text-primary uppercase block">In Progress</span>
            <span class="font-headline text-xl font-bold text-primary font-numeric">{{ $metrics['in_progress'] }}</span>
        </a>
        <a href="{{ route('tasks.index', ['status' => 'review']) }}" class="p-3.5 rounded-xl border transition-all {{ $status === 'review' ? 'bg-surface border-primary ring-1 ring-primary tbm-shadow' : 'bg-surface border-border-quiet hover:border-outline-variant' }}">
            <span class="text-xs font-semibold text-purple-600 uppercase block">In Review</span>
            <span class="font-headline text-xl font-bold text-purple-700 font-numeric">{{ $metrics['review'] }}</span>
        </a>
        <a href="{{ route('tasks.index', ['status' => 'completed']) }}" class="p-3.5 rounded-xl border transition-all {{ $status === 'completed' ? 'bg-surface border-primary ring-1 ring-primary tbm-shadow' : 'bg-surface border-border-quiet hover:border-outline-variant' }}">
            <span class="text-xs font-semibold text-success uppercase block">Completed</span>
            <span class="font-headline text-xl font-bold text-success font-numeric">{{ $metrics['completed'] }}</span>
        </a>
    </div>

    <!-- Tasks Main Table Card -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="p-4 sm:p-5 border-b border-border-quiet flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-bg-subtle">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl fill">assignment</span>
                <span class="font-headline font-bold text-text-rich text-base">Operational Tasks Board</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-secondary">
                <span>Displaying budgets converted in <strong class="text-primary font-semibold">{{ $currentCurrency->code }} ({{ $currentCurrency->symbol }})</strong></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-bg-subtle/80 border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Task & Description</th>
                        <th class="px-4 py-3.5">Project / Team</th>
                        <th class="px-4 py-3.5">Priority</th>
                        <th class="px-4 py-3.5">Due Date</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Budget ({{ $currentCurrency->code }})</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-bg-subtle/70 transition-colors">
                            <td class="px-5 py-4">
                                <a href="{{ route('tasks.show', $task) }}" class="font-bold text-text-rich hover:text-primary transition-colors text-sm">
                                    {{ $task->title }}
                                </a>
                                <p class="text-xs text-secondary mt-0.5 line-clamp-1">{{ $task->description ?? 'No detailed description' }}</p>
                                <span class="text-[11px] text-outline mt-1 block">Lead: {{ $task->assigned_user ?? 'Unassigned' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-semibold text-text-rich block">{{ $task->project_name }}</span>
                                <span class="text-[11px] text-secondary">{{ $task->client_name ?? 'Internal Enterprise' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $pClass = match($task->priority) {
                                        'critical' => 'bg-red-100 text-red-700 border-red-200',
                                        'high' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $pClass }} uppercase">
                                    {{ $task->priority }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs font-numeric text-secondary">
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <form action="{{ route('tasks.status', $task) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded-md border border-border-quiet px-2 py-1 bg-surface focus:ring-1 focus:ring-primary outline-none cursor-pointer">
                                        <option value="backlog" {{ $task->status === 'backlog' ? 'selected' : '' }}>Backlog</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-right font-numeric font-bold text-primary">
                                {{ $currencyService->format($task->price_cents) }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Add to Queue / POS -->
                                    <form action="{{ route('cart.add') }}" method="POST" title="Add to Checkout Queue">
                                        @csrf
                                        <input type="hidden" name="type" value="task">
                                        <input type="hidden" name="id" value="{{ $task->id }}">
                                        <button type="submit" class="p-1.5 rounded-lg text-secondary hover:text-primary hover:bg-primary-fixed-dim/20 transition-colors">
                                            <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                                        </button>
                                    </form>

                                    <!-- Edit -->
                                    <a href="{{ route('tasks.edit', $task) }}" class="p-1.5 rounded-lg text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Edit Task">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>

                                    <!-- Delete (SweetAlert2 Intercepted) -->
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="delete-form" title="Delete Task">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-secondary hover:text-danger hover:bg-red-50 transition-colors">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-secondary">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <span class="material-symbols-outlined text-4xl text-outline">assignment_late</span>
                                    <p class="font-medium text-sm text-text-rich">No tasks found matching criteria</p>
                                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1.5 bg-primary text-white text-xs font-semibold px-3 py-1.5 rounded-lg">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                        Create New Task
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
