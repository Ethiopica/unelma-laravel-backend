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

        // Log all incoming webhook attempts (even if they fail)
        Log::info('=== WEBHOOK RECEIVED ===', [
            'environment' => app()->environment(),
            'is_local' => $isLocal,
            'has_signature' => !empty($signature),
            'has_webhook_secret' => !empty($webhookSecret),
            'webhook_secret_length' => strlen($webhookSecret ?? ''),
            'payload_length' => strlen($payload),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => [
                'stripe-signature' => $signature ? substr($signature, 0, 50) . '...' : null,
                'content-type' => $request->header('Content-Type'),
            ],
            'payload_preview' => substr($payload, 0, 500),
        ]);

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
        Log::info('=== PROCESSING WEBHOOK EVENT ===', [
            'event_type' => $event->type ?? 'unknown',
            'event_id' => $event->id ?? null,
            'has_data' => isset($event->data),
            'has_object' => isset($event->data->object),
        ]);
        
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
                    $stripe = \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                    $session = \Stripe\Checkout\Session::retrieve($sessionId, [
                        'expand' => ['subscription', 'customer', 'subscription.latest_invoice'],
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
                
                Log::info('Checkout session completed', [
                    'session_id' => $session->id ?? null,
                    'client_reference_id' => $session->client_reference_id ?? null,
                    'metadata' => $metadata,
                    'subscription' => $session->subscription ?? null,
                    'customer' => $session->customer ?? null,
                    'mode' => $session->mode ?? null,
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

                // For subscription mode, we need subscription ID
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
                            'trace' => $e->getTraceAsString(),
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
                // TODO: notify user / downgrade service
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
            case 'payment_intent.created':
                $paymentIntent = $event->data->object;
                Log::info('Payment intent event', [
                    'payment_intent_id' => $paymentIntent->id,
                    'customer' => $paymentIntent->customer ?? null,
                    'status' => $paymentIntent->status ?? null,
                    'event_type' => $event->type,
                ]);
                // Payment intents are typically part of checkout flow
                // The actual subscription creation happens via checkout.session.completed
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

        Log::info('=== WEBHOOK PROCESSED SUCCESSFULLY ===', [
            'event_type' => $event->type ?? 'unknown',
        ]);
        
        return response('Webhook handled', 200);
    }
}
