<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Create a subscription checkout session for the authenticated user.
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
