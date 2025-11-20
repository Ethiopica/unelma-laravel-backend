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
     * @throws \RuntimeException
     * @throws RequestException
     */
    public function subscribe(string $email, ?string $firstName = null, ?string $lastName = null, array $attributes = []): array
    {
        if (! $this->listUid) {
            throw new \RuntimeException('Unelma Mail list UID is not configured.');
        }

        // Unelma Mail public API expects form data with uppercase field names
        $payload = array_filter([
            'list_uid' => $this->listUid,
            'EMAIL' => $email,
            'FIRST_NAME' => $firstName,
            'LAST_NAME' => $lastName,
        ], static fn($value) => $value !== null);

        // Use the public subscribers endpoint with form data (application/x-www-form-urlencoded)
        $response = Http::baseUrl($this->baseUrl)
            ->asForm() // This sets Content-Type to application/x-www-form-urlencoded
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/public/subscribers', $payload);

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $response->json() ?? [];
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

        // Build query parameters
        $query = array_filter([
            'list_uid' => $this->listUid,
            'per_page' => $perPage,
            'page' => $page,
            'status' => $status,
        ], static fn($value) => $value !== null);

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
            \Log::error('Unelma Mail Fetch Subscribers Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
                'url' => $this->baseUrl . $url,
            ]);
            throw new RequestException($response);
        }

        $jsonResponse = $response->json() ?? [];
        
        // Log successful response structure for debugging
        \Log::info('Unelma Mail Fetch Subscribers Success', [
            'response_keys' => array_keys($jsonResponse),
            'data_count' => count(data_get($jsonResponse, 'data', [])),
        ]);

        return $jsonResponse;
    }
}
