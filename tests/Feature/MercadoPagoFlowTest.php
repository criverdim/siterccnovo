<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MercadoPagoFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_loads_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->be($user);

        $event = Event::factory()->create([
            'is_paid' => true,
            'price' => 15.5,
            'generates_ticket' => true,
        ]);

        $res = $this->get('/checkout?event='.$event->id.'&method=pix');
        $res->assertStatus(200);
    }

    public function test_payment_success_marks_payment_as_paid_when_validation_approved(): void
    {
        $user = User::factory()->create();
        $this->be($user);

        $event = Event::factory()->create([
            'is_paid' => true,
            'price' => 50,
            'generates_ticket' => true,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => 1,
            'amount' => 50,
            'currency' => 'BRL',
            'status' => 'pending',
            'payment_method' => 'mercadopago',
            'external_reference' => null,
            'mercado_pago_id' => 'test_abc_123',
            'mercado_pago_preference_id' => 'pref_123',
            'mercado_pago_data' => [],
        ]);

        $this->app->bind(\App\Services\MercadoPagoService::class, function () {
            return new class extends \App\Services\MercadoPagoService {
                public function __construct() {}
                public function validatePayment(string $paymentId): array
                {
                    return ['success' => true, 'data' => ['status' => 'approved']];
                }
            };
        });

        $res = $this->get('/events/payment/success/'.$payment->id);
        $res->assertStatus(200);

        $payment->refresh();
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals('approved', $payment->payment_status);
    }

    public function test_payment_pending_page_renders(): void
    {
        $user = User::factory()->create();
        $this->be($user);

        $event = Event::factory()->create([
            'is_paid' => true,
            'price' => 10,
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => 1,
            'amount' => 10,
            'currency' => 'BRL',
            'status' => 'pending',
            'payment_method' => 'mercadopago',
        ]);

        $res = $this->get('/events/payment/pending/'.$payment->id);
        $res->assertStatus(200);
    }

    public function test_service_returns_error_when_not_configured(): void
    {
        config(['services.mercadopago.access_token' => '']);
        $svc = app(\App\Services\MercadoPagoService::class);

        $event = Event::factory()->create([
            'is_paid' => true,
            'price' => 20,
            'name' => 'Evento Teste',
        ]);

        $out = $svc->createPaymentPreference($event, 1, 'buyer@example.com');
        $this->assertFalse($out['success']);
        $this->assertArrayHasKey('error', $out);
    }
}

