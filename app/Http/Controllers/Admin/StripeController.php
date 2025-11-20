<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $validated = $request->validate([
            'price_id' => 'required|string',
            'quantity' => 'nullable|integer|min:1',
            'success_url' => 'required|url',
            'cancel_url' => 'required|url',
            'product_id' => 'nullable|string',
        ]);

        $stripe = new StripeClient(config('services.stripe.secret'));

        try {
            $session = $stripe->checkout->sessions->create([
                'mode' => 'subscription',
                'line_items' => [[
                    'price' => $validated['price_id'],
                    'quantity' => $validated['quantity'] ?? 1,
                ]],
                'success_url' => $validated['success_url'].'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $validated['cancel_url'],
                'client_reference_id' => $request->user()?->id,
                'metadata' => [
                    'user_id' => $request->user()?->id,
                    'product_id' => $validated['product_id'] ?? null,
                ],
            ]);

            return response()->json([
                'id' => $session->id,
                'url' => $session->url,
            ]);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout session creation failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to create checkout session',
            ], 500);
        }
    }
}
