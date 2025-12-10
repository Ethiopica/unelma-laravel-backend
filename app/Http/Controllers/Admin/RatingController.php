<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RatingController extends Controller
{
    /**
     * Display all product ratings with filters.
     */
    public function index(Request $request): View
    {
        $query = ProductRating::with(['product', 'user']);

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        // Filter by rating value
        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $ratings = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Get all products for the filter dropdown
        $products = Product::orderBy('name')->get(['id', 'name']);

        // Calculate stats
        $stats = [
            'total_ratings' => ProductRating::count(),
            'average_rating' => round(ProductRating::avg('rating') ?? 0, 2),
            'ratings_with_feedback' => ProductRating::whereNotNull('feedback')->where('feedback', '!=', '')->count(),
            'ratings_today' => ProductRating::whereDate('created_at', today())->count(),
        ];

        return view('admin.ratings.index', [
            'ratings' => $ratings,
            'products' => $products,
            'stats' => $stats,
            'filters' => [
                'product_id' => $request->input('product_id'),
                'rating' => $request->input('rating'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
            ],
        ]);
    }

    /**
     * Delete a rating.
     */
    public function destroy(ProductRating $rating)
    {
        $product = $rating->product;
        $rating->delete();

        // Recalculate product's average rating
        if ($product) {
            $product->updateAverageRating();
        }

        return redirect()
            ->route('admin.ratings.index')
            ->with('success', 'Rating deleted successfully.');
    }
}
