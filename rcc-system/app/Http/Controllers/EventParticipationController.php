<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventParticipationController extends Controller
{
    public function participate(Request $request, Event $event)
    {
        if (! auth()->check()) {
            return response()->json(['status' => 'error', 'message' => 'Faça login para participar.', 'unauthenticated' => true], 401);
        }
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:14'],
        ]);

        $user = auth()->user();

        $participation = EventParticipation::firstOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->id],
            [
                'quantity' => 1,
                'payment_status' => $event->is_paid ? 'pending' : 'approved',
                'ticket_uuid' => $event->generates_ticket ? (string) Str::uuid() : null,
            ]
        );

        return response()->json([
            'status' => 'ok',
            'participation_id' => $participation->id,
            'payment_required' => $event->is_paid,
        ]);
    }

    public function status(EventParticipation $participation)
    {
        if (! auth()->check() || $participation->user_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Não autorizado.'], 403);
        }

        if (! app()->environment('testing') && in_array($participation->payment_method, ['pix', 'boleto'], true)) {
            $currentStatus = (string) ($participation->payment_status ?? '');
            if (! in_array($currentStatus, ['approved', 'rejected', 'cancelled', 'refunded'], true)) {
                try {
                    $raw = $participation->mp_payload_raw ?? [];
                    $orderId = $raw['order_id'] ?? ($raw['order']['id'] ?? null);
                    $token = (string) config('services.mercadopago.access_token');
                    if ($orderId && $token !== '') {
                        $resp = Http::withToken($token)
                            ->acceptJson()
                            ->get('https://api.mercadopago.com/v1/orders/'.urlencode((string) $orderId));

                        if ($resp->ok()) {
                            $order = $resp->json();
                            $payments = $order['transactions']['payments'] ?? ($order['payments'] ?? []);
                            if (is_array($payments) && count($payments) > 0) {
                                $first = $payments[0];
                                $newStatus = (string) ($first['status'] ?? ($order['status'] ?? $currentStatus));
                                if ($newStatus !== '' && $newStatus !== $currentStatus) {
                                    $participation->payment_status = $newStatus;
                                    $payload = [
                                        'id' => $first['id'] ?? ($raw['id'] ?? null),
                                        'status' => $newStatus,
                                        'order_id' => $order['id'] ?? $orderId,
                                        'order' => $order,
                                    ];
                                    $participation->mp_payment_id = (string) ($payload['id'] ?? $participation->mp_payment_id);
                                    $participation->mp_payload_raw = $payload;

                                    if (($newStatus === 'approved') && empty($participation->ticket_uuid) && ($participation->event?->generates_ticket)) {
                                        $participation->ticket_uuid = (string) Str::uuid();
                                    }

                                    $participation->save();
                                }
                            }
                        } else {
                            Log::warning('PIX status refresh HTTP error', [
                                'participation_id' => $participation->id,
                                'order_id' => $orderId,
                                'http_status' => $resp->status(),
                            ]);
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('PIX status refresh failed', [
                        'participation_id' => $participation->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'ok',
            'payment_status' => $participation->payment_status,
            'payment_method' => $participation->payment_method,
            'ticket_uuid' => $participation->ticket_uuid,
        ]);
    }
}
