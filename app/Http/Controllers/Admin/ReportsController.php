<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index()
    {
        // Get user registration statistics
        $userStats = $this->getUserStatistics();
        
        // Get activity data
        $recentUsers = User::latest()->take(10)->get();
        
        // Get monthly user growth
        $monthlyGrowth = $this->getMonthlyUserGrowth();
        
        return view('admin.reports.index', compact('userStats', 'recentUsers', 'monthlyGrowth'));
    }

    /**
     * Get user statistics
     */
    private function getUserStatistics()
    {
        return [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'regular_users' => User::where('is_admin', false)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'users_with_pictures' => User::whereNotNull('profile_picture')->count(),
            'today_registrations' => User::whereDate('created_at', today())->count(),
            'this_week_registrations' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_registrations' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];
    }

    /**
     * Get monthly user growth data
     */
    private function getMonthlyUserGrowth()
    {
        return User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get()
        ->reverse();
    }

    /**
     * Export reports (future implementation)
     */
    public function export(Request $request)
    {
        // This could be implemented to export reports as CSV/PDF
        return redirect()
            ->route('admin.reports.index')
            ->with('info', 'Export functionality coming soon!');
    }
}
