<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Plan;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SubscriptionController extends Controller
{
    /**
     * Get all available subscription options (products and plans) with their Stripe price IDs
     * This allows the frontend to fetch price IDs from the backend instead of hardcoding them
     */
    public function options(Request $request): JsonResponse
    {
        try {
            $options = [
                'products' => [],
                'plans' => [],
            ];

            // Get products with Stripe price IDs
            $products = Product::where('is_active', true)
                ->whereNotNull('stripe_price_id')
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'stripe_price_id' => $product->stripe_price_id,
                        'type' => 'product',
                    ];
                });

            $options['products'] = $products;

            // Get plans with Stripe price IDs (if plans table exists)
            if (Schema::hasTable('plans')) {
                $plans = Plan::whereNotNull('stripe_price_id')
                    ->with('service:id,name,is_active')
                    ->get()
                    ->filter(function ($plan) {
                        // Only include plans from active services
                        return $plan->service && $plan->service->is_active;
                    })
                    ->map(function ($plan) {
                        return [
                            'id' => $plan->id,
                            'name' => $plan->name,
                            'price' => $plan->price,
                            'period' => $plan->period,
                            'features' => $plan->features,
                            'stripe_price_id' => $plan->stripe_price_id,
                            'service_id' => $plan->service_id,
                            'service_name' => $plan->service->name ?? null,
                            'type' => 'plan',
                        ];
                    });

                $options['plans'] = $plans->values();
            }

            return response()->json([
                'success' => true,
                'data' => $options,
                'meta' => [
                    'products_count' => $products->count(),
                    'plans_count' => $options['plans']->count(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Subscription Options API Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch subscription options',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Get a specific subscription option by ID (product or plan)
     */
    public function show(Request $request, string $type, int $id): JsonResponse
    {
        try {
            if ($type === 'product') {
                $product = Product::where('id', $id)
                    ->where('is_active', true)
                    ->whereNotNull('stripe_price_id')
                    ->firstOrFail();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'stripe_price_id' => $product->stripe_price_id,
                        'type' => 'product',
                    ],
                ]);
            } elseif ($type === 'plan') {
                if (!Schema::hasTable('plans')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Plans table does not exist',
                    ], 404);
                }

                $plan = Plan::where('id', $id)
                    ->whereNotNull('stripe_price_id')
                    ->with('service:id,name,is_active')
                    ->firstOrFail();

                if (!$plan->service || !$plan->service->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Plan service is not active',
                    ], 404);
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'price' => $plan->price,
                        'period' => $plan->period,
                        'features' => $plan->features,
                        'stripe_price_id' => $plan->stripe_price_id,
                        'service_id' => $plan->service_id,
                        'service_name' => $plan->service->name ?? null,
                        'type' => 'plan',
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid type. Must be "product" or "plan"',
                ], 400);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => ucfirst($type) . ' not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Subscription Option API Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch subscription option',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }
}

