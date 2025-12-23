<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;

class TestMercadoPago extends Command
{
    protected $signature = 'mercadopago:test';
    protected $description = 'Testa a conexão e credenciais do Mercado Pago';

    public function handle()
    {
        $this->info('Iniciando teste de conexão com Mercado Pago...');

        $accessToken = config('services.mercadopago.access_token');
        $publicKey = config('services.mercadopago.public_key');

        if (empty($accessToken)) {
            $this->error('❌ Access Token não configurado no .env (MERCADOPAGO_ACCESS_TOKEN)');
            return 1;
        }

        if (empty($publicKey)) {
            $this->warn('⚠️ Public Key não configurada no .env (MERCADOPAGO_PUBLIC_KEY). O frontend precisará dela.');
        }

        $this->info('Configurando SDK...');
        try {
            MercadoPagoConfig::setAccessToken($accessToken);
            $this->info('✅ SDK inicializado.');
        } catch (\Throwable $e) {
            $this->error('❌ Falha ao configurar SDK: ' . $e->getMessage());
            return 1;
        }

        $this->info('Testando criação de Preferência (Checkout)...');
        try {
            $client = new PreferenceClient();
            $preference = $client->create([
                "items" => [
                    [
                        "id" => "test_item_01",
                        "title" => "Item de Teste de Conexão",
                        "quantity" => 1,
                        "unit_price" => 1.00,
                        "currency_id" => "BRL"
                    ]
                ],
                "payer" => [
                    "email" => "test_user_123@test.com"
                ],
                "back_urls" => [
                    "success" => "https://www.google.com",
                    "failure" => "https://www.google.com",
                    "pending" => "https://www.google.com"
                ],
                "auto_return" => "approved",
            ]);

            if ($preference && $preference->id) {
                $this->info('✅ Preferência criada com sucesso!');
                $this->info('   ID da Preferência: ' . $preference->id);
                $this->info('   Init Point (Sandbox): ' . $preference->sandbox_init_point);
            } else {
                $this->error('❌ Preferência criada mas sem ID retornado.');
            }

        } catch (\Exception $e) {
            $this->error('❌ Erro ao criar preferência: ' . $e->getMessage());
            if (method_exists($e, 'getApiResponse')) {
                $response = $e->getApiResponse();
                if ($response) {
                    $this->error('   Detalhes da API: ' . json_encode($response->getContent()));
                }
            }
            return 1;
        }

        $this->info('Testando criação de Pagamento via API (PIX)...');
        try {
            $client = new PaymentClient();
            $payerEmail = 'test_user_' . uniqid() . '@test.com';
            $this->info("Usando e-mail do pagador: $payerEmail");

            $paymentRequest = [
                "transaction_amount" => 10.0,
                "token" => "valid_token", // Sandbox uses mocked token? No, for PIX we don't need token.
                "description" => "Teste de Pagamento Console",
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => $payerEmail,
                    "first_name" => "Test",
                    "last_name" => "User",
                    "identification" => [
                        "type" => "CPF",
                        "number" => "19119119100"
                    ]
                ]
            ];
            $payment = $client->create($paymentRequest);
            $this->info('✅ Pagamento PIX criado com sucesso! ID: ' . $payment->id);
            $this->info('   Status: ' . $payment->status);
        } catch (\Exception $e) {
            $this->error('❌ Erro ao criar pagamento PIX: ' . $e->getMessage());
            // Mostra detalhes se disponível
            if (method_exists($e, 'getApiResponse')) {
                $response = $e->getApiResponse();
                if ($response) {
                    $this->error('   Detalhes da API: ' . json_encode($response->getContent()));
                }
            }
        }

        $this->info('-------------------------------------------');
        $this->info('RESULTADO: Conexão bem sucedida! As credenciais parecem válidas.');
        return 0;
    }
}
