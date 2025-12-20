<?php

namespace Tests\Feature;

use App\Models\Ministry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorsSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_mark_user_as_coordinator_and_sobre_displays_it(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'status' => 'active',
            'role' => 'admin',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);
        $this->actingAs($admin);

        $ministry = Ministry::create([
            'name' => 'Ministério de Música e Artes',
            'description' => null,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Coordenador Teste',
            'email' => 'coord@example.com',
            'status' => 'active',
            'role' => 'servo',
        ]);

        $res = $this->post('/api/v1/admin/users/'.$user->id.'/coordinator', [
            'is_coordinator' => true,
            'coordinator_ministry_id' => $ministry->id,
        ]);
        $res->assertStatus(200);

        $html = $this->get('/sobre')->getContent();
        $this->assertStringContainsString('Coordenadores', $html);
        $this->assertStringContainsString($user->name, $html);
        $this->assertStringContainsString($ministry->name, $html);
    }
}
