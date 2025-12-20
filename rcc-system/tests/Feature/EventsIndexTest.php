<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_filters_by_category_and_location(): void
    {
        Event::factory()->active()->create([
            'name' => 'Retiro A',
            'category' => 'retiro',
            'location' => 'Paróquia São José',
            'start_date' => now()->addMonth(),
        ]);
        Event::factory()->active()->create([
            'name' => 'Congresso B',
            'category' => 'congresso',
            'location' => 'Centro de Eventos',
            'start_date' => now()->addMonth(),
        ]);

        $res = $this->get('/events?category=retiro&location=Paróquia');
        $res->assertStatus(200);
        $res->assertSee('Retiro A');
        $res->assertDontSee('Congresso B');
    }

    public function test_filters_by_date_range(): void
    {
        Event::factory()->active()->create([
            'name' => 'Encontro X',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(11),
        ]);
        Event::factory()->active()->create([
            'name' => 'Encontro Y',
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(31),
        ]);

        $from = now()->addDays(5)->toDateString();
        $to = now()->addDays(20)->toDateString();
        $res = $this->get("/events?date_from={$from}&date_to={$to}");
        $res->assertStatus(200);
        $res->assertSee('Encontro X');
        $res->assertDontSee('Encontro Y');
    }

    public function test_json_endpoint_returns_events_and_next_page_url(): void
    {
        Event::factory()->count(15)->active()->create();

        $res = $this->get('/events', ['Accept' => 'application/json']);
        $res->assertStatus(200);
        $json = $res->json();
        $this->assertArrayHasKey('events', $json);
        $this->assertArrayHasKey('nextPageUrl', $json);
        $this->assertIsArray($json['events']);
        $this->assertGreaterThan(0, count($json['events']));
        $first = $json['events'][0];
        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('name', $first);
        $this->assertArrayHasKey('photo_thumb_url', $first);
    }
}
