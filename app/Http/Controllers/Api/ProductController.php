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
        try {
            $query = Product::where('is_active', true)
                ->orderBy('order', 'asc')
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

            // Transform products to ensure image_url always has a valid URL
            // Priority: full_image_url > image_local_url > existing image_url > null
            $transformedProducts = collect($products->items())->map(function ($product) {
                $productArray = $product->toArray();
                // Use full_image_url first (generates correct Supabase URLs)
                // Falls back to image_local_url if full_image_url is not available
                // Falls back to existing image_url field as last resort
                $productArray['image_url'] = $product->full_image_url ?? $product->image_local_url ?? $productArray['image_url'] ?? null;
                return $productArray;
            });

            return response()->json([
                'success' => true,
                'data' => $transformedProducts,
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Products API Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch products',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'file' => config('app.debug') ? $e->getFile() : null,
                'line' => config('app.debug') ? $e->getLine() : null,
            ], 500);
        }
    }

    /**
     * Get a single product by ID
     */
    public function show($id)
    {
        $product = Product::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $productData = $product->toArray();
        // Use full_image_url first (generates correct Supabase URLs)
        // Falls back to image_local_url if full_image_url is not available
        // Falls back to existing image_url field as last resort
        $productData['image_url'] = $product->full_image_url ?? $product->image_local_url ?? $productData['image_url'] ?? null;

        return response()->json([
            'success' => true,
            'data' => $productData,
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

        // Transform products to ensure image_url always has a valid URL
        // Priority: full_image_url > image_local_url > existing image_url > null
        $transformedProducts = $products->map(function ($product) {
            $productArray = $product->toArray();
            // Use full_image_url first (generates correct Supabase URLs)
            // Falls back to image_local_url if full_image_url is not available
            // Falls back to existing image_url field as last resort
            $productArray['image_url'] = $product->full_image_url ?? $product->image_local_url ?? $productArray['image_url'] ?? null;
            return $productArray;
        });

        return response()->json([
            'success' => true,
            'data' => $transformedProducts,
        ]);
    }
}
