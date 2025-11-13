<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request): JsonResponse
    {
        $data = $request->validate([
            'price_id'    => ['required', 'string'],
            'product_id'  => ['nullable', 'string'],
            'quantity'    => ['nullable'],
            'success_url' => ['required', 'string'],
            'cancel_url'  => ['required', 'string'],
            'subscription_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required to start a subscription checkout session.',
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

        $customer = $user->createOrGetStripeCustomer();
        $subscriptionName = $data['subscription_name'] ?? 'default';
        $stripe = $user->stripe();

        $metadata = array_filter([
            'user_id' => (string) $user->getKey(),
            'email' => $user->email,
            'subscription_name' => $subscriptionName,
            'product_id' => $data['product_id'] ?? null,
        ]);

        $session = $stripe->checkout->sessions->create([
            'mode' => 'subscription',
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price'    => $data['price_id'],
                'quantity' => $quantity,
            ]],
            'success_url'        => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'         => $cancelUrl,
            'client_reference_id' => $data['product_id'] ?? null,
            'metadata' => $metadata,
            'subscription_data' => [
                'metadata' => array_merge($metadata, [
                    'type' => $subscriptionName,
                ]),
            ],
        ]);

        return response()->json([
            'success'   => true,
            'sessionId' => $session->id,
            'url'       => $session->url,
        ]);
    }

    protected function qualifyUrl(string $url): string
    {
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        $base = config('app.frontend_url', config('app.url'));

        return rtrim($base, '/') . '/' . ltrim($url, '/');
    }
}
