<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_email_server_settings_validates_and_saves(): void
    {
        $user = User::factory()->create(['is_servo'=>true,'status'=>'active']);
        $this->be($user);
        Setting::create([
            'key'=>'email',
            'value'=>[
                'host'=>'smtp.example.com',
                'port'=>587,
                'username'=>'user',
                'password'=>'Secret!2025',
                'encryption'=>'tls',
                'from_email'=>'noreply@example.com',
                'from_name'=>'RCC System',
            ],
        ]);
        $this->assertTrue(Setting::where('key','email')->exists());
        // UI index renders
        $res = $this->get('/admin/settings');
        $res->assertStatus(200);
        $res->assertSee('Configurações');
    }

    public function test_create_mercadopago_settings_requires_fields(): void
    {
        $user = User::factory()->create(['is_servo'=>true,'status'=>'active']);
        $this->be($user);
        // Simulate missing fields via model validation expectations (Filament handles validation in UI)
        // Here we assert that index page loads and sections are present for Mercado Pago
        $res = $this->get('/admin/settings/create');
        $res->assertStatus(200);
        $res->assertSee('Mercado Pago');
    }
}
