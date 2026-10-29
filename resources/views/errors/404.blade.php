@extends('layouts.app')

@section('title', '404 - Page Not Found • TBM Enterprise')

@section('content')
<div class="max-w-xl mx-auto py-16 text-center space-y-6">
    <div class="w-20 h-20 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto shadow-sm">
        <span class="material-symbols-outlined text-4xl">search_off</span>
    </div>
    <div class="space-y-2">
        <span class="text-xs font-bold text-primary uppercase tracking-widest">Error 404 • Resource Not Located</span>
        <h1 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Page Not Found</h1>
        <p class="text-sm text-secondary">
            The requested task ledger page, billing statement, or administrative view does not exist or has been relocated.
        </p>
    </div>
    <div class="pt-4 flex justify-center gap-3">
        <a href="{{ route('dashboard') }}" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">home</span>
            <span>Return to Dashboard</span>
        </a>
        <a href="{{ route('contact') }}" class="bg-surface hover:bg-bg-subtle border border-border-quiet text-text-rich px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">contact_support</span>
            <span>Contact Support</span>
        </a>
    </div>
</div>
@endsection
