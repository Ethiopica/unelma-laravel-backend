<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Get all contact messages (Admin only)
     */
    public function index(Request $request)
    {
        // Ensure user is admin
        if (! auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $query = ContactMessage::latest();

        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'read') {
                $query->where('is_read', true);
            } elseif ($request->status === 'unread') {
                $query->where('is_read', false);
            }
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $messages = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $messages->items(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
                'unread_count' => ContactMessage::where('is_read', false)->count(),
            ],
        ]);
    }

    /**
     * Get a single contact message (Admin only)
     */
    public function show(ContactMessage $message)
    {
        // Ensure user is admin
        if (! auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $message,
        ]);
    }

    /**
     * Mark message as read (Admin only)
     */
    public function markAsRead(ContactMessage $message)
    {
        // Ensure user is admin
        if (! auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $message->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read',
            'data' => $message,
        ]);
    }

    /**
     * Delete a contact message (Admin only)
     */
    public function destroy(ContactMessage $message)
    {
        // Ensure user is admin
        if (! auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully',
        ]);
    }

    /**
     * Get message statistics (Admin only)
     */
    public function stats()
    {
        // Ensure user is admin
        if (! auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_messages' => ContactMessage::count(),
                'unread_messages' => ContactMessage::where('is_read', false)->count(),
                'read_messages' => ContactMessage::where('is_read', true)->count(),
            ],
        ]);
    }
}
