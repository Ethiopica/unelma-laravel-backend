<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Contact Form Submission

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (login form)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // User Management
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

        // Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::post('/sendMail', [ContactMessageController::class, 'sendMail'])->name('contact-messages.reply');
        Route::post('/contact-messages/{message}/read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.read');
        Route::delete('/contact-messages/{message}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
    });
});

// Only for testing Frontend Part. Remove a tthe end
Route::view('/sendmessage', 'TestApi.index');
Route::view('/mail', 'mail.mail'); // Just for mail template
Route::view('/mailuser', 'mail.user'); // Just for mail template
