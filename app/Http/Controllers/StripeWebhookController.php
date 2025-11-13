<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Cashier;
use App\Models\User;
use Laravel\Cashier\Subscription;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $signature = $request->header('Stripe-Signature');
        $payload = $request->getContent();

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);

            return response('Invalid signature', 400);
        }

        return $this->processEvent($event);
    }

    protected function processEvent($event): Response
    {
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                Log::info('Checkout session completed', [
                    'session_id' => $session->id,
                    'client_reference_id' => $session->client_reference_id,
                    'metadata' => $session->metadata,
                ]);

                // Retrieve the user based on metadata
                $userId = $session->metadata->user_id ?? $session->client_reference_id;
                $user = User::find($userId);

                if ($user && $session->subscription) {
                    // Ensure the user has a Stripe customer ID
                    if (is_null($user->stripe_id)) {
                        $user->createAsStripeCustomer();
                    }

                    // Get the subscription from Stripe
                    $stripeSubscription = $user->stripe()->subscriptions->retrieve($session->subscription);

                    // Create or update the local subscription record
                    $user->subscriptions()->updateOrCreate(
                        ['stripe_id' => $stripeSubscription->id],
                        [
                            'name' => $stripeSubscription->metadata->subscription_name ?? $stripeSubscription->metadata->name ?? 'default',
                            'stripe_status' => $stripeSubscription->status,
                            'stripe_price' => $stripeSubscription->items->data[0]->price->id,
                            'quantity' => $stripeSubscription->items->data[0]->quantity,
                            'trial_ends_at' => $stripeSubscription->trial_end ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end) : null,
                            'ends_at' => null, // Reset ends_at for new subscriptions
                        ]
                    );
                    Log::info('Subscription created/updated via webhook for user', ['user_id' => $user->id, 'subscription_id' => $stripeSubscription->id]);
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
                }
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                Log::warning('Invoice payment failed', [
                    'invoice_id' => $invoice->id,
                    'customer' => $invoice->customer,
                ]);
                // TODO: notify user / downgrade service
                break;

            default:
                Log::debug('Unhandled Stripe webhook', ['type' => $event->type]);
        }

        return response('Webhook handled', 200);
    }
}


