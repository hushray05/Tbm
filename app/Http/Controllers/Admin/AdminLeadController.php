<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLeadController extends Controller
{
    public function index(): View
    {
        $leads = Lead::latest()->paginate(15);
        return view('admin.leads.index', compact('leads'));
    }

    public function create(): View
    {
        return view('admin.leads.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'avatar_url' => 'nullable|string|max:500',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:new,contacted,qualified,proposal,won,lost',
            'estimated_value' => 'required|numeric|min:0',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $validated['estimated_value_cents'] = (int) round($validated['estimated_value'] * 100);
        unset($validated['estimated_value']);

        Lead::create($validated);

        return redirect()->route('admin.leads.index')->with('success', 'CRM Lead added to pipeline successfully.');
    }

    public function edit(Lead $lead): View
    {
        return view('admin.leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'avatar_url' => 'nullable|string|max:500',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:new,contacted,qualified,proposal,won,lost',
            'estimated_value' => 'required|numeric|min:0',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $validated['estimated_value_cents'] = (int) round($validated['estimated_value'] * 100);
        unset($validated['estimated_value']);

        $lead->update($validated);

        return redirect()->route('admin.leads.index')->with('success', "Lead for '{$lead->name}' updated.");
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $name = $lead->name;
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', "Lead '{$name}' deleted.");
    }
}
