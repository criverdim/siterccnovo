<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Ministry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserProfilePdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_profile_view_contains_expected_sections_and_translations(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'role' => 'admin',
            'status' => 'active',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);

        $group1 = Group::factory()->create([
            'name' => 'Chagas de Amor',
            'color_hex' => '#10B981',
        ]);
        $group2 = Group::factory()->create([
            'name' => 'Grupo São José',
            'color_hex' => '#f59e0b',
        ]);

        $ministry1 = Ministry::query()->create([
            'name' => 'Pregação',
            'description' => 'Ministério de pregação',
            'is_active' => true,
        ]);
        $ministry2 = Ministry::query()->create([
            'name' => 'Música',
            'description' => 'Ministério de música',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Servo Teste',
            'email' => 'servo@test.local',
            'status' => 'active',
            'role' => 'servo',
            'group_id' => $group1->id,
            'is_servo' => true,
        ]);
        $user->groups()->attach([$group1->id, $group2->id]);
        $user->ministries()->attach([$ministry1->id, $ministry2->id]);

        $this->actingAs($admin);
        $response = $this->get("/admin/users/{$user->id}/profile");
        $response->assertStatus(200);
        $response->assertSee('Informações Pessoais');
        $response->assertSee('Endereço');
        $response->assertSee('Grupo que participa');
        $response->assertSee('Ministérios de serviço');
        $response->assertSee('Cidade');
        $response->assertSee('Estado');
        $this->assertFalse(str_contains($response->getContent(), 'Cidade / Estado'));
        $response->assertSee('Status: Ativo');
        $response->assertSee('Voltar');
        $response->assertSee('cv-btn warning', false);

        $html = $response->getContent();
        $dividerCount = substr_count($html, 'cv-divider');
        $this->assertTrue($dividerCount >= 2, "Esperado ao menos 2 separadores, encontrado {$dividerCount}");
    }

    public function test_admin_profile_pdf_is_application_pdf_and_single_page(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'role' => 'admin',
            'status' => 'active',
            'can_access_admin' => true,
            'is_master_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Servo PDF',
            'email' => 'servo.pdf@test.local',
            'status' => 'active',
            'role' => 'servo',
        ]);

        $this->actingAs($admin);
        $response = $this->get("/admin/users/{$user->id}/profile/pdf");
        $response->assertStatus(200);
        $contentType = $response->headers->get('Content-Type');
        $this->assertSame('application/pdf', $contentType);

        $bytes = $response->getContent();
        $this->assertTrue(str_starts_with($bytes, '%PDF'), 'PDF inválido: cabeçalho não inicia com %PDF');

        $pageCount = preg_match_all('/\/Type\s*\/Page(?!s)\b/', $bytes);
        $this->assertSame(1, $pageCount, "PDF deve ter 1 página, encontrado {$pageCount}");

    }

    public function test_route_names_exist(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('admin.users.profile'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('admin.users.profile.pdf'));
    }
}
