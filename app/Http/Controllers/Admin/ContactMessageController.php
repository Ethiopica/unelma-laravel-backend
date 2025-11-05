<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReplyToMessage;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);

        $stats = [
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where(function ($q) {
                $q->where('is_read', false)->orWhereNull('is_read');
            })->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    /**
     * Mark message as read
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read');
    }

    /**
     * Delete message
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully');
    }

    /**
     * Send a reply email to a contact message
     */
    public function reply(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'reply' => ['required', 'string'],
        ]);

        Mail::to($validated['email'])->send(new ReplyToMessage($validated['reply']));

        return back()->with('success', 'Reply sent successfully');
    }
}
