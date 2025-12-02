<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Group;
use App\Models\Ministerio;
use App\Models\Setting;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@example.com',
            'name' => 'Admin User',
            'status' => 'active',
            'role' => 'admin',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);
    }

    /** @test */
    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('/admin');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_access_core_resources(): void
    {
        // Create test data
        User::factory()->count(3)->create();
        Group::factory()->count(2)->create();
        Event::factory()->count(2)->create();
        Ministerio::factory()->count(2)->create();
        Visit::factory()->count(2)->create();
        Setting::factory()->count(2)->create();

        $resources = [
            '/admin/users' => 'Users',
            '/admin/groups' => 'Groups',
            '/admin/events' => 'Events',
            '/admin/ministerios' => 'Ministerios',
            '/admin/visitas' => 'Visitas',
            '/admin/settings' => 'Settings',
        ];

        foreach ($resources as $url => $name) {
            $response = $this->actingAs($this->user)->get($url);
            $this->assertEquals(200, $response->status(), "Failed to access {$name} resource at {$url}");
        }
    }

    /** @test */
    public function test_admin_can_access_duplicates_tool(): void
    {
        $response = $this->actingAs($this->user)->get('/admin/duplicates-tool');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_navigation_groups_are_accessible(): void
    {
        $navigationGroups = [
            'Gerenciamento' => [
                '/admin/users',
                '/admin/groups',
                '/admin/duplicates-tool',
            ],
            'Eventos' => [
                '/admin/events',
                '/admin/ministerios',
            ],
            'Configurações' => [
                '/admin/settings',
            ],
        ];

        foreach ($navigationGroups as $group => $urls) {
            foreach ($urls as $url) {
                $response = $this->actingAs($this->user)->get($url);
                $this->assertEquals(200, $response->status(), "Failed to access {$group} navigation item at {$url}");
            }
        }
    }

    /** @test */
    public function test_admin_panel_has_correct_branding(): void
    {
        $response = $this->actingAs($this->user)->get('/admin');
        $response->assertStatus(200);

        // Check for RCC Admin branding
        $response->assertSee('RCC Admin');
    }

    /** @test */
    public function test_working_admin_links(): void
    {
        $workingLinks = [
            '/admin',
            '/admin/users',
            '/admin/groups',
            '/admin/events',
            '/admin/ministerios',
            '/admin/visitas',
            '/admin/settings',
            '/admin/duplicates-tool',
        ];

        foreach ($workingLinks as $link) {
            $response = $this->actingAs($this->user)->get($link);
            $this->assertEquals(200, $response->status(), "Admin link {$link} is not working");
        }
    }

    /** @test */
    public function test_admin_can_logout(): void
    {
        $response = $this->actingAs($this->user)->post('/admin/logout');
        $response->assertRedirect('/admin/login');

        // Verify user is logged out
        $this->assertGuest();
    }
}
