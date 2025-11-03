<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// COntact form submisstion
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

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
