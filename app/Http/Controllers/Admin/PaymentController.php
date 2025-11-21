<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;

class PaymentController extends Controller
{
    /**
     * Display a listing of subscription payments.
     */
    public function index(Request $request)
    {
        $subscriptionsQuery = Subscription::with('user')
            ->orderByDesc('created_at');

        if ($status = $request->get('status')) {
            if ($status === 'canceled') {
                $subscriptionsQuery->whereIn('stripe_status', ['canceled', 'incomplete_expired']);
            } else {
                $subscriptionsQuery->where('stripe_status', $status);
            }
        }

        $subscriptions = $subscriptionsQuery->paginate(15);

        $summary = [
            'total' => Subscription::count(),
            'active' => Subscription::where('stripe_status', 'active')->count(),
            'trialing' => Subscription::where('stripe_status', 'trialing')->count(),
            'past_due' => Subscription::where('stripe_status', 'past_due')->count(),
            'canceled' => Subscription::whereIn('stripe_status', ['canceled', 'incomplete_expired'])->count(),
        ];

        return view('admin.payments.index', [
            'subscriptions' => $subscriptions,
            'summary' => $summary,
        ]);
    }

    /**
     * Delete a subscription.
     */
    public function destroy($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);

            // Cancel the subscription in Stripe if it's still active
            if ($subscription->user && in_array($subscription->stripe_status, ['active', 'trialing', 'past_due'])) {
                try {
                    $subscription->cancel();
                    Log::info('Subscription canceled in Stripe', [
                        'subscription_id' => $subscription->id,
                        'stripe_id' => $subscription->stripe_id,
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to cancel subscription in Stripe, deleting locally anyway', [
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Delete the local subscription record
            $subscription->delete();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Subscription deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete subscription', [
                'subscription_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Failed to delete subscription. Please try again.');
        }
    }
}
