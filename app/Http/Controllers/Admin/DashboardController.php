<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'regular_users' => User::where(function($query) {
                $query->where('is_admin', false)
                      ->orWhereNull('is_admin');
            })->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}








