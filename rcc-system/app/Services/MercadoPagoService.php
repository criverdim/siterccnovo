<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoService
{
    private string $accessToken;

    private bool $sandbox;

    public function __construct()
    {
        $this->accessToken = (string) config('services.mercadopago.access_token');
        $mode = (string) config('services.mercadopago.mode', 'sandbox');
        $this->sandbox = ($mode === 'sandbox');

        try {
            if ($this->accessToken) {
                MercadoPagoConfig::setAccessToken($this->accessToken);
                $integratorId = (string) env('MERCADOPAGO_INTEGRATOR_ID', '');
                if ($integratorId) {
                    MercadoPagoConfig::setIntegratorId($integratorId);
                }
            } else {
                Log::warning('Mercado Pago não configurado: MERCADOPAGO_ACCESS_TOKEN ausente');
            }
        } catch (\Throwable $e) {
            Log::error('Falha ao inicializar SDK Mercado Pago', ['error' => $e->getMessage()]);
        }
    }

    public function createPaymentPreference(Event $event, int $quantity, string $userEmail): array
    {
        try {
            if (! $this->accessToken) {
                return [
                    'success' => false,
                    'error' => 'Pagamento indisponível: configure o Mercado Pago.',
                ];
            }

            $client = new PreferenceClient();
            
            // Calcular valor total
            $totalAmount = $event->price * $quantity;

            // Criar pagamento no banco
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'quantity' => $quantity,
                'amount' => $totalAmount,
                'currency' => 'BRL',
                'status' => 'pending',
                'payment_method' => 'mercadopago',
                'external_reference' => null,
                'mercado_pago_id' => null,
                'mercado_pago_preference_id' => null,
                'mercado_pago_data' => null,
                'paid_at' => null,
            ]);

                $preference = $client->create([
                    "items" => [
                        [
                            "id" => (string) $event->id,
                            "title" => $event->name,
                            "quantity" => $quantity,
                            "unit_price" => floatval($event->price),
                            "currency_id" => "BRL",
                            "category_id" => (string) ($event->category ?? 'event'),
                            "description" => substr((string) $event->description, 0, 250)
                        ]
                    ],
                "payer" => [
                    "email" => $userEmail,
                    "name" => auth()->user()->name ?? 'Comprador'
                ],
                "back_urls" => [
                    "success" => route('events.payment.success', ['payment' => $payment->id]),
                    "failure" => route('events.payment.failure', ['payment' => $payment->id]),
                    "pending" => route('events.payment.pending', ['payment' => $payment->id])
                ],
                "auto_return" => "approved",
                "notification_url" => route('events.payment.webhook'),
                "external_reference" => (string) $payment->id,
                "expires" => false,
                "payment_methods" => [
                    "excluded_payment_types" => [],
                    "installments" => 12
                ],
                "statement_descriptor" => "RCC EVENTOS",
                "binary_mode" => false
            ]);

            // Atualizar pagamento com preference_id
            $payment->update([
                'mercado_pago_preference_id' => $preference->id,
                'mercado_pago_data' => [
                    'preference_id' => $preference->id,
                ],
                'external_reference' => (string) $payment->id,
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'preference_id' => $preference->id,
                'init_point' => $this->sandbox ? $preference->sandbox_init_point : $preference->init_point,
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao criar preferência de pagamento', [
                'event_id' => $event->id,
                'quantity' => $quantity,
                'user_email' => $userEmail,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Erro ao processar pagamento. Tente novamente.',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function processWebhook(array $data): bool
    {
        try {
            $paymentId = $data['data']['id'] ?? null;

            if (! $paymentId) {
                Log::warning('Webhook recebido sem payment_id', ['data' => $data]);

                return false;
            }

            if ($paymentId === 'test_cli_ping') {
                Log::info('Webhook ping recebido', ['data' => $data]);

                return true;
            }

            if (! is_numeric($paymentId)) {
                Log::warning('Webhook com payment_id não numérico', [
                    'payment_id' => $paymentId,
                    'data' => $data,
                ]);

                return false;
            }

            // Buscar informações do pagamento no Mercado Pago
            $client = new PaymentClient();
            try {
                $paymentMP = $client->get((int) $paymentId);
            } catch (\Exception $e) {
                Log::error('Erro ao buscar pagamento no Mercado Pago', [
                    'payment_id' => $paymentId,
                    'error' => $e->getMessage()
                ]);
                return false;
            }

            if (! $paymentMP) {
                return false;
            }

            $externalReference = $paymentMP->external_reference ?? null;

            if (! $externalReference) {
                Log::warning('Pagamento sem external_reference', ['payment_id' => $paymentId]);

                return false;
            }

            // Buscar pagamento no banco
            $paymentRecord = Payment::find($externalReference);

            if (! $paymentRecord) {
                Log::warning('Pagamento não encontrado no banco', ['external_reference' => $externalReference]);

                return false;
            }

            // Atualizar status do pagamento
            // Nota: Os campos retornados pelo SDK v3 são objetos/propriedades, não arrays
            $status = $paymentMP->status;
            $paymentTypeId = $paymentMP->payment_type_id;
            $dateApproved = $paymentMP->date_approved;

            $paymentRecord->update([
                'mercado_pago_id' => (string) $paymentId,
                'payment_method' => $paymentTypeId,
                'paid_at' => $dateApproved ? \Carbon\Carbon::parse($dateApproved) : null,
                'status' => $status,
                'mercado_pago_data' => array_merge($paymentRecord->mercado_pago_data ?? [], [
                    'webhook_data' => (array) $paymentMP,
                ]),
            ]);

            // Se pagamento foi aprovado, criar ingressos
            if ($status === 'approved') {
                $this->createTickets($paymentRecord);
            }

            Log::info('Webhook processado com sucesso', [
                'payment_id' => $paymentId,
                'external_reference' => $externalReference,
                'status' => $status,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    private function mapPaymentStatus(string $mpStatus): string
    {
        return match ($mpStatus) {
            'approved' => 'paid',
            'pending' => 'pending',
            'in_process' => 'pending',
            'rejected' => 'failed',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
            'charged_back' => 'refunded',
            default => 'unknown',
        };
    }

    private function createTickets(Payment $payment): void
    {
        try {
            $event = $payment->event;

            for ($i = 0; $i < $payment->quantity; $i++) {
                $ticket = Ticket::create([
                    'event_id' => $payment->event_id,
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'ticket_code' => $this->generateTicketCode($payment, $i),
                    'qr_code' => null,
                    'status' => 'active',
                ]);

                // Gerar QR code
                $this->generateQrCode($ticket);
            }

            // Atualizar contador de ingressos vendidos
            $event->increment('tickets_sold', $payment->quantity);

        } catch (\Exception $e) {
            Log::error('Erro ao criar ingressos', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    private function generateTicketCode(Payment $payment, int $index): string
    {
        $event = $payment->event;
        $user = $payment->user;

        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $event->name), 0, 3));
        $date = $event->start_date->format('Ymd');
        $userInitials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user->name), 0, 2));
        $sequence = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        $random = strtoupper(substr(md5(uniqid()), 0, 4));

        return "{$prefix}-{$date}-{$userInitials}-{$sequence}-{$random}";
    }

    private function generateQrCode(Ticket $ticket): void
    {
        try {
            $qrData = [
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'event_id' => $ticket->event_id,
                'user_id' => $ticket->user_id,
                'timestamp' => now()->toIso8601String(),
            ];

            // Criar token JWT para o QR code
            $token = auth()->guard('api')->login(auth()->user());

            $qrContent = json_encode([
                'type' => 'event_ticket',
                'ticket_code' => $ticket->ticket_code,
                'validation_token' => $token,
                'data' => $qrData,
            ]);

            // Gerar QR code
            $qrCode = \QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($qrContent);

            // Salvar QR code no ticket
            $ticket->update([
                'qr_code' => base64_encode($qrCode),
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao gerar QR code', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function validatePayment(string $paymentId): array
    {
        try {
            $payment = SDK::get('/v1/payments/'.$paymentId);

            if (! $payment || ! isset($payment['response'])) {
                return [
                    'success' => false,
                    'error' => 'Pagamento não encontrado',
                ];
            }

            return [
                'success' => true,
                'data' => $payment['response'],
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao validar pagamento', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Erro ao validar pagamento',
            ];
        }
    }
}
