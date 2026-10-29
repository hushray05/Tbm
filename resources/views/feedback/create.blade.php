@extends('layouts.app')

@section('title', 'Submit Feedback • Administrator Support')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-headline font-bold text-text-rich">Submit Feedback to Administrator</h1>
        <p class="text-xs text-secondary mt-0.5">Send feature requests, suggestions, bug reports, or inquiries directly to the system administrators.</p>
    </div>

    <!-- Feedback Submission Card -->
    <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
        <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">feedback</span>
            <span>New Feedback Message</span>
        </h2>

        <form action="{{ route('feedback.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Feedback Category *</label>
                    <select name="category" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-semibold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none">
                        <option value="feature">Feature Request / Suggestion</option>
                        <option value="bug">Bug Report / System Issue</option>
                        <option value="billing">Subscription & Billing Query</option>
                        <option value="general">General Feedback</option>
                        <option value="support">Help & Operational Support</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-rich mb-1">Subject *</label>
                    <input type="text" name="subject" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs font-bold text-text-rich focus:ring-2 focus:ring-primary focus:outline-none" placeholder="Brief summary of your feedback...">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-text-rich mb-1">Detailed Message *</label>
                <textarea name="message" rows="5" required class="w-full px-3 py-2 bg-bg-subtle border border-border-quiet rounded-lg text-xs text-text-rich focus:ring-2 focus:ring-primary focus:outline-none leading-relaxed" placeholder="Describe your request, problem, or recommendation in detail..."></textarea>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-border-quiet">
                <span class="text-[11px] text-secondary">Transmitted securely with user: <strong>{{ auth()->user()->email }}</strong></span>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">send</span>
                    <span>Send to Administrator</span>
                </button>
            </div>
        </form>
    </div>

    <!-- User's Previous Submissions -->
    @if(isset($myFeedbacks) && $myFeedbacks->count() > 0)
        <div class="bg-surface border border-border-quiet rounded-xl p-6 shadow-xs space-y-4">
            <h2 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">history</span>
                <span>My Past Submissions</span>
            </h2>

            <div class="divide-y divide-border-quiet">
                @foreach($myFeedbacks as $fb)
                    <div class="py-3 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-text-rich">{{ $fb->subject }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $fb->status_badge }}">
                                {{ $fb->status }}
                            </span>
                        </div>
                        <p class="text-xs text-secondary">{{ Str::limit($fb->message, 120) }}</p>
                        <div class="flex items-center gap-3 text-[10px] text-secondary">
                            <span>Sent on {{ $fb->created_at->format('d M Y, H:i') }}</span>
                            <span>•</span>
                            <span class="capitalize font-semibold text-primary">{{ $fb->category }}</span>
                            @if($fb->admin_notes)
                                <span>•</span>
                                <span class="text-emerald-700 font-bold">Admin response: {{ $fb->admin_notes }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
