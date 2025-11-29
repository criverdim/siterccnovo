<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_event_with_new_fields(): void
    {
        $event = Event::create([
            'name' => 'Retiro Jovem',
            'category' => 'retiro',
            'description' => '<p>Evento especial</p>',
            'location' => 'Paróquia São José',
            'start_date' => now()->toDateString(),
            'start_time' => '19:00:00',
            'end_date' => now()->addDay()->toDateString(),
            'end_time' => '21:00:00',
            'days_count' => 2,
            'min_age' => 14,
            'arrival_info' => 'Estacionamento lateral',
            'map_embed_url' => 'https://maps.google.com',
            'is_paid' => true,
            'price' => 100,
            'parceling_enabled' => true,
            'parceling_max' => 3,
            'coupons_enabled' => false,
            'extra_services' => [['title'=>'Almoço','desc'=>'Buffet','price'=>30]],
            'terms' => '<p>Leia os termos</p>',
            'rules' => '<p>Siga as regras</p>',
            'generates_ticket' => true,
            'allows_online_payment' => true,
            'capacity' => 200,
            'show_on_homepage' => true,
            'is_active' => true,
        ]);

        $this->assertNotNull($event->id);
        $this->assertEquals('retiro', $event->category);
        $this->assertTrue($event->is_paid);
        $this->assertTrue($event->generates_ticket);
    }

    public function test_user_inscription_and_checkout_simulation(): void
    {
        $event = Event::factory()->create(['is_paid'=>true,'price'=>50,'generates_ticket'=>true]);
        $user = User::factory()->create();

        $this->be($user);

        $inscribe = $this->post("/events/{$event->id}/participate", []);
        $inscribe->assertStatus(200);
        $pid = $inscribe->json('participation_id');
        $this->assertNotEmpty($pid);

        $checkout = $this->post('/checkout', [
            'participation_id' => $pid,
            'payment_method' => 'pix',
            'payer' => ['email'=>$user->email],
        ]);
        $checkout->assertStatus(200);
        $this->assertEquals('pending', $checkout->json('status'));
    }

    public function test_event_show_page_renders_new_blocks(): void
    {
        $event = Event::factory()->create([
            'category'=>'congresso',
            'arrival_info'=>'Chegar 30min antes',
            'map_embed_url'=>'https://maps.google.com',
            'extra_services'=>[['title'=>'Café','desc'=>'Incluso','price'=>0]],
            'terms'=>'<p>Termos</p>',
            'rules'=>'<p>Regras</p>',
        ]);

        $res = $this->get("/events/{$event->id}");
        $res->assertStatus(200);
        $res->assertSee('Chegada e estacionamento');
        $res->assertSee('Serviços adicionais');
        $res->assertSee('Termos e condições');
        $res->assertSee('Regras de participação');
    }
}

