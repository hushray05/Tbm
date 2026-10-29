@extends('layouts.admin')

@section('title', 'CRM Leads CRUD • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">CRM Leads & Commercial Pipeline CRUD</h1>
            <p class="text-sm text-secondary mt-0.5">Manage customer acquisitions, pipeline values, and assigned accounts.</p>
        </div>
        <a href="{{ route('admin.leads.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
            <span class="material-symbols-outlined text-sm">add</span>
            <span>Add New Lead</span>
        </a>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Lead Name</th>
                        <th class="px-4 py-3.5">Company / Entity</th>
                        <th class="px-4 py-3.5">Contact Details</th>
                        <th class="px-4 py-3.5">Stage</th>
                        <th class="px-4 py-3.5">Account Owner</th>
                        <th class="px-4 py-3.5 text-right">Est. Value (Converted)</th>
                        <th class="px-4 py-3.5 text-center">CRUD Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-900 border border-border-quiet shadow-sm shrink-0">
                                        <img src="{{ asset($lead->avatar) }}" alt="{{ $lead->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-bold text-text-rich">{{ $lead->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-text-rich">{{ $lead->company ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-secondary">
                                <span>{{ $lead->email }}</span>
                                @if($lead->phone)<span class="block text-[10px]">{{ $lead->phone }}</span>@endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase bg-primary/10 text-primary">
                                    {{ $lead->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-text-rich">{{ $lead->assigned_to ?? 'General Pool' }}</td>
                            <td class="px-4 py-3.5 text-right font-numeric font-bold text-primary">
                                {{ $currencyService->format($lead->estimated_value_cents) }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.leads.edit', $lead) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" class="delete-form inline">
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
                            <td colspan="7" class="px-4 py-12 text-center text-secondary">No leads recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
