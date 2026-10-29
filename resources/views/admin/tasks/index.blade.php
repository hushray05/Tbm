@extends('layouts.admin')

@section('title', 'Tasks Management CRUD • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Tasks Management CRUD</h1>
            <p class="text-sm text-secondary mt-0.5">Create, Read, Update, and Delete operations for enterprise task entities.</p>
        </div>
        <a href="{{ route('admin.tasks.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            <span>Create New Task</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-surface border border-border-quiet rounded-xl p-4 tbm-shadow flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form action="{{ route('admin.tasks.index') }}" method="GET" class="relative flex-1 max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by title, project, assignee..." class="w-full h-9 pl-9 pr-3 rounded-lg border border-border-quiet bg-bg-subtle focus:bg-surface focus:border-primary text-xs text-text-rich outline-none">
        </form>

        <div class="flex items-center gap-2 text-xs text-secondary">
            <span>Total Records: <strong class="text-text-rich font-numeric">{{ $tasks->total() }}</strong></span>
        </div>
    </div>

    <!-- Tasks Data Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">ID</th>
                        <th class="px-4 py-3.5">Title & Deliverables</th>
                        <th class="px-4 py-3.5">Project Stream</th>
                        <th class="px-4 py-3.5">Priority</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Budget (USD / Converted)</th>
                        <th class="px-4 py-3.5 text-center">CRUD Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($tasks as $t)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5 font-numeric text-secondary">#{{ $t->id }}</td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <span class="font-bold text-text-rich block truncate">{{ $t->title }}</span>
                                <span class="text-[11px] text-secondary">Assignee: {{ $t->assigned_user ?? 'Unassigned' }}</span>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-text-rich">{{ $t->project_name }}</td>
                            <td class="px-4 py-3.5">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $t->priority === 'critical' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $t->priority }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $t->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-numeric font-bold text-primary">
                                {{ $currencyService->format($t->price_cents) }}
                                <span class="block text-[10px] text-secondary font-normal font-numeric">(${{ number_format($t->price_cents / 100, 2) }} USD)</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.tasks.edit', $t) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors" title="Edit Item">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>

                                    <form action="{{ route('admin.tasks.destroy', $t) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-danger hover:bg-red-50 transition-colors" title="Delete Item">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-secondary">
                                No task entities found.
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
