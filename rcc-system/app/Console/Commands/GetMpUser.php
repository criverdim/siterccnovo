<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetMpUser extends Command
{
    protected $signature = 'debug:mp-user';
    protected $description = 'Get Mercado Pago User Info';

    public function handle()
    {
        $token = config('services.mercadopago.access_token');
        $this->info("Token: " . substr($token, 0, 10) . "...");

        $response = Http::withToken($token)->get('https://api.mercadopago.com/users/me');

        if ($response->ok()) {
            $this->info("User ID: " . $response['id']);
            $this->info("Email: " . $response['email']);
            $this->info("Nickname: " . $response['nickname']);
            $this->info("Site ID: " . $response['site_id']);
        } else {
            $this->error("Failed: " . $response->body());
        }
    }
}
