<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Services\UnelmaMailService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function __construct(
        protected UnelmaMailService $unelmaMail
    ) {
    }

    /**
     * Subscribe the given email address to the newsletter.
     * Tries Unelma Mail first, falls back to local database storage.
     */
    public function subscribe(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email', 'max:255'],
                'first_name' => ['nullable', 'string', 'max:255'],
                'last_name' => ['nullable', 'string', 'max:255'],
                'send_verification' => ['nullable', 'boolean'],
                'double_opt_in' => ['nullable', 'boolean'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        // Log the incoming request for debugging
        \Log::info('Newsletter subscription request', [
            'email' => $data['email'],
            'send_verification' => $data['send_verification'] ?? false,
            'double_opt_in' => $data['double_opt_in'] ?? false,
        ]);

        // Try Unelma Mail first
        $unelmaSuccess = false;
        $unelmaError = null;
        
        try {
            $result = $this->unelmaMail->subscribe(
                $data['email'],
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
                [
                    'send_verification' => $data['send_verification'] ?? false,
                    'double_opt_in' => $data['double_opt_in'] ?? false,
                ]
            );
            $unelmaSuccess = true;

            // Also save to local database as backup
            $this->saveToLocalDatabase($data, 'synced');

            $message = 'You have been subscribed to the newsletter.';
            if (!empty($data['send_verification']) || !empty($data['double_opt_in'])) {
                $message = 'Subscription successful. Please check your email to verify your subscription.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $result,
            ], 201);
        } catch (\RuntimeException $exception) {
            $unelmaError = $exception->getMessage();
        } catch (ConnectionException $exception) {
            $unelmaError = 'Unable to reach Unelma Mail service.';
        } catch (RequestException $exception) {
            $response = $exception->response;
            $statusCode = $response->status() ?: 502;
            $responseData = $response->json() ?? [];

            // Check if it's a duplicate email error (not a server error)
            if ($statusCode === 403 || $statusCode === 422) {
                $errorMessages = [];
                if (is_array($responseData)) {
                    foreach ($responseData as $field => $messages) {
                        if (is_array($messages)) {
                            $errorMessages[$field] = $messages;
                        } else {
                            $errorMessages[$field] = [$messages];
                        }
                    }
                }
                
                // Check if already subscribed
                $firstError = reset($errorMessages);
                $errorMsg = is_array($firstError) ? ($firstError[0] ?? '') : (string) $firstError;
                
                if (str_contains(strtolower($errorMsg), 'taken') || 
                    str_contains(strtolower($errorMsg), 'already') ||
                    str_contains(strtolower($errorMsg), 'exists')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email is already subscribed to the newsletter.',
                    ], 409);
                }
            }

            // Server error - fall back to local storage
            $unelmaError = 'Unelma Mail server error (status: ' . $statusCode . ')';
            
            \Log::warning('Unelma Mail API error, falling back to local storage', [
                'status' => $statusCode,
                'response' => $responseData,
            ]);
        } catch (\Throwable $exception) {
            $unelmaError = $exception->getMessage();
            
            \Log::warning('Unelma Mail unexpected error, falling back to local storage', [
                'error' => $exception->getMessage(),
                'type' => get_class($exception),
            ]);
        }

        // Unelma Mail failed - save to local database
        if (!$unelmaSuccess) {
            try {
                $subscriber = $this->saveToLocalDatabase($data, 'pending');
                
                \Log::info('Newsletter subscriber saved to local database (Unelma Mail unavailable)', [
                    'email' => $data['email'],
                    'subscriber_id' => $subscriber->id,
                    'unelma_error' => $unelmaError,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'You have been subscribed to the newsletter.',
                    'note' => config('app.debug') ? 'Saved locally (Unelma Mail temporarily unavailable)' : null,
                ], 201);
            } catch (\Illuminate\Database\QueryException $e) {
                // Check for duplicate entry
                if (str_contains($e->getMessage(), 'Duplicate entry') || 
                    str_contains($e->getMessage(), 'UNIQUE constraint')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email is already subscribed to the newsletter.',
                    ], 409);
                }
                
                \Log::error('Failed to save newsletter subscriber to database', [
                    'email' => $data['email'],
                    'error' => $e->getMessage(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to process your subscription. Please try again later.',
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to process your subscription. Please try again later.',
        ], 500);
    }

    /**
     * Save subscriber to local database.
     */
    protected function saveToLocalDatabase(array $data, string $status = 'pending'): NewsletterSubscriber
    {
        return NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'status' => $status,
            ]
        );
    }
}



