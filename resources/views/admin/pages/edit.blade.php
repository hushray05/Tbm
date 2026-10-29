@extends('layouts.admin')

@section('title', 'Edit Page: ' . $page->title . ' • TBM Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-secondary mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.pages.index') }}" class="hover:text-primary">Pages</a>
                <span>/</span>
                <span class="text-primary">{{ $page->title }}</span>
            </div>
            <h1 class="font-headline text-2xl md:text-3xl font-bold text-text-rich">Edit: {{ $page->title }}</h1>
            <p class="text-sm text-secondary mt-0.5">Update content for the public <code class="px-1.5 py-0.5 rounded bg-bg-subtle border border-border-quiet text-[10px] font-mono">/{{ $page->slug }}</code> page.</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="text-xs font-semibold text-secondary hover:text-primary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Back to Pages</span>
        </a>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Page Details Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow space-y-4">
            <h3 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">settings</span>
                <span>Page Settings</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Page Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $page->title) }}" class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase mb-1">Slug (URL)</label>
                    <input type="text" value="{{ $page->slug }}" disabled class="w-full h-10 px-3 rounded-lg border border-border-quiet bg-surface text-xs font-mono text-secondary cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs text-text-rich outline-none focus:border-primary" placeholder="Brief description for SEO...">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} class="w-4 h-4 text-primary border-border-quiet rounded focus:ring-primary">
                <label for="is_active" class="text-xs font-semibold text-text-rich">Active (visible on public site)</label>
            </div>
        </div>

        <!-- Content Editor Card -->
        <div class="bg-surface border border-border-quiet rounded-xl p-6 tbm-shadow space-y-4">
            <h3 class="text-sm font-bold text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit_note</span>
                <span>Page Content (JSON)</span>
            </h3>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase mb-1">Content Data</label>
                <p class="text-[10px] text-secondary mb-2">Store page-specific content as JSON. This can be accessed in views via <code class="px-1 py-0.5 rounded bg-bg-subtle border border-border-quiet font-mono">$page->content</code></p>
                <textarea name="content" rows="15" class="w-full p-3 rounded-lg border border-border-quiet bg-bg-subtle text-xs font-mono text-text-rich outline-none focus:border-primary" placeholder='{"hero_title": "...", "hero_text": "...", "sections": [...]}'>{{ is_array($page->content) ? json_encode($page->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : old('content', $page->content) }}</textarea>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.pages.index') }}" class="px-5 py-2.5 rounded-lg border border-border-quiet text-xs font-bold text-secondary hover:bg-bg-subtle">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-container text-white text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-base">save</span>
                <span>Save Changes</span>
            </button>
        </div>
    </form>
</div>
@endsection
