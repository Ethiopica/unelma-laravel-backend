<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    /**
     * Get authenticated user profile
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'profile_picture' => $user->profile_picture_url,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                'member_since' => $user->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'profile_picture' => $user->profile_picture_url,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Check if current password is correct
        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
                'errors' => [
                    'current_password' => ['The current password is incorrect.'],
                ],
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Delete user account
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // Validate password before deletion
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect',
                'errors' => [
                    'password' => ['The password is incorrect.'],
                ],
            ], 422);
        }

        // Delete all user tokens
        $user->tokens()->delete();

        // Delete user
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }

    /**
     * Get user activity summary
     */
    public function activity(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'activity' => [
                'total_logins' => $user->tokens()->count(),
                'account_age_days' => $user->created_at->diffInDays(now()),
                'last_login' => $user->tokens()->latest()->first()?->created_at?->format('Y-m-d H:i:s'),
                'account_status' => 'active',
            ],
        ]);
    }

    /**
     * Get user's payment subscriptions.
     * Note: This returns payment subscriptions (Stripe), not newsletter/email subscriptions (Unelma Mail).
     */
    public function subscriptions(Request $request)
    {
        $user = $request->user();

        $subscriptions = $user->subscriptions()->orderByDesc('created_at')->get()->map(function ($subscription) {
            return [
                'id' => $subscription->id,
                'name' => $subscription->name,
                'stripe_id' => $subscription->stripe_id,
                'status' => $subscription->stripe_status,
                'price_id' => $subscription->stripe_price,
                'quantity' => $subscription->quantity,
                'amount' => $subscription->amount,
                'payment_type' => 'subscription', // Always subscription for this endpoint
                'trial_ends_at' => $subscription->trial_ends_at?->format('Y-m-d H:i:s'),
                'ends_at' => $subscription->ends_at?->format('Y-m-d H:i:s'),
                'created_at' => $subscription->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $subscription->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
            'has_active_subscription' => $user->subscriptions()->whereIn('stripe_status', ['active', 'trialing'])->exists(),
        ]);
    }

    /**
     * Get user's one-time purchases.
     * Note: This returns one-time payment purchases (Stripe), not subscriptions or newsletter subscriptions.
     */
    public function purchases(Request $request)
    {
        $user = $request->user();
        $purchases = $user->purchases()
            ->with(['product', 'service', 'plan'])
            ->orderByDesc('purchased_at')
            ->get()
            ->map(function ($purchase) {
                $itemName = null;
                $itemType = null;
                
                if ($purchase->product) {
                    $itemName = $purchase->product->name;
                    $itemType = 'product';
                } elseif ($purchase->service) {
                    $itemName = $purchase->service->name;
                    $itemType = 'service';
                } elseif ($purchase->plan) {
                    $itemName = $purchase->plan->name;
                    $itemType = 'plan';
                }

                return [
                    'id' => $purchase->id,
                    'item_name' => $itemName,
                    'item_type' => $itemType,
                    'product_id' => $purchase->product_id,
                    'service_id' => $purchase->service_id,
                    'plan_id' => $purchase->plan_id,
                    'stripe_payment_intent_id' => $purchase->stripe_payment_intent_id,
                    'stripe_session_id' => $purchase->stripe_session_id,
                    'stripe_price_id' => $purchase->stripe_price_id,
                    'amount' => (float) $purchase->amount,
                    'currency' => $purchase->currency,
                    'status' => $purchase->status,
                    'quantity' => $purchase->quantity,
                    'payment_type' => 'one_time', // Always one-time for this endpoint
                    'purchased_at' => $purchase->purchased_at?->format('Y-m-d H:i:s'),
                    'created_at' => $purchase->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $purchase->updated_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'purchases' => $purchases,
            'total_purchases' => $purchases->count(),
            'total_spent' => $purchases->where('status', 'completed')->sum('amount'),
        ]);
    }
}
