<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ServiceController extends Controller
{
    /**
     * Get all active services
     */
    public function index(Request $request)
    {
        try {
            $query = Service::where('is_active', true)
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc');

            if (Schema::hasTable('plans')) {
                $query->with('plans');
            }

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
            $services = $query->paginate($perPage);

            // Transform services to include consistent image URL
            // Preserves existing valid URLs, only enhances if needed
            $transformedServices = collect($services->items())->map(function ($service) {
                $serviceArray = $service->toArray();
                // Only enhance image_url if it's empty or not a full URL (preserve existing valid URLs)
                if (empty($serviceArray['image_url']) || !str_starts_with($serviceArray['image_url'] ?? '', 'http')) {
                    $serviceArray['image_url'] = $service->full_image_url ?? $service->image_local_url ?? $serviceArray['image_url'] ?? null;
                }
                // All image fields (image_url, image_local_url, full_image_url) are already included via appended attributes
                return $serviceArray;
            });

            return response()->json([
                'success' => true,
                'data' => $transformedServices,
                'meta' => [
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                    'per_page' => $services->perPage(),
                    'total' => $services->total(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Services API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch services',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Get a single service by ID
     */
    public function show($id)
    {
        $serviceQuery = Service::where('id', $id)
            ->where('is_active', true)
            ->orderByDesc('created_at');

        if (Schema::hasTable('plans')) {
            $serviceQuery->with('plans');
        }

        $service = $serviceQuery->firstOrFail();

        $serviceData = $service->toArray();
        // Only enhance image_url if it's empty or not a full URL (preserve existing valid URLs)
        if (empty($serviceData['image_url']) || !str_starts_with($serviceData['image_url'] ?? '', 'http')) {
            $serviceData['image_url'] = $service->full_image_url ?? $service->image_local_url ?? $serviceData['image_url'] ?? null;
        }
        // All image fields (image_url, image_local_url, full_image_url) are already included via appended attributes

        return response()->json([
            'success' => true,
            'data' => $serviceData,
        ]);
    }

    /**
     * Get featured services
     */
    public function featured(Request $request)
    {
        $limit = $request->get('limit', 5);

        $services = Service::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        // Transform services to include consistent image URL
        // Preserves existing valid URLs, only enhances if needed
        $transformedServices = $services->map(function ($service) {
            $serviceArray = $service->toArray();
            // Only enhance image_url if it's empty or not a full URL (preserve existing valid URLs)
            if (empty($serviceArray['image_url']) || !str_starts_with($serviceArray['image_url'] ?? '', 'http')) {
                $serviceArray['image_url'] = $service->full_image_url ?? $service->image_local_url ?? $serviceArray['image_url'] ?? null;
            }
            // All image fields (image_url, image_local_url, full_image_url) are already included via appended attributes
            return $serviceArray;
        });

        return response()->json([
            'success' => true,
            'data' => $transformedServices,
        ]);
    }
}
