<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;

class CheckoutController extends Controller
{
    private function mpExtractApiError(\Throwable $e): array
    {
        $details = null;
        $message = $e->getMessage();

        if (method_exists($e, 'getApiResponse')) {
            $content = $e->getApiResponse() ? $e->getApiResponse()->getContent() : null;
            if (is_string($content)) {
                $decoded = json_decode($content, true);
                $details = is_array($decoded) ? $decoded : $content;
            } else {
                $details = $content;
            }

            if (is_array($details)) {
                if (isset($details['message']) && is_string($details['message'])) {
                    $message = $details['message'];
                }
                if (isset($details['cause']) && is_array($details['cause'])) {
                    $causes = collect($details['cause'])
                        ->filter(fn ($c) => is_array($c) && isset($c['description']) && is_string($c['description']))
                        ->map(fn ($c) => $c['description'])
                        ->values()
                        ->all();
                    if (count($causes)) {
                        $message .= ' ('.implode(', ', $causes).')';
                    }
                }
            }
        }

        return [$message, $details];
    }

    private function mpThrowHttpError(string $prefix, \Illuminate\Http\Client\Response $resp): void
    {
        $body = null;
        $message = $prefix.' (HTTP '.$resp->status().')';

        try {
            $json = $resp->json();
            if (is_array($json)) {
                $body = $json;
                if (isset($json['message']) && is_string($json['message'])) {
                    $message .= ': '.$json['message'];
                }
                if (isset($json['error']) && is_string($json['error'])) {
                    $message .= ': '.$json['error'];
                }
                if (isset($json['cause']) && is_array($json['cause'])) {
                    $causes = collect($json['cause'])
                        ->filter(fn ($c) => is_array($c) && isset($c['description']) && is_string($c['description']))
                        ->map(fn ($c) => $c['description'])
                        ->values()
                        ->all();
                    if (count($causes)) {
                        $message .= ' ('.implode(', ', $causes).')';
                    }
                }
            } else {
                $body = $resp->body();
            }
        } catch (\Throwable $e) {
            $body = $resp->body();
        }

        $ex = new \RuntimeException($message);
        $ex->mp_details = $body;
        throw $ex;
    }

    private function mpGetOrder(string $token, string $orderId): array
    {
        $resp = Http::withToken($token)
            ->acceptJson()
            ->get('https://api.mercadopago.com/v1/orders/'.$orderId);

        if (! $resp->ok()) {
            $this->mpThrowHttpError('Erro ao consultar order no Mercado Pago', $resp);
        }

        $order = $resp->json();

        return is_array($order) ? $order : [];
    }

    private function mpBuildPaymentFromOrder(array $order): array
    {
        $payment = [];
        $firstPayment = $order['transactions']['payments'][0] ?? null;
        if (is_array($firstPayment)) {
            $payment = $firstPayment;
        }

        $pm = [];
        if (isset($payment['payment_method']) && is_array($payment['payment_method'])) {
            $pm = $payment['payment_method'];
        }
        if (isset($pm['data']) && is_array($pm['data'])) {
            $pm = array_merge($pm, $pm['data']);
        }

        $poi = [];
        if (isset($payment['point_of_interaction']) && is_array($payment['point_of_interaction'])) {
            $poi = $payment['point_of_interaction'];
        } elseif (isset($order['point_of_interaction']) && is_array($order['point_of_interaction'])) {
            $poi = $order['point_of_interaction'];
        }
        if (isset($poi['transaction_data']) && is_array($poi['transaction_data'])) {
            $poi['transaction_data'] = array_merge($poi['transaction_data'], $pm);
        } else {
            $poi['transaction_data'] = $pm;
        }

        $tx = $poi['transaction_data'] ?? [];

        return [
            'id' => $payment['id'] ?? null,
            'status' => $payment['status'] ?? ($order['status'] ?? 'pending'),
            'order_id' => $order['id'] ?? null,
            'order' => $order,
            'point_of_interaction' => [
                'transaction_data' => [
                    'ticket_url' => $tx['ticket_url'] ?? $tx['ticketUrl'] ?? null,
                    'qr_code' => $tx['qr_code'] ?? $tx['qrCode'] ?? null,
                    'qr_code_base64' => $tx['qr_code_base64'] ?? $tx['qrCodeBase64'] ?? null,
                ],
            ],
            'barcode' => [
                'content' => $tx['barcode_content'] ?? $tx['digitable_line'] ?? $tx['digitableLine'] ?? null,
            ],
        ];
    }

    private function mpOrderHasPresentationData(string $paymentMethodId, array $order): bool
    {
        $payment = $this->mpBuildPaymentFromOrder($order);
        $tx = $payment['point_of_interaction']['transaction_data'] ?? [];
        $barcode = $payment['barcode']['content'] ?? null;

        if ($paymentMethodId === 'pix') {
            return ! empty($tx['qr_code']) || ! empty($tx['qr_code_base64']);
        }

        if ($paymentMethodId === 'boleto') {
            return ! empty($barcode) || ! empty($tx['ticket_url']);
        }

        return true;
    }

    public function checkout(Request $request)
    {
        if ($request->isMethod('get')) {
            $currentUrl = $request->fullUrl();
            if (! auth()->check()) {
                return redirect('/login?redirect='.urlencode($currentUrl));
            }
            $participationId = (int) $request->integer('participation_id') ?: null;
            $eventId = (int) $request->integer('event') ?: null;
            $method = $request->string('method')->toString() ?: 'pix';
            if (! in_array($method, ['pix', 'credit_card', 'boleto', 'card'], true)) {
                $method = 'pix';
            }
            if ($method === 'card') {
                $method = 'credit_card';
            }
            if ($eventId) {
                $evt = \App\Models\Event::find($eventId);
                if (! $evt || ! $evt->isActive()) {
                    return redirect('/events');
                }
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
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
            'device_id' => ['nullable', 'string'],
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
        if ($request->input('payment_method') === 'credit_card') {
            $baseRules = array_merge($baseRules, [
                'token' => ['required', 'string'],
                'installments' => ['required', 'integer', 'min:1'],
                'payer.identification.type' => ['required', 'string'],
                'payer.identification.number' => ['required', 'string'],
                'issuer_id' => ['nullable', 'integer'],
                'payment_method_id' => ['nullable', 'string'],
            ]);
        }
        $data = $request->validate($baseRules);

        // LOGGING DEBUG
        \Illuminate\Support\Facades\Log::info('Checkout Request', [
            'user_id' => auth()->id(),
            'data' => $data,
            'env' => app()->environment(),
            'token_prefix' => substr(config('services.mercadopago.access_token'), 0, 10)
        ]);

        $participation = EventParticipation::findOrFail($data['participation_id']);

        if (app()->environment('testing')) {
            $payment = [
                'id' => (string) ('test_'.uniqid()),
                'status' => 'pending',
                'method' => $data['payment_method'],
            ];
        } else {
            try {
                if (class_exists(\MercadoPago\MercadoPagoConfig::class)) {
                    \MercadoPago\MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
                } elseif (class_exists(\MercadoPago\SDK::class)) {
                    \MercadoPago\SDK::setAccessToken(config('services.mercadopago.access_token'));
                }
            } catch (\Throwable $e) {
            }
            $client = null;
            if (class_exists(\MercadoPago\Client\Payment\PaymentClient::class)) {
                $client = new PaymentClient;
            }

            try {
                $token = (string) config('services.mercadopago.access_token');
                $isSandbox = str_starts_with($token, 'TEST-');
                $deviceId = trim($request->string('device_id')->toString());
                $payer = $data['payer'];
                if ($isSandbox && isset($payer['email']) && is_string($payer['email'])) {
                    $email = $payer['email'];
                    if (! str_contains($email, '+')) {
                        $parts = explode('@', $email);
                        if (count($parts) === 2) {
                            $newEmail = $parts[0].'+sbx'.rand(1000, 9999).'@'.$parts[1];
                            $payer['email'] = $newEmail;
                            \Illuminate\Support\Facades\Log::info('Sandbox: Aliased email for MP', ['original' => $email, 'new' => $newEmail]);
                        }
                    }
                }

                $quantity = (int) ($data['quantity'] ?? 0);
                if ($quantity <= 0) {
                    $quantity = (int) ($participation->quantity ?? 1) ?: 1;
                }

                $event = $participation->event;
                $eventName = (string) ($event->name ?? 'Evento');
                $eventCategory = (string) ($event->category ?? 'event');
                $unitPrice = (float) ($event->price ?? 0.0);
                $itemId = 'event_'.$participation->id;
                $itemDescription = 'Inscrição no evento '.$eventName;

                if (in_array($data['payment_method'], ['pix', 'boleto'], true)) {
                    $amount = $unitPrice * $quantity;
                    if ($amount <= 0) {
                        throw new \RuntimeException('Valor inválido para pagamento.');
                    }

                    $paymentMethodType = $data['payment_method'] === 'pix' ? 'bank_transfer' : 'ticket';
                    $orderPayload = [
                        'type' => 'online',
                        'total_amount' => number_format($amount, 2, '.', ''),
                        'external_reference' => 'participation_'.$participation->id.($isSandbox ? '_'.uniqid() : ''),
                        'processing_mode' => 'automatic',
                        'transactions' => [
                            'payments' => [[
                                'amount' => number_format($amount, 2, '.', ''),
                                'payment_method' => [
                                    'id' => $data['payment_method'],
                                    'type' => $paymentMethodType,
                                ],
                            ]],
                        ],
                        'payer' => $payer,
                        'items' => [[
                            'title' => $eventName,
                            'description' => $itemDescription,
                            'category_id' => $eventCategory ?: 'others',
                            'quantity' => $quantity,
                            'unit_price' => number_format($unitPrice, 2, '.', ''),
                        ]],
                    ];

                    if ($data['payment_method'] === 'boleto') {
                        $orderPayload['description'] = 'Pagamento de evento '.(string) ($participation->event->name ?? '');
                    }

                    \Illuminate\Support\Facades\Log::info('MP Creating Order Payload', $orderPayload);

                    $orderHeaders = ['X-Idempotency-Key' => Str::uuid()->toString()];
                    if ($deviceId !== '') {
                        $orderHeaders['X-Meli-Session-Id'] = $deviceId;
                    }

                    $resp = Http::withToken($token)
                        ->acceptJson()
                        ->withHeaders($orderHeaders)
                        ->post('https://api.mercadopago.com/v1/orders', $orderPayload);

                    if (! $resp->successful()) {
                        $this->mpThrowHttpError('Erro ao criar order no Mercado Pago', $resp);
                    }

                    $order = $resp->json();
                    if (! is_array($order)) {
                        $order = [];
                    }

                    $orderId = $order['id'] ?? null;
                    if (is_string($orderId) || is_int($orderId)) {
                        $orderId = (string) $orderId;
                        $tries = 0;
                        while ($tries < 8) {
                            $hasId = $this->mpBuildPaymentFromOrder($order)['id'] ?? null;
                            $hasData = $this->mpOrderHasPresentationData($data['payment_method'], $order);

                            if (! empty($hasId) && $hasData) {
                                break;
                            }

                            usleep(350000);
                            $order = $this->mpGetOrder($token, $orderId);
                            $tries++;
                        }
                    }

                    $payment = $this->mpBuildPaymentFromOrder($order);

                    if (empty($payment['id'])) {
                        throw new \RuntimeException('Order criada, mas o pagamento ainda está em processamento. Tente novamente em instantes.');
                    }
                } else {
                    $amount = $unitPrice * $quantity;

                    if ($amount <= 0) {
                        throw new \RuntimeException('Valor inválido para pagamento.');
                    }

                    $payload = [
                        'transaction_amount' => $amount,
                        'description' => 'Pagamento de evento '.$eventName,
                        'payment_method_id' => $request->string('payment_method_id')->toString() ?: $data['payment_method'],
                        'payer' => $payer,
                        'notification_url' => config('services.mercadopago.webhook_url'),
                        'external_reference' => 'participation_'.$participation->id.($isSandbox ? '_'.uniqid() : ''),
                        'additional_info' => [
                            'items' => [[
                                'id' => $itemId,
                                'title' => $eventName,
                                'description' => $itemDescription,
                                'category_id' => $eventCategory ?: 'event',
                                'quantity' => $quantity,
                                'unit_price' => $unitPrice,
                            ]],
                        ],
                        'binary_mode' => false,
                    ];
                    if ($data['payment_method'] === 'credit_card') {
                        $payload['token'] = $request->string('token')->toString();
                        $payload['installments'] = (int) $request->integer('installments') ?: 1;
                        if ($request->filled('issuer_id')) {
                            $payload['issuer_id'] = (int) $request->integer('issuer_id');
                        }
                        if ($request->filled('payment_method_id')) {
                            $payload['payment_method_id'] = $request->string('payment_method_id')->toString();
                        }
                    }

                    if ($client) {
                        \Illuminate\Support\Facades\Log::info('MP Creating Payment Payload', $payload);
                        $payment = $client->create($payload);
                        \Illuminate\Support\Facades\Log::info('MP Payment Created', ['id' => $payment->id]);
                    } else {
                        $http = Http::withToken($token)->acceptJson();
                        if ($deviceId !== '') {
                            $http = $http->withHeaders(['X-Meli-Session-Id' => $deviceId]);
                        }
                        $resp = $http->post('https://api.mercadopago.com/v1/payments', $payload);
                        if (! $resp->successful()) {
                            $this->mpThrowHttpError('Erro ao criar pagamento no Mercado Pago', $resp);
                        }
                        $obj = (object) $resp->json();
                        $payment = (object) [
                            'id' => $obj->id ?? null,
                            'status' => $obj->status ?? 'pending',
                            'point_of_interaction' => $obj->point_of_interaction ?? null,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                [$msg, $details] = $this->mpExtractApiError($e);

                if (isset($e->mp_details)) {
                    $details = $e->mp_details;
                }

                \Illuminate\Support\Facades\Log::error('MP API Error', ['msg' => $msg, 'details' => $details]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Falha na comunicação com Mercado Pago: '.$msg,
                    'details' => $details
                ], 422);
            }
        }

        $participation->update([
            'payment_method' => $data['payment_method'],
            'mp_payment_id' => (string) (is_array($payment) ? $payment['id'] : $payment->id),
            'mp_payload_raw' => is_array($payment) ? $payment : $payment->toArray(),
            'payment_status' => is_array($payment) ? ($payment['status'] ?? 'pending') : ($payment->status ?? 'pending'),
            'quantity' => $quantity ?? ($participation->quantity ?? 1),
        ]);

        return response()->json([
            'status' => is_array($payment) ? ($payment['status'] ?? 'pending') : ($payment->status ?? 'pending'),
            'payment' => $payment,
            'order' => is_array($payment) ? ($payment['order'] ?? null) : null,
        ]);
    }
}
