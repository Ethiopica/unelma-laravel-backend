<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Exception\ApiErrorException;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request): JsonResponse
    {
        $data = $request->validate([
            'price_id' => ['nullable', 'string'], // Optional if product_id, service_id, or plan_id is provided
            'product_id' => ['nullable', 'integer'], // Product ID to look up price_id
            'service_id' => ['nullable', 'integer'], // Service ID to look up price_id
            'plan_id' => ['nullable', 'integer'], // Plan ID to look up price_id
            'quantity' => ['nullable'],
            'success_url' => ['required', 'string'],
            'cancel_url' => ['required', 'string'],
            'subscription_name' => ['nullable', 'string', 'max:255'],
        ]);

        // Determine price_id from product_id, service_id, or plan_id if price_id not provided
        $priceId = $data['price_id'] ?? null;
        $resolvedProductId = $data['product_id'] ?? null;
        $resolvedServiceId = $data['service_id'] ?? null;
        $resolvedPlanId = $data['plan_id'] ?? null;
        
        if (!$priceId) {
            if (isset($data['product_id'])) {
                $product = \App\Models\Product::where('id', $data['product_id'])
                    ->where('is_active', true)
                    ->whereNotNull('stripe_price_id')
                    ->first();
                
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product not found or does not have a Stripe price ID configured.',
                    ], 404);
                }
                
                $priceId = $product->stripe_price_id;
                $resolvedProductId = (string) $product->id;
                
                Log::info('Price ID resolved from product', [
                    'product_id' => $product->id,
                    'price_id' => $priceId,
                ]);
            } elseif (isset($data['service_id'])) {
                $service = \App\Models\Service::where('id', $data['service_id'])
                    ->where('is_active', true)
                    ->whereNotNull('stripe_price_id')
                    ->first();
                
                if (!$service) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Service not found or does not have a Stripe price ID configured.',
                    ], 404);
                }
                
                $priceId = $service->stripe_price_id;
                $resolvedServiceId = (string) $service->id;
                
                Log::info('Price ID resolved from service', [
                    'service_id' => $service->id,
                    'price_id' => $priceId,
                ]);
            } elseif (isset($data['plan_id'])) {
                if (!\Illuminate\Support\Facades\Schema::hasTable('plans')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Plans table does not exist.',
                    ], 404);
                }
                
                $plan = \App\Models\Plan::where('id', $data['plan_id'])
                    ->whereNotNull('stripe_price_id')
                    ->with('service')
                    ->first();
                
                if (!$plan || !$plan->service || !$plan->service->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Plan not found, inactive, or does not have a Stripe price ID configured.',
                    ], 404);
                }
                
                $priceId = $plan->stripe_price_id;
                $resolvedPlanId = (string) $plan->id;
                
                Log::info('Price ID resolved from plan', [
                    'plan_id' => $plan->id,
                    'price_id' => $priceId,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Either price_id, product_id, service_id, or plan_id must be provided.',
                ], 400);
            }
        }

        $user = $request->user();

        if (! $user) {
            Log::warning('Checkout session creation attempted without authentication', [
                'has_authorization_header' => $request->hasHeader('Authorization'),
                'authorization_header_preview' => $request->header('Authorization') ? substr($request->header('Authorization'), 0, 20) . '...' : null,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Authentication required to start a subscription checkout session. Please ensure you are logged in and your authentication token is included in the Authorization header.',
                'error' => 'Unauthenticated',
            ], 401);
        }

        $quantity = (int) ($data['quantity'] ?? 1);
        $quantity = $quantity > 0 ? $quantity : 1;

        $successUrl = $this->qualifyUrl($data['success_url']);
        $cancelUrl = $this->qualifyUrl($data['cancel_url']);

        $secret = config('services.stripe.secret');

        if (blank($secret)) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe secret key is not configured. Set STRIPE_SECRET in your environment.',
            ], 500);
        }

        // Try to get or create Stripe customer, handling invalid customer IDs
        try {
            $customer = $user->createOrGetStripeCustomer();
        } catch (ApiErrorException $e) {
            // If the stored customer ID doesn't exist in Stripe, clear it and create a new customer
            if (str_contains($e->getMessage(), 'No such customer')) {
                Log::warning('Invalid Stripe customer ID found, clearing and creating new customer', [
                    'user_id' => $user->getKey(),
                    'old_stripe_id' => $user->stripe_id,
                ]);
                
                // Clear the invalid stripe_id directly in the database
                DB::table('users')
                    ->where('id', $user->getKey())
                    ->update(['stripe_id' => null]);
                
                // Reload the user model to get the fresh data
                $user = $user->fresh();
                
                // Create a new customer directly using Stripe API
                try {
                    $stripe = $user->stripe();
                    $newCustomer = $stripe->customers->create([
                        'email' => $user->email,
                        'name' => $user->name,
                        'metadata' => [
                            'user_id' => (string) $user->getKey(),
                        ],
                    ]);
                    
                    // Update the user with the new customer ID
                    $user->update(['stripe_id' => $newCustomer->id]);
                    $customer = $newCustomer;
                    
                    Log::info('Successfully created new Stripe customer after clearing invalid ID', [
                        'user_id' => $user->getKey(),
                        'new_stripe_id' => $newCustomer->id,
                    ]);
                } catch (\Exception $retryException) {
                    Log::error('Failed to create new Stripe customer after clearing invalid ID', [
                        'user_id' => $user->getKey(),
                        'error' => $retryException->getMessage(),
                        'trace' => $retryException->getTraceAsString(),
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to create Stripe customer. Please try again later.',
                        'error' => config('app.debug') ? [
                            'message' => $retryException->getMessage(),
                        ] : null,
                    ], 500);
                }
            } else {
                // Re-throw if it's a different error
                throw $e;
            }
        }
        
        $subscriptionName = $data['subscription_name'] ?? 'default';
        $stripe = $user->stripe();

        $metadata = array_filter([
            'user_id' => (string) $user->getKey(),
            'email' => $user->email,
            'subscription_name' => $subscriptionName,
            'product_id' => $resolvedProductId,
            'service_id' => $resolvedServiceId,
            'plan_id' => $resolvedPlanId,
        ]);

        try {
            $session = $stripe->checkout->sessions->create([
                'mode' => 'subscription',
                'customer' => $customer->id,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => $quantity,
                ]],
                'success_url' => $successUrl.'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'client_reference_id' => $data['product_id'] ?? null,
                'metadata' => $metadata,
                'subscription_data' => [
                    'metadata' => array_merge($metadata, [
                        'type' => $subscriptionName,
                    ]),
                ],
            ]);

            Log::info('Stripe checkout session created', [
                'session_id' => $session->id,
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'price_id' => $priceId,
                'product_id' => $resolvedProductId,
                'service_id' => $resolvedServiceId,
                'plan_id' => $resolvedPlanId,
                'mode' => $session->livemode ? 'live' : 'test',
                'url' => $session->url,
            ]);

            return response()->json([
                'success' => true,
                'sessionId' => $session->id,
                'url' => $session->url,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage(), [
                'price_id' => $priceId,
                'product_id' => $resolvedProductId,
                'service_id' => $resolvedServiceId,
                'plan_id' => $resolvedPlanId,
                'user_id' => $user->getKey(),
                'error_type' => method_exists($e, 'getStripeCode') ? $e->getStripeCode() : null,
            ]);

            $errorMessage = $e->getMessage();
            
            // Provide user-friendly error messages
            if (str_contains($errorMessage, 'No such price')) {
                $errorMessage = "The price ID '{$priceId}' does not exist in Stripe. Please verify the price ID is correct and exists in your Stripe account.";
            } elseif (str_contains($errorMessage, 'No such customer')) {
                $errorMessage = 'Customer not found in Stripe. Please try again.';
            } elseif (str_contains($errorMessage, 'Invalid')) {
                $errorMessage = 'Invalid request to Stripe. Please check your input and try again.';
            }

            $errorData = [
                'type' => method_exists($e, 'getStripeCode') ? $e->getStripeCode() : null,
            ];
            
            // Only add error code if the method exists
            if (method_exists($e, 'getStripeErrorCode')) {
                $errorData['code'] = $e->getStripeErrorCode();
            } elseif (method_exists($e, 'getErrorCode')) {
                $errorData['code'] = $e->getErrorCode();
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => $errorData,
            ], 400);
        } catch (\Exception $e) {
            Log::error('Unexpected error creating Stripe checkout session', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'price_id' => $priceId ?? null,
                'product_id' => $resolvedProductId,
                'service_id' => $resolvedServiceId,
                'plan_id' => $resolvedPlanId,
                'user_id' => $user->getKey(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while creating the checkout session. Please try again later.',
                'error' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                ] : null,
            ], 500);
        }
    }

    public function handleCheckoutSuccess(Request $request): JsonResponse
    {
        $sessionId = $request->query('session_id');
        
        Log::info('=== CHECKOUT SUCCESS CALLBACK RECEIVED ===', [
            'session_id' => $sessionId,
            'has_user' => $request->user() ? true : false,
            'user_id' => $request->user()?->id,
            'auth_header' => $request->header('Authorization') ? 'present' : 'missing',
            'all_query_params' => $request->query->all(),
        ]);
        
        if (!$sessionId) {
            Log::warning('Checkout success callback missing session_id', [
                'query_params' => $request->query->all(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Missing session_id parameter',
            ], 400);
        }

        try {
            // Initialize Stripe with default key (works even without user)
            \Stripe\Stripe::setApiKey(config('cashier.secret'));
            
            // Retrieve session first to get customer ID
            $session = \Stripe\Checkout\Session::retrieve($sessionId, [
                'expand' => ['subscription', 'customer'],
            ]);
            
            Log::info('=== STRIPE SESSION RETRIEVED ===', [
                'session_id' => $sessionId,
                'customer_id' => $session->customer ?? null,
                'status' => $session->status ?? null,
                'payment_status' => $session->payment_status ?? null,
            ]);
            
            // Try to get user from authenticated request first
            $user = $request->user();
            
            // If no authenticated user, find user by Stripe customer ID
            if (!$user && $session->customer) {
                $customerId = is_string($session->customer) ? $session->customer : $session->customer->id;
                $user = \App\Models\User::where('stripe_id', $customerId)->first();
                
                Log::info('=== USER LOOKUP BY STRIPE CUSTOMER ===', [
                    'customer_id' => $customerId,
                    'user_found' => $user ? true : false,
                    'user_id' => $user?->id,
                ]);
            }
            
            if (!$user) {
                Log::warning('Checkout success callback: Could not identify user', [
                    'session_id' => $sessionId,
                    'customer_id' => $session->customer ?? null,
                    'has_auth' => $request->user() ? true : false,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Could not identify user. Please ensure you are logged in.',
                ], 401);
            }
            
            // Use user's Stripe instance for consistency
            $stripe = $user->stripe();
            
            Log::info('=== PROCESSING CHECKOUT SUCCESS ===', [
                'session_id' => $sessionId,
                'user_id' => $user->id,
                'session_status' => $session->status ?? null,
                'payment_status' => $session->payment_status ?? null,
                'has_subscription' => isset($session->subscription),
                'subscription_id' => $session->subscription ?? null,
                'customer_id' => $session->customer ?? null,
            ]);

            // If subscription exists, process it
            if (isset($session->subscription) && $session->subscription) {
                try {
                    // Get subscription ID (could be string or object)
                    $subscriptionId = is_string($session->subscription) 
                        ? $session->subscription 
                        : (is_object($session->subscription) ? $session->subscription->id : null);
                    
                    if (!$subscriptionId) {
                        throw new \Exception('Invalid subscription ID in session');
                    }
                    
                    // If already expanded, use it directly; otherwise retrieve
                    if (is_object($session->subscription) && isset($session->subscription->id)) {
                        $stripeSubscription = $session->subscription;
                    } else {
                        $stripeSubscription = $stripe->subscriptions->retrieve($subscriptionId);
                    }
                    
                    // Get subscription name from metadata
                    $subscriptionName = 'default';
                    if (isset($stripeSubscription->metadata->subscription_name)) {
                        $subscriptionName = $stripeSubscription->metadata->subscription_name;
                    } elseif (isset($stripeSubscription->metadata->name)) {
                        $subscriptionName = $stripeSubscription->metadata->name;
                    } elseif (isset($session->metadata->subscription_name)) {
                        $subscriptionName = $session->metadata->subscription_name;
                    } elseif (isset($session->metadata['subscription_name'])) {
                        $subscriptionName = $session->metadata['subscription_name'];
                    }

                    // Extract amount from subscription price (in cents, convert to dollars)
                    $amount = null;
                    if (isset($stripeSubscription->items->data[0]->price->unit_amount)) {
                        $amount = $stripeSubscription->items->data[0]->price->unit_amount / 100;
                    } elseif (isset($session->latest_invoice)) {
                        // Try to get from latest invoice if available
                        $latestInvoice = is_string($session->latest_invoice) 
                            ? null 
                            : $session->latest_invoice;
                        if ($latestInvoice && isset($latestInvoice->amount_paid)) {
                            $amount = $latestInvoice->amount_paid / 100;
                        }
                    }

                    // Create or update the local subscription record
                    $user->subscriptions()->updateOrCreate(
                        ['stripe_id' => $stripeSubscription->id],
                        [
                            'name' => $subscriptionName,
                            'stripe_status' => $stripeSubscription->status,
                            'stripe_price' => $stripeSubscription->items->data[0]->price->id,
                            'quantity' => $stripeSubscription->items->data[0]->quantity,
                            'amount' => $amount,
                            'trial_ends_at' => $stripeSubscription->trial_end ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end) : null,
                            'ends_at' => null,
                        ]
                    );
                    
                    Log::info('Subscription created/updated via checkout success callback', [
                        'user_id' => $user->id,
                        'subscription_id' => $stripeSubscription->id,
                        'status' => $stripeSubscription->status,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Subscription activated successfully',
                        'subscription' => [
                            'id' => $stripeSubscription->id,
                            'status' => $stripeSubscription->status,
                        ],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to process subscription in checkout success', [
                        'session_id' => $sessionId,
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment successful but subscription processing failed. Please contact support.',
                    ], 500);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully',
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Invalid checkout session in success callback', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid checkout session',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error processing checkout success', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred processing your checkout',
            ], 500);
        }
    }

    protected function qualifyUrl(string $url): string
    {
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        $base = config('app.frontend_url', config('app.url'));

        return rtrim($base, '/').'/'.ltrim($url, '/');
    }
}
