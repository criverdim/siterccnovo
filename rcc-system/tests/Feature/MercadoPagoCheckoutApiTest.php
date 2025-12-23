<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MercadoPagoCheckoutApiTest extends TestCase
{
    use RefreshDatabase;

    private function loginAndEvent(): array
    {
        $user = User::factory()->create();
        $this->be($user);
        $event = Event::factory()->create(['is_paid' => true, 'price' => 12.34]);
        $res = $this->get('/checkout?event='.$event->id.'&method=pix');
        $res->assertStatus(200);
        $participationId = \App\Models\EventParticipation::where('user_id', $user->id)->where('event_id', $event->id)->value('id');

        return [$user, $event, $participationId];
    }

    public function test_pix_checkout_updates_participation_and_returns_json(): void
    {
        [$user, $event, $pid] = $this->loginAndEvent();
        $payload = [
            'participation_id' => $pid,
            'payment_method' => 'pix',
            'payer' => ['email' => 'test_user_123@testuser.com'],
        ];
        $res = $this->post('/checkout', $payload);
        $res->assertStatus(200);
        $json = $res->json();
        $this->assertArrayHasKey('status', $json);
        $this->assertEquals('pending', $json['status']);

        $p = \App\Models\EventParticipation::find($pid);
        $this->assertEquals('pix', $p->payment_method);
        $this->assertEquals('pending', $p->payment_status);
        $this->assertNotEmpty($p->mp_payment_id);
    }

    public function test_credit_card_checkout_requires_token_and_identification(): void
    {
        [$user, $event, $pid] = $this->loginAndEvent();
        $payload = [
            'participation_id' => $pid,
            'payment_method' => 'credit_card',
            'payer' => ['email' => 'test_user_123@testuser.com'],
        ];
        $res = $this->post('/checkout', $payload);
        $res->assertStatus(302);
        $res->assertSessionHasErrors(['token', 'installments', 'payer.identification.type', 'payer.identification.number']);
    }

    public function test_boleto_checkout_requires_address_and_identification(): void
    {
        [$user, $event, $pid] = $this->loginAndEvent();
        $payload = [
            'participation_id' => $pid,
            'payment_method' => 'boleto',
            'payer' => ['email' => 'test_user_123@testuser.com'],
        ];
        $res = $this->post('/checkout', $payload);
        $res->assertStatus(302);
        $res->assertSessionHasErrors([
            'payer.first_name', 'payer.last_name', 'payer.identification.type', 'payer.identification.number',
            'payer.address.street_name', 'payer.address.street_number', 'payer.address.zip_code',
            'payer.address.neighborhood', 'payer.address.state', 'payer.address.city',
        ]);
    }
}
