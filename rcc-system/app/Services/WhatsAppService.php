<?php

namespace App\Services;

use App\Models\WaMessage;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function send(WaMessage $message): string
    {
        $url = config('services.whatsapp.url');
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');

        if (! $url || ! $token || ! $phoneId) {
            $message->update(['status' => 'sent']);

            return 'sent';
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $message->user->whatsapp ?? $message->user->phone,
            'type' => 'text',
            'text' => ['body' => $message->message],
        ];

        $response = Http::withToken($token)->post(rtrim($url, '/')."/{$phoneId}/messages", $payload);

        if ($response->successful()) {
            $message->update(['status' => 'delivered', 'delivered_at' => now()]);

            return 'delivered';
        }

        $message->update(['status' => 'failed']);

        return 'failed';
    }
}
