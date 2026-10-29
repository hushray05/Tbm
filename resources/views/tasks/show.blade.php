@extends('layouts.app')

@section('title', $task->title . ' • TBM Task Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-secondary uppercase tracking-wider">Task #{{ $task->id }}</span>
                <span>•</span>
                <span class="text-xs text-primary font-semibold">{{ $task->project_name }}</span>
            </div>
            <h1 class="font-headline text-2xl font-bold text-text-rich">{{ $task->title }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tasks.edit', $task) }}" class="bg-surface hover:bg-bg-subtle text-primary border border-border-quiet px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">edit</span>
                <span>Edit Task</span>
            </a>
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="task">
                <input type="hidden" name="id" value="{{ $task->id }}">
                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">shopping_cart_checkout</span>
                    <span>Add to Billing Queue</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Details Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Main Info -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow space-y-4">
                <h3 class="font-headline font-bold text-text-rich text-sm uppercase tracking-wider border-b border-border-quiet pb-2">
                    Scope of Deliverables
                </h3>
                <p class="text-sm text-on-surface leading-relaxed whitespace-pre-line">
                    {{ $task->description ?: 'No detailed scope provided for this task.' }}
                </p>

                <div class="pt-4 border-t border-border-quiet grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-secondary block">Assigned Specialist:</span>
                        <strong class="text-text-rich text-sm">{{ $task->assigned_user ?? 'Unassigned' }}</strong>
                    </div>
                    <div>
                        <span class="text-secondary block">Client / Organization:</span>
                        <strong class="text-text-rich text-sm">{{ $task->client_name ?? 'Internal Enterprise' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Col: Financial Meta -->
        <div class="space-y-6">
            <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow space-y-4">
                <h3 class="font-headline font-bold text-text-rich text-sm uppercase tracking-wider border-b border-border-quiet pb-2">
                    Budget & Status
                </h3>

                <div>
                    <span class="text-xs text-secondary block">Dynamic Budget Value</span>
                    <div class="font-headline text-2xl font-bold text-primary font-numeric mt-1">
                        {{ $currencyService->format($task->price_cents) }}
                    </div>
                    <span class="text-[11px] text-outline block mt-0.5">Base: ${{ number_format($task->price_cents / 100, 2) }} USD</span>
                </div>

                <div class="pt-3 border-t border-border-quiet space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-secondary">Status:</span>
                        <span class="font-bold uppercase px-2 py-0.5 rounded-full bg-primary/10 text-primary">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-secondary">Priority:</span>
                        <span class="font-bold uppercase px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                            {{ $task->priority }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-secondary">Target Completion:</span>
                        <span class="font-semibold text-text-rich">
                            {{ $task->due_date ? $task->due_date->format('M d, Y') : 'Open Ended' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
