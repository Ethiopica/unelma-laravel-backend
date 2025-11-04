<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController as ApiBlogController;
use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\ServiceController as ApiServiceController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Google OAuth routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

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
});
