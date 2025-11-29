<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Http\Request;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $type = $request->input('type');
        $dataId = $request->input('data.id');
        $action = $request->input('action');

        $payload = $request->all();

        $participation = EventParticipation::query()
            ->where('mp_payment_id', (string) $dataId)
            ->first();

        if (! $participation) {
            return response()->json(['status' => 'ignored']);
        }

        $status = $payload['data']['status'] ?? $payload['status'] ?? null;
        if ($status) {
            $participation->update([
                'payment_status' => $status,
                'mp_payload_raw' => $payload,
            ]);
        }

        if ($status === 'approved' && $participation->event && $participation->event->generates_ticket) {
            app(\App\Services\TicketService::class)->generateAndSend($participation);
        }

        return response()->json(['status' => 'ok']);
    }
}
