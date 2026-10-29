@extends('layouts.admin')

@section('title', 'Platform Notifications • Super Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-primary">Platform Notifications & Activity</h1>
            <p class="text-xs text-secondary mt-1">Review global platform activities, logs, and system alerts.</p>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-surface rounded-2xl border border-border-quiet tbm-shadow overflow-hidden">
        <div class="divide-y divide-border-quiet">
            @forelse($notifications as $notification)
                <div class="p-4 flex gap-4 hover:bg-bg-subtle transition-colors">
                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">
                            {{ str_contains(strtolower($notification->action), 'delete') ? 'delete' : (str_contains(strtolower($notification->action), 'create') ? 'add_circle' : 'admin_panel_settings') }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="text-sm font-bold text-text-rich">{{ $notification->action }}</h4>
                            <span class="text-[10px] text-secondary font-semibold whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-secondary mt-1">{{ $notification->description }}</p>
                        @if($notification->user)
                        <div class="mt-2 flex items-center gap-1.5 text-[10px] text-secondary">
                            <span class="material-symbols-outlined text-[14px]">person</span>
                            <span>{{ $notification->user->name }}</span>
                            @if($notification->user->business)
                            <span class="px-1.5 py-0.5 bg-bg-subtle border border-border-quiet rounded text-[9px]">{{ $notification->user->business->name }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-secondary">
                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">notifications_off</span>
                    <h3 class="text-sm font-bold text-text-rich mb-1">No notifications</h3>
                    <p class="text-xs">You are all caught up! There is no recent platform activity.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
