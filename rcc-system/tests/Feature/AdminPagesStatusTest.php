<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = \App\Models\User::factory()->create([
            'name' => 'Admin Health',
            'email' => 'admin.health@test.local',
            'role' => 'admin',
            'status' => 'active',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);
    }

    public function test_admin_core_pages_return_200(): void
    {
        $this->actingAs($this->adminUser);

        $paths = [
            '/admin',
            '/admin/users',
            '/admin/groups',
            '/admin/events',
            '/admin/ministerios',
            '/admin/settings',
            '/admin/logs',
            '/admin/payment-logs',
            '/admin/visitas',
            '/admin/duplicates-tool',
            '/admin/pastoreio-history',
            '/admin/presenca-rapida',
        ];

        foreach ($paths as $path) {
            $response = $this->get($path);
            $status = $response->getStatusCode();
            if ($status >= 400) {
                file_put_contents(storage_path('logs/admin-page-'.$this->slug($path).'-error.html'), $response->getContent());
            }
            $this->assertTrue($status < 400, "Page '$path' returned status $status");
        }
    }

    public function test_filament_route_names_exist(): void
    {
        $names = [
            'filament.admin.resources.users.index',
            'filament.admin.resources.groups.index',
            'filament.admin.resources.events.index',
            'filament.admin.resources.ministerios.index',
            'filament.admin.resources.settings.index',
            'filament.admin.resources.logs.index',
            'filament.admin.resources.payment-logs.index',
            'filament.admin.resources.visitas.index',
            'filament.admin.pages.pastoreio-history',
            'filament.admin.pages.presenca-rapida',
            'filament.admin.pages.duplicates-tool',
        ];
        foreach ($names as $name) {
            $this->assertTrue(\Illuminate\Support\Facades\Route::has($name), "Route name missing: $name");
        }
    }

    private function slug(string $path): string
    {
        return trim(str_replace(['/', '{', '}', ':'], ['-', '', '', '-'], $path), '-');
    }
}
