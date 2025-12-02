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
        $enabled = (bool) config('services.whatsapp.enabled');

        if (! $enabled || ! $url || ! $token || ! $phoneId) {
            $message->update(['status' => 'sent']);

            return 'sent';
        }

        $toRaw = $message->user->whatsapp ?? $message->user->phone;
        $to = preg_replace('/\D+/', '', (string) $toRaw);
        if (str_starts_with($to, '0')) {
            $to = ltrim($to, '0');
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $message->message],
        ];
        $forceTemplate = (bool) data_get($message->payload, 'force_template');

        $hasMessagesPath = str_contains($url, '/messages');
        $endpoint = $hasMessagesPath ? rtrim($url, '/') : (rtrim($url, '/')."/{$phoneId}/messages");
        $response = Http::withToken($token)->post($endpoint, $payload);

        $resJson = null;
        try { $resJson = $response->json(); } catch (\Throwable $e) { $resJson = null; }
        $errCode = is_array($resJson) ? data_get($resJson, 'error.code') : null;
        $msgId = is_array($resJson) ? data_get($resJson, 'messages.0.id') : null;

        if ($response->successful() && ! $forceTemplate && $msgId) {
            $message->update([
                'status' => 'sent',
                'payload' => [
                    'transport' => 'text',
                    'request' => $payload,
                    'endpoint' => $endpoint,
                    'response' => $resJson,
                    'response_status' => $response->status(),
                    'message_id' => $msgId,
                ],
            ]);

            return 'sent';
        }

        $shouldFallback = $forceTemplate || ($response->status() === 400) || ($errCode !== null) || (! $msgId);
        if ($shouldFallback) {
            $tplPayload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => 'hello_world',
                    'language' => ['code' => 'en_US'],
                ],
            ];
            $tplResp = Http::withToken($token)->post($endpoint, $tplPayload);
            $tplJson = null;
            try { $tplJson = $tplResp->json(); } catch (\Throwable $e) { $tplJson = null; }

            $tplMsgId = is_array($tplJson) ? data_get($tplJson, 'messages.0.id') : null;
            if ($tplResp->successful() && $tplMsgId) {
                $message->update([
                    'status' => 'sent',
                    'payload' => [
                        'transport' => 'template',
                        'request' => $payload,
                        'fallback_request' => $tplPayload,
                        'endpoint' => $endpoint,
                        'response' => $resJson,
                        'fallback_response' => $tplJson,
                        'response_status' => $response->status(),
                        'fallback_response_status' => $tplResp->status(),
                        'message_id' => $tplMsgId,
                    ],
                ]);

                return 'sent';
            }

            $message->update([
                'status' => 'failed',
                'payload' => [
                    'transport' => 'text',
                    'request' => $payload,
                    'fallback_request' => $tplPayload,
                    'endpoint' => $endpoint,
                    'response' => $resJson,
                    'fallback_response' => $tplJson,
                    'response_status' => $response->status(),
                    'fallback_response_status' => $tplResp->status(),
                ],
            ]);

            return 'failed';
        }

        $message->update([
            'status' => 'failed',
            'payload' => [
                'transport' => 'text',
                'request' => $payload,
                'endpoint' => $endpoint,
                'response' => $resJson,
                'response_status' => $response->status(),
            ],
        ]);

        return 'failed';
    }
}
