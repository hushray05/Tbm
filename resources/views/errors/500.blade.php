@extends('layouts.app')

@section('title', '500 - Server Error • TBM Enterprise')

@section('content')
<div class="max-w-xl mx-auto py-16 text-center space-y-6">
    <div class="w-20 h-20 rounded-2xl bg-red-100 text-danger flex items-center justify-center mx-auto shadow-sm">
        <span class="material-symbols-outlined text-4xl">dns</span>
    </div>
    <div class="space-y-2">
        <span class="text-xs font-bold text-danger uppercase tracking-widest">Error 500 • Internal System Exception</span>
        <h1 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">System Encountered An Error</h1>
        <p class="text-sm text-secondary">
            Our multi-region enterprise server experienced a transient exception. The incident has been recorded in the security audit log.
        </p>
    </div>
    <div class="pt-4 flex justify-center gap-3">
        <a href="{{ route('dashboard') }}" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">refresh</span>
            <span>Reload Overview</span>
        </a>
    </div>
</div>
@endsection
