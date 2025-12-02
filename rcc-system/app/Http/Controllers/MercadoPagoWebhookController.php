<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Http\Request;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $type = $request->string('type')->toString();
        $data = $request->input('data', []);
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? $request->string('status')->toString();

        if ($type !== 'payment' || ! $id) {
            return response()->json(['ok' => true]);
        }

        $p = EventParticipation::where('mp_payment_id', (string) $id)->first();
        if (! $p) {
            return response()->json(['ok' => true]);
        }

        $p->payment_status = $status ?: $p->payment_status;
        if (($p->payment_status === 'approved') && empty($p->ticket_uuid) && ($p->event?->generates_ticket)) {
            $p->ticket_uuid = (string) \Illuminate\Support\Str::uuid();
        }
        $p->save();

        return response()->json(['ok' => true]);
    }
}
