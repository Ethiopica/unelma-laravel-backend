<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UnelmaMailService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $result = $this->unelmaMail->subscribe(
                $data['email'],
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
            );

            return response()->json([
                'success' => true,
                'message' => 'You have been subscribed to the newsletter.',
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
            if (!empty($errorMessages)) {
                $firstError = reset($errorMessages);
                if (is_array($firstError) && !empty($firstError)) {
                    $message = $firstError[0];
                }
            } elseif (isset($responseData['message'])) {
                $message = $responseData['message'];
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errorMessages ?: $responseData,
            ], $statusCode);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Unexpected error while subscribing.',
            ], 500);
        }
    }
}



