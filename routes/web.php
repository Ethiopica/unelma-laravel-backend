<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->intended('/');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Contact Form Submission
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

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

        // Blog Management - Use ID binding for admin routes (numeric = ID, otherwise slug)
        Route::bind('blog', function ($value) {
            // For admin routes, try ID first if value is numeric
            if (is_numeric($value)) {
                return \App\Models\Blog::where('id', $value)->firstOrFail();
            }

            // Fallback to slug for non-numeric values
            return \App\Models\Blog::where('slug', $value)->firstOrFail();
        });
        Route::resource('blogs', BlogController::class)->except(['show']);
        // Product Management
        Route::resource('products', AdminProductController::class)->except(['show']);

        // Services Management
        Route::resource('services', ServicesController::class)->except(['show']);

        // Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::post('/contact-messages/{message}/read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.read');
        Route::delete('/contact-messages/{message}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
        Route::post('/contact-messages/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

        // Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::post('/contact-messages/{message}/read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.read');
        Route::delete('/contact-messages/{message}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
    });
});
