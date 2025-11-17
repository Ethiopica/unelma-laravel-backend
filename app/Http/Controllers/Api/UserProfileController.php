<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * Get user's subscriptions
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
                'trial_ends_at' => $subscription->trial_ends_at?->format('Y-m-d H:i:s'),
                'ends_at' => $subscription->ends_at?->format('Y-m-d H:i:s'),
                'created_at' => $subscription->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $subscription->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'subscriptions' => $subscriptions,
            'has_active_subscription' => $user->subscriptions()->whereIn('stripe_status', ['active', 'trialing'])->exists(),
        ]);
    }
}
