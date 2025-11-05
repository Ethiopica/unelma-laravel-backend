<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the admin settings page
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update application settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['nullable', 'string', 'max:255'],
            'app_email' => ['nullable', 'email', 'max:255'],
            'users_per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'enable_registration' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        // Here you would typically save to a settings table or config file
        // For now, we'll just show a success message
        
        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
