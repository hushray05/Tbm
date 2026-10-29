<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTicketController extends Controller
{
    public function index(): View
    {
        $tickets = Ticket::latest()->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $nextTicketNum = 'TCK-' . rand(1000, 9999);
        return view('admin.tickets.create', compact('nextTicketNum'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_number' => 'required|string|unique:tickets,ticket_number',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'nullable|email|max:255',
            'assigned_agent' => 'nullable|string|max:255',
        ]);

        Ticket::create($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Support ticket logged.');
    }

    public function edit(Ticket $ticket): View
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'nullable|email|max:255',
            'assigned_agent' => 'nullable|string|max:255',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('success', "Ticket {$ticket->ticket_number} updated.");
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $num = $ticket->ticket_number;
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', "Ticket {$num} removed.");
    }
}
