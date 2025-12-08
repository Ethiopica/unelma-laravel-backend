<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UnelmaMailService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class MailSubscriberController extends Controller
{
    public function __construct(
        protected UnelmaMailService $unelmaMail
    ) {
    }

    public function index(Request $request): View
    {
        $perPage = min(max((int) $request->input('per_page', 20), 5), 100);
        $page = max((int) $request->input('page', 1), 1);
        $status = $request->input('status');

        $subscribers = collect();
        $meta = null;
        $error = null;

        try {
            $response = $this->unelmaMail->subscribers($perPage, $page, $status);

            // Handle different response structures from Unelma Mail
            // Response might be: {data: [...], meta: {...}} or directly an array
            $subscribersData = data_get($response, 'data', []);
            
            // If response is directly an array (not wrapped in 'data')
            if (empty($subscribersData) && is_array($response) && isset($response[0])) {
                $subscribersData = $response;
            }

            $subscribers = collect($subscribersData)
                ->map(function (array $subscriber): array {
                    $createdAt = data_get($subscriber, 'created_at') 
                        ?? data_get($subscriber, 'createdAt')
                        ?? data_get($subscriber, 'CREATED_AT');

                    try {
                        $createdAt = $createdAt ? Carbon::parse($createdAt) : null;
                    } catch (\Throwable) {
                        $createdAt = null;
                    }

                    return [
                        'id' => data_get($subscriber, 'subscriber_uid') 
                            ?? data_get($subscriber, 'uid')
                            ?? data_get($subscriber, 'SUBSCRIBER_UID')
                            ?? data_get($subscriber, 'id'),
                        'email' => data_get($subscriber, 'email') 
                            ?? data_get($subscriber, 'EMAIL'),
                        'first_name' => data_get($subscriber, 'first_name') 
                            ?? data_get($subscriber, 'FIRST_NAME')
                            ?? data_get($subscriber, 'firstName'),
                        'last_name' => data_get($subscriber, 'last_name') 
                            ?? data_get($subscriber, 'LAST_NAME')
                            ?? data_get($subscriber, 'lastName'),
                        'status' => strtolower(data_get($subscriber, 'status', 'subscribed') 
                            ?? data_get($subscriber, 'STATUS', 'subscribed')),
                        'created_at' => $createdAt,
                        'source' => data_get($subscriber, 'source') 
                            ?? data_get($subscriber, 'SOURCE'),
                        'tags' => data_get($subscriber, 'tags', []) 
                            ?? data_get($subscriber, 'TAGS', []),
                    ];
                });

            // Handle pagination meta - try different possible structures
            $meta = [
                'current_page' => data_get($response, 'meta.current_page') 
                    ?? data_get($response, 'current_page') 
                    ?? data_get($response, 'page') 
                    ?? $page,
                'last_page' => data_get($response, 'meta.last_page') 
                    ?? data_get($response, 'last_page') 
                    ?? data_get($response, 'total_pages') 
                    ?? $page,
                'per_page' => data_get($response, 'meta.per_page') 
                    ?? data_get($response, 'per_page') 
                    ?? $perPage,
                'total' => data_get($response, 'meta.total') 
                    ?? data_get($response, 'total') 
                    ?? $subscribers->count(),
            ];
        } catch (\RuntimeException $exception) {
            $error = $exception->getMessage();
            \Log::error('MailSubscriberController RuntimeException', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        } catch (RequestException $exception) {
            $response = optional($exception->response);
            $errorMessage = data_get($response->json(), 'message', 'Failed to load subscribers from Unelma Mail.');
            
            // Provide more detailed error information
            if ($response && $response->status() === 401) {
                $errorMessage = 'Unauthorized: Invalid API key. Please check your UNELMA_MAIL_API_KEY in .env file.';
            } elseif ($response && $response->status() === 403) {
                $errorMessage = 'Forbidden: Access denied. Please check your API credentials and permissions.';
            } elseif ($response && $response->status() === 404) {
                $errorMessage = 'Not Found: The subscribers endpoint was not found. Please verify the API endpoint.';
            }
            
            $error = $errorMessage;
            \Log::error('MailSubscriberController RequestException', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);
        } catch (\Throwable $exception) {
            report($exception);
            $error = 'Something went wrong while loading subscribers. Please check the logs for details.';
            \Log::error('MailSubscriberController Exception', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        return view('admin.subscribers.index', [
            'subscribers' => $subscribers,
            'meta' => $meta,
            'error' => $error,
            'filters' => [
                'status' => $status,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Delete a subscriber from Unelma Mail.
     */
    public function destroy(Request $request, string $subscriberUid)
    {
        try {
            $this->unelmaMail->deleteSubscriber($subscriberUid);

            return redirect()
                ->route('admin.subscribers.index')
                ->with('success', 'Subscriber deleted successfully.');
        } catch (\RuntimeException $exception) {
            \Log::error('MailSubscriberController Delete RuntimeException', [
                'message' => $exception->getMessage(),
                'subscriber_uid' => $subscriberUid,
                'trace' => $exception->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.subscribers.index')
                ->with('error', 'Failed to delete subscriber: ' . $exception->getMessage());
        } catch (RequestException $exception) {
            $response = optional($exception->response);
            $errorMessage = data_get($response->json(), 'message', 'Failed to delete subscriber from Unelma Mail.');

            if ($response && $response->status() === 404) {
                $errorMessage = 'Subscriber not found. It may have already been deleted.';
            } elseif ($response && $response->status() === 401) {
                $errorMessage = 'Unauthorized: Invalid API key. Please check your UNELMA_MAIL_API_KEY in .env file.';
            } elseif ($response && $response->status() === 403) {
                $errorMessage = 'Forbidden: Access denied. Please check your API credentials and permissions.';
            }

            \Log::error('MailSubscriberController Delete RequestException', [
                'status' => $response->status(),
                'body' => $response->body(),
                'subscriber_uid' => $subscriberUid,
            ]);

            return redirect()
                ->route('admin.subscribers.index')
                ->with('error', $errorMessage);
        } catch (\Throwable $exception) {
            report($exception);
            \Log::error('MailSubscriberController Delete Exception', [
                'message' => $exception->getMessage(),
                'subscriber_uid' => $subscriberUid,
                'trace' => $exception->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.subscribers.index')
                ->with('error', 'Something went wrong while deleting the subscriber. Please check the logs for details.');
        }
    }
}

