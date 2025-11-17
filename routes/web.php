<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CarrerController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifyUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('verify-user/{link?}', [VerifyUserController::class, 'verifyUser'])->name('verify.user');
    Route::get('verify-user/{link}/confirm', [VerifyUserController::class, 'confirmUser'])->name('verify.user');

    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
});

// Contact Form Submission
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

Route::view('/checkout/success', 'checkout.success')->name('checkout.success');
Route::view('/checkout/cancel', 'checkout.cancel')->name('checkout.cancel');

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

        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

        // Job Management
        Route::resource('carrers', CarrerController::class)->except(['show']);

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

// Delete at end not at beginning just to see
Route::view('/user-register-email', 'mail.user');
Route::view('/contactmessage-frontend', 'TestApi.index');
Route::view('/reply-message-mail', 'mail.mail');
Route::get('/checkout/success', function () {
    return 'Subscription successful!';
})->name('checkout.success');

Route::get('/checkout/cancel', function () {
    return 'Subscription canceled.';
})->name('checkout.cancel');
// Stripe webhook - excluded from CSRF protection in bootstrap/app.php
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');

Route::get('/stripe/webhook', function () {
    return response()->json([
        'message' => 'Stripe webhook endpoint ready. Use POST for event delivery.',
    ]);
});
