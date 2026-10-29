@extends('layouts.admin')

@section('title', 'User Management • Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">User & Access Management</h1>
            <p class="text-xs text-secondary mt-0.5">Manage all platform users.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-sm">person_add</span>
                <span>Add User</span>
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-surface border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle text-secondary uppercase text-[10px] font-bold border-b border-border-quiet">
                    <tr>
                        <th class="px-5 py-3.5">User</th>
                        <th class="px-5 py-3.5">Job Title / Dept</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5">Phone</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($users as $u)
                        <tr class="hover:bg-bg-subtle/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-900 border border-primary/20 shrink-0">
                                        <img src="{{ $u->avatar }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span class="font-bold text-text-rich text-xs block leading-tight">{{ $u->name }}</span>
                                        <span class="text-[11px] text-secondary">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-text-rich">
                                <span class="font-semibold block">{{ $u->job_title ?? 'Staff' }}</span>
                                <span class="text-[10px] text-secondary">{{ $u->department ?? 'Operations' }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $u->role === 'Business Owner' ? 'bg-purple-100 text-purple-800' : ($u->role === 'Accountant' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-numeric text-secondary">{{ $u->phone ?? 'N/A' }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $u->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $u) }}" class="p-1.5 rounded text-secondary hover:text-primary hover:bg-bg-subtle transition-colors">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="delete-form inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded text-secondary hover:text-danger hover:bg-red-50 transition-colors">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-secondary">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection