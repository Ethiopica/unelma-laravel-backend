<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductRatingController extends Controller
{
    /**
     * Get all ratings for a product
     */
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        
        $ratings = ProductRating::where('product_id', $productId)
            ->with('user:id,name,profile_picture')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'product_id' => $product->id,
            'average_rating' => round($product->rating ?? 0, 1),
            'total_ratings' => $ratings->count(),
            'ratings' => $ratings->map(function ($rating) {
                return [
                    'id' => $rating->id,
                    'rating' => $rating->rating,
                    'feedback' => $rating->feedback,
                    'user' => $rating->user ? [
                        'id' => $rating->user->id,
                        'name' => $rating->user->name,
                        'profile_picture' => $rating->user->profile_picture,
                    ] : null,
                    'created_at' => $rating->created_at->toISOString(),
                ];
            }),
        ]);
    }

    /**
     * Store a new rating or update existing one
     */
    public function store(Request $request, $productId)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $product = Product::findOrFail($productId);
        $user = $request->user();

        // Create or update the rating
        $rating = ProductRating::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $user->id,
            ],
            [
                'rating' => $validated['rating'],
                'feedback' => $validated['feedback'] ?? null,
            ]
        );

        // Update product's average rating
        $this->updateProductRating($product);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'rating' => [
                'id' => $rating->id,
                'rating' => $rating->rating,
                'feedback' => $rating->feedback,
                'created_at' => $rating->created_at->toISOString(),
            ],
            'product' => [
                'id' => $product->id,
                'average_rating' => round($product->fresh()->rating ?? 0, 1),
                'rating_count' => $product->fresh()->rating_count ?? 0,
            ],
        ], $rating->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Get the current user's rating for a product
     */
    public function show(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = $request->user();

        $rating = ProductRating::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$rating) {
            return response()->json([
                'message' => 'You have not rated this product yet',
                'rating' => null,
            ]);
        }

        return response()->json([
            'rating' => [
                'id' => $rating->id,
                'rating' => $rating->rating,
                'feedback' => $rating->feedback,
                'created_at' => $rating->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * Delete the current user's rating for a product
     */
    public function destroy(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = $request->user();

        $rating = ProductRating::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$rating) {
            return response()->json([
                'message' => 'Rating not found',
            ], 404);
        }

        $rating->delete();

        // Update product's average rating
        $this->updateProductRating($product);

        return response()->json([
            'message' => 'Rating deleted successfully',
            'product' => [
                'id' => $product->id,
                'average_rating' => round($product->fresh()->rating ?? 0, 1),
                'rating_count' => $product->fresh()->rating_count ?? 0,
            ],
        ]);
    }

    /**
     * Update the product's average rating and count
     */
    private function updateProductRating(Product $product): void
    {
        $stats = ProductRating::where('product_id', $product->id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as count')
            ->first();

        $product->update([
            'rating' => $stats->avg_rating ?? 0,
            'rating_count' => $stats->count ?? 0,
        ]);
    }
}
