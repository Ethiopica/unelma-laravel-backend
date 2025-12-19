<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MailSubscriberController;
use App\Http\Controllers\Admin\FavoriteController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StripeController as AdminStripeController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\VerifyUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Default login route - redirects to admin login
// This is required for Laravel's auth middleware
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

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

    // Send verification email (user clicks "Verify Email" button)
    Route::get('send-verification-email', [VerifyUserController::class, 'sendVerificationEmail'])->name('verify.send');
    
    // Confirm email verification (user clicks link in email)
    Route::get('verify-user/{link}/confirm', [VerifyUserController::class, 'confirmUser'])->name('verify.confirm');

    // Payment subscription route (Stripe), not newsletter subscription (Unelma Mail)
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
});

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


        // comments for blog
        Route::get('/blogs/{blog}/comments', [CommentController::class, 'showByBlog'])->name('blogs.comments');

        // Delete a comment
        Route::delete('/admin/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

        // Product Management
        Route::resource('products', AdminProductController::class)->except(['show']);

        // Services Management
        Route::resource('services', ServicesController::class)->except(['show']);

        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::delete('/payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        Route::get('/subscribers', [MailSubscriberController::class, 'index'])->name('subscribers.index');
        Route::delete('/subscribers/{subscriberUid}', [MailSubscriberController::class, 'destroy'])->name('subscribers.destroy');
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
        Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');

        // Job Management
        Route::resource('careers', CareerController::class)->except(['show']);

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

        // Stripe Management
        Route::prefix('stripe')->name('stripe.')->group(function () {
            Route::get('/prices', [AdminStripeController::class, 'getPrices'])->name('prices');
            Route::get('/products', [AdminStripeController::class, 'getProducts'])->name('products');
            Route::post('/create-product-price', [AdminStripeController::class, 'createProductWithPrice'])->name('create-product-price');
            Route::post('/validate-price', [AdminStripeController::class, 'validatePriceId'])->name('validate-price');
        });
    });
});

// Email preview routes (for development/testing)
Route::view('/user-register-email', 'mail.user');
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

// Alternative webhook route for Stripe CLI/Shell compatibility
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook.alternative');

// GET routes for webhook endpoint verification
Route::get('/stripe/webhook', function () {
    return response()->json([
        'message' => 'Stripe webhook endpoint ready. Use POST for event delivery.',
    ]);
});

Route::get('/webhook/stripe', function () {
    return response()->json([
        'message' => 'Stripe webhook endpoint ready. Use POST for event delivery.',
    ]);
});

Route::get('/sitemap.xml', [SitemapController::class, 'index']);