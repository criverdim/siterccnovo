<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'brand'], ['value' => ['logo' => null]]);

        Setting::updateOrCreate(['key' => 'site'], ['value' => [
            'address' => 'Rua Exemplo, 123 - Cidade/UF',
            'phone' => '(00) 0000-0000',
            'whatsapp' => '(00) 90000-0000',
            'email' => 'contato@rcc.local',
        ]]);

        Setting::updateOrCreate(['key' => 'social'], ['value' => [
            'facebook' => 'https://facebook.com/rcc',
            'instagram' => 'https://instagram.com/rcc',
            'youtube' => 'https://youtube.com/@rcc',
            'whatsapp' => 'https://wa.me/55999999999',
            'tiktok' => 'https://tiktok.com/@rcc',
        ]]);
    }
}
