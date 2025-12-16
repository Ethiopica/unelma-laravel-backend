<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Create a payment subscription checkout session for the authenticated user.
     * Note: This is for payment subscriptions (Stripe), not newsletter/email subscriptions (Unelma Mail).
     */
    public function subscribe(Request $request)
    {
        $user = $request->user();

        return $user->newSubscription('default', 'price_12345')
            ->checkout([
                'success_url' => route('checkout.success'),
                'cancel_url' => route('checkout.cancel'),
            ]);
    }
}
