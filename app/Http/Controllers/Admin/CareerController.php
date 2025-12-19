<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        $careers = Career::all();

        return view('admin.careers.index', compact('careers'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.careers.create');
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => 'required'
        ]);

        $service = Career::create($validated);

        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job created successfully!');
    }

    /**
     * Show the form for editing a service
     */
    public function edit(Career $career)
    {
        return view('admin.careers.edit', compact('career'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Career $career)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => 'required',
        ]);

        $career->update($validated);

        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Career $career)
    {
        $career->delete();
        return redirect()
            ->route('admin.careers.index')
            ->with('success', 'Job deleted successfully!');
    }

    /**
     * Display a listing of job applicants
     */
    public function applicants()
    {
        $applicants = \App\Models\Career_Apply::with('career')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.applicants.index', compact('applicants'));
    }

    /**
     * Display a single applicant's details
     */
    public function showApplicant($id)
    {
        $applicant = \App\Models\Career_Apply::with(['career', 'replies.sender'])->findOrFail($id);

        return view('admin.applicants.show', compact('applicant'));
    }

    /**
     * Send a reply email to an applicant
     */
    public function replyApplicant(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => ['required', 'exists:career_applied,id'],
            'email' => ['required', 'email'],
            'reply' => ['required', 'string'],
        ]);

        // Send the email
        \Illuminate\Support\Facades\Mail::to($validated['email'])
            ->send(new \App\Mail\ReplyToMessage($validated['reply']));

        // Save the reply to database
        \App\Models\ApplicantReply::create([
            'career_apply_id' => $validated['applicant_id'],
            'user_id' => auth()->id(),
            'sent_to_email' => $validated['email'],
            'message' => $validated['reply'],
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Reply sent successfully to ' . $validated['email']);
    }
}
