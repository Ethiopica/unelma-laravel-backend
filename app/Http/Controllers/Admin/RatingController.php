<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductRating;
use App\Models\Product;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of all product ratings
     */
    public function index(Request $request)
    {
        $query = ProductRating::with(['product', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by product if specified
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by rating if specified
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        $ratings = $query->paginate(20);
        $products = Product::orderBy('name')->get();

        // Calculate stats
        $stats = [
            'total_ratings' => ProductRating::count(),
            'average_rating' => round(ProductRating::avg('rating') ?? 0, 1),
            'five_star' => ProductRating::where('rating', 5)->count(),
            'one_star' => ProductRating::where('rating', 1)->count(),
        ];

        return view('admin.ratings.index', compact('ratings', 'products', 'stats'));
    }

    /**
     * Delete a rating
     */
    public function destroy(ProductRating $rating)
    {
        $product = $rating->product;
        $rating->delete();

        // Update product's average rating
        $product->updateAverageRating();

        return redirect()
            ->route('admin.ratings.index')
            ->with('success', 'Rating deleted successfully!');
    }
}

































