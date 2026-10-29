<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Show the user feedback submission form.
     */
    public function create(): View
    {
        $user = auth()->user();
        $myFeedbacks = Feedback::where('user_id', $user->id)
            ->latest('id')
            ->take(10)
            ->get();

        return view('feedback.create', compact('user', 'myFeedbacks'));
    }

    /**
     * Store submitted user feedback.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|in:feature,bug,general,billing,support',
            'message' => 'required|string|max:3000',
        ]);

        $user = auth()->user();
        $business = $user->getActiveBusiness() ?? Business::first();

        Feedback::create([
            'user_id' => $user->id,
            'business_id' => $business ? $business->id : 1,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Thank you! Your feedback has been sent directly to the System Administrator.');
    }
}
