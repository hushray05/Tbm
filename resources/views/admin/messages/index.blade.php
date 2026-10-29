@extends('layouts.admin')

@section('title', 'Inquiries & Contact Messages • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div>
        <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Inquiries & Contact Messages</h1>
        <p class="text-sm text-secondary mt-0.5">Customer contact requests from the enterprise support portal.</p>
    </div>

    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">From</th>
                        <th class="px-4 py-3.5">Subject</th>
                        <th class="px-4 py-3.5">Message Excerpt</th>
                        <th class="px-4 py-3.5">Date</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-bg-subtle/60 transition-colors {{ !$msg->is_read ? 'bg-primary/5 font-semibold' : '' }}">
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-text-rich block">{{ $msg->name }}</span>
                                <span class="text-secondary text-[11px]">{{ $msg->email }}</span>
                                @if($msg->phone)<span class="text-[10px] text-outline block">{{ $msg->phone }}</span>@endif
                            </td>
                            <td class="px-4 py-3.5 font-bold text-text-rich">{{ $msg->subject }}</td>
                            <td class="px-4 py-3.5 text-secondary max-w-xs truncate">{{ $msg->message }}</td>
                            <td class="px-4 py-3.5 text-secondary font-numeric">{{ $msg->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3.5">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase {{ $msg->is_read ? 'bg-slate-100 text-slate-700' : 'bg-primary text-white' }}">
                                    {{ $msg->is_read ? 'Read' : 'New' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if(!$msg->is_read)
                                        <form action="{{ route('admin.messages.read', $msg) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-primary font-bold hover:underline">Mark Read</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" class="delete-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded text-secondary hover:text-danger">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-secondary">
                                No contact messages submitted yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="p-4 border-t border-border-quiet bg-bg-subtle">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
