<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController as ApiBlogController;
use App\Http\Controllers\Api\CarrerController as ApiCarrerController;
use App\Http\Controllers\Api\ContactController as ApiContactController;
use App\Http\Controllers\Api\ContactMessageController as ApiContactMessageController;
use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\ServiceController as ApiServiceController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/stripe/checkout/session', [StripeController::class, 'createCheckoutSession']);
});
// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'google']);

// Public Blog Routes
Route::get('/blogs', [ApiBlogController::class, 'index']);
Route::get('/blogs/{slug}', [ApiBlogController::class, 'show']);
Route::get('/blogs/categories/list', [ApiBlogController::class, 'categories']);
Route::get('/blogs/recent/list', [ApiBlogController::class, 'recent']);
Route::get('/blogs/popular/list', [ApiBlogController::class, 'popular']);

// Public Product Routes
Route::get('/products', [ApiProductController::class, 'index']);
Route::get('/products/{id}', [ApiProductController::class, 'show']);
Route::get('/products/featured/list', [ApiProductController::class, 'featured']);

// Public Page Routes
Route::get('/pages', [ApiPageController::class, 'index']);
Route::get('/pages/{slug}', [ApiPageController::class, 'show']); // Get any page by slug (home, about, services, products, contact)
Route::get('/services/page', [ApiPageController::class, 'services']); // Convenience route for services CMS page

// Public Service Routes (Service Management)
Route::get('/services', [ApiServiceController::class, 'index']);
Route::get('/services/{id}', [ApiServiceController::class, 'show']);
Route::get('/services/featured/list', [ApiServiceController::class, 'featured']);

// Public Contact Form Route
Route::post('/contact/submit', [ApiContactController::class, 'submit']);

// Public Vacancy Routes
Route::get('/vacancies', [ApiCarrerController::class, 'index']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // User Profile Management
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::put('/profile', [UserProfileController::class, 'update']);
    Route::post('/profile/change-password', [UserProfileController::class, 'changePassword']);
    Route::delete('/profile', [UserProfileController::class, 'destroy']);
    Route::get('/profile/activity', [UserProfileController::class, 'activity']);
    Route::get('/profile/subscriptions', [UserProfileController::class, 'subscriptions']);
    // Handle successful checkout
    Route::get('/checkout/success', function () {
        return 'Subscription successful!';
    })->name('checkout.success');

    Route::get('/checkout/cancel', function () {
        return 'Subscription canceled.';
    })->name('checkout.cancel');

    // Contact Messages Management (Admin only)
    Route::prefix('contact-messages')->group(function () {
        Route::get('/', [ApiContactMessageController::class, 'index']);
        Route::get('/stats', [ApiContactMessageController::class, 'stats']);
        Route::get('/{message}', [ApiContactMessageController::class, 'show']);
        Route::post('/{message}/read', [ApiContactMessageController::class, 'markAsRead']);
        Route::delete('/{message}', [ApiContactMessageController::class, 'destroy']);
    });
});
