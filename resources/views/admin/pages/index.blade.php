@extends('layouts.admin')

@section('title', 'Manage Pages • TBM Admin')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Public Pages</h1>
            <p class="text-sm text-secondary mt-0.5">Manage content for all public-facing pages on the website.</p>
        </div>
    </div>

    <!-- Pages Table -->
    <div class="bg-surface border border-border-quiet rounded-xl tbm-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-bg-subtle border-b border-border-quiet text-[11px] font-bold text-secondary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">Page Title</th>
                        <th class="px-4 py-3.5">Slug</th>
                        <th class="px-4 py-3.5">Meta Description</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Last Updated</th>
                        <th class="px-4 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-quiet font-sans">
                    @forelse($pages as $page)
                        <tr class="hover:bg-bg-subtle/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <span class="font-semibold text-text-rich">{{ $page->title }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <code class="px-2 py-0.5 rounded bg-bg-subtle border border-border-quiet text-[10px] font-mono text-secondary">/{{ $page->slug }}</code>
                            </td>
                            <td class="px-4 py-3.5 text-secondary truncate max-w-xs">{{ $page->meta_description ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase {{ $page->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $page->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center text-secondary font-numeric">
                                {{ $page->updated_at ? $page->updated_at->format('d M Y H:i') : '—' }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white text-xs font-bold transition-all">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                    <span>Edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-secondary">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2">article</span>
                                <p class="text-sm font-bold text-text-rich">No pages found</p>
                                <p class="text-xs mt-1">Run the page seeder to create default public pages.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
