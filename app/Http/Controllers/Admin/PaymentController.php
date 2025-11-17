<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
