<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class DebugPaymentPayload extends Command
{
    protected $signature = 'debug:payment-payload {email}';
    protected $description = 'Debug payment creation with specific email and minimal payload';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Testing payment for email: $email");

        // Config
        $token = config('services.mercadopago.access_token');
        MercadoPagoConfig::setAccessToken($token);

        $client = new PaymentClient();

        // 1. Test Minimal Payload (Like Purchase Blade PIX)
        $this->info("1. Testing Minimal Payload (Email only)...");
        try {
            $request = [
                "transaction_amount" => 10.0,
                "description" => "Test Minimal",
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => $email
                ]
            ];
            $payment = $client->create($request);
            $this->info("✅ Success! ID: " . $payment->id);
        } catch (\Throwable $e) {
            $this->error("❌ Failed: " . $e->getMessage());
            if (method_exists($e, 'getApiResponse')) {
                $this->line(json_encode($e->getApiResponse()->getContent(), JSON_PRETTY_PRINT));
            }
        }

        // 3. Test Exact Controller Payload
        $this->info("\n3. Testing Exact Controller Payload...");
        try {
            $webhookUrl = config('services.mercadopago.webhook_url');
            $this->info("Webhook URL: $webhookUrl");
            
            $request = [
                "transaction_amount" => 10.0,
                "description" => "Test Controller Payload",
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => $email
                ],
                "notification_url" => $webhookUrl,
                "external_reference" => "debug_" . uniqid(),
                "additional_info" => [
                    "items" => [[
                        "title" => "Evento Teste",
                        "quantity" => 1,
                        "unit_price" => 10.0
                    ]]
                ],
                "binary_mode" => true
            ];
            
            $payment = $client->create($request);
            $this->info("✅ Success! ID: " . $payment->id);
        } catch (\Throwable $e) {
            $this->error("❌ Failed: " . $e->getMessage());
            if (method_exists($e, 'getApiResponse')) {
                $this->line(json_encode($e->getApiResponse()->getContent(), JSON_PRETTY_PRINT));
            }
        }
    }
}
