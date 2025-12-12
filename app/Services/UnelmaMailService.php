<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class UnelmaMailService
{
    private const DEFAULT_BASE_URL = 'https://core.unelmamail.com/api/v1';

    protected string $baseUrl;

    protected ?string $apiKey;

    protected ?string $listUid;

    public function __construct()
    {
        $configuredUrl = trim((string) config('services.unelma_mail.base_url'));

        if ($configuredUrl === '' || ! preg_match('/^https?:\\/\\//i', $configuredUrl)) {
            $configuredUrl = self::DEFAULT_BASE_URL;
        }

        $this->baseUrl = rtrim($configuredUrl, '/');
        $this->apiKey = config('services.unelma_mail.api_key');
        $this->listUid = config('services.unelma_mail.list_uid');
    }

    /**
     * Subscribe a contact to Unelma Mail.
     *
     * @param string $email
     * @param string|null $firstName
     * @param string|null $lastName
     * @param array $attributes Additional attributes including verification flags:
     *                          - send_verification (bool): Request verification email
     *                          - double_opt_in (bool): Enable double opt-in
     * @return array
     * @throws \RuntimeException
     * @throws RequestException
     */
    public function subscribe(string $email, ?string $firstName = null, ?string $lastName = null, array $attributes = []): array
    {
        if (! $this->listUid) {
            throw new \RuntimeException('Unelma Mail list UID is not configured.');
        }

        // Unelma Mail public API expects form data with uppercase field names
        // Format matches: curl -X POST https://core.unelmamail.com/api/v1/public/subscribers
        // -d list_uid='...' -d EMAIL='...' -d FIRST_NAME='...' -d LAST_NAME='...'
        // Note: Verification emails are sent automatically by Unelma Mail when using the public API endpoint
        // if double opt-in is enabled in the Unelma Mail dashboard for this list
        $payload = array_filter([
            'list_uid' => $this->listUid,
            'EMAIL' => $email,
            'FIRST_NAME' => $firstName,
            'LAST_NAME' => $lastName,
        ], static fn($value) => $value !== null);

        // Note: Based on Unelma Mail's curl example, verification emails are handled automatically
        // by their system when double opt-in is enabled in the dashboard. The public API endpoint
        // doesn't require explicit verification parameters - it uses the list's configured settings.
        // If verification flags are requested, we log them but don't send them as Unelma Mail
        // handles verification based on list configuration, not API parameters.
        $sendVerification = !empty($attributes['send_verification']) || !empty($attributes['double_opt_in']);

        // Log the payload being sent for debugging
        \Log::info('Unelma Mail API request', [
            'url' => $this->baseUrl . '/public/subscribers',
            'payload' => $payload,
            'send_verification' => $sendVerification,
        ]);

        // Use the public subscribers endpoint with JSON format
        // Based on Unelma Mail's curl example: curl -X POST -H 'Content-Type: application/json' -d '{...}'
        $response = Http::baseUrl($this->baseUrl)
            ->asJson()
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('/public/subscribers', $payload);

        if ($response->failed()) {
            \Log::error('Unelma Mail API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload_sent' => $payload,
            ]);
            
            throw new RequestException($response);
        }

        $result = $response->json() ?? [];
        
        \Log::info('Unelma Mail API Success', [
            'response' => $result,
        ]);

        return $result;
    }

    /**
     * Fetch subscribers from Unelma Mail.
     *
     * @throws \RuntimeException
     * @throws RequestException
     */
    public function subscribers(int $perPage = 20, int $page = 1, ?string $status = null): array
    {
        if (! $this->apiKey || ! $this->listUid) {
            throw new \RuntimeException('Unelma Mail credentials are not configured. Please set UNELMA_MAIL_API_KEY and UNELMA_MAIL_LIST_UID in your .env file.');
        }

        // Build query parameters (filter out null and empty strings)
        $query = array_filter([
            'list_uid' => $this->listUid,
            'per_page' => $perPage,
            'page' => $page,
            'status' => $status,
        ], static fn($value) => $value !== null && $value !== '');

        // Try different authentication methods
        // Option 1: API token in query string (most common)
        $url = '/subscribers?' . http_build_query(array_merge($query, ['api_token' => $this->apiKey]));

        $response = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get($url);

        // If that fails, try with API token in header
        if ($response->failed() && $response->status() === 401) {
            $url = '/subscribers?' . http_build_query($query);
            $response = Http::baseUrl($this->baseUrl)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->get($url);
        }

        // If still fails, try with X-API-Key header
        if ($response->failed() && $response->status() === 401) {
            $url = '/subscribers?' . http_build_query($query);
            $response = Http::baseUrl($this->baseUrl)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'X-API-Key' => $this->apiKey,
                ])
                ->get($url);
        }

        // Log the response for debugging
        if ($response->failed()) {

            throw new RequestException($response);
        }

        $jsonResponse = $response->json() ?? [];

        // Log successful response structure for debugging


        return $jsonResponse;
    }

    /**
     * Delete a subscriber from Unelma Mail.
     *
     * @param string $subscriberUid The subscriber UID
     * @return array
     * @throws \RuntimeException
     * @throws RequestException
     */
    public function deleteSubscriber(string $subscriberUid): array
    {
        if (! $this->apiKey || ! $this->listUid) {
            throw new \RuntimeException('Unelma Mail credentials are not configured. Please set UNELMA_MAIL_API_KEY and UNELMA_MAIL_LIST_UID in your .env file.');
        }

        // Build query parameters
        $query = [
            'list_uid' => $this->listUid,
            'api_token' => $this->apiKey,
        ];

        // Try different authentication methods
        $url = '/subscribers/' . $subscriberUid . '?' . http_build_query($query);

        $response = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->delete($url);

        // If that fails, try with API token in header
        if ($response->failed() && $response->status() === 401) {
            $url = '/subscribers/' . $subscriberUid . '?' . http_build_query(['list_uid' => $this->listUid]);
            $response = Http::baseUrl($this->baseUrl)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->delete($url);
        }

        // If still fails, try with X-API-Key header
        if ($response->failed() && $response->status() === 401) {
            $url = '/subscribers/' . $subscriberUid . '?' . http_build_query(['list_uid' => $this->listUid]);
            $response = Http::baseUrl($this->baseUrl)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'X-API-Key' => $this->apiKey,
                ])
                ->delete($url);
        }

        if ($response->failed()) {
            \Log::error('Unelma Mail Delete Subscriber Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'subscriber_uid' => $subscriberUid,
            ]);

            throw new RequestException($response);
        }

        $jsonResponse = $response->json() ?? [];

        \Log::info('Unelma Mail Delete Subscriber Success', [
            'subscriber_uid' => $subscriberUid,
            'response' => $jsonResponse,
        ]);

        return $jsonResponse;
    }
}
