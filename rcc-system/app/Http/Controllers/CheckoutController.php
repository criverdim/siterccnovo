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
        $data = $request->validate([
            'participation_id' => ['required', 'integer', 'exists:event_participations,id'],
            'payment_method' => ['required', 'in:pix,credit_card,boleto'],
            'payer' => ['required', 'array'],
        ]);

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
            $payment = $client->create([
                'transaction_amount' => (float) optional($participation->event)->price ?? 0.0,
                'description' => 'Pagamento de evento',
                'payment_method_id' => $data['payment_method'],
                'payer' => $data['payer'],
            ]);
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
