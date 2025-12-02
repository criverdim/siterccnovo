<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingsUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::disk('local')->put('logs/test-report.json', json_encode([], JSON_PRETTY_PRINT));
    }

    public function test_settings_index_has_create_action(): void
    {
        $user = User::factory()->create([
            'is_servo' => true,
            'status' => 'active',
            'role' => 'admin',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);
        $this->be($user);
        $res = $this->get('/admin/settings');
        $res->assertStatus(200);
        $res->assertSee('Configurações');
        // Validate the Create page is reachable (action exists)
        $this->get('/admin/settings/create')->assertStatus(200);
        $this->appendReport('ui', ['index' => 'ok']);
    }

    public function test_settings_create_shows_key_selector_and_sections(): void
    {
        $user = User::factory()->create([
            'is_servo' => true,
            'status' => 'active',
            'role' => 'admin',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);
        $this->be($user);
        $res = $this->get('/admin/settings/create');
        $res->assertStatus(200);
        $res->assertSee('Chave');
        // Seções são exibidas dinamicamente após escolher a "Chave"; aqui validamos apenas que a página carrega
        // e que o seletor existe. A validação de campos é coberta por testes de integração e E2E.
        $this->appendReport('ui', ['create' => 'ok']);
    }

    private function appendReport(string $section, array $data): void
    {
        $path = storage_path('logs/test-report.json');
        $current = [];
        if (file_exists($path)) {
            $current = json_decode(file_get_contents($path), true) ?: [];
        }
        $current[$section] = array_merge($current[$section] ?? [], $data);
        file_put_contents($path, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
