<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminFeedbackController extends Controller
{
    /**
     * Display all user feedbacks in admin console.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $query = Feedback::with('user', 'business');

        if ($status) {
            $query->where('status', $status);
        }

        $feedbacks = $query->latest('id')->paginate(15)->withQueryString();
        $totalCount = Feedback::count();
        $pendingCount = Feedback::where('status', 'pending')->count();
        $reviewedCount = Feedback::where('status', 'reviewed')->count();
        $resolvedCount = Feedback::where('status', 'resolved')->count();

        return view('admin.feedback.index', compact(
            'feedbacks',
            'status',
            'totalCount',
            'pendingCount',
            'reviewedCount',
            'resolvedCount'
        ));
    }

    /**
     * Update status and add admin resolution notes.
     */
    public function updateStatus(Request $request, Feedback $feedback): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $feedback->update($validated);

        return back()->with('success', "Feedback #{$feedback->id} marked as " . ucfirst($validated['status']) . ".");
    }

    /**
     * Delete feedback.
     */
    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();
        return back()->with('success', 'Feedback record deleted.');
    }
}
