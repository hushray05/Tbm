@extends('layouts.admin')

@section('title', 'User Feedback & Support Inquiries • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">User Feedback & Inquiries</h1>
            <p class="text-sm text-secondary mt-1">Review feedback, feature requests, and support messages submitted by business users.</p>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a href="{{ route('admin.feedback.index') }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ empty($status) ? 'bg-primary text-white' : 'bg-surface border border-border-quiet text-secondary hover:bg-bg-subtle' }}">
            All Submissions ({{ $totalCount }})
        </a>
        <a href="{{ route('admin.feedback.index', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ $status === 'pending' ? 'bg-amber-600 text-white' : 'bg-surface border border-border-quiet text-secondary hover:bg-bg-subtle' }}">
            Pending Review ({{ $pendingCount }})
        </a>
        <a href="{{ route('admin.feedback.index', ['status' => 'reviewed']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ $status === 'reviewed' ? 'bg-blue-600 text-white' : 'bg-surface border border-border-quiet text-secondary hover:bg-bg-subtle' }}">
            Reviewed ({{ $reviewedCount }})
        </a>
        <a href="{{ route('admin.feedback.index', ['status' => 'resolved']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ $status === 'resolved' ? 'bg-emerald-600 text-white' : 'bg-surface border border-border-quiet text-secondary hover:bg-bg-subtle' }}">
            Resolved ({{ $resolvedCount }})
        </a>
    </div>

    <!-- Feedback List Card -->
    <div class="bg-surface border border-border-quiet rounded-xl shadow-xs overflow-hidden">
        <div class="divide-y divide-border-quiet">
            @forelse($feedbacks as $fb)
                <div class="p-5 hover:bg-bg-subtle/40 transition-colors space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $fb->status_badge }}">
                                {{ $fb->status }}
                            </span>
                            <span class="text-xs font-bold uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded">
                                {{ $fb->category }}
                            </span>
                            <h3 class="font-bold text-sm text-text-rich">{{ $fb->subject }}</h3>
                        </div>
                        <span class="text-xs text-secondary font-numeric">{{ $fb->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <p class="text-xs text-text-rich leading-relaxed whitespace-pre-line bg-bg-subtle p-3 rounded-lg border border-border-quiet">
                        {{ $fb->message }}
                    </p>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs border-t border-border-quiet pt-3">
                        <div class="flex items-center gap-2 text-secondary">
                            <span class="material-symbols-outlined text-sm">person</span>
                            <span>From: <strong>{{ $fb->user_name }}</strong> ({{ $fb->user_email }})</span>
                            @if($fb->business)
                                <span>• Organization: <strong>{{ $fb->business->name }}</strong></span>
                            @endif
                        </div>

                        <!-- Status Update Form -->
                        <form action="{{ route('admin.feedback.status', $fb) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="text" name="admin_notes" value="{{ $fb->admin_notes }}" placeholder="Resolution remark..." class="h-8 px-2.5 bg-bg-subtle border border-border-quiet rounded text-xs text-text-rich outline-none">
                            <select name="status" class="h-8 px-2 bg-bg-subtle border border-border-quiet rounded text-xs font-semibold text-text-rich outline-none">
                                <option value="pending" {{ $fb->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ $fb->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="resolved" {{ $fb->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                            <button type="submit" class="h-8 px-3 rounded bg-primary text-white font-bold text-xs shadow-2xs">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center text-secondary">
                    <span class="material-symbols-outlined text-5xl text-outline mb-2">feedback</span>
                    <p class="text-sm font-bold text-text-rich">No feedback submissions found</p>
                    <p class="text-xs mt-1">User inquiries and feature suggestions will appear here.</p>
                </div>
            @endforelse
        </div>

        @if($feedbacks->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
