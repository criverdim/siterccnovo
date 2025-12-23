<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Config;

class ValidateMercadoPago extends Command
{
    protected $signature = 'mcp:validate';

    protected $description = 'Valida integração Mercado Pago: credenciais, webhook e criação de pagamento (sandbox)';

    public function handle(): int
    {
        $mp = Setting::where('key', 'mercadopago')->first();
        $vals = $mp?->value ?? [];
        $report = [
            'has_access_token' => ! empty($vals['access_token']),
            'has_public_key' => ! empty($vals['public_key']),
            'mode' => $vals['mode'] ?? null,
            'webhook_url' => $vals['webhook_url'] ?? null,
            'webhook_ping' => null,
            'sandbox_payment' => null,
            'timestamp' => now()->toIso8601String(),
        ];

        $this->info('Validando credenciais MCP...');
        $this->line(json_encode([
            'access_token' => $report['has_access_token'],
            'public_key' => $report['has_public_key'],
            'mode' => $report['mode'],
        ]));

        if (! empty($report['webhook_url'])) {
            $this->info('Pingando webhook...');
            try {
                $resp = Http::timeout(5)->post($report['webhook_url'], [
                    'type' => 'order',
                    'data' => [
                        'id' => 'order_cli_ping',
                        'payments' => [
                            [
                                'id' => 'test_cli_ping',
                                'status' => 'approved',
                            ],
                        ],
                    ],
                    'action' => 'order.updated',
                ]);
                $report['webhook_ping'] = ['status' => $resp->status()];
                $this->info('Webhook ping status: '.$resp->status());
            } catch (\Throwable $e) {
                $report['webhook_ping'] = ['error' => $e->getMessage()];
                $this->error('Falha no webhook: '.$e->getMessage());
            }
        } else {
            $this->warn('Webhook URL não definido');
        }

        // Teste de pagamento sandbox (PIX)
        if (($vals['mode'] ?? 'sandbox') === 'sandbox' && ! empty($vals['access_token'])) {
            try {
                $this->info('Criando pagamento sandbox PIX...');
                if (class_exists(\MercadoPago\Config::class)) {
                    Config::setAccessToken($vals['access_token']);
                    $client = new PaymentClient;
                    $payment = $client->create([
                        'transaction_amount' => 1.0,
                        'description' => 'Teste de integração',
                        'payment_method_id' => 'pix',
                        'payer' => ['email' => 'test@testuser.com'],
                        'external_reference' => 'cli_validate_'.uniqid(),
                        'notification_url' => $vals['webhook_url'] ?? null,
                    ]);
                    $report['sandbox_payment'] = ['id' => $payment->id ?? null, 'status' => $payment->status ?? null];
                } else {
                    $resp = \Illuminate\Support\Facades\Http::withToken($vals['access_token'])->post('https://api.mercadopago.com/v1/payments', [
                        'transaction_amount' => 1.0,
                        'description' => 'Teste de integração',
                        'payment_method_id' => 'pix',
                        'payer' => ['email' => 'test_user_123@testuser.com'],
                        'external_reference' => 'cli_validate_'.uniqid(),
                        'notification_url' => $vals['webhook_url'] ?? null,
                        'binary_mode' => false,
                    ]);
                    $status = $resp->status();
                    if ($status >= 200 && $status < 300) {
                        $report['sandbox_payment'] = ['id' => $resp->json('id'), 'status' => $resp->json('status')];
                    } else {
                        $report['sandbox_payment'] = ['error' => 'http_status_'.$status];
                    }
                }
                $this->info('Pagamento sandbox criado: '.json_encode($report['sandbox_payment']));
            } catch (\Throwable $e) {
                $report['sandbox_payment'] = ['error' => $e->getMessage()];
                $this->error('Falha ao criar pagamento sandbox: '.$e->getMessage());
            }
        } else {
            $this->warn('Modo produção ou Access Token ausente — pulando criação sandbox.');
        }

        // Persistir relatório
        $path = storage_path('logs/mcp-validation.json');
        file_put_contents($path, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info('Relatório salvo em: '.$path);

        return self::SUCCESS;
    }
}
