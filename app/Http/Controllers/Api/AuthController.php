<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);

            // Create token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_admin' => $user->is_admin,
                    'created_at' => $user->created_at?->toISOString() ?? $user->created_at,
                ],
                'token' => $token,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Registration failed',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred during registration',
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean']
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete old tokens
        $user->tokens()->delete();

        // Create new token
        // Token expiration based on "remember me"
        $expiresAt = $request->boolean('remember')
            ? now()->addWeeks(2)
            : now()->addHours(2);

        $tokenResult = $user->createToken('auth-token', [], $expiresAt);
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'message' => 'Login successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at?->toISOString() ?? $user->created_at,
            ],
            'token' => $token,
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Delete current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successfully',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at?->toISOString() ?? $user->created_at,
                'updated_at' => $user->updated_at?->toISOString() ?? $user->updated_at,
            ],
        ]);
    }

    /**
     * Authenticate user via Google OAuth token
     */
    public function google(Request $request)
    {
        $validated = $request->validate([
            'token' => ['nullable', 'string'],
            'email' => ['required_without:token', 'nullable', 'email'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        if (! empty($validated['token'])) {
            try {
                $googleUser = Socialite::driver('google')
                    ->stateless()
                    ->userFromToken($validated['token']);
            } catch (\Throwable $exception) {
                \Log::warning('Google OAuth token validation failed.', [
                    'error' => $exception->getMessage(),
                ]);

                return response()->json([
                    'message' => 'Google authentication failed.',
                ], 422);
            }

            if (! $googleUser || ! $googleUser->getEmail()) {
                return response()->json([
                    'message' => 'Unable to retrieve Google account details.',
                ], 422);
            }

            $user = User::where('google_id', $googleUser->getId())->first();

            if (! $user && $googleUser->getEmail()) {
                $user = User::where('email', $googleUser->getEmail())->first();
            }

            if ($user) {
                $user->fill([
                    'name' => $googleUser->getName() ?: $user->name,
                    'email' => $googleUser->getEmail(),
                ]);
            } else {
                $user = new User([
                    'name' => $googleUser->getName() ?: ($googleUser->getEmail() ?? 'Google User'),
                    'email' => $googleUser->getEmail(),
                    'is_admin' => false,
                ]);
            }

            if (! $user->google_id) {
                $user->google_id = $googleUser->getId();
            }

            if (! $user->email_verified_at) {
                $user->email_verified_at = now();
            }

            if (! $user->password) {
                $user->password = Hash::make(Str::random(32));
            }

            if ($avatar = $googleUser->getAvatar()) {
                $user->profile_picture = $avatar;
            }
        } else {
            $user = User::where('email', $validated['email'])->first();

            if ($user) {
                if (! empty($validated['name'])) {
                    $user->name = $validated['name'];
                }
            } else {
                $user = new User([
                    'email' => $validated['email'],
                    'name' => $validated['name'] ?: Str::before($validated['email'], '@'),
                    'is_admin' => false,
                ]);
            }

            if (! $user->password) {
                $user->password = Hash::make(Str::random(32));
            }

            if (! $user->email_verified_at) {
                $user->email_verified_at = now();
            }
        }

        $user->save();

        // Invalidate existing tokens before issuing a new one
        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at?->toISOString() ?? $user->created_at,
            ],
            'token' => $token,
        ]);
    }
}
