<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

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

            return response()->json([
                'success' => true,
                'data' => $services->items(),
                'meta' => [
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                    'per_page' => $services->perPage(),
                    'total' => $services->total(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Services API Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

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
        $service = Service::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $service,
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

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }
}
