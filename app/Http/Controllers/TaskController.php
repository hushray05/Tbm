<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $priority = $request->query('priority');

        $query = Task::query();

        if ($status && in_array($status, ['backlog', 'in_progress', 'review', 'completed'])) {
            $query->where('status', $status);
        }

        if ($priority && in_array($priority, ['low', 'medium', 'high', 'critical'])) {
            $query->where('priority', $priority);
        }

        $tasks = $query->latest()->paginate(12);

        $metrics = [
            'total' => Task::count(),
            'backlog' => Task::where('status', 'backlog')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'review' => Task::where('status', 'review')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'total_budget_cents' => Task::sum('price_cents'),
        ];

        return view('tasks.index', compact('tasks', 'metrics', 'status', 'priority'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_name' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:backlog,in_progress,review,completed',
            'price' => 'required|numeric|min:0', // in standard dollars/units
            'due_date' => 'nullable|date',
            'assigned_user' => 'nullable|string|max:255',
            'client_name' => 'nullable|string|max:255',
        ]);

        // Convert standard price to base integer cents
        $validated['price_cents'] = (int) round($validated['price'] * 100);
        unset($validated['price']);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task has been successfully provisioned and added to the board.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): View
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
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

        return redirect()->route('tasks.index')->with('success', "Task '{$task->title}' was successfully updated.");
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $title = $task->title;
        $task->delete();

        return redirect()->route('tasks.index')->with('success', "Task '{$title}' was permanently removed.");
    }

    /**
     * Quick status update endpoint.
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:backlog,in_progress,review,completed',
        ]);

        $task->update(['status' => $request->input('status')]);

        return back()->with('success', "Task status changed to " . ucfirst(str_replace('_', ' ', $task->status)));
    }
}
