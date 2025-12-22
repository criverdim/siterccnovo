<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PastoreioAttendanceTest extends TestCase
{
    use RefreshDatabase;

    private function loginPastoreioUser(): void
    {
        $user = \App\Models\User::factory()->create([
            'status' => 'active',
            'allowed_pages' => ['/pastoreio'],
        ]);

        $this->actingAs($user);
    }

    public function test_register_attendance_with_iso_date(): void
    {
        $this->loginPastoreioUser();
        $g = \App\Models\Group::factory()->create();
        $res = $this->post('/pastoreio/attendance', ['group_id' => $g->id, 'date' => '2025-11-30', 'name' => 'Teste', 'cpf' => '00000000000']);
        $res->assertStatus(200);
        $json = $res->json();
        $this->assertEquals('ok', $json['status'] ?? null);
    }

    public function test_register_attendance_with_br_date(): void
    {
        $this->loginPastoreioUser();
        $g = \App\Models\Group::factory()->create();
        $res = $this->post('/pastoreio/attendance', ['group_id' => $g->id, 'date' => '30/11/2025', 'name' => 'Teste', 'cpf' => '00000000000']);
        $res->assertStatus(200);
        $json = $res->json();
        $this->assertEquals('ok', $json['status'] ?? null);
    }

    public function test_register_attendance_without_date_uses_today(): void
    {
        $this->loginPastoreioUser();
        $g = \App\Models\Group::factory()->create();
        $res = $this->post('/pastoreio/attendance', ['group_id' => $g->id, 'name' => 'Teste', 'cpf' => '00000000000']);
        $res->assertStatus(200);
        $json = $res->json();
        $this->assertEquals('ok', $json['status'] ?? null);
    }
}
