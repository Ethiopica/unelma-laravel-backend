<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * Subscribe the given email address to Unelma Mail.
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

            // Unelma Mail sends verification emails automatically if double opt-in is enabled
            // in the Unelma Mail dashboard for the list. The API doesn't require verification parameters.
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
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        } catch (ConnectionException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to reach Unelma Mail. Please check your internet connection or API base URL.',
            ], 502);
        } catch (RequestException $exception) {
            $response = $exception->response;
            $statusCode = $response->status() ?: 502;
            $responseData = $response->json() ?? [];
            $responseBody = $response->body();

            // Extract error messages from Unelma Mail response
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

            // Build user-friendly message
            $message = 'Failed to subscribe to newsletter.';
            
            // Handle validation errors (like email already taken)
            if (!empty($errorMessages)) {
                $firstError = reset($errorMessages);
                if (is_array($firstError) && !empty($firstError)) {
                    $message = $firstError[0];
                } elseif (is_string($firstError)) {
                    $message = $firstError;
                }
            } elseif (isset($responseData['message'])) {
                $rawMessage = $responseData['message'];
                
                // Clean up Unelma Mail internal errors (view-related errors)
                if (str_contains($rawMessage, 'View:') || 
                    str_contains($rawMessage, 'Undefined variable') ||
                    str_contains($rawMessage, 'resources/views')) {
                    // This is an internal Unelma Mail error, provide user-friendly message
                    if ($statusCode === 500) {
                        $message = 'The newsletter service is temporarily unavailable. Please try again later.';
                    } else {
                        $message = 'An error occurred while processing your subscription. Please try again.';
                    }
                } else {
                    $message = $rawMessage;
                }
            } elseif ($statusCode === 500) {
                // Unelma Mail server error
                $message = 'The newsletter service is temporarily unavailable. Please try again later.';
            } elseif ($statusCode === 403) {
                // Forbidden - usually means email already exists or validation failed
                $message = 'Unable to complete subscription. This email may already be subscribed.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errorMessages ?: ($responseData ?: []),
            ], $statusCode);
        } catch (\Throwable $exception) {
            // Log the full exception for debugging
            \Log::error('Newsletter subscription unexpected error', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'exception_type' => get_class($exception),
            ]);

            // Don't call report() as it might trigger view rendering
            // The exception handler in bootstrap/app.php will handle it
            
            // Clean error message - remove view-related details
            $errorMessage = $exception->getMessage();
            if (str_contains($errorMessage, 'View:') || str_contains($errorMessage, 'Undefined variable')) {
                $errorMessage = 'An error occurred while processing your subscription. Please try again later.';
            }

            // Always return JSON, never render views
            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $exception->getMessage() : $errorMessage,
                'error' => config('app.debug') ? [
                    'type' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ] : null,
            ], 500);
        }
    }
}



