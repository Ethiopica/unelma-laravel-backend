<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductRatingController extends Controller
{
    /**
     * Submit or update a product rating
     *
     * Request body:
     * {
     *   "productId": 1,
     *   "rating": 4,        // Required (1-5)
     *   "feedback": "..."   // Optional
     * }
     *
     * Note: Only users who have purchased the product can rate it
     */
    public function rate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'productId' => ['required', 'integer', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        $productId = $validated['productId'];
        $product = Product::findOrFail($productId);

        // Check if user has purchased this product
        // if (!$user->hasPurchasedProduct($product)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You can only rate products you have purchased.',
        //         'error' => 'purchase_required',
        //     ], 403);
        // }

        // Check if user already has a rating for this product
        $existingRating = ProductRating::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        $isUpdate = $existingRating !== null;

        // Create or update the user's rating for this product
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

        // Recalculate product's average rating and count
        $product->updateAverageRating();
        $product->refresh();

        // Load user info (name, profile_picture) with the rating
        $productRating->load('user:id,name,profile_picture');

        return response()->json([
            'success' => true,
            'message' => $isUpdate ? 'Rating updated successfully!' : 'Rating submitted successfully!',
            'is_update' => $isUpdate,
            'data' => [
                'rating' => $productRating,
                'product' => $product,
            ],
        ]);
    }

    /**
     * Get all ratings for a product with user info
     */
    public function getProductRatings(int $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);

        $ratings = ProductRating::where('product_id', $productId)
            ->with('user:id,name,profile_picture')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'rating' => $product->rating,             // Average rating
                'rating_count' => $product->rating_count, // Amount of users who rated
                'ratings' => $ratings,                    // Individual ratings with user info
            ],
        ]);
    }

    /**
     * Get current user's rating for a product
     * Also returns whether the user can rate (has purchased) the product
     */
    public function getUserRating(int $productId): JsonResponse
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $rating = ProductRating::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        $hasPurchased = $user->hasPurchasedProduct($product);

        return response()->json([
            'success' => true,
            'data' => $rating,
            'can_rate' => $hasPurchased,
            'has_purchased' => $hasPurchased,
        ]);
    }

    /**
     * Delete current user's rating for a product
     */
    public function deleteUserRating(int $productId): JsonResponse
    {
        $user = Auth::user();

        $rating = ProductRating::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        if (!$rating) {
            return response()->json([
                'success' => false,
                'message' => 'Rating not found.',
            ], 404);
        }

        $product = $rating->product;
        $rating->delete();

        // Recalculate product's average rating and count
        if ($product) {
            $product->updateAverageRating();
            $product->refresh();
        }

        return response()->json([
            'success' => true,
            'message' => 'Rating deleted successfully!',
            'data' => [
                'product' => $product,
            ],
        ]);
    }
}
