<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController as ApiBlogController;

use App\Http\Controllers\Api\BlogCommentController;


use App\Http\Controllers\Api\CareerController as ApiCareerController;
use App\Http\Controllers\Api\CommentController as ApiCommentController;
use App\Http\Controllers\Api\ContactController as ApiContactController;
use App\Http\Controllers\Api\ContactMessageController as ApiContactMessageController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\ProductRatingController;
use App\Http\Controllers\Api\ServiceController as ApiServiceController;
use App\Http\Controllers\Api\PaymentSubscriptionController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health check endpoint - no database required
Route::get('/health', function () {
    $dbStatus = 'not checked';
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }
    
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'environment' => app()->environment(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'database' => $dbStatus,
        'timestamp' => now()->toISOString(),
    ]);
});

// Debug database config - REMOVE AFTER DEBUGGING
Route::get('/debug-db', function () {
    return response()->json([
        'env_connection' => env('DB_CONNECTION'),
        'env_host' => env('DB_HOST'),
        'env_port' => env('DB_PORT'),
        'env_database' => env('DB_DATABASE'),
        'env_username' => env('DB_USERNAME'),
        'env_password_set' => env('DB_PASSWORD') ? 'YES' : 'NO',
        'env_url_set' => env('DB_URL') ? 'YES' : 'NO',
        'config_default' => config('database.default'),
        'config_host' => config('database.connections.mysql.host'),
        'config_port' => config('database.connections.mysql.port'),
        'config_database' => config('database.connections.mysql.database'),
        'config_username' => config('database.connections.mysql.username'),
    ]);
});

// Check if migrations ran
Route::get('/debug-tables', function () {
    try {
        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        return response()->json([
            'status' => 'ok',
            'tables' => $tables,
            'count' => count($tables),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
});

Route::middleware('auth:sanctum')->group(function () {
    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites', [FavoriteController::class, 'destroy']);
    Route::post('/stripe/checkout/session', [StripeController::class, 'createCheckoutSession']);
});
// Stripe webhook - must be public and excluded from CSRF (handled in bootstrap/app.php)
// Accessible at /api/stripe/webhook
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook.api');

// Debug endpoint to test Stripe connection (remove in production)
Route::get('/stripe/test', function () {
    try {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $account = $stripe->accounts->retrieve('self');
        
        return response()->json([
            'success' => true,
            'message' => 'Stripe connection successful',
            'account_id' => $account->id ?? 'unknown',
            'webhook_configured' => !empty(config('services.stripe.webhook_secret')),
            'environment' => app()->environment(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Stripe connection failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Alternative webhook route for compatibility
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook.api.alternative');

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'google']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);

// Public Blog Routes
Route::get('/blogs', [ApiBlogController::class, 'index']);
Route::get('/blogs/{id}/comments', [ApiCommentController::class, 'index']); // Must come before /blogs/{id} to avoid route conflict
Route::get('/blogs/{id}', [ApiBlogController::class, 'show']);
Route::get('/blogs/{slug}', [ApiBlogController::class, 'showBySlug']);
Route::get('/blogs/categories/list', [ApiBlogController::class, 'categories']);
Route::get('/blogs/recent/list', [ApiBlogController::class, 'recent']);
Route::get('/blogs/popular/list', [ApiBlogController::class, 'popular']);
Route::get('/blogs/latest', [ApiBlogController::class, 'latest']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/blogs/{id}/comments', [ApiCommentController::class, 'store']);

    // Product Ratings (authenticated)
    Route::post('/products/rate', [ProductRatingController::class, 'rate']);
    Route::put('/products/rate', [ProductRatingController::class, 'rate']);  // Also allow PUT for updates
    Route::patch('/products/rate', [ProductRatingController::class, 'rate']); // Also allow PATCH for updates
    Route::get('/products/{productId}/ratings/mine', [ProductRatingController::class, 'show']);
    Route::delete('/products/{productId}/ratings', [ProductRatingController::class, 'destroy']);
});

// Public Product Routes
Route::get('/products', [ApiProductController::class, 'index']);
Route::get('/products/{id}', [ApiProductController::class, 'show']);
Route::get('/products/featured/list', [ApiProductController::class, 'featured']);

// Public Product Ratings (view ratings)
Route::get('/products/{productId}/ratings', [ProductRatingController::class, 'index']);

// Debug endpoint to test rating (remove in production)
Route::post('/products/{productId}/ratings/test', function (Request $request, $productId) {
    return response()->json([
        'success' => true,
        'message' => 'Rating endpoint is reachable',
        'received' => [
            'productId' => $productId,
            'body' => $request->all(),
            'headers' => [
                'content_type' => $request->header('Content-Type'),
                'authorization' => $request->header('Authorization') ? 'present' : 'missing',
            ],
        ],
    ]);
});

// Public Page Routes
Route::get('/pages', [ApiPageController::class, 'index']);
Route::get('/pages/{slug}', [ApiPageController::class, 'show']); // Get any page by slug (home, about, services, products, contact)
Route::get('/services/page', [ApiPageController::class, 'services']); // Convenience route for services CMS page

// Public Service Routes (Service Management)
Route::get('/services', [ApiServiceController::class, 'index']);
Route::get('/services/{id}', [ApiServiceController::class, 'show']);
Route::get('/services/featured/list', [ApiServiceController::class, 'featured']);

// Public Payment Subscription Options Routes (Get available payment subscription options with price IDs)
// Note: These are for payment subscriptions (Stripe), not newsletter/email subscriptions (Unelma Mail)
Route::get('/subscriptions/options', [PaymentSubscriptionController::class, 'options']);
Route::get('/subscriptions/{type}/{id}', [PaymentSubscriptionController::class, 'show']); // type: product or plan

// Public Contact Form Routes
Route::post('/contact/submit', [ApiContactController::class, 'submit']);
Route::post('/contact', [ApiContactController::class, 'submit']); // Alias for frontend compatibility

// Public Vacancy Routes
Route::get('/vacancies', [ApiCareerController::class, 'index']);


Route::post('/vacancies', [ApiCareerController::class, 'apply']);



// Handle successful checkout - process subscription if webhook didn't
// Note: This route works without auth by finding user from Stripe session
Route::get('/checkout/success', [StripeController::class, 'handleCheckoutSuccess'])
    ->name('checkout.success');

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
    Route::get('/profile/purchases', [UserProfileController::class, 'purchases']);
    // Alternative routes for purchases (for frontend compatibility)
    Route::get('/purchases', [UserProfileController::class, 'purchases']);
    Route::get('/user/purchases', [UserProfileController::class, 'purchases']);

    Route::get('/checkout/cancel', function () {
        return 'Subscription canceled.';
    })->name('checkout.cancel');


    // Payment Subscription Management
    // Note: These are for payment subscriptions (Stripe), not newsletter/email subscriptions (Unelma Mail)
    Route::get('/subscriptions', function (Request $request) {
        $subscriptions = $request->user()->subscriptions()->orderByDesc('created_at')->get()->map(function ($subscription) {
            return [
                'id' => $subscription->id,
                'name' => $subscription->name,
                'stripe_id' => $subscription->stripe_id,
                'status' => $subscription->stripe_status,
                'price_id' => $subscription->stripe_price,
                'quantity' => $subscription->quantity,
                'amount' => $subscription->amount,
                'payment_type' => 'subscription', // Always subscription for this endpoint
                'trial_ends_at' => $subscription->trial_ends_at?->toISOString(),
                'ends_at' => $subscription->ends_at?->toISOString(),
                'created_at' => $subscription->created_at->toISOString(),
                'updated_at' => $subscription->updated_at->toISOString(),
            ];
        });
        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
            'has_active_subscription' => $request->user()->subscriptions()->whereIn('stripe_status', ['active', 'trialing'])->exists(),
        ]);
    });

    Route::delete('/subscriptions/{id}', function (Request $request, $id) {
        $subscription = $request->user()->subscriptions()->findOrFail($id);
        $subscription->cancel();
        return response()->json(['message' => 'Subscription canceled successfully']);
    });



    // Contact Messages Management (Admin only)
    Route::prefix('contact-messages')->group(function () {
        Route::get('/', [ApiContactMessageController::class, 'index']);
        Route::get('/stats', [ApiContactMessageController::class, 'stats']);
        Route::get('/{message}', [ApiContactMessageController::class, 'show']);
        Route::post('/{message}/read', [ApiContactMessageController::class, 'markAsRead']);
        Route::delete('/{message}', [ApiContactMessageController::class, 'destroy']);
    });
});
