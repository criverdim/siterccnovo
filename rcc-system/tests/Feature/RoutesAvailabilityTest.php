<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutesAvailabilityTest extends TestCase
{
    public function test_events_index_opens(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_events_show_missing_returns_404(): void
    {
        $response = $this->get('/events/999999');
        $response->assertStatus(404);
    }

    public function test_my_tickets_requires_auth(): void
    {
        $response = $this->get('/events/my-tickets');
        $this->assertTrue($response->status() === 302);
        $location = $response->headers->get('Location');
        $this->assertTrue(is_string($location) && str_contains($location, '/login'));
    }
}
