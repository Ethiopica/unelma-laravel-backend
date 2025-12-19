<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Fetch all prices from Stripe
     */
    public function getPrices(Request $request)
    {
        try {
            $prices = $this->stripe->prices->all([
                'active' => true,
                'limit' => 100,
                'expand' => ['data.product'],
            ]);

            $formattedPrices = collect($prices->data)->map(function ($price) {
                $productName = 'Unknown Product';
                if ($price->product && is_object($price->product)) {
                    $productName = $price->product->name ?? 'Unknown Product';
                }

                $interval = '';
                if ($price->recurring) {
                    $interval = $price->recurring->interval;
                    if ($price->recurring->interval_count > 1) {
                        $interval = $price->recurring->interval_count . ' ' . $interval . 's';
                    }
                }

                return [
                    'id' => $price->id,
                    'product_id' => is_object($price->product) ? $price->product->id : $price->product,
                    'product_name' => $productName,
                    'nickname' => $price->nickname,
                    'unit_amount' => $price->unit_amount,
                    'currency' => strtoupper($price->currency),
                    'formatted_price' => $this->formatPrice($price->unit_amount, $price->currency),
                    'type' => $price->type,
                    'recurring' => $price->recurring ? true : false,
                    'interval' => $interval,
                    'display_name' => $this->buildDisplayName($price, $productName),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'prices' => $formattedPrices,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch Stripe prices', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch Stripe prices: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fetch all products from Stripe
     */
    public function getProducts(Request $request)
    {
        try {
            $products = $this->stripe->products->all([
                'active' => true,
                'limit' => 100,
            ]);

            $formattedProducts = collect($products->data)->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'images' => $product->images,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'products' => $formattedProducts,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch Stripe products', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch Stripe products: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new product and price in Stripe
     */
    public function createProductWithPrice(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'interval' => 'nullable|string|in:day,week,month,year',
            'interval_count' => 'nullable|integer|min:1',
        ]);

        try {
            // Create the product
            $product = $this->stripe->products->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            // Create the price
            $priceData = [
                'product' => $product->id,
                'unit_amount' => (int) ($validated['price'] * 100), // Convert to cents
                'currency' => strtolower($validated['currency']),
            ];

            // Add recurring if interval is provided
            if (!empty($validated['interval'])) {
                $priceData['recurring'] = [
                    'interval' => $validated['interval'],
                    'interval_count' => $validated['interval_count'] ?? 1,
                ];
            }

            $price = $this->stripe->prices->create($priceData);

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                ],
                'price' => [
                    'id' => $price->id,
                    'unit_amount' => $price->unit_amount,
                    'currency' => $price->currency,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to create Stripe product/price', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create product/price: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate a Stripe price ID exists
     */
    public function validatePriceId(Request $request)
    {
        $validated = $request->validate([
            'price_id' => 'required|string',
        ]);

        try {
            $price = $this->stripe->prices->retrieve($validated['price_id'], [
                'expand' => ['product'],
            ]);

            $productName = 'Unknown Product';
            if ($price->product && is_object($price->product)) {
                $productName = $price->product->name ?? 'Unknown Product';
            }

            return response()->json([
                'success' => true,
                'valid' => true,
                'price' => [
                    'id' => $price->id,
                    'product_name' => $productName,
                    'formatted_price' => $this->formatPrice($price->unit_amount, $price->currency),
                    'active' => $price->active,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => true,
                'valid' => false,
                'message' => 'Price ID not found',
            ]);
        }
    }

    /**
     * Format price for display
     */
    private function formatPrice(int $amountInCents, string $currency): string
    {
        $amount = $amountInCents / 100;
        $currencySymbols = [
            'usd' => '$',
            'eur' => '€',
            'gbp' => '£',
            'jpy' => '¥',
        ];

        $symbol = $currencySymbols[strtolower($currency)] ?? strtoupper($currency) . ' ';

        return $symbol . number_format($amount, 2);
    }

    /**
     * Build display name for price dropdown
     */
    private function buildDisplayName($price, string $productName): string
    {
        $displayName = $productName;

        if ($price->nickname) {
            $displayName .= ' - ' . $price->nickname;
        }

        $displayName .= ' (' . $this->formatPrice($price->unit_amount, $price->currency);

        if ($price->recurring) {
            $interval = $price->recurring->interval;
            if ($price->recurring->interval_count > 1) {
                $displayName .= ' / ' . $price->recurring->interval_count . ' ' . $interval . 's';
            } else {
                $displayName .= ' / ' . $interval;
            }
        }

        $displayName .= ')';

        return $displayName;
    }
}
