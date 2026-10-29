<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTaskController extends Controller
{
    /**
     * Admin Tasks List.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Task::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('assigned_user', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $tasks = $query->latest()->paginate(15);

        return view('admin.tasks.index', compact('tasks', 'search', 'status'));
    }

    /**
     * Admin Create Task Form.
     */
    public function create(): View
    {
        return view('admin.tasks.create');
    }

    /**
     * Admin Store Task.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'project_name' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:backlog,in_progress,review,completed',
            'price' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'assigned_user' => 'nullable|string|max:255',
            'client_name' => 'nullable|string|max:255',
        ]);

        $validated['price_cents'] = (int) round($validated['price'] * 100);
        unset($validated['price']);

        Task::create($validated);

        return redirect()->route('admin.tasks.index')->with('success', 'Enterprise task created successfully.');
    }

    /**
     * Admin Edit Task Form.
     */
    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Admin Update Task.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'project_name' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:backlog,in_progress,review,completed',
            'price' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'assigned_user' => 'nullable|string|max:255',
            'client_name' => 'nullable|string|max:255',
        ]);

        $validated['price_cents'] = (int) round($validated['price'] * 100);
        unset($validated['price']);

        $task->update($validated);

        return redirect()->route('admin.tasks.index')->with('success', "Task #{$task->id} '{$task->title}' updated successfully.");
    }

    /**
     * Admin Destroy Task.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $title = $task->title;
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', "Task '{$title}' has been permanently deleted.");
    }
}
