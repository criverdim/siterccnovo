<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Services\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_service_returns_defaults_when_no_rows(): void
    {
        $svc = app(SiteSettings::class);
        $site = $svc->site();
        $this->assertArrayHasKey('address', $site);
        $this->assertArrayHasKey('phone', $site);
        $this->assertArrayHasKey('whatsapp', $site);
        $this->assertArrayHasKey('email', $site);
    }

    public function test_service_merges_site_values(): void
    {
        Setting::updateOrCreate(['key' => 'site'], ['value' => [
            'address' => 'Rua Teste, 456',
            'phone' => '(11) 1111-1111',
        ]]);
        $svc = app(SiteSettings::class);
        $site = $svc->site();
        $this->assertSame('Rua Teste, 456', $site['address']);
        $this->assertSame('(11) 1111-1111', $site['phone']);
    }

    public function test_api_site_route_returns_json(): void
    {
        $resp = $this->get('/api/site');
        $resp->assertStatus(200);
        $j = $resp->json();
        $this->assertArrayHasKey('site', $j);
        $this->assertArrayHasKey('social', $j);
        $this->assertArrayHasKey('brand_logo', $j);
    }

    public function test_cache_invalidation_on_setting_save(): void
    {
        Setting::updateOrCreate(['key' => 'site'], ['value' => ['phone' => '(22) 2222-2222']]);
        $svc = app(SiteSettings::class);
        $this->assertSame('(22) 2222-2222', $svc->site()['phone']);

        $setting = Setting::where('key', 'site')->first();
        $setting->value = array_merge($setting->value, ['phone' => '(33) 3333-3333']);
        $setting->save();

        $this->assertSame('(33) 3333-3333', $svc->site()['phone']);
    }
}
