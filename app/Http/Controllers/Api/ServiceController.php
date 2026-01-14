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

            // Transform services to ensure image_url always has a valid URL
            // Priority: full_image_url > image_local_url > existing image_url > null
            $transformedServices = collect($services->items())->map(function ($service) {
                $serviceArray = $service->toArray();
                // Use full_image_url first (generates correct Supabase URLs)
                // Falls back to image_local_url if full_image_url is not available
                // Falls back to existing image_url field as last resort
                $serviceArray['image_url'] = $service->full_image_url ?? $service->image_local_url ?? $serviceArray['image_url'] ?? null;
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
        // Use full_image_url first (generates correct Supabase URLs)
        // Falls back to image_local_url if full_image_url is not available
        // Falls back to existing image_url field as last resort
        $serviceData['image_url'] = $service->full_image_url ?? $service->image_local_url ?? $serviceData['image_url'] ?? null;

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

        // Transform services to ensure image_url always has a valid URL
        // Priority: full_image_url > image_local_url > existing image_url > null
        $transformedServices = $services->map(function ($service) {
            $serviceArray = $service->toArray();
            // Use full_image_url first (generates correct Supabase URLs)
            // Falls back to image_local_url if full_image_url is not available
            // Falls back to existing image_url field as last resort
            $serviceArray['image_url'] = $service->full_image_url ?? $service->image_local_url ?? $serviceArray['image_url'] ?? null;
            return $serviceArray;
        });

        return response()->json([
            'success' => true,
            'data' => $transformedServices,
        ]);
    }
}
