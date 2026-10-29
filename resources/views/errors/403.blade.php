@extends('layouts.app')

@section('title', '403 - Forbidden • TBM Enterprise')

@section('content')
<div class="max-w-xl mx-auto py-16 text-center space-y-6">
    <div class="w-20 h-20 rounded-2xl bg-amber-100 text-warning flex items-center justify-center mx-auto shadow-sm">
        <span class="material-symbols-outlined text-4xl">gpp_maybe</span>
    </div>
    <div class="space-y-2">
        <span class="text-xs font-bold text-warning uppercase tracking-widest">Error 403 • Access Restricted</span>
        <h1 class="font-headline text-3xl md:text-4xl font-bold text-text-rich">Administrative Clearance Required</h1>
        <p class="text-sm text-secondary">
            You do not possess the required executive role or multi-tenant permission to access this protected ledger module.
        </p>
    </div>
    <div class="pt-4 flex justify-center gap-3">
        <a href="{{ route('dashboard') }}" class="bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">home</span>
            <span>Return to Dashboard</span>
        </a>
    </div>
</div>
@endsection
