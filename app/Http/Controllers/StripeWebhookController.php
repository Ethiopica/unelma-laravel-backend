<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $signature = $request->header('Stripe-Signature');
        $payload = $request->getContent();
        $webhookSecret = config('services.stripe.webhook_secret');
        $isLocal = app()->environment('local');

        // Log webhook receipt (only in local environment)
        if ($isLocal) {
            Log::info('Webhook received', [
                'has_signature' => !empty($signature),
                'payload_length' => strlen($payload),
            ]);
        }

        // For local development with Stripe CLI, try to verify but allow fallback
        if ($isLocal) {
            // Try to verify signature if webhook secret is set and looks valid (normal length ~64 chars)
            // Stripe CLI webhook secrets start with "whsec_" and are typically 64-70 characters
            $isValidWebhookSecret = !empty($webhookSecret) && 
                                    strlen($webhookSecret) < 100 && 
                                    str_starts_with($webhookSecret, 'whsec_');
            
            if ($isValidWebhookSecret && !empty($signature)) {
                try {
                    $event = Webhook::constructEvent(
                        $payload,
                        $signature,
                        $webhookSecret
                    );
                    Log::info('Webhook signature verified successfully in local environment', [
                        'event_type' => $event->type,
                    ]);
                    return $this->processEvent($event);
                } catch (\Throwable $e) {
                    // If signature verification fails in local, try to parse without verification
                    Log::warning('Stripe webhook signature verification failed in local, attempting to parse without verification', [
                        'error' => $e->getMessage(),
                        'error_class' => get_class($e),
                        'has_signature' => !empty($signature),
                    ]);
                }
            } else {
                if (empty($signature)) {
                    Log::info('No signature header in local environment, processing without verification');
                } else {
                    Log::info('Skipping signature verification in local (webhook secret invalid or missing)', [
                        'has_webhook_secret' => !empty($webhookSecret),
                        'webhook_secret_length' => strlen($webhookSecret ?? ''),
                        'webhook_secret_starts_with_whsec' => !empty($webhookSecret) && str_starts_with($webhookSecret, 'whsec_'),
                    ]);
                }
            }

                // Fallback: Parse webhook without signature verification for local development
            Log::info('Processing webhook without signature verification in local environment');
            try {
                $eventData = json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
                
                Log::info('Webhook payload decoded', [
                    'has_type' => isset($eventData->type),
                    'type' => $eventData->type ?? null,
                    'has_data' => isset($eventData->data),
                    'data_keys' => isset($eventData->data) ? array_keys((array) $eventData->data) : [],
                ]);
                
                if (!isset($eventData->type)) {
                    Log::error('Invalid webhook payload: missing type', [
                        'payload_keys' => array_keys((array) $eventData),
                        'payload_preview' => substr($payload, 0, 500),
                    ]);
                    return response('Invalid webhook payload: missing type', 400);
                }

                if (!isset($eventData->data)) {
                    Log::error('Invalid webhook payload: missing data', [
                        'event_type' => $eventData->type ?? 'unknown',
                    ]);
                    return response('Invalid webhook payload: missing data', 400);
                }

                // Create a minimal event object compatible with Stripe event structure
                // Handle both object and array formats for data
                $eventObject = $eventData->data->object ?? $eventData->data ?? null;
                
                // If eventObject is an array, convert to object
                if (is_array($eventObject)) {
                    $eventObject = json_decode(json_encode($eventObject), false);
                }
                
                $event = (object) [
                    'type' => $eventData->type,
                    'id' => $eventData->id ?? null,
                    'data' => (object) ['object' => $eventObject],
                ];
                
                Log::info('Webhook parsed successfully in local mode', [
                    'type' => $event->type,
                    'event_id' => $event->id,
                    'has_object' => isset($event->data->object),
                    'object_id' => $event->data->object->id ?? null,
                ]);
                
                return $this->processEvent($event);
            } catch (\JsonException $e) {
                Log::error('Failed to parse webhook payload as JSON in local mode', [
                    'error' => $e->getMessage(),
                    'payload_preview' => substr($payload, 0, 500),
                ]);
                return response('Invalid JSON payload', 400);
            } catch (\Throwable $e) {
                Log::error('Failed to parse webhook payload in local mode', [
                    'error' => $e->getMessage(),
                    'error_class' => get_class($e),
                    'payload_preview' => substr($payload, 0, 500),
                ]);
                return response('Invalid payload: ' . $e->getMessage(), 400);
            }
        }

        // Production: Strict signature verification
        if (empty($webhookSecret)) {
            Log::error('Stripe webhook secret is not configured');
            return response('Webhook secret not configured', 500);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (\Throwable $e) {
            Log::error('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
                'has_signature' => !empty($signature),
                'webhook_secret_length' => strlen($webhookSecret),
            ]);

            return response('Invalid signature', 400);
        }

        return $this->processEvent($event);
    }

    protected function processEvent($event): Response
    {
        // Initialize Stripe client for use throughout event processing
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        
        switch ($event->type) {
            case 'checkout.session.completed':
                $sessionData = $event->data->object;
                $sessionId = $sessionData->id ?? null;
                
                if (!$sessionId) {
                    Log::error('Checkout session completed webhook missing session ID');
                    return response('Missing session ID', 400);
                }
                
                // Retrieve the full session from Stripe API with expansions
                // This ensures we get subscription and customer data even if not in webhook payload
                try {
                    $session = \Stripe\Checkout\Session::retrieve($sessionId, [
                        'expand' => ['subscription', 'customer', 'subscription.latest_invoice', 'line_items'],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to retrieve checkout session from Stripe', [
                        'session_id' => $sessionId,
                        'error' => $e->getMessage(),
                    ]);
                    return response('Failed to retrieve session: ' . $e->getMessage(), 500);
                }
                
                // Extract metadata properly - handle both object and array formats
                $metadata = [];
                if (isset($session->metadata)) {
                    if (is_object($session->metadata)) {
                        // Convert object to array, filtering out Stripe internal properties
                        $metadataArray = (array) $session->metadata;
                        $metadata = array_filter($metadataArray, function($key) {
                            return !str_starts_with($key, "\0*\0");
                        }, ARRAY_FILTER_USE_KEY);
                    } elseif (is_array($session->metadata)) {
                        $metadata = $session->metadata;
                    }
                }
                
                $sessionMode = $session->mode ?? 'subscription'; // Default to subscription for backward compatibility
                
                Log::info('Checkout session completed', [
                    'session_id' => $session->id ?? null,
                    'client_reference_id' => $session->client_reference_id ?? null,
                    'metadata' => $metadata,
                    'subscription' => $session->subscription ?? null,
                    'customer' => $session->customer ?? null,
                    'mode' => $sessionMode,
                    'payment_status' => $session->payment_status ?? null,
                ]);

                // Try to get user from metadata first, then from customer
                $userId = null;
                if (isset($metadata['user_id'])) {
                    $userId = $metadata['user_id'];
                } elseif (isset($session->metadata->user_id)) {
                    $userId = $session->metadata->user_id;
                }

                $user = null;
                if ($userId) {
                    $user = User::find($userId);
                }

                // If user not found by ID, try to find by Stripe customer ID
                if (!$user && isset($session->customer)) {
                    $customerId = is_string($session->customer) ? $session->customer : $session->customer->id ?? null;
                    if ($customerId) {
                        $user = User::where('stripe_id', $customerId)->first();
                    }
                }

                // Handle based on checkout mode
                if ($sessionMode === 'payment') {
                    // One-time payment mode
                    if ($user) {
                        // Retrieve payment intent to get amount and details
                        $paymentIntentId = $session->payment_intent ?? null;
                        $amount = null;
                        $currency = null;
                        
                        if ($paymentIntentId) {
                            try {
                                $paymentIntent = is_string($paymentIntentId) 
                                    ? \Stripe\PaymentIntent::retrieve($paymentIntentId)
                                    : $paymentIntentId;
                                
                                $amount = isset($paymentIntent->amount) ? $paymentIntent->amount / 100 : null;
                                $currency = $paymentIntent->currency ?? null;
                            } catch (\Exception $e) {
                                Log::warning('Failed to retrieve payment intent', [
                                    'payment_intent_id' => $paymentIntentId,
                                    'error' => $e->getMessage(),
                                ]);
                            }
                        }
                        
                        // Extract product/service/plan info from metadata
                        $productId = $metadata['product_id'] ?? null;
                        $serviceId = $metadata['service_id'] ?? null;
                        $planId = $metadata['plan_id'] ?? null;
                        
                        // Get price ID and quantity from line items using allLineItems() method
                        // This ensures we get the actual quantity that was purchased
                        $priceId = null;
                        $quantity = 1; // Default to 1
                        try {
                            $lineItems = $stripe->checkout->sessions->allLineItems($sessionId, ['limit' => 10]);
                            
                            if (count($lineItems->data) > 0) {
                                $lineItem = $lineItems->data[0];
                                $priceId = $lineItem->price->id ?? null;
                                $quantity = isset($lineItem->quantity) ? (int) $lineItem->quantity : 1;
                            }
                        } catch (\Exception $e) {
                            Log::warning('Failed to retrieve line items in webhook for purchase', [
                                'session_id' => $sessionId,
                                'error' => $e->getMessage(),
                            ]);
                            // Fallback: try to get from expanded line_items if available
                            if (isset($session->line_items) && isset($session->line_items->data) && count($session->line_items->data) > 0) {
                                $lineItem = $session->line_items->data[0];
                                $priceId = $lineItem->price->id ?? null;
                                $quantity = isset($lineItem->quantity) ? (int) $lineItem->quantity : 1;
                                
                                // Using fallback expanded line_items
                            }
                        }
                        
                        // Create purchase record for one-time payment
                        try {
                            $purchase = \App\Models\Purchase::create([
                                'user_id' => $user->id,
                                'stripe_payment_intent_id' => is_string($paymentIntentId) ? $paymentIntentId : ($paymentIntentId->id ?? null),
                                'stripe_session_id' => $session->id,
                                'stripe_price_id' => $priceId,
                                'amount' => $amount ?? 0,
                                'currency' => $currency ?? 'usd',
                                'status' => 'completed',
                                'product_id' => $productId ? (int) $productId : null,
                                'service_id' => $serviceId ? (int) $serviceId : null,
                                'plan_id' => $planId ? (int) $planId : null,
                                'quantity' => $quantity, // Use actual quantity from Stripe line item
                                'metadata' => $metadata,
                                'purchased_at' => now(),
                            ]);
                            
                            Log::info('One-time payment purchase record created', [
                                'purchase_id' => $purchase->id,
                                'user_id' => $user->id,
                                'amount' => $amount,
                                'quantity' => $quantity,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to create purchase record for one-time payment', [
                                'user_id' => $user->id,
                                'session_id' => $session->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    } else {
                        Log::warning('One-time payment completed but user not found', [
                            'session_id' => $session->id,
                            'customer_id' => $session->customer ?? null,
                        ]);
                    }
                    
                    // Return success for one-time payments
                    return response('One-time payment processed', 200);
                }

                // Subscription mode handling (existing logic)
                $subscriptionId = null;
                if (isset($session->subscription)) {
                    $subscriptionId = is_string($session->subscription) ? $session->subscription : $session->subscription->id ?? null;
                }

                if ($user && $subscriptionId) {
                    // Ensure the user has a Stripe customer ID
                    if (is_null($user->stripe_id)) {
                        try {
                            $user->createAsStripeCustomer();
                            $user->refresh();
                        } catch (\Exception $e) {
                            Log::error('Failed to create Stripe customer for user', [
                                'user_id' => $user->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    // Get the subscription from Stripe (may already be expanded)
                    try {
                        if (is_object($session->subscription)) {
                            $stripeSubscription = $session->subscription;
                        } else {
                            $stripeSubscription = $user->stripe()->subscriptions->retrieve($subscriptionId, [
                                'expand' => ['latest_invoice', 'items.data.price.product'],
                            ]);
                        }
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        Log::warning('Could not retrieve subscription from Stripe, will be handled by customer.subscription.created event', [
                            'subscription_id' => $session->subscription,
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Return success - the subscription.created event will handle it
                        return response('Webhook received, subscription will be created by subscription.created event', 200);
                    } catch (\Exception $e) {
                        Log::error('Unexpected error retrieving subscription from Stripe', [
                            'subscription_id' => $session->subscription,
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                            'error_class' => get_class($e),
                        ]);
                        return response('Error processing webhook: ' . $e->getMessage(), 500);
                    }

                    // Get subscription name from metadata
                    $subscriptionName = 'default';
                    if (isset($stripeSubscription->metadata->subscription_name)) {
                        $subscriptionName = $stripeSubscription->metadata->subscription_name;
                    } elseif (isset($stripeSubscription->metadata->name)) {
                        $subscriptionName = $stripeSubscription->metadata->name;
                    } elseif (isset($metadata['subscription_name'])) {
                        $subscriptionName = $metadata['subscription_name'];
                    } elseif (isset($session->metadata->subscription_name)) {
                        $subscriptionName = $session->metadata->subscription_name;
                    } elseif (isset($session->metadata['subscription_name'])) {
                        $subscriptionName = $session->metadata['subscription_name'];
                    }

                    // Extract amount from subscription price (in cents, convert to dollars)
                    $amount = null;
                    if (isset($stripeSubscription->items->data[0]->price->unit_amount)) {
                        $amount = $stripeSubscription->items->data[0]->price->unit_amount / 100;
                    } elseif (isset($session->latest_invoice->amount_paid)) {
                        // Fallback to invoice amount if available
                        $amount = $session->latest_invoice->amount_paid / 100;
                    } elseif (isset($stripeSubscription->latest_invoice)) {
                        // Try to get from latest invoice if expanded
                        $latestInvoice = is_string($stripeSubscription->latest_invoice) 
                            ? null 
                            : $stripeSubscription->latest_invoice;
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
                            'ends_at' => null, // Reset ends_at for new subscriptions
                        ]
                    );
                    Log::info('Subscription created/updated via webhook for user', [
                        'user_id' => $user->id,
                        'subscription_id' => $stripeSubscription->id,
                        'status' => $stripeSubscription->status,
                    ]);
                } else {
                    Log::warning('Could not create subscription from checkout.session.completed', [
                        'user_found' => $user ? true : false,
                        'has_subscription' => isset($session->subscription),
                        'session_id' => $session->id,
                    ]);
                }
                break;

            case 'customer.subscription.created':
                $stripeSubscription = $event->data->object;
                Log::info('Subscription created event received', [
                    'subscription_id' => $stripeSubscription->id ?? null,
                    'customer' => $stripeSubscription->customer ?? null,
                    'status' => $stripeSubscription->status ?? null,
                ]);

                // Find the user by Stripe customer ID
                $user = null;
                if (isset($stripeSubscription->customer)) {
                    $user = User::where('stripe_id', $stripeSubscription->customer)->first();
                }

                if ($user && isset($stripeSubscription->id)) {
                    try {
                        // Get subscription name from metadata
                        $subscriptionName = 'default';
                        if (isset($stripeSubscription->metadata->subscription_name)) {
                            $subscriptionName = $stripeSubscription->metadata->subscription_name;
                        } elseif (isset($stripeSubscription->metadata->name)) {
                            $subscriptionName = $stripeSubscription->metadata->name;
                        } elseif (isset($stripeSubscription->metadata['subscription_name'])) {
                            $subscriptionName = $stripeSubscription->metadata['subscription_name'];
                        } elseif (isset($stripeSubscription->metadata['name'])) {
                            $subscriptionName = $stripeSubscription->metadata['name'];
                        }

                        // Extract amount from subscription price (in cents, convert to dollars)
                        $amount = null;
                        if (isset($stripeSubscription->items->data[0]->price->unit_amount)) {
                            $amount = $stripeSubscription->items->data[0]->price->unit_amount / 100;
                        } elseif (isset($stripeSubscription->latest_invoice)) {
                            // Try to get from latest invoice if available
                            $latestInvoice = is_string($stripeSubscription->latest_invoice) 
                                ? null 
                                : $stripeSubscription->latest_invoice;
                            if ($latestInvoice && isset($latestInvoice->amount_paid)) {
                                $amount = $latestInvoice->amount_paid / 100;
                            }
                        }

                        // Create or update the local subscription record
                        $user->subscriptions()->updateOrCreate(
                            ['stripe_id' => $stripeSubscription->id],
                            [
                                'name' => $subscriptionName,
                                'stripe_status' => $stripeSubscription->status ?? 'incomplete',
                                'stripe_price' => isset($stripeSubscription->items->data[0]->price->id) 
                                    ? $stripeSubscription->items->data[0]->price->id 
                                    : null,
                                'quantity' => isset($stripeSubscription->items->data[0]->quantity) 
                                    ? $stripeSubscription->items->data[0]->quantity 
                                    : 1,
                                'amount' => $amount,
                                'trial_ends_at' => isset($stripeSubscription->trial_end) && $stripeSubscription->trial_end 
                                    ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end) 
                                    : null,
                                'ends_at' => null,
                            ]
                        );
                        Log::info('Subscription created/updated via customer.subscription.created event', [
                            'user_id' => $user->id,
                            'subscription_id' => $stripeSubscription->id,
                            'status' => $stripeSubscription->status ?? 'unknown',
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to create/update subscription from customer.subscription.created event', [
                            'user_id' => $user->id,
                            'subscription_id' => $stripeSubscription->id ?? null,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('Could not process customer.subscription.created event', [
                        'user_found' => $user ? true : false,
                        'has_subscription_id' => isset($stripeSubscription->id),
                        'customer_id' => $stripeSubscription->customer ?? null,
                    ]);
                }
                break;

            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
            case 'customer.subscription.paused':
            case 'customer.subscription.resumed':
                $stripeSubscription = $event->data->object;
                Log::info('Subscription status event received', [
                    'subscription_id' => $stripeSubscription->id,
                    'status' => $stripeSubscription->status,
                    'event_type' => $event->type,
                ]);

                // Find the local subscription and update its status
                $subscription = Subscription::where('stripe_id', $stripeSubscription->id)->first();

                if ($subscription) {
                    $subscription->stripe_status = $stripeSubscription->status;
                    if (in_array($event->type, ['customer.subscription.deleted', 'customer.subscription.paused'])) {
                        $subscription->ends_at = \Carbon\Carbon::now();
                    } elseif ($event->type === 'customer.subscription.resumed') {
                        $subscription->ends_at = null;
                    }
                    $subscription->save();
                    Log::info('Local subscription status updated', ['subscription_id' => $subscription->id, 'new_status' => $subscription->stripe_status]);
                } else {
                    Log::warning('Subscription not found for status update', [
                        'stripe_subscription_id' => $stripeSubscription->id,
                        'event_type' => $event->type,
                    ]);
                }
                break;

            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                Log::info('Invoice payment succeeded', [
                    'invoice_id' => $invoice->id,
                    'customer' => $invoice->customer,
                    'subscription' => $invoice->subscription ?? null,
                    'amount_paid' => $invoice->amount_paid ?? null,
                ]);

                // Find the user by Stripe customer ID
                $user = User::where('stripe_id', $invoice->customer)->first();

                if ($user && $invoice->subscription) {
                    // Update subscription status to active if payment succeeded
                    $subscription = $user->subscriptions()
                        ->where('stripe_id', $invoice->subscription)
                        ->first();

                    if ($subscription) {
                        $subscription->stripe_status = 'active';
                        $subscription->ends_at = null; // Clear any end date
                        
                        // Update amount from invoice if available (in cents, convert to dollars)
                        if (isset($invoice->amount_paid)) {
                            $subscription->amount = $invoice->amount_paid / 100;
                        } elseif (isset($invoice->amount_due)) {
                            $subscription->amount = $invoice->amount_due / 100;
                        }
                        
                        $subscription->save();
                        Log::info('Subscription activated after successful payment', [
                            'user_id' => $user->id,
                            'subscription_id' => $subscription->id,
                            'invoice_id' => $invoice->id,
                            'amount' => $subscription->amount,
                        ]);
                    }
                } else {
                    Log::warning('User or subscription not found for invoice.payment_succeeded', [
                        'user_found' => $user ? true : false,
                        'has_subscription' => isset($invoice->subscription),
                        'invoice_id' => $invoice->id,
                    ]);
                }
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                Log::warning('Invoice payment failed', [
                    'invoice_id' => $invoice->id,
                    'customer' => $invoice->customer,
                    'subscription' => $invoice->subscription ?? null,
                ]);

                // Find the user by Stripe customer ID
                $user = User::where('stripe_id', $invoice->customer)->first();

                if ($user && $invoice->subscription) {
                    // Update subscription status
                    $subscription = $user->subscriptions()
                        ->where('stripe_id', $invoice->subscription)
                        ->first();

                    if ($subscription) {
                        $subscription->stripe_status = 'past_due';
                        $subscription->save();
                        Log::warning('Subscription marked as past_due after payment failure', [
                            'user_id' => $user->id,
                            'subscription_id' => $subscription->id,
                            'invoice_id' => $invoice->id,
                        ]);
                    }
                }
                // Note: User should be notified and service downgraded when subscription is canceled
                break;

            case 'charge.succeeded':
                $charge = $event->data->object;
                Log::info('Charge succeeded', [
                    'charge_id' => $charge->id,
                    'customer' => $charge->customer ?? null,
                    'amount' => $charge->amount ?? null,
                    'currency' => $charge->currency ?? null,
                ]);
                // Charge succeeded events are typically handled by invoice.payment_succeeded
                // but we log them for tracking purposes
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                Log::info('Payment intent succeeded event', [
                    'payment_intent_id' => $paymentIntent->id,
                    'customer' => $paymentIntent->customer ?? null,
                    'status' => $paymentIntent->status ?? null,
                    'amount' => $paymentIntent->amount ?? null,
                ]);
                
                // For one-time payments, create purchase record if checkout.session.completed didn't handle it
                // This is a fallback in case the checkout.session.completed webhook didn't fire or failed
                if ($paymentIntent->status === 'succeeded' && $paymentIntent->customer) {
                    $user = User::where('stripe_id', $paymentIntent->customer)->first();
                    
                    if ($user) {
                        // Check if purchase already exists
                        $existingPurchase = \App\Models\Purchase::where('stripe_payment_intent_id', $paymentIntent->id)->first();
                        
                        if (!$existingPurchase) {
                            // Try to get checkout session from payment intent metadata
                            $sessionId = $paymentIntent->metadata->checkout_session_id ?? null;
                            
                            if ($sessionId) {
                                try {
                                    $session = \Stripe\Checkout\Session::retrieve($sessionId, [
                                        'expand' => ['line_items'],
                                    ]);
                                    
                                    if ($session->mode === 'payment') {
                                        // Extract metadata
                                        $metadata = [];
                                        if (isset($session->metadata)) {
                                            if (is_object($session->metadata)) {
                                                $metadataArray = (array) $session->metadata;
                                                $metadata = array_filter($metadataArray, function($key) {
                                                    return !str_starts_with($key, "\0*\0");
                                                }, ARRAY_FILTER_USE_KEY);
                                            } elseif (is_array($session->metadata)) {
                                                $metadata = $session->metadata;
                                            }
                                        }
                                        
                                        $productId = $metadata['product_id'] ?? null;
                                        $serviceId = $metadata['service_id'] ?? null;
                                        $planId = $metadata['plan_id'] ?? null;
                                        
                                        // Get price ID and quantity from line items using allLineItems() method
                                        $priceId = null;
                                        $quantity = 1;
                                        try {
                                            $lineItems = $stripe->checkout->sessions->allLineItems($sessionId, ['limit' => 1]);
                                            if (count($lineItems->data) > 0) {
                                                $lineItem = $lineItems->data[0];
                                                $priceId = $lineItem->price->id ?? null;
                                                $quantity = $lineItem->quantity ?? 1;
                                            }
                                        } catch (\Exception $e) {
                                            Log::warning('Failed to retrieve line items in payment_intent.succeeded fallback', [
                                                'session_id' => $sessionId,
                                                'error' => $e->getMessage(),
                                            ]);
                                            // Fallback: try to get from expanded line_items if available
                                            if (isset($session->line_items) && isset($session->line_items->data) && count($session->line_items->data) > 0) {
                                                $lineItem = $session->line_items->data[0];
                                                $priceId = $lineItem->price->id ?? null;
                                                $quantity = $lineItem->quantity ?? 1;
                                            }
                                        }
                                        
                                        try {
                                            \App\Models\Purchase::create([
                                                'user_id' => $user->id,
                                                'stripe_payment_intent_id' => $paymentIntent->id,
                                                'stripe_session_id' => $sessionId,
                                                'stripe_price_id' => $priceId,
                                                'amount' => ($paymentIntent->amount ?? 0) / 100,
                                                'currency' => $paymentIntent->currency ?? 'usd',
                                                'status' => 'completed',
                                                'product_id' => $productId ? (int) $productId : null,
                                                'service_id' => $serviceId ? (int) $serviceId : null,
                                                'plan_id' => $planId ? (int) $planId : null,
                                                'quantity' => $quantity, // Use actual quantity from Stripe line item
                                                'metadata' => $metadata,
                                                'purchased_at' => now(),
                                            ]);
                                            
                                            Log::info('Purchase record created from payment_intent.succeeded fallback', [
                                                'user_id' => $user->id,
                                                'quantity' => $quantity,
                                            ]);
                                        } catch (\Exception $e) {
                                            Log::error('Failed to create purchase from payment_intent.succeeded', [
                                                'user_id' => $user->id,
                                                'payment_intent_id' => $paymentIntent->id,
                                                'error' => $e->getMessage(),
                                            ]);
                                        }
                                    }
                                } catch (\Exception $e) {
                                    Log::warning('Failed to retrieve session from payment_intent.succeeded', [
                                        'session_id' => $sessionId,
                                        'error' => $e->getMessage(),
                                    ]);
                                }
                            }
                        }
                    }
                }
                break;
                
            case 'payment_intent.created':
                $paymentIntent = $event->data->object;
                Log::info('Payment intent created event', [
                    'payment_intent_id' => $paymentIntent->id,
                    'customer' => $paymentIntent->customer ?? null,
                    'status' => $paymentIntent->status ?? null,
                ]);
                // Just log, don't create purchase until succeeded
                break;

            case 'charge.updated':
                $charge = $event->data->object;
                Log::debug('Charge updated', [
                    'charge_id' => $charge->id,
                    'status' => $charge->status ?? null,
                ]);
                break;

            default:
                Log::warning('Unhandled Stripe webhook event type', [
                    'type' => $event->type,
                    'event_id' => $event->id ?? null,
                ]);
        }

        // Webhook processed successfully
        
        return response('Webhook handled', 200);
    }
}
