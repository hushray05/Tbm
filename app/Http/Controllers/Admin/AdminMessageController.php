<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminMessageController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function markAsRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => true]);
        return back()->with('success', 'Message marked as read.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Inquiry message removed.');
    }
}
