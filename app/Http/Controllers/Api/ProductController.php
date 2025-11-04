<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all active products
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc');

        // Filter by featured if provided
        if ($request->has('featured') && $request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Get a single product by ID
     */
    public function show($id)
    {
        $product = Product::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Get featured products
     */
    public function featured(Request $request)
    {
        $limit = $request->get('limit', 5);
        
        $products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}







