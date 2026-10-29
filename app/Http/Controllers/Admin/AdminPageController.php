<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPageController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('slug')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if (!empty($validated['content'])) {
            $decoded = json_decode($validated['content'], true);
            $validated['content'] = json_last_error() === JSON_ERROR_NONE ? $decoded : $validated['content'];
        }

        $page->update($validated);

        AuditLog::record('update_page', "Updated page: {$page->title}", 'page', $page->id);

        return back()->with('success', "Page '{$page->title}' updated successfully.");
    }
}
