<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProductRatingController extends Controller
{
    /**
     * Submit or update a product rating
     */
    public function rate(Request $request): JsonResponse
    {
        try {
            // Log full request for debugging
            Log::info('=== RATING REQUEST RECEIVED ===', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'all_input' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'has_auth' => $request->header('Authorization') ? true : false,
                'user' => $request->user() ? $request->user()->id : 'not authenticated',
            ]);

            // Accept both camelCase and snake_case for flexibility
            $productId = $request->input('productId') ?? $request->input('product_id');
            
            if (!$productId) {
                Log::warning('Rating failed: No product ID', ['input' => $request->all()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required',
                    'errors' => ['productId' => ['The product ID field is required.']],
                    'debug' => [
                        'received_input' => $request->all(),
                        'tip' => 'Send productId or product_id in request body'
                    ]
                ], 422);
            }

// Also accept rating as string and convert
            $ratingValue = $request->input('rating');
            if (is_string($ratingValue)) {
                $ratingValue = (int) $ratingValue;
            }
            
            $request->merge(['rating' => $ratingValue]);

            

            $validated = $request->validate([
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'feedback' => ['nullable', 'string', 'max:5000'],
            ]);

            // Check if product exists
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                ], 401);
            }

            // Check if user has purchased this product (optional - uncomment to enable)
            if (!$user->hasPurchasedProduct($product)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only rate products you have purchased.',
                    'error' => 'purchase_required',
                ], 403);
            }

            Log::info('Rating submission attempt', [
                'user_id' => $user->id,
                'product_id' => $productId,
                'rating' => $validated['rating'],
            ]);

            // Create or update rating
            $productRating = ProductRating::updateOrCreate(
                [
                    'product_id' => $productId,
                    'user_id' => $user->id,
                ],
                [
                    'rating' => $validated['rating'],
                    'feedback' => $validated['feedback'] ?? null,
                ]
            );

            // Update product average rating
            $product->updateAverageRating();
            $product->refresh();

            // Load user info
            $productRating->load('user:id,name,profile_picture');

            Log::info('Rating submitted successfully', [
                'rating_id' => $productRating->id,
                'product_rating' => $product->rating,
                'product_rating_count' => $product->rating_count,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully!',
                'data' => [
                    'rating' => $productRating,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'rating' => round($product->rating ?? 0, 2),
                        'rating_count' => $product->rating_count ?? 0,
                    ],
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Rating submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit rating. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get all ratings for a product
     */
    public function index($productId): JsonResponse
    {
        try {
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }
            
            $ratings = ProductRating::where('product_id', $productId)
                ->with('user:id,name,profile_picture')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'average_rating' => round($product->rating ?? 0, 2),
                    'rating_count' => $product->rating_count ?? 0,
                    'ratings' => $ratings,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch ratings', [
                'product_id' => $productId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch ratings',
            ], 500);
        }
    }

    /**
     * Get the current user's rating for a product
     */
    public function show(Request $request, $productId): JsonResponse
    {
        try {
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                ], 401);
            }

            $rating = ProductRating::where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->with('user:id,name,profile_picture')
                ->first();

            if (!$rating) {
                return response()->json([
                    'success' => true,
                    'message' => 'You have not rated this product yet',
                    'data' => null,
                    'hasRated' => false,
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $rating,
                'hasRated' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch user rating', [
                'product_id' => $productId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch your rating',
            ], 500);
        }
    }

    /**
     * Delete the current user's rating for a product
     */
    public function destroy(Request $request, $productId): JsonResponse
    {
        try {
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                ], 401);
            }

            $rating = ProductRating::where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$rating) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating not found',
                ], 404);
            }

            $rating->delete();

            // Update product average rating
            $product->updateAverageRating();
            $product->refresh();

            Log::info('Rating deleted', [
                'user_id' => $user->id,
                'product_id' => $productId,
                'new_average' => $product->rating,
                'new_count' => $product->rating_count,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rating deleted successfully',
                'data' => [
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'rating' => round($product->rating ?? 0, 2),
                        'rating_count' => $product->rating_count ?? 0,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete rating', [
                'product_id' => $productId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete rating',
            ], 500);
        }
    }
}




















