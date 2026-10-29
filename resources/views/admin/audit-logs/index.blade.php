@extends('layouts.admin')

@section('title', 'Platform Audit Logs • Super Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">System Audit Trail & Log Ledger</h1>
            <p class="text-xs text-secondary mt-0.5">Immutable audit record of all administrative operations, trial extensions, plan changes, and security overrides.</p>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">Timestamp</th>
                        <th class="px-5 py-3.5">Administrator</th>
                        <th class="px-5 py-3.5">Action Executed</th>
                        <th class="px-5 py-3.5">Target Entity</th>
                        <th class="px-5 py-3.5">Operation Description</th>
                        <th class="px-5 py-3.5">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($logs as $log)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5 font-numeric text-secondary whitespace-nowrap">
                                {{ $log->created_at ? $log->created_at->format('d M Y H:i:s') : 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5 font-bold text-text-rich whitespace-nowrap">
                                {{ $log->user->name ?? 'System Automated' }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-primary/10 text-primary">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-secondary whitespace-nowrap">
                                {{ $log->entity_type ? $log->entity_type . ' #' . $log->entity_id : '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-text-rich max-w-md">
                                {{ $log->description }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-secondary whitespace-nowrap">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-secondary">
                                No audit events logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-border-quiet">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
