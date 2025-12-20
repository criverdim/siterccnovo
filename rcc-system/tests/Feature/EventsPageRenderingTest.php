<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsPageRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_index_renders_cards(): void
    {
        $event = Event::factory()->create([
            'name' => 'Retiro Jovem',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(11),
            'location' => 'Igreja Matriz',
            'price' => 0,
            'capacity' => 100,
            'status' => 'active',
            'is_active' => true,
        ]);

        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Próximos Eventos');
        $response->assertSee('Retiro Jovem');
    }

    public function test_event_show_renders_gallery_and_map_when_present(): void
    {
        $event = Event::factory()->create([
            'name' => 'Congresso RCC',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(5),
            'location' => 'Centro de Eventos',
            'price' => 10,
            'capacity' => 200,
            'status' => 'active',
            'is_active' => true,
            'featured_image' => 'events/cover.jpg',
            'gallery_images' => ['events/gallery/img1.jpg', 'events/gallery/img2.jpg', 'events/gallery/img3.jpg'],
            'map_embed_url' => 'https://maps.google.com/?q=Test',
        ]);

        $response = $this->get('/events/'.$event->id);
        $response->assertStatus(200);
        $response->assertSee('Galeria');
        $response->assertSee('Mapa de Localização');
    }
}
