@extends('layouts.admin')

@section('title', 'Support Tickets CRUD • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Support Tickets CRUD</h1>
            <p class="text-sm text-secondary mt-0.5">Enterprise incident management and technical desk tickets.</p>
        </div>
        <a href="{{ route('admin.tickets.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            <span>Create Ticket</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Ticket #</th>
                        <th class="px-4 py-3.5">Issue Summary</th>
                        <th class="px-4 py-3.5">Requester</th>
                        <th class="px-4 py-3.5">Priority</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5">Assigned Agent</th>
                        <th class="px-4 py-3.5 text-center">CRUD Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($tickets as $t)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5 font-bold font-numeric text-primary">{{ $t->ticket_number }}</td>
                            <td class="px-4 py-3.5 max-w-xs font-semibold text-text-rich truncate">{{ $t->title }}</td>
                            <td class="px-4 py-3.5">
                                <span class="font-semibold text-text-rich block">{{ $t->requester_name }}</span>
                                <span class="text-secondary text-[11px]">{{ $t->requester_email ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $t->priority === 'urgent' || $t->priority === 'high' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $t->priority }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase {{ $t->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $t->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-secondary">{{ $t->assigned_agent ?? 'Unassigned' }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.tickets.edit', $t) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="{{ route('admin.tickets.destroy', $t) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded text-secondary hover:text-danger hover:bg-red-50 transition-colors">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-secondary">No support tickets recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
