<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'customers' => User::where(function ($query) {
                $query->where('is_admin', false)
                    ->orWhereNull('is_admin');
            })->count(),
            'unread_messages' => ContactMessage::where(function ($query) {
                $query->where('is_read', false)->orWhereNull('is_read');
            })->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
