<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('MP Webhook recebido', [
            'method' => $request->method(),
            'data' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        $type = $request->string('type')->toString();
        $topic = $request->string('topic')->toString();
        $action = $request->string('action')->toString();

        if ($type === '' && $topic !== '') {
            $type = $topic;
        }
        if ($type === 'order.updated') {
            $type = 'order';
        }
        if ($type === 'payment.updated' || $type === 'payment.created') {
            $type = 'payment';
        }

        $data = $request->input('data', []);
        $id = $data['id'] ?? $request->input('id') ?? null;
        $status = $data['status'] ?? $request->string('status')->toString();

        if (! $id) {
            Log::warning('MP Webhook sem id em data', ['payload' => $request->all()]);
            return response()->json(['ok' => true]);
        }

        $paymentId = null;

        if ($type === 'payment') {
            $paymentId = (string) $id;
        } elseif ($type === 'order') {
            $paymentsData = [];
            if (isset($data['payments']) && is_array($data['payments'])) {
                $paymentsData = $data['payments'];
            } elseif (isset($data['order']['payments']) && is_array($data['order']['payments'])) {
                $paymentsData = $data['order']['payments'];
            }

            if ($paymentsData) {
                $first = $paymentsData[0] ?? [];
                if (isset($first['id'])) {
                    $paymentId = (string) $first['id'];
                }
                if (! $status && isset($first['status'])) {
                    $status = (string) $first['status'];
                }
            } elseif (! app()->environment('testing')) {
                try {
                    $token = (string) config('services.mercadopago.access_token');
                    if ($token !== '') {
                        $resp = Http::withToken($token)
                            ->acceptJson()
                            ->get('https://api.mercadopago.com/v1/orders/'.urlencode((string) $id));

                        if ($resp->ok()) {
                            $order = $resp->json();
                            $payments = $order['payments'] ?? ($order['transactions']['payments'] ?? []);
                            if (is_array($payments) && count($payments) > 0) {
                                $first = $payments[0];
                                if (isset($first['id'])) {
                                    $paymentId = (string) $first['id'];
                                }
                                if (! $status && isset($first['status'])) {
                                    $status = (string) $first['status'];
                                }
                            }
                        } else {
                            Log::warning('MP Webhook: erro ao buscar order', [
                                'order_id' => $id,
                                'http_status' => $resp->status(),
                            ]);
                        }
                    }
                } catch (\Throwable $e) {
                    Log::error('MP Webhook: exceção ao buscar order', [
                        'order_id' => $id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } else {
            return response()->json(['ok' => true]);
        }

        if (! $paymentId) {
            return response()->json(['ok' => true]);
        }

        $p = EventParticipation::where('mp_payment_id', $paymentId)->first();
        if (! $p) {
            return response()->json(['ok' => true]);
        }

        if ($status) {
            $normalizedStatus = $status;
            if ($normalizedStatus === 'processed') {
                $normalizedStatus = 'approved';
            } elseif ($normalizedStatus === 'action_required') {
                $normalizedStatus = 'pending';
            }
            $p->payment_status = $normalizedStatus;
        }

        $shouldGenerateTicket = ($p->payment_status === 'approved') && ($p->event?->generates_ticket);

        if ($shouldGenerateTicket && empty($p->ticket_uuid)) {
            $p->ticket_uuid = (string) \Illuminate\Support\Str::uuid();
        }

        $p->save();

        if ($shouldGenerateTicket && empty($p->ticket_qr_hash) && ! app()->environment('testing')) {
            try {
                app(\App\Services\TicketService::class)->generateAndSend($p);
            } catch (\Throwable $e) {
                Log::error('Ticket generation failed after webhook', [
                    'participation_id' => $p->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['ok' => true]);
    }
}
