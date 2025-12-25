<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Order\OrderClient;

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

    private function mpGetOrder(string $orderId): array
    {
        $token = (string) config('services.mercadopago.access_token');
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
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
            'device_id' => ['nullable', 'string'],
        ];
        // Condicionais por método
        if ($request->input('payment_method') === 'pix') {
            $baseRules['payer.email'] = ['required', 'email'];
        }
        if ($request->input('payment_method') === 'boleto') {
            $baseRules = array_merge($baseRules, [
                'payer.email' => ['required', 'email'],
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
                'payer.email' => ['nullable', 'email'],
                'payer.identification.type' => ['required', 'string'],
                'payer.identification.number' => ['required', 'string'],
                'issuer_id' => ['nullable', 'integer'],
                'payment_method_id' => ['nullable', 'string'],
            ]);
        }
        try {
            $data = $request->validate($baseRules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Checkout Validation Failed', [
                'errors' => $e->errors(),
                'user_id' => auth()->id(),
                'route' => optional($request->route())->getName(),
            ]);
            throw $e;
        }

        if (($data['payment_method'] ?? null) === 'credit_card') {
            $reqData = $request->all();
            unset($reqData['token']);
            \Illuminate\Support\Facades\Log::info('Checkout credit_card request debug', [
                'keys' => array_keys($reqData),
                'payment_method_id' => $reqData['payment_method_id'] ?? null,
                'paymentMethodId' => $reqData['paymentMethodId'] ?? null,
            ]);
        }

        $participation = EventParticipation::findOrFail($data['participation_id']);

        $existingStatus = (string) ($participation->payment_status ?? '');
        $existingMethod = (string) ($participation->payment_method ?? '');
        $recentSeconds = $participation->updated_at ? now()->diffInSeconds($participation->updated_at) : null;

        if ($existingStatus === 'approved') {
            $raw = $participation->mp_payload_raw;
            $userMessage = $this->buildUserFriendlyMessage($existingStatus, $raw ?? null) ?? 'Pagamento já aprovado para esta inscrição.';

            return response()->json([
                'status' => $existingStatus,
                'payment' => $raw,
                'order' => is_array($raw) ? ($raw['order'] ?? null) : null,
                'message' => $userMessage,
            ]);
        }

        if (
            $existingStatus === 'pending'
            && $existingMethod === ($data['payment_method'] ?? null)
            && $participation->mp_payment_id
            && is_int($recentSeconds)
            && $recentSeconds < 30
        ) {
            $raw = $participation->mp_payload_raw;
            $userMessage = $this->buildUserFriendlyMessage($existingStatus, $raw ?? null) ?? 'Já existe um pagamento em processamento para esta inscrição. Aguarde alguns instantes.';

            return response()->json([
                'status' => $existingStatus,
                'payment' => $raw,
                'order' => is_array($raw) ? ($raw['order'] ?? null) : null,
                'message' => $userMessage,
            ]);
        }

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

            try {
                $token = (string) config('services.mercadopago.access_token');
                $mode = (string) config('services.mercadopago.mode');
                $isSandbox = str_starts_with($token, 'TEST-') || $mode === 'sandbox';
                $deviceId = trim($request->string('device_id')->toString());
                $payer = $data['payer'];
                if (empty($payer['email'])) {
                    $fallbackEmail = auth()->user()->email ?? 'user@local';
                    $payer['email'] = $fallbackEmail;
                }
                if ($isSandbox) {
                    $originalEmail = $payer['email'] ?? null;
                    $payer['email'] = 'buyer_'.uniqid().'@testuser.com';
                    \Illuminate\Support\Facades\Log::info('Sandbox: Forced test email for MP', ['original' => $originalEmail, 'new' => $payer['email']]);
                }
                $user = auth()->user();
                if ($user && empty($payer['address'])) {
                    $street = (string) ($user->address ?? '');
                    $number = (string) ($user->number ?? '');
                    $cep = preg_replace('/\D+/', '', (string) ($user->cep ?? ''));
                    $neighborhood = (string) ($user->district ?? '');
                    $city = (string) ($user->city ?? '');
                    $state = (string) ($user->state ?? '');
                    $hasAddress = $street !== '' || $number !== '' || $cep !== '' || $neighborhood !== '' || $city !== '' || $state !== '';
                    if ($hasAddress) {
                        $payer['address'] = [
                            'street_name' => $street !== '' ? $street : 'Rua',
                            'street_number' => $number !== '' ? $number : 'S/N',
                            'zip_code' => $cep !== '' ? $cep : '00000000',
                            'neighborhood' => $neighborhood !== '' ? $neighborhood : 'Centro',
                            'state' => $state !== '' ? $state : 'SP',
                            'city' => $city !== '' ? $city : 'São Paulo',
                        ];
                    }
                }
                if ($user && empty($payer['phone'])) {
                    $rawPhone = (string) ($user->phone ?? $user->whatsapp ?? '');
                    $digits = preg_replace('/\D+/', '', $rawPhone);
                    if ($digits !== '') {
                        $areaCode = '';
                        $numberPhone = $digits;
                        if (strlen($digits) >= 10) {
                            $areaCode = substr($digits, 0, 2);
                            $numberPhone = substr($digits, 2);
                        }
                        $payer['phone'] = [
                            'area_code' => $areaCode,
                            'number' => $numberPhone,
                        ];
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

                $amount = $unitPrice * $quantity;
                if ($amount <= 0) {
                    throw new \RuntimeException('Valor inválido para pagamento.');
                }

                $paymentMethod = [];
                if ($data['payment_method'] === 'pix') {
                    $paymentMethod = [
                        'id' => 'pix',
                        'type' => 'bank_transfer',
                    ];
                } elseif ($data['payment_method'] === 'boleto') {
                    $paymentMethod = [
                        'id' => 'boleto',
                        'type' => 'ticket',
                    ];
                } elseif ($data['payment_method'] === 'credit_card') {
                    $cardMethodId = $request->string('payment_method_id')->toString();
                    $installments = (int) $request->integer('installments') ?: 1;
                    $cardToken = $request->string('token')->toString();
                    $paymentMethod = [
                        'type' => 'credit_card',
                        'token' => $cardToken,
                        'installments' => $installments,
                        'statement_descriptor' => 'RCC Miguelopolis',
                    ];
                    if ($cardMethodId !== '') {
                        $paymentMethod['id'] = $cardMethodId;
                    }
                }

                $orderPayload = [
                    'type' => 'online',
                    'total_amount' => number_format($amount, 2, '.', ''),
                    'external_reference' => 'participation_'.$participation->id.($isSandbox ? '_'.uniqid() : ''),
                    'processing_mode' => 'automatic',
                    'transactions' => [
                        'payments' => [[
                            'amount' => number_format($amount, 2, '.', ''),
                            'payment_method' => $paymentMethod,
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
                        $order = $this->mpGetOrder($orderId);
                        $tries++;
                    }
                }

                $payment = $this->mpBuildPaymentFromOrder($order);

                if (empty($payment['id'])) {
                    throw new \RuntimeException('Order criada, mas o pagamento ainda está em processamento. Tente novamente em instantes.');
                }
            } catch (\Throwable $e) {
                [$msg, $details] = $this->mpExtractApiError($e);

                if (isset($e->mp_details)) {
                    $details = $e->mp_details;
                }

                if (is_array($details)) {
                    $statusDetail = null;
                    if (isset($details['data']['transactions']['payments'][0]['status_detail'])) {
                        $statusDetail = (string) $details['data']['transactions']['payments'][0]['status_detail'];
                    } elseif (isset($details['errors']) && is_array($details['errors'])) {
                        $joined = strtolower(implode(' ', array_map(function ($err) {
                            if (is_array($err) && isset($err['details']) && is_array($err['details'])) {
                                return implode(' ', $err['details']);
                            }

                            return '';
                        }, $details['errors'])));
                        if (str_contains($joined, 'high_risk')) {
                            $statusDetail = 'high_risk';
                        } elseif (str_contains($joined, 'rejected_by_issuer')) {
                            $statusDetail = 'rejected_by_issuer';
                        } elseif (str_contains($joined, 'invalid_transaction_amount')) {
                            $statusDetail = 'invalid_transaction_amount';
                        } elseif (str_contains($joined, 'processing_error')) {
                            $statusDetail = 'processing_error';
                        } elseif (str_contains($joined, 'insufficient_amount') || str_contains($joined, 'insufficient_funds')) {
                            $statusDetail = 'insufficient_amount';
                        }
                    }

                    if ($statusDetail !== null) {
                        if (str_contains($statusDetail, 'high_risk')) {
                            $msg = 'Pagamento recusado por segurança do banco ou do Mercado Pago. Entre em contato com seu banco ou tente outro método de pagamento (Pix, boleto ou outro cartão).';
                        } elseif (str_contains($statusDetail, 'rejected_by_issuer')) {
                            $msg = 'Pagamento recusado pelo emissor do cartão. Verifique com seu banco ou tente outro cartão ou método de pagamento (Pix ou boleto).';
                        } elseif (str_contains($statusDetail, 'invalid_transaction_amount')) {
                            $msg = 'Não foi possível processar o pagamento com este valor e parcelamento. Tente reduzir o número de parcelas ou usar outro valor/método de pagamento.';
                        } elseif (str_contains($statusDetail, 'insufficient_amount') || str_contains($statusDetail, 'insufficient_funds') || str_contains($statusDetail, 'cc_rejected_insufficient_amount')) {
                            $msg = 'Pagamento recusado por saldo ou limite insuficiente no cartão. Verifique o limite disponível ou tente outro cartão ou método de pagamento (Pix ou boleto).';
                        } elseif (str_contains($statusDetail, 'processing_error')) {
                            $msg = 'Ocorreu um erro ao processar o pagamento. Tente novamente em instantes ou utilize outro método de pagamento (Pix ou boleto).';
                        } elseif (
                            str_contains($statusDetail, 'bad_filled_card_data') ||
                            str_contains($statusDetail, 'bad_filled_security_code') ||
                            str_contains($statusDetail, 'cc_rejected_bad_filled_security_code') ||
                            str_contains($statusDetail, 'bad_filled_date') ||
                            str_contains($statusDetail, 'cc_rejected_bad_filled_date')
                        ) {
                            $msg = 'Pagamento recusado por dados do cartão inválidos. Confira número, validade e código de segurança ou tente outro cartão ou método de pagamento (Pix ou boleto).';
                        }
                    }
                }

                \Illuminate\Support\Facades\Log::error('MP API Error', ['msg' => $msg, 'details' => $details]);

                $errorText = $msg;
                if (! (
                    str_starts_with($msg, 'Pagamento recusado')
                    || str_starts_with($msg, 'Seu pagamento')
                    || str_starts_with($msg, 'Não foi possível processar')
                    || str_starts_with($msg, 'Ocorreu um erro ao processar')
                )) {
                    $errorText = 'Falha na comunicação com Mercado Pago: '.$msg;
                }
                
                return response()->json([
                    'success' => false,
                    'error' => $errorText,
                    'details' => $details
                ], 422);
            }
        }

        $isArrayPayment = is_array($payment);
        $isObjectPayment = is_object($payment);

        $paymentId = null;
        $paymentStatus = 'pending';
        $rawPayload = null;
        $orderData = null;

        if ($isArrayPayment) {
            $paymentId = $payment['id'] ?? null;
            $paymentStatus = $payment['status'] ?? 'pending';
            $rawPayload = $payment;
            $orderData = $payment['order'] ?? null;
        } elseif ($isObjectPayment) {
            $paymentId = $payment->id ?? null;
            $paymentStatus = $payment->status ?? 'pending';
            if (method_exists($payment, 'toArray')) {
                $rawPayload = $payment->toArray();
            } else {
                $rawPayload = json_decode(json_encode($payment), true);
            }
        }

        $participation->update([
            'payment_method' => $data['payment_method'],
            'mp_payment_id' => $paymentId !== null ? (string) $paymentId : null,
            'mp_payload_raw' => $rawPayload,
            'payment_status' => $paymentStatus,
            'quantity' => $quantity ?? ($participation->quantity ?? 1),
        ]);
        $userMessage = $this->buildUserFriendlyMessage((string) $paymentStatus, $rawPayload ?? $payment);

        return response()->json([
            'status' => $paymentStatus,
            'payment' => $rawPayload ?? $payment,
            'order' => $orderData,
            'message' => $userMessage,
        ]);
    }

    private function buildUserFriendlyMessage(string $status, $payment): ?string
    {
        $detail = null;
        if (is_array($payment)) {
            $detail = $payment['status_detail'] ?? null;
        } elseif (is_object($payment)) {
            $detail = $payment->status_detail ?? null;
        }

        if (in_array($status, ['in_review', 'in_process', 'pending', 'in_mediation'], true)) {
            return 'Seu pagamento está em análise pelo Mercado Pago. Assim que for aprovado, seu ingresso será liberado.';
        }

        if ($status === 'rejected' && is_string($detail)) {
            $d = $detail;
            if (str_contains($d, 'high_risk') || str_contains($d, 'risk')) {
                return 'Pagamento recusado por segurança do banco ou do Mercado Pago. Entre em contato com seu banco ou tente outro método de pagamento (Pix, boleto ou outro cartão).';
            }
        }

        return null;
    }
}
