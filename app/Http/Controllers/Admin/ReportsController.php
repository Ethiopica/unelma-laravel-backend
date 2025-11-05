<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Service;
use App\Models\Page;
use App\Models\ContactMessage;
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
            'regular_users' => User::where(function($query) {
                $query->where('is_admin', false)
                      ->orWhereNull('is_admin');
            })->count(),
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
     * Export reports as CSV
     */
    public function export(Request $request)
    {
        $filename = 'system_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            
            // Write BOM for UTF-8 to ensure Excel opens it correctly
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Section
            fputcsv($handle, ['SYSTEM REPORT']);
            fputcsv($handle, ['Generated on: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, []); // Empty row
            
            // Summary Statistics
            $userStats = $this->getUserStatistics();
            fputcsv($handle, ['=== SUMMARY STATISTICS ===']);
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Total Users', $userStats['total_users']]);
            fputcsv($handle, ['Admin Users', $userStats['admin_users']]);
            fputcsv($handle, ['Regular Users', $userStats['regular_users']]);
            fputcsv($handle, ['Verified Users', $userStats['verified_users']]);
            fputcsv($handle, ['Users with Profile Pictures', $userStats['users_with_pictures']]);
            fputcsv($handle, ['Today Registrations', $userStats['today_registrations']]);
            fputcsv($handle, ['This Week Registrations', $userStats['this_week_registrations']]);
            fputcsv($handle, ['This Month Registrations', $userStats['this_month_registrations']]);
            fputcsv($handle, []); // Empty row
            
            // Content Statistics
            fputcsv($handle, ['=== CONTENT STATISTICS ===']);
            fputcsv($handle, ['Content Type', 'Count']);
            fputcsv($handle, ['Total Blogs', Blog::count()]);
            fputcsv($handle, ['Total Products', Product::count()]);
            fputcsv($handle, ['Total Services', Service::count()]);
            fputcsv($handle, ['Total Pages', Page::count()]);
            fputcsv($handle, ['Total Contact Messages', ContactMessage::count()]);
            fputcsv($handle, []); // Empty row
            
            // User Details
            fputcsv($handle, ['=== USER DETAILS ===']);
            $users = User::orderBy('created_at', 'desc')->get();
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Email Verified', 'Profile Picture', 'Created At']);
            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->is_admin ? 'Admin' : 'User',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->profile_picture ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fputcsv($handle, []); // Empty row
            
            // Monthly Growth
            $monthlyGrowth = $this->getMonthlyUserGrowth();
            fputcsv($handle, ['=== MONTHLY USER GROWTH ===']);
            fputcsv($handle, ['Month', 'New Users']);
            foreach ($monthlyGrowth as $month) {
                $monthName = \Carbon\Carbon::parse($month->month . '-01')->format('F Y');
                fputcsv($handle, [$monthName, $month->count]);
            }
            fputcsv($handle, []); // Empty row
            
            // Recent Blogs
            fputcsv($handle, ['=== RECENT BLOGS (Last 10) ===']);
            $recentBlogs = Blog::with('author')->latest()->take(10)->get();
            fputcsv($handle, ['ID', 'Title', 'Author', 'Category', 'Published', 'Views', 'Created At']);
            foreach ($recentBlogs as $blog) {
                fputcsv($handle, [
                    $blog->id,
                    $blog->title,
                    $blog->author ? $blog->author->name : 'N/A',
                    $blog->category ?? 'N/A',
                    $blog->is_published ? 'Yes' : 'No',
                    $blog->views ?? 0,
                    $blog->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fputcsv($handle, []); // Empty row
            
            // Recent Contact Messages
            fputcsv($handle, ['=== RECENT CONTACT MESSAGES (Last 10) ===']);
            $recentMessages = ContactMessage::latest()->take(10)->get();
            fputcsv($handle, ['ID', 'Name', 'Email', 'Read', 'IP Address', 'Created At']);
            foreach ($recentMessages as $message) {
                fputcsv($handle, [
                    $message->id,
                    $message->name,
                    $message->email,
                    $message->is_read ? 'Yes' : 'No',
                    $message->ip_address ?? 'N/A',
                    $message->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
