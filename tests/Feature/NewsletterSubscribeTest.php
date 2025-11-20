<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NewsletterSubscribeTest extends TestCase
{
    public function test_it_subscribes_a_contact_to_unelma_mail(): void
    {
        Config::set('services.unelma_mail.api_key', 'test-api-key');
        Config::set('services.unelma_mail.list_uid', 'list-uid-123');

        Http::fake([
            '*' => Http::response([
                'message' => 'Subscriber created',
            ], 200),
        ]);

        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response->assertCreated()
            ->assertJson([
                'success' => true,
                'message' => 'You have been subscribed to the newsletter.',
            ]);
    }

    public function test_missing_credentials_returns_error(): void
    {
        Config::set('services.unelma_mail.api_key', null);
        Config::set('services.unelma_mail.list_uid', null);

        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
            ]);
    }
}

