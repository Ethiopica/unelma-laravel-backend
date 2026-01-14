<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Get the storage disk for uploads.
     * Uses S3 in production (Railway), local in development.
     */
    protected function getUploadDisk(): string
    {
        // Use environment variable to determine disk, fallback to 'public' for local dev
        return config('filesystems.default') === 'local' ? 'public' : config('filesystems.default');
    }

    /**
     * Display a listing of products
     */
    public function index()
    {
        $products = Product::orderBy('order')->orderBy('created_at', 'desc')->get();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['string', 'max:255'],
            'sku' => ['required', 'string', 'max:255'],
            'highlights' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stripe_price_id' => ['required', 'string', 'max:255'],
            'payment_type' => ['nullable', 'string', 'in:subscription,one_time'],
            'rating' => ['nullable','numeric', 'min:0','max:5'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'image_url' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Handle image upload - use configurable disk (S3 for production)
        if ($request->hasFile('image')) {
            $disk = $this->getUploadDisk();
            $imagePath = $request->file('image')->store('products', $disk);
            $validated['image'] = $imagePath;
            
            // Log upload for debugging
            \Log::info('Product image uploaded', [
                'disk' => $disk,
                'path' => $imagePath,
                'url' => Storage::disk($disk)->url($imagePath),
            ]);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['category'] = $validated['category'] ?? 'uncategorized';


        $product = Product::create($validated);
        
        // Refresh the model to ensure appended attributes are computed
        $product->refresh();
        
        // Clear cache to ensure fresh data
        \Cache::forget('products_list');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing a product
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['string', 'max:255'],
            'sku' => ['required', 'string', 'max:255'],
            'highlights' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stripe_price_id' => ['required', 'string', 'max:255'],
            'payment_type' => ['nullable', 'string', 'in:subscription,one_time'],
            'rating' => ['nullable','numeric', 'min:0','max:5'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'image_url' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Handle image upload - use configurable disk (S3 for production)
        if ($request->hasFile('image')) {
            $disk = $this->getUploadDisk();
            // Delete old image if exists
            if ($product->image) {
                Storage::disk($disk)->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', $disk);
            $validated['image'] = $imagePath;
            
            // Log upload for debugging
            \Log::info('Product image updated', [
                'product_id' => $product->id,
                'disk' => $disk,
                'path' => $imagePath,
                'url' => Storage::disk($disk)->url($imagePath),
            ]);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);
        
        // Refresh the model to ensure appended attributes are computed
        $product->refresh();
        
        // Clear cache to ensure fresh data
        \Cache::forget('products_list');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            $disk = $this->getUploadDisk();
            Storage::disk($disk)->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
