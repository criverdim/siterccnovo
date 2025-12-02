<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Config;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        if ($request->isMethod('get')) {
            $participationId = (int) $request->integer('participation_id') ?: null;
            $eventId = (int) $request->integer('event') ?: null;
            $method = $request->string('method')->toString() ?: 'pix';
            if (! in_array($method, ['pix', 'credit_card', 'boleto', 'card'], true)) {
                $method = 'pix';
            }
            if ($method === 'card') {
                $method = 'credit_card';
            }
            if (! $participationId && $eventId && auth()->check()) {
                $p = \App\Models\EventParticipation::firstOrCreate([
                    'user_id' => auth()->id(),
                    'event_id' => $eventId,
                ], ['payment_status' => 'pending']);
                $participationId = $p->id;
            }

            return view('checkout.index', [
                'participation_id' => $participationId,
                'payment_method' => $method,
                'mp_public_key' => config('services.mercadopago.public_key'),
            ]);
        }
        $baseRules = [
            'participation_id' => ['required', 'integer', 'exists:event_participations,id'],
            'payment_method' => ['required', 'in:pix,credit_card,boleto'],
            'payer' => ['required', 'array'],
            'payer.email' => ['required', 'email'],
        ];
        // Condicionais por método
        if ($request->input('payment_method') === 'boleto') {
            $baseRules = array_merge($baseRules, [
                'payer.first_name' => ['required', 'string'],
                'payer.last_name' => ['required', 'string'],
                'payer.identification.type' => ['required', 'string'],
                'payer.identification.number' => ['required', 'string'],
                'payer.address.street_name' => ['required', 'string'],
                'payer.address.street_number' => ['required', 'string'],
                'payer.address.zip_code' => ['required', 'string'],
                'payer.address.neighborhood' => ['required', 'string'],
                'payer.address.state' => ['required', 'string'],
                'payer.address.city' => ['required', 'string'],
            ]);
        }
        $data = $request->validate($baseRules);

        $participation = EventParticipation::findOrFail($data['participation_id']);

        if (app()->environment('testing')) {
            $payment = [
                'id' => (string) ('test_'.uniqid()),
                'status' => 'pending',
                'method' => $data['payment_method'],
            ];
        } else {
            Config::setAccessToken(config('services.mercadopago.access_token'));
            $client = new PaymentClient;
            $payload = [
                'transaction_amount' => (float) optional($participation->event)->price ?? 0.0,
                'description' => 'Pagamento de evento '.(string) ($participation->event->name ?? ''),
                'payment_method_id' => $data['payment_method'],
                'payer' => $data['payer'],
                'notification_url' => config('services.mercadopago.webhook_url'),
                'external_reference' => 'participation_'.$participation->id,
                'additional_info' => [
                    'items' => [[
                        'title' => (string) ($participation->event->name ?? 'Evento'),
                        'quantity' => 1,
                        'unit_price' => (float) ($participation->event->price ?? 0.0),
                    ]],
                ],
            ];
            if ($data['payment_method'] === 'credit_card') {
                $payload['token'] = $request->string('token')->toString();
                $payload['installments'] = (int) $request->integer('installments') ?: 1;
                if ($request->filled('issuer_id')) {
                    $payload['issuer_id'] = (int) $request->integer('issuer_id');
                }
            }
            $payment = $client->create($payload);
        }

        $participation->update([
            'payment_method' => $data['payment_method'],
            'mp_payment_id' => (string) (is_array($payment) ? $payment['id'] : $payment->id),
            'mp_payload_raw' => is_array($payment) ? $payment : $payment->toArray(),
            'payment_status' => is_array($payment) ? ($payment['status'] ?? 'pending') : ($payment->status ?? 'pending'),
        ]);

        return response()->json(['status' => is_array($payment) ? $payment['status'] : $payment->status, 'payment' => $payment]);
    }
}
