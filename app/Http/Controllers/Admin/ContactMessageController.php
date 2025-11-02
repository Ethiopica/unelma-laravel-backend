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
        // dd($messages);

        return view('admin.contact-messages.index', compact('messages'));
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

    //Send Mail here to that Email 
    public function sendMail(Request $request)
    {

        $request->validate([
            'reply' => 'required',
            'email' => 'required|email',
        ]);
        $data = Mail::to($request->email)->send(new ReplyToMessage($request->reply));
        if ($data) {
            return redirect()
                ->route('admin.contact-messages.index')
                ->with('success', 'Mail Sent successfully');
        }
    }
}
