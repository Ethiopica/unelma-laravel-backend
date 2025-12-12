<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;

class PaymentController extends Controller
{
    /**
     * Display a listing of payment subscriptions and one-time purchases.
     * Supports filtering by type: 'subscriptions', 'purchases', or 'all'
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // 'subscriptions', 'purchases', or 'all'
        $status = $request->get('status');

        // Initialize data arrays
        $subscriptions = null;
        $purchases = null;
        $subscriptionSummary = [];
        $purchaseSummary = [];

        // Load subscriptions if needed
        if ($type === 'all' || $type === 'subscriptions') {
            $subscriptionsQuery = Subscription::with('user')
                ->orderByDesc('created_at');

            if ($status) {
                if ($status === 'canceled') {
                    $subscriptionsQuery->whereIn('stripe_status', ['canceled', 'incomplete_expired']);
                } else {
                    $subscriptionsQuery->where('stripe_status', $status);
                }
            }

            $subscriptions = $subscriptionsQuery->paginate(15, ['*'], 'subscriptions_page');

            $subscriptionSummary = [
                'total' => Subscription::count(),
                'active' => Subscription::where('stripe_status', 'active')->count(),
                'trialing' => Subscription::where('stripe_status', 'trialing')->count(),
                'past_due' => Subscription::where('stripe_status', 'past_due')->count(),
                'canceled' => Subscription::whereIn('stripe_status', ['canceled', 'incomplete_expired'])->count(),
            ];
        }

        // Load purchases if needed
        if ($type === 'all' || $type === 'purchases') {
            $purchasesQuery = Purchase::with(['user', 'product', 'service', 'plan'])
                ->orderByDesc('purchased_at');

            if ($status) {
                $purchasesQuery->where('status', $status);
            }

            $purchases = $purchasesQuery->paginate(15, ['*'], 'purchases_page');

            $purchaseSummary = [
                'total' => Purchase::count(),
                'completed' => Purchase::where('status', 'completed')->count(),
                'refunded' => Purchase::where('status', 'refunded')->count(),
                'failed' => Purchase::where('status', 'failed')->count(),
                'total_revenue' => Purchase::where('status', 'completed')->sum('amount'),
            ];
        }

        return view('admin.payments.index', [
            'type' => $type,
            'subscriptions' => $subscriptions,
            'purchases' => $purchases,
            'subscriptionSummary' => $subscriptionSummary,
            'purchaseSummary' => $purchaseSummary,
            'status' => $status,
        ]);
    }

    /**
     * Delete a subscription or purchase.
     */
    public function destroy(Request $request, $id)
    {
        $paymentType = $request->get('type', 'subscription'); // 'subscription' or 'purchase'

        try {
            if ($paymentType === 'purchase') {
                $purchase = Purchase::findOrFail($id);
                $purchase->delete();

                return redirect()
                    ->route('admin.payments.index', ['type' => 'purchases'])
                    ->with('success', 'Purchase deleted successfully.');
            } else {
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
                    ->route('admin.payments.index', ['type' => 'subscriptions'])
                    ->with('success', 'Subscription deleted successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete payment', [
                'payment_id' => $id,
                'payment_type' => $paymentType,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Failed to delete payment. Please try again.');
        }
    }
}
