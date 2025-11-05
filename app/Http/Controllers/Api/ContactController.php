<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Mail\ContactFormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Submit contact form message
     */
    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'message' => ['required', 'string', 'min:10', 'max:5000'],
            ]);

            // Store the contact message
            $message = ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'is_read' => false,
            ]);

            // Send email notification to admin
            try {
                $adminEmail = config('mail.from.address', env('MAIL_FROM_ADDRESS', 'admin@example.com'));
                Mail::to($adminEmail)->send(new ContactFormSubmitted($message));
            } catch (\Exception $e) {
                // Log email error but don't fail the request
                Log::error('Failed to send contact form email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you soon.',
                'data' => [
                    'id' => $message->id,
                    'name' => $message->name,
                    'email' => $message->email,
                    'created_at' => $message->created_at,
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Contact form submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your message. Please try again.',
            ], 500);
        }
    }
}





