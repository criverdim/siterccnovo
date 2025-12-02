<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MercadoPagoWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_updates_payment_status_and_generates_ticket(): void
    {
        $event = Event::factory()->create(['is_paid' => true, 'price' => 50, 'generates_ticket' => true]);
        $user = User::factory()->create();
        $p = EventParticipation::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'payment_status' => 'pending',
            'mp_payment_id' => 'test_123',
        ]);

        $res = $this->post('/webhooks/mercadopago', [
            'type' => 'payment',
            'data' => ['id' => 'test_123', 'status' => 'approved'],
            'action' => 'payment.updated',
        ]);
        $res->assertStatus(200);

        $p->refresh();
        $this->assertEquals('approved', $p->payment_status);
        $this->assertNotEmpty($p->ticket_uuid);
    }
}
