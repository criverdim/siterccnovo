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

    public function test_event_show_renders_folder_image_when_featured_missing(): void
    {
        $event = Event::factory()->create([
            'name' => 'Encontro Paroquial',
            'start_date' => now()->addDays(3),
            'end_date' => now()->addDays(3),
            'location' => 'Salão Paroquial',
            'price' => 0,
            'capacity' => 50,
            'status' => 'active',
            'is_active' => true,
            'folder_image' => 'events/folder.jpg',
            'featured_image' => null,
        ]);
        $response = $this->get('/events/'.$event->id);
        $response->assertStatus(200);
        $response->assertSee('/storage/events/folder.jpg');
    }

    public function test_events_index_renders_folder_image_on_card(): void
    {
        $event = Event::factory()->create([
            'name' => 'Vigília Jovem',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(7),
            'location' => 'Capela São José',
            'price' => 0,
            'capacity' => 100,
            'status' => 'active',
            'is_active' => true,
            'folder_image' => 'events/cards/vigilia.jpg',
            'featured_image' => null,
        ]);
        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee("background-image: url('/storage/events/cards/vigilia.jpg')", false);
    }

    public function test_events_show_normalizes_bare_filename_for_folder_image(): void
    {
        $event = Event::factory()->create([
            'name' => 'Retiro Teste',
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(2),
            'location' => 'Salão',
            'price' => 0,
            'capacity' => 10,
            'status' => 'active',
            'is_active' => true,
            'folder_image' => '287712508_397249315733025_7627927783651194042_n.jpg',
            'featured_image' => null,
        ]);
        $response = $this->get('/events/'.$event->id);
        $response->assertStatus(200);
        $response->assertSee("background-image: url('/storage/events/folder/287712508_397249315733025_7627927783651194042_n.jpg')", false);
    }
}
