<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminEditUserPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_user_page_loads_without_error(): void
    {
        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'User X',
            'email' => 'userx@test.local',
            'status' => 'active',
        ]);

        $this->actingAs($admin);
        $response = $this->get("/admin/users/{$user->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Nível de Acesso');
    }

    public function test_filament_route_name_for_user_edit_exists(): void
    {
        $name = 'filament.admin.resources.users.edit';
        $this->assertTrue(\Illuminate\Support\Facades\Route::has($name), "Missing route: $name");
    }
}

